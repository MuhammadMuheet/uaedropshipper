<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\Product;
use App\Models\productVariation;
use App\Models\State;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Verify seller permission for dashboard access
        if (!ActivityLogger::hasSellerPermission('dashboard', 'view')) {
            abort(403, 'Unauthorized access to seller dashboard.');
        }

        // Log the dashboard access action
        ActivityLogger::UserLog((Auth::user()->role == 'seller' ? 'Seller' : 'Sub-Seller') . ' ' . Auth::user()->name . ' accessed the dashboard');

        // Determine if user is a seller or sub-seller
        $isSubSeller = Auth::user()->role === 'sub_seller';
        $sellerId = $isSubSeller ? Auth::user()->seller_id : Auth::id();
        $subSellerId = $isSubSeller ? Auth::id() : null;

        // Validate and parse start and end dates
        try {
            $startDate = $request->input('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::now()->startOfMonth();
            $endDate = $request->input('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : Carbon::now()->endOfMonth();
        } catch (\Exception $e) {
            return redirect()->route('seller_dashboard')->withErrors(['date' => 'Invalid date format provided.']);
        }

        // Ensure startDate is not after endDate
        if ($startDate->gt($endDate)) {
            return redirect()->route('seller_dashboard')->withErrors(['date' => 'Start date cannot be after end date.']);
        }

        // Calculate previous period for trends
        $previousStartDate = $startDate->copy()->subDays($endDate->diffInDays($startDate))->startOfDay();
        $previousEndDate = $startDate->copy()->subDay()->endOfDay();

        // Apply state and area filters if provided
        $stateId = $request->input('state');
        $areaId = $request->input('area');

        // Initialize queries with date boundaries
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $previousOrdersQuery = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate]);
        $transactionsQuery = Transaction::join('orders', 'transactions.order_id', '=', 'orders.id')
                            ->whereBetween('transactions.created_at', [$startDate, $endDate]);
        $topAreasQuery = Area::with('state')
                            ->join('orders', 'areas.id', '=', 'orders.area_id')
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->groupBy('areas.id', 'areas.area', 'areas.state_id')
                            ->select(
                                'areas.id',
                                'areas.area',
                                'areas.state_id',
                                \DB::raw('COUNT(orders.id) as order_count'),
                                \DB::raw('SUM(CASE WHEN orders.status = "Delivered" THEN 1 ELSE 0 END) as delivered_count'),
                                \DB::raw('SUM(CASE WHEN orders.status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_count')
                            );
        $topStatesQuery = State::join('areas', 'states.id', '=', 'areas.state_id')
                            ->join('orders', 'areas.id', '=', 'orders.area_id')
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->groupBy('states.id', 'states.state')
                            ->select(
                                'states.id',
                                'states.state',
                                \DB::raw('COUNT(orders.id) as order_count'),
                                \DB::raw('SUM(CASE WHEN orders.status = "Delivered" THEN 1 ELSE 0 END) as delivered_count'),
                                \DB::raw('SUM(CASE WHEN orders.status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_count')
                            );
        $topProductsQuery = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->leftJoin('product_variations', 'order_items.product_variation_id', '=', 'product_variations.id')
                            ->leftJoin('product_stock_batches', 'order_items.batch_id', '=', 'product_stock_batches.id')
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->groupBy('products.id', 'products.product_name', 'product_variations.id', 'product_variations.variation_name', 'product_variations.variation_value', 'product_stock_batches.regular_price')
                            ->select(
                                'products.id',
                                'products.product_name',
                                'product_variations.id as variation_id',
                                'product_variations.variation_name',
                                'product_variations.variation_value',
                                'product_stock_batches.regular_price',
                                \DB::raw('SUM(CASE WHEN orders.status = "Delivered" THEN order_items.quantity ELSE 0 END) as delivered_quantity'),
                                \DB::raw('SUM(order_items.quantity) as total_quantity'),
                                \DB::raw('SUM(CASE WHEN orders.status = "Cancelled" THEN order_items.quantity ELSE 0 END) as cancelled_quantity')
                            );

        // Apply seller/sub-seller filter
        if ($isSubSeller) {
            $ordersQuery->where('sub_seller_id', $subSellerId);
            $previousOrdersQuery->where('sub_seller_id', $subSellerId);
            $topAreasQuery->where('orders.sub_seller_id', $subSellerId);
            $topStatesQuery->where('orders.sub_seller_id', $subSellerId);
            $topProductsQuery->where('orders.sub_seller_id', $subSellerId);
        } else {
            $ordersQuery->where(function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId)
                      ->orWhereIn('sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
            $previousOrdersQuery->where(function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId)
                      ->orWhereIn('sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
            $transactionsQuery->where(function ($query) use ($sellerId) {
                $query->where('orders.seller_id', $sellerId)
                      ->orWhereIn('orders.sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
            $topAreasQuery->where(function ($query) use ($sellerId) {
                $query->where('orders.seller_id', $sellerId)
                      ->orWhereIn('orders.sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
            $topStatesQuery->where(function ($query) use ($sellerId) {
                $query->where('orders.seller_id', $sellerId)
                      ->orWhereIn('orders.sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
            $topProductsQuery->where(function ($query) use ($sellerId) {
                $query->where('orders.seller_id', $sellerId)
                      ->orWhereIn('orders.sub_seller_id', function ($subQuery) use ($sellerId) {
                          $subQuery->select('id')
                                   ->from('users')
                                   ->where('seller_id', $sellerId)
                                   ->where('role', 'sub_seller');
                      });
            });
        }

        // Apply state and area filters if provided
        if ($stateId) {
            $ordersQuery->where('state_id', $stateId);
            $previousOrdersQuery->where('state_id', $stateId);
            $transactionsQuery->where('orders.state_id', $stateId);
            $topAreasQuery->where('orders.state_id', $stateId);
            $topStatesQuery->where('orders.state_id', $stateId);
            $topProductsQuery->where('orders.state_id', $stateId);
        }

        if ($areaId) {
            $ordersQuery->where('area_id', $areaId);
            $previousOrdersQuery->where('area_id', $areaId);
            $transactionsQuery->where('orders.area_id', $areaId);
            $topAreasQuery->where('orders.area_id', $areaId);
            $topStatesQuery->where('orders.area_id', $areaId);
            $topProductsQuery->where('orders.area_id', $areaId);
        }

        // Fetch data
        $currentOrders = $ordersQuery->get();
        $previousOrders = $previousOrdersQuery->get();
        $transactions = $isSubSeller ? collect([]) : $transactionsQuery->get();
        $topAreas = $topAreasQuery->orderBy('order_count', 'DESC')->take(3)->get();
        $topTenAreas = $topAreasQuery->orderBy('order_count', 'DESC')->take(10)->get();
        $topStates = $topStatesQuery->orderBy('order_count', 'DESC')->take(3)->get();
        $topTenStates = $topStatesQuery->orderBy('order_count', 'DESC')->take(10)->get();
        $topProducts = $topProductsQuery->orderBy('delivered_quantity', 'DESC')->take(3)->get();
        $topTenProducts = $topProductsQuery->orderBy('delivered_quantity', 'DESC')->take(10)->get();

        // Calculate total delivered quantity for percentage normalization
        $totalDeliveredQuantity = $topTenProducts->sum('delivered_quantity');

        // Calculate metrics for current period
        $totalCod = 0;
        $totalProfit = 0;
        $totalPendingCount = 0;
        $totalProcessingCount = 0;
        $totalShippedCount = 0;
        $totalDeliveredCount = 0;
        $totalCancelledCount = 0;
        $totalOutForDeliveryCount = 0;
        $totalFutureCount = 0;

        foreach ($currentOrders as $order) {
            $totalCod += $order->cod_amount;
            if ($order->status === 'Delivered') {
                $totalProfit += $order->profit ?? 0;
            }
            switch ($order->status) {
                case 'Pending':
                    $totalPendingCount++;
                    break;
                case 'Processing':
                    $totalProcessingCount++;
                    break;
                case 'Shipped':
                    $totalShippedCount++;
                    break;
                case 'Delivered':
                    $totalDeliveredCount++;
                    break;
                case 'Cancelled':
                    $totalCancelledCount++;
                    break;
                case 'Out_for_delivery':
                    $totalOutForDeliveryCount++;
                    break;
                case 'Future':
                    $totalFutureCount++;
                    break;
            }
        }

        // Total Orders
        $totalOrders = $totalPendingCount + $totalProcessingCount + $totalShippedCount +
                       $totalDeliveredCount + $totalCancelledCount + $totalOutForDeliveryCount +
                       $totalFutureCount;

        // Total Revenue
        $totalRevenue = $totalCod;

        // Total Earnings
        $totalEarnings = $totalProfit;

        // Active Customers
        $activeCustomers = $ordersQuery->distinct('phone')->count();

        // Previous period's data
        $previousTotalCod = $previousOrders->sum('cod_amount');
        $previousTotalProfit = $previousOrders->where('status', 'Delivered')->sum('profit') ?? 0;
        $previousTotalOrders = $previousOrders->count();
        $previousActiveCustomers = $previousOrdersQuery->distinct('phone')->count();

        // Calculate trends
        $revenueTrend = $previousTotalCod > 0 ? (($totalRevenue - $previousTotalCod) / $previousTotalCod) * 100 : 0;
        $ordersTrend = $previousTotalOrders > 0 ? (($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100 : 0;
        $customersTrend = $previousActiveCustomers > 0 ? (($activeCustomers - $previousActiveCustomers) / $previousActiveCustomers) * 100 : 0;
        $earningsGrowth = $previousTotalProfit > 0 ? (($totalEarnings - $previousTotalProfit) / $previousTotalProfit) * 100 : 0;

        // Delivery Ratio
        $deliveryRatio = $totalOrders > 0 ? ($totalDeliveredCount / $totalOrders) * 100 : 0;

        // Previous period's delivery ratio
        $previousTotalDeliveredCount = $previousOrders->where('status', 'Delivered')->count();
        $previousDeliveryRatio = $previousTotalOrders > 0 ? ($previousTotalDeliveredCount / $previousTotalOrders) * 100 : 0;

        // Delivery trend
        $deliveryTrend = $deliveryRatio - $previousDeliveryRatio;

        // Sales Growth
        $salesGrowth = $ordersTrend;

        // Transaction calculations
        $totalWallet = 0;
        $totalAmountIn = 0;
        $totalAmountOut = 0;

        if (!$isSubSeller) {
            foreach ($transactions as $transaction) {
                if ($transaction->amount_type == 'in') {
                    $totalAmountIn += $transaction->amount;
                } elseif ($transaction->amount_type == 'out') {
                    $totalAmountOut += $transaction->amount;
                }
            }
            $totalWallet = $totalAmountIn - $totalAmountOut;
        }

        // Recent Orders
        $recentOrders = $ordersQuery->orderBy('created_at', 'DESC')->take(5)->get();

        // Pass data to the view
        return view('seller.dashboard', compact(
            'isSubSeller',
            'totalRevenue',
            'totalOrders',
            'activeCustomers',
            'deliveryRatio',
            'salesGrowth',
            'totalEarnings',
            'revenueTrend',
            'ordersTrend',
            'customersTrend',
            'deliveryTrend',
            'earningsGrowth',
            'totalWallet',
            'totalAmountIn',
            'totalAmountOut',
            'topAreas',
            'topTenAreas',
            'topStates',
            'topTenStates',
            'topProducts',
            'topTenProducts',
            'totalDeliveredQuantity',
            'startDate',
            'endDate',
            'recentOrders'
        ));
    }
}