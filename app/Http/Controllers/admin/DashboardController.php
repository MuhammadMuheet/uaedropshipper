<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\imageUploadTrait;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Contract;
use App\Models\Renter;
use App\Models\Reservation;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\ProductStockBatch;
use App\Models\productVariation;
use App\Models\Area;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Log user activity
        ActivityLogger::UserLog('Logged in User ' . Auth::user()->name);

        // Validate and parse start and end dates
        try {
            $startDate = $request->input('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::now()->startOfMonth();
            $endDate = $request->input('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : Carbon::now()->endOfMonth();
        } catch (\Exception $e) {
            // Handle invalid date input
            return redirect()->route('admin.dashboard')->withErrors(['date' => 'Invalid date format provided.']);
        }

        // Ensure startDate is not after endDate
        if ($startDate->gt($endDate)) {
            return redirect()->route('admin.dashboard')->withErrors(['date' => 'Start date cannot be after end date.']);
        }

        // Calculate previous period for trends
        $previousStartDate = $startDate->copy()->subDays($endDate->diffInDays($startDate))->startOfDay();
        $previousEndDate = $startDate->copy()->subDay()->endOfDay();

        // Initialize queries with proper date boundaries
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $previousOrdersQuery = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate]);
        $transactionsQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);
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

        // Debug: Check if orders exist in the date range
        $orderCount = $ordersQuery->count();
        if ($orderCount === 0) {
            \Log::info("No orders found for range: {$startDate} to {$endDate}");
        }

        // Fetch data
        $currentOrders = $ordersQuery->get();
        $previousOrders = $previousOrdersQuery->get();
        $transactions = $transactionsQuery->get();
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

        foreach ($transactions as $transaction) {
            if ($transaction->amount_type == 'in') {
                $totalAmountIn += $transaction->amount;
            } elseif ($transaction->amount_type == 'out') {
                $totalAmountOut += $transaction->amount;
            }
        }
        $totalWallet = $totalAmountIn - $totalAmountOut;

        // Inventory Stocks Calculations
        $totalProducts = Product::count();

        // Low Stock Products
        $lowStockQuery = ProductStockBatch::join('products as p', 'product_stock_batches.product_id', '=', 'p.id')
            ->leftJoin('product_variations as pv', 'product_stock_batches.product_variation_id', '=', 'pv.id')
            ->select(
                'p.product_name',
                'p.product_sku',
                'pv.variation_name',
                'pv.variation_value',
                'product_stock_batches.quantity'
            )
            ->where('product_stock_batches.quantity', '>', 0)
            ->where('product_stock_batches.quantity', '<=', 10);

        $lowStock = $lowStockQuery->count();
        $lowStockProducts = $lowStockQuery->get();

        // Out of Stock Products
        $outOfStockQuery = ProductStockBatch::join('products as p', 'product_stock_batches.product_id', '=', 'p.id')
            ->leftJoin('product_variations as pv', 'product_stock_batches.product_variation_id', '=', 'pv.id')
            ->select(
                'p.product_name',
                'p.product_sku',
                'pv.variation_name',
                'pv.variation_value',
                'product_stock_batches.quantity'
            )
            ->where('product_stock_batches.quantity', '=', 0);

        $outOfStock = $outOfStockQuery->count();
        $outOfStockProducts = $outOfStockQuery->get();

        // Pass data to the view
        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'activeCustomers' => $activeCustomers,
            'deliveryRatio' => $deliveryRatio,
            'salesGrowth' => $salesGrowth,
            'totalEarnings' => $totalEarnings,
            'revenueTrend' => $revenueTrend,
            'ordersTrend' => $ordersTrend,
            'customersTrend' => $customersTrend,
            'deliveryTrend' => $deliveryTrend,
            'earningsGrowth' => $earningsGrowth,
            'totalWallet' => $totalWallet,
            'totalAmountIn' => $totalAmountIn,
            'totalAmountOut' => $totalAmountOut,
            'totalProducts' => $totalProducts,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'topAreas' => $topAreas,
            'topTenAreas' => $topTenAreas,
            'topStates' => $topStates,
            'topTenStates' => $topTenStates,
            'topProducts' => $topProducts,
            'topTenProducts' => $topTenProducts,
            'totalDeliveredQuantity' => $totalDeliveredQuantity,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString()
        ]);
    }
}