<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ServiceCharge;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\productVariation;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Yajra\DataTables\DataTables;
use Milon\Barcode\DNS2D;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductStockBatch;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            $states = State::all();
            $cartItems = \App\Models\Cart::where('sub_seller_id', auth()->id())->count();
            if ($cartItems <= 0) {
                return redirect()->route('all_cart');
            }
            ActivityLogger::UserLog(Auth::user()->name . ' Open checkout');

            return view('seller.pages.products.checkout', compact('states'));
        }
    }
    public function getCartData()
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            if (Auth::user()->role == 'seller') {
                $cartItems = Cart::where('seller_id', Auth::id())
                    ->with(['product', 'productVariation', 'ProductStockBatch'])
                    ->get();
            } else {
                $cartItems = Cart::where('sub_seller_id', Auth::id())
                    ->with(['product', 'productVariation', 'ProductStockBatch'])
                    ->get();
            }

            $html = view('seller.pages.products.partials.cart_items', compact('cartItems'))->render();
            return response()->json(['html' => $html]);
        }
    }
    public function updateCartAjax(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            $cart = Cart::find($request->cart_id);
            if (!$cart) {
                return response()->json(['status' => 0, 'message' => 'Cart item not found']);
            }

            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json(['status' => 0, 'message' => 'Product not found']);
            }
            $productStockBatch =  ProductStockBatch::where('id', $cart->batch_id)->first();
            $newQuantity = (int) $request->quantity;
            $oldQuantity = $cart->quantity;
            $quantityDifference = $newQuantity - $oldQuantity;
            if ($productStockBatch->quantity < $quantityDifference) {
                return response()->json(['status' => 0, 'message' => 'Not enough stock']);
            }
            $productStockBatch->quantity -= $quantityDifference;
            $productStockBatch->save();


            $cart->quantity = $newQuantity;
            $cart->save();
            ActivityLogger::UserLog(Auth::user()->name . ' Update Cart items');
            return response()->json(['status' => 1, 'message' => 'Cart updated successfully']);
        }
    }
    public function get_areas(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            $areas = Area::where('state_id', '=', $request->state_id)->get();
            $options = '<option value="" disabled selected>Choose Area</option>';
            foreach ($areas as $area) {
                $options .= '<option value="' . $area->id . '">' . $area->area . '</option>';
            }
            return response()->json(['options' => $options]);
        }
    }

    public function get_areas_shipping(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            $areas = Area::where('id', '=', $request->id)->first();
            $shipping = $areas->shipping;
            return response()->json(['shipping' => $shipping]);
        }
    }
    public function get_cart_subtotal(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            $user_id = auth()->id();
            if (Auth::user()->role == 'seller') {
                $cartItems  = Cart::where('seller_id', $user_id)->get();
            } else {
                $cartItems  = Cart::where('sub_seller_id', $user_id)->get();
            }
            $subtotal = 0;

            foreach ($cartItems as $cart) {
                $product  = Product::where('id', $cart->product_id)->first();
                $ProductStockBatch  = ProductStockBatch::where('id', $cart->batch_id)->first();
                $price = $ProductStockBatch->regular_price;

                $subtotal += $price * $cart->quantity;
            }
            return response()->json(['subtotal' => $subtotal]);
        }
    }
    public function get_seller_service_charges(Request $request)
    {
        if (!ActivityLogger::hasSellerPermission('orders', 'checkout')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        $cod_amount = (float) $request->cod_amount;

        $sellerId = Auth::user()->role === 'seller'
            ? Auth::id()
            : Auth::user()->seller_id;
        $serviceCharge = ServiceCharge::where('user_id', $sellerId)
            ->orderBy('start_range')
            ->get()
            ->first(function ($charge) use ($cod_amount) {
                return $cod_amount >= $charge->start_range && $cod_amount <= $charge->end_range;
            });

        if ($serviceCharge) {
            return response()->json([
                'status' => true,
                'details' => $serviceCharge
            ]);
        }

        return response()->json([
            'status' => true,
            'details' => ''
        ]);
    }
    // public function index(Request $request) {
    //     if (ActivityLogger::hasSellerPermission('orders', 'view')) {
    //     if ($request->ajax()) {
    //         $query = Order::query();
    //         if (!empty($request->state)) {
    //             $query->where('state_id', $request->state);
    //         }

    //         if (!empty($request->area)) {
    //             $query->where('area_id', $request->area);
    //         }
    //             if (!empty($request->status)) {
    //                 if($request->status == 'Delivered' || $request->status == 'Future'){
    //                     if (!empty($request->current_date)) {
    //                         $query->whereDate('delivery_date', '=', $request->current_date);
    //                     }
    //                     if (!empty($request->start_date)) {
    //                         $query->whereDate('delivery_date', '>=', $request->start_date);
    //                     }

    //                     if (!empty($request->end_date)) {
    //                         $query->whereDate('delivery_date', '<=', $request->end_date);
    //                     }
    //                 }else if($request->status == 'Processing'){
    //                     if (!empty($request->current_date)) {
    //                         $query->whereDate('company_assign_date', '=', $request->current_date);
    //                     }
    //                     if (!empty($request->start_date)) {
    //                         $query->whereDate('company_assign_date', '>=', $request->start_date);
    //                     }

    //                     if (!empty($request->end_date)) {
    //                         $query->whereDate('company_assign_date', '<=', $request->end_date);
    //                     }
    //                 }else if($request->status == 'Shipped'|| $request->status == 'Out_for_delivery'){
    //                     if (!empty($request->current_date)) {
    //                         $query->whereDate('driver_assign_date', '=', $request->current_date);
    //                     }
    //                     if (!empty($request->start_date)) {
    //                         $query->whereDate('driver_assign_date', '>=', $request->start_date);
    //                     }

    //                     if (!empty($request->end_date)) {
    //                         $query->whereDate('driver_assign_date', '<=', $request->end_date);
    //                     }
    //                 }else{
    //                     if (!empty($request->current_date)) {
    //                         $query->whereDate('created_at', '=', $request->current_date);
    //                     }

    //                     if (!empty($request->start_date)) {
    //                         $query->whereDate('created_at', '>=', $request->start_date);
    //                     }

    //                     if (!empty($request->end_date)) {
    //                         $query->whereDate('created_at', '<=', $request->end_date);
    //                     }
    //                 }
    //                     $query->where('status', $request->status);

    //             }
    //         if (empty($request->status) && !empty($request->current_date)) {
    //                 $query->whereDate('created_at', '=', $request->current_date);
    //             }

    //             if (empty($request->status) && !empty($request->start_date)) {
    //                 $query->whereDate('created_at', '>=', $request->start_date);
    //             }

    //             if (empty($request->status) && !empty($request->end_date)) {
    //                 $query->whereDate('created_at', '<=', $request->end_date);
    //             }
    //         if (Auth::user()->role == 'seller'){
    //             $data = $query->where('seller_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
    //         }else{
    //             $data = $query->where('sub_seller_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
    //         }
    //         $totalCod = 0;
    //         $deliveredCod = 0;
    //         $cancelledCod = 0;
    //         $totalProfit = 0;
    //         $totalShipping = 0;
    //         $totalPendingCount = 0;
    //         $totalProcessingCount = 0;
    //         $totalShippedCount = 0;
    //         $totalDeliveredCount = 0;
    //         $totalCancelledCount = 0;
    //         $totalOut_for_deliveryCount = 0;
    //         $totalFutureCount = 0;
    //         foreach ($data as $item) {
    //             $totalCod += $item->cod_amount;
    //             if($item->status == 'Pending'){
    //                 $totalPendingCount += 1;
    //             }elseif($item->status == 'Processing'){
    //                 $totalProcessingCount += 1;
    //             }elseif($item->status == 'Shipped'){
    //                 $totalShippedCount += 1;
    //             }elseif($item->status == 'Delivered'){
    //                 $deliveredCod += $item->cod_amount;
    //                 $totalShipping += $item->shipping_fee;
    //                 $totalProfit += $item->profit;
    //                 $totalDeliveredCount += 1;
    //             }elseif($item->status == 'Cancelled'){
    //                 $cancelledCod += $item->cod_amount;
    //                 $totalCancelledCount += 1;
    //             }elseif($item->status == 'Out_for_delivery'){
    //                 $totalOut_for_deliveryCount += 1;
    //             }elseif($item->status == 'Future'){
    //                 $totalFutureCount += 1;
    //             }
    //         }
    //         return Datatables::of($data)
    //             ->addColumn('customerName', function($data) {
    //                 if (!empty($data->customer_name)){
    //                     $customer_name = ucfirst($data->customer_name);
    //                 }else{
    //                     $customer_name = "N/A";
    //                 }
    //                 return $customer_name;
    //             })
    //             ->addColumn('OrderPlacedBy', function($data) {
    //                 if (!empty($data->sub_seller_id)){
    //                   $sellerData =  User::where('id',$data->sub_seller_id)->first();
    //                     $OrderPlacedBy = ucfirst($sellerData->name);
    //                 }else{
    //                     $OrderPlacedBy = "N/A";
    //                 }
    //                 return $OrderPlacedBy;
    //             })
    //             ->addColumn('BulkAction', function($data) {
    //                 $BulkAction = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="bulk_action[]" value="'.$data->id.'" ></div>';
    //             return $BulkAction;
    //         })
    //             ->addColumn('COD', function($data) {
    //                 if (!empty($data->cod_amount)){
    //                     $cod_amount = $data->cod_amount. ' AED';
    //                 }else{
    //                     $cod_amount = 'N/A';
    //                 }
    //                 if (!empty($data->changed_cod_amount)){
    //                     $changed_cod_amount = 'Requested COD: '.$data->changed_cod_amount. ' AED';
    //                 }else{
    //                     $changed_cod_amount = '';
    //                 }
    //                 $COD = '<div class="d-flex align-items-center">
    //                     <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
    //                     <div class="d-flex flex-column">
    //                         <a  class="text-gray-800 text-hover-primary mb-1">' . $cod_amount . '</a>
    //                         <span>' . $changed_cod_amount . '</span>
    //                     </div>
    //                 </div>';
    //                 return $COD;
    //             })
    //             ->addColumn('Location', function($data) {
    //                 $stateData =  State::where('id',$data->state_id)->first();
    //                 $areaData =  Area::where('id',$data->area_id)->first();
    //                 $Location = '<div class="d-flex align-items-center">
    //                     <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
    //                     <div class="d-flex flex-column">
    //                         <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($stateData->state) . '</a>
    //                         <span>' . ucfirst($areaData->area) . '</span>
    //                     </div>
    //                 </div>';
    //                 return $Location;
    //             })
    //             ->addColumn('statusView', function($data) {
    //                 $statusLabels = [
    //                     'Pending' => ['class' => 'bg-warning', 'text' => 'Pending'],
    //                     'Processing' => ['class' => 'bg-primary', 'text' => 'Processing'],
    //                     'Shipped' => ['class' => 'bg-info', 'text' => 'Shipped'],
    //                     'Delivered' => ['class' => 'bg-success', 'text' => 'Delivered'],
    //                     'Cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled'],
    //                     'Future' => ['class' => 'bg-primary', 'text' => 'Future'],
    //                     'Out_for_delivery' => ['class' => 'bg-primary', 'text' => 'Out For Delivery']
    //                 ];
    //                 if (isset($statusLabels[$data->status])) {
    //                     return "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
    //                 }
    //                 return "<div class='badge bg-secondary'>Unknown</div>";
    //             })
    //             ->addColumn('action', function($data) {
    //                 $action = '';

    //                 $action .= '<a href="#" class="view btn btn-sm btn-dark me-2" data-id="'.$data->id.'"
    //                 data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
    //                 <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
    //             </a>';
    //                 if ($data->status == 'Pending'){
    //                     $action .= '<a href="'.route('orders.edit',$data->id).'" class="btn btn-sm btn-info me-2" >
    //               <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
    //             </a>';
    //                 }

    //                 return $action;
    //             })
    //             ->with('totalCod', $totalCod)
    //             ->with('deliveredCod', $deliveredCod)
    //             ->with('cancelledCod', $cancelledCod)
    //             ->with('totalProfit', $totalProfit)
    //             ->with('totalShipping', $totalShipping)
    //             ->with('totalPendingCount', $totalPendingCount)
    //             ->with('totalProcessingCount', $totalProcessingCount)
    //             ->with('totalShippedCount', $totalShippedCount)
    //             ->with('totalDeliveredCount', $totalDeliveredCount)
    //             ->with('totalCancelledCount', $totalCancelledCount)
    //             ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
    //             ->with('totalFutureCount', $totalFutureCount)
    //             ->rawColumns(['BulkAction','COD','Location','OrderPlacedBy','customerName','statusView','action'])
    //             ->make(true);
    //     }
    //         ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page');
    //         $StateData = State::orderBy('id', 'DESC')->get();
    //         $AreaData = Area::orderBy('id', 'DESC')->get();
    //     return view('seller.pages.products.order.all',compact('StateData','AreaData'));
    // }
    // }
    public function index(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'view')) {
            if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }

                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                if (!empty($request->status)) {
                    if ($request->status == 'Delivered' || $request->status == 'Future') {
                        if (!empty($request->current_date)) {
                            $query->whereDate('delivery_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('delivery_date', '>=', $request->start_date);
                        }

                        if (!empty($request->end_date)) {
                            $query->whereDate('delivery_date', '<=', $request->end_date);
                        }
                    } else if ($request->status == 'Processing') {
                        if (!empty($request->current_date)) {
                            $query->whereDate('company_assign_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('company_assign_date', '>=', $request->start_date);
                        }

                        if (!empty($request->end_date)) {
                            $query->whereDate('company_assign_date', '<=', $request->end_date);
                        }
                    } else if ($request->status == 'Shipped' || $request->status == 'Out_for_delivery') {
                        if (!empty($request->current_date)) {
                            $query->whereDate('driver_assign_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('driver_assign_date', '>=', $request->start_date);
                        }

                        if (!empty($request->end_date)) {
                            $query->whereDate('driver_assign_date', '<=', $request->end_date);
                        }
                    } else {
                        if (!empty($request->current_date)) {
                            $query->whereDate('created_at', '=', $request->current_date);
                        }

                        if (!empty($request->start_date)) {
                            $query->whereDate('created_at', '>=', $request->start_date);
                        }

                        if (!empty($request->end_date)) {
                            $query->whereDate('created_at', '<=', $request->end_date);
                        }
                    }
                    $query->where('status', $request->status);
                }
                if (empty($request->status) && !empty($request->current_date)) {
                    $query->whereDate('created_at', '=', $request->current_date);
                }

                if (empty($request->status) && !empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }

                if (empty($request->status) && !empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }

                if (empty($request->status) && empty($request->start_date) && empty($request->end_date) && empty($request->current_date)) {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                }


                if (Auth::user()->role == 'seller') {
                    $data = $query->where('seller_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                } else {
                    $data = $query->where('sub_seller_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                }








                $totalCod = 0;
                $deliveredCod = 0;
                $cancelledCod = 0;
                $totalProfit = 0;
                $totalShipping = 0;
                $totalPendingCount = 0;
                $totalProcessingCount = 0;
                $totalShippedCount = 0;
                $totalDeliveredCount = 0;
                $totalCancelledCount = 0;
                $totalOut_for_deliveryCount = 0;
                $totalFutureCount = 0;
                foreach ($data as $item) {
                    $totalCod += $item->cod_amount;
                    if ($item->status == 'Pending') {
                        $totalPendingCount += 1;
                    } elseif ($item->status == 'Processing') {
                        $totalProcessingCount += 1;
                    } elseif ($item->status == 'Shipped') {
                        $totalShippedCount += 1;
                    } elseif ($item->status == 'Delivered') {
                        $deliveredCod += $item->cod_amount;
                        $totalShipping += $item->shipping_fee;
                        $totalProfit += $item->profit;
                        $totalDeliveredCount += 1;
                    } elseif ($item->status == 'Cancelled') {
                        $cancelledCod += $item->cod_amount;
                        $totalCancelledCount += 1;
                    } elseif ($item->status == 'Out_for_delivery') {
                        $totalOut_for_deliveryCount += 1;
                    } elseif ($item->status == 'Future') {
                        $totalFutureCount += 1;
                    }
                }

                $totalOrdersCount =
                    $totalPendingCount +
                    $totalProcessingCount +
                    $totalShippedCount +
                    $totalDeliveredCount +
                    $totalCancelledCount +
                    $totalOut_for_deliveryCount +
                    $totalFutureCount;
                return Datatables::of($data)
                    ->addColumn('customerName', function ($data) {
                        if (!empty($data->customer_name)) {
                            $customer_name = ucfirst($data->customer_name);
                        } else {
                            $customer_name = "N/A";
                        }
                        return $customer_name;
                    })
                    ->addColumn('OrderPlacedBy', function ($data) {
                        if (!empty($data->sub_seller_id)) {
                            $sellerData =  User::where('id', $data->sub_seller_id)->first();
                            $OrderPlacedBy = ucfirst($sellerData->name);
                        } else {
                            $OrderPlacedBy = "N/A";
                        }
                        return $OrderPlacedBy;
                    })
                    ->addColumn('BulkAction', function ($data) {
                        $BulkAction = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="bulk_action[]" value="' . $data->id . '" ></div>';
                        return $BulkAction;
                    })
                    ->addColumn('COD', function ($data) {
                        if (!empty($data->cod_amount)) {
                            $cod_amount = $data->cod_amount . ' AED';
                        } else {
                            $cod_amount = 'N/A';
                        }
                        if (!empty($data->changed_cod_amount)) {
                            $changed_cod_amount = 'Requested COD: ' . $data->changed_cod_amount . ' AED';
                        } else {
                            $changed_cod_amount = '';
                        }
                        $COD = '<div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
                        <div class="d-flex flex-column">
                            <a  class="text-gray-800 text-hover-primary mb-1">' . $cod_amount . '</a>
                            <span>' . $changed_cod_amount . '</span>
                        </div>
                    </div>';
                        return $COD;
                    })
                    ->addColumn('custom_order_id', function ($data) {
                        $subSeller = User::find($data->sub_seller_id);
                        $subSellerUniqueId = $subSeller->unique_id ?? 'N/A';
                        return $subSellerUniqueId . '-' . $data->id;
                    })
                    ->addColumn('Location', function ($data) {
                        $stateData =  State::where('id', $data->state_id)->first();
                        $areaData =  Area::where('id', $data->area_id)->first();
                        $Location = '<div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($stateData->state) . '</a>
                            <span>' . ucfirst($areaData->area) . '</span>
                        </div>
                    </div>';
                        return $Location;
                    })
                    ->addColumn('statusView', function ($data) {
                        $statusLabels = [
                            'Pending' => ['class' => 'bg-warning', 'text' => 'Pending'],
                            'Processing' => ['class' => 'bg-primary', 'text' => 'Processing'],
                            'Shipped' => ['class' => 'bg-info', 'text' => 'Shipped'],
                            'Delivered' => ['class' => 'bg-success', 'text' => 'Delivered'],
                            'Cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled'],
                            'Future' => ['class' => 'bg-primary', 'text' => 'Future'],
                            'Out_for_delivery' => ['class' => 'bg-primary', 'text' => 'Out For Delivery']
                        ];
                        if (isset($statusLabels[$data->status])) {
                            return "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
                        }
                        return "<div class='badge bg-secondary'>Unknown</div>";
                    })
                    ->addColumn('action', function ($data) {
                        $action = '';

                        $action .= '<a href="#" class="view btn btn-sm btn-dark me-2" data-id="' . $data->id . '"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        if ($data->status == 'Pending') {
                            $action .= '<a href="' . route('orders.edit', $data->id) . '" class="btn btn-sm btn-info me-2" >
                   <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                </a>';
                        }

                        return $action;
                    })
                    ->with('totalCod', number_format($totalCod, 2, '.', ''))
                    ->with('deliveredCod', number_format($deliveredCod, 2, '.', ''))
                    ->with('cancelledCod', number_format($cancelledCod, 2, '.', ''))
                    ->with('totalProfit', number_format($totalProfit, 2, '.', ''))
                    ->with('totalShipping', number_format($totalShipping, 2, '.', ''))
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->with('totalOrdersCount', $totalOrdersCount)
                    ->rawColumns(['BulkAction', 'COD', 'Location', 'OrderPlacedBy', 'customerName', 'statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name . ' Open Order Page');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            return view('seller.pages.products.order.all', compact('StateData', 'AreaData'));
        }
    }

    public function get_order(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'view')) {
            $id = $request->id;
            $order = Order::with([
                'seller' => function ($query) {
                    $query->select('id', 'name', 'unique_id');
                },
                'subSeller' => function ($query) {
                    $query->select('id', 'name', 'unique_id');
                },
                'state' => function ($query) {
                    $query->select('id', 'state');
                },
                'area' => function ($query) {
                    $query->select('id', 'area');
                },
                'orderItems.product' => function ($query) {
                    $query->select('id', 'product_name', 'product_image');
                },
                'orderItems.productVariation' => function ($query) {
                    $query->select('id', 'variation_name', 'variation_value', 'variation_image');
                }
            ])->find($id);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            return response()->json([
                'order' => $order
            ]);
        }
    }
    public function placeOrder(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'add')) {
            if (empty($request->cus_name) || empty($request->phone) || empty($request->state) || empty($request->areas) || empty($request->address) || empty($request->total) || empty($request->profit)) {
                return response()->json(2);
            }
            // Normalize WhatsApp number by removing spaces
        $whatsappNumber = preg_replace('/\s+/', '', $request->whatsapp);
            if (Auth::user()->role == 'seller') {
                $seller_id = Auth::user()->id;
                $sub_seller_id = Auth::user()->id;
            } else {
                $seller_id = Auth::user()->seller_id;
                $sub_seller_id = Auth::user()->id;
            }
            $order = Order::create([
                'seller_id'       => $seller_id,
                'sub_seller_id'  => $sub_seller_id,
                'customer_name' => $request->cus_name,
                'phone'         => $request->phone,
                'whatsapp'      => $whatsappNumber, // Use normalized number
                'state_id'      => $request->state,
                'area_id'       => $request->areas,
                'instructions'  => $request->instructions,
                'address'       => $request->address,
                'map_url'       => $request->map_url,
                'subtotal'      => $request->subtotal,
                'shipping_fee'  => $request->shipping,
                'total'         => $request->total,
                'cod_amount'    => $request->cod_amount,
                'profit'    => $request->profit,
                'service_charges'    => $request->service_charge_input,
                'service_charges_id'    => $request->service_charge_input_id,
                'status'        => 'Pending',
            ]);
            $qrCode = new DNS2D();
            $publicURL = route('order_public_details', encrypt($order->id));
            $qrCodeImage = $qrCode->getBarcodePNG($publicURL, 'QRCODE', 5, 5);
            $qrCodeBinary = base64_decode($qrCodeImage);
            $qrCodeFileName = 'qr_order_' . time() . '.png';
            Storage::put('public/order_qrcodes/' . $qrCodeFileName, $qrCodeBinary);
            $qrCodeRto = new DNS2D();
            $publicRTOURL = route('order_public_rto', encrypt($order->id));
            $qrCodeRtoImage = $qrCodeRto->getBarcodePNG($publicRTOURL, 'QRCODE', 5, 5);
            $qrCodeRtoBinary = base64_decode($qrCodeRtoImage);
            $qrCodeRtoFileName = 'qr_order_rto_' . time() . '.png';
            Storage::put('public/order_qrcodes/' . $qrCodeRtoFileName, $qrCodeRtoBinary);
            $order->qr_code = $qrCodeFileName;
            $order->qr_code_rto = $qrCodeRtoFileName;
            $order->save();
            if (Auth::user()->role == 'seller') {
                $cartItems = Cart::where('seller_id', auth()->id())->get();
            } else {
                $cartItems = Cart::where('sub_seller_id', auth()->id())->get();
            }
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'            => $order->id,
                    'product_id'          => $item->product_id,
                    'product_variation_id' => $item->product_variation_id,
                    'batch_id' => $item->batch_id,
                    'quantity'            => $item->quantity,
                ]);
            }
            if (Auth::user()->role == 'seller') {
                Cart::where('seller_id', auth()->id())->delete();
            } else {
                Cart::where('sub_seller_id', auth()->id())->delete();
            }

            ActivityLogger::UserLog('Placed an order: ' . $order->id);
            ActivityLogger::UserLog(Auth::user()->name . ' Place Order');
            return response()->json(1);
        }
    }
    public function edit($id)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'edit')) {
            $order = Order::with([
                'orderItems.product',
                'orderItems.productVariation',
                'orderItems.productStockBatch',
                'state',
                'area'
            ])->findOrFail($id);

            $states = State::all();
            $areas = Area::where('state_id', $order->state_id)->get();

            // Get all products that can be added to the order
            $products = Product::where('status', 'active')
                ->with([
                    'batches' => function ($query) {
                        $query->where('quantity', '>', 0)
                            ->orderBy('purchase_date', 'asc');
                    },
                    'variations' => function ($query) {
                        $query->with(['batches' => function ($batchQuery) {
                            $batchQuery->where('quantity', '>', 0)
                                ->orderBy('purchase_date', 'asc');
                        }]);
                    }
                ])
                ->get();

            ActivityLogger::UserLog(Auth::user()->name . ' Edit order: ' . $id);
            return view('seller.pages.products.order.edit', compact('order', 'states', 'areas', 'products'));
        }
    }
    public function update(Request $request, $id)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'edit')) {
            $order = Order::findOrFail($id);

            $request->validate([
                'customer_name' => 'required',
                'phone' => 'required',
                'state' => 'required',
                'areas' => 'required',
                'address' => 'required',
                'cod_amount' => 'required|numeric|min:0'
            ]);

            // Begin transaction
            DB::beginTransaction();

            try {
                // Update basic order info
                $order->update([
                    'customer_name' => $request->customer_name,
                    'phone' => $request->phone,
                    'whatsapp' => $request->whatsapp,
                    'state_id' => $request->state,
                    'area_id' => $request->areas,
                    'instructions' => $request->instructions,
                    'address' => $request->address,
                    'map_url' => $request->map_url,
                    'cod_amount' => $request->cod_amount,
                ]);

                // Recalculate all totals
                $totals = $this->recalculateOrderTotals($order);

                DB::commit();

                ActivityLogger::UserLog(Auth::user()->name . ' Updated order: ' . $id);
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully',
                    'totals' => $totals
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }
    public function addItemToOrder(Request $request, $orderId)
    {
        if (!ActivityLogger::hasSellerPermission('orders', 'edit')) return abort(403);

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'variation_id' => 'nullable'
        ]);

        $order = Order::findOrFail($orderId);
        $product = Product::findOrFail($request->product_id);

        DB::beginTransaction();
        try {
            $existingItem = OrderItem::where('order_id', $orderId)
                ->where('product_id', $request->product_id)
                ->when($request->variation_id, function ($q) use ($request) {
                    return $q->where('product_variation_id', $request->variation_id);
                }, function ($q) {
                    return $q->whereNull('product_variation_id');
                })
                ->first();

            $requiredQty = $request->quantity;
            $consumedBatches = [];

            $batchQuery = ProductStockBatch::where('product_id', $product->id)
                ->where('quantity', '>', 0)
                ->orderBy('purchase_date', 'asc');

            if ($product->product_type === 'variable') {
                if (!$request->variation_id) {
                    throw new \Exception('Variation is required for variable products');
                }
                $batchQuery->where('product_variation_id', $request->variation_id);
            } else {
                $batchQuery->whereNull('product_variation_id');
            }

            $batches = $batchQuery->get();
            $remainingQty = $requiredQty;

            foreach ($batches as $batch) {
                if ($remainingQty <= 0) break;

                $deductQty = min($batch->quantity, $remainingQty);
                $batch->quantity -= $deductQty;
                $batch->save();

                $consumedBatches[] = [
                    'batch_id' => $batch->id,
                    'used_quantity' => $deductQty
                ];

                $remainingQty -= $deductQty;
            }

            if ($remainingQty > 0) {
                throw new \Exception('Not enough stock available in FIFO batches');
            }

            if ($existingItem) {
                $existingItem->quantity += $requiredQty;
                $existingItem->save();
            } else {
                $orderItem = OrderItem::create([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'product_variation_id' => $request->variation_id,
                    'quantity' => $requiredQty,
                    'batch_id' => $consumedBatches[0]['batch_id'] ?? null,
                ]);
            }

            $this->recalculateOrderTotals($order);
            DB::commit();

            ActivityLogger::UserLog(Auth::user()->name . ' Added item to order: ' . $orderId);
            return response()->json([
                'success' => true,
                'message' => 'Item added to order successfully',
                'stock' => $batches->sum('quantity') // Optional
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateOrderItem(Request $request, $orderId)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'edit')) {
            $request->validate([
                'item_id' => 'required',
                'quantity' => 'required|numeric|min:1'
            ]);

            $orderItem = OrderItem::with(['product', 'productVariation', 'productStockBatch'])
                ->where('order_id', $orderId)
                ->where('id', $request->item_id)
                ->firstOrFail();

            $oldQuantity = $orderItem->quantity;
            $newQuantity = $request->quantity;
            $quantityDifference = $newQuantity - $oldQuantity;

            // Begin transaction for atomic operations
            DB::beginTransaction();

            try {
                $productStockBatch = ProductStockBatch::where('id', $orderItem->batch_id)->first();
                if ($quantityDifference > 0 && $orderItem->productStockBatch->quantity < $quantityDifference) {
                    throw new \Exception('Not enough stock available for this variation');
                }

                $productStockBatch->quantity -= $quantityDifference;
                $productStockBatch->save();

                // Update order item
                $orderItem->quantity = $newQuantity;
                $orderItem->save();

                // Recalculate order totals
                $order = Order::findOrFail($orderId);
                $this->recalculateOrderTotals($order);

                DB::commit();

                ActivityLogger::UserLog(Auth::user()->name . ' Updated item in order: ' . $orderId);
                return response()->json([
                    'success' => true,
                    'message' => 'Item updated successfully',
                    'new_stock' => $orderItem->productStockBatch->quantity
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }
    public function removeOrderItem(Request $request, $orderId)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'edit')) {
            $request->validate([
                'item_id' => 'required'
            ]);

            $orderItem = OrderItem::with(['product', 'productVariation'])
                ->where('order_id', $orderId)
                ->where('id', $request->item_id)
                ->firstOrFail();

            // Begin transaction for atomic operations
            DB::beginTransaction();

            try {
                $productStockBatch = ProductStockBatch::where('id', $orderItem->batch_id)->first();
                $productStockBatch->quantity += $orderItem->quantity;
                $productStockBatch->save();


                // Delete order item
                $orderItem->delete();

                // Recalculate order totals
                $order = Order::findOrFail($orderId);
                $this->recalculateOrderTotals($order);

                DB::commit();

                ActivityLogger::UserLog(Auth::user()->name . ' Removed item from order: ' . $orderId);
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed successfully',
                    'restored_stock' => $orderItem->productStockBatch->quantity
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }
    private function recalculateOrderTotals($order)
    {
        // Calculate subtotal from all order items
        $subtotal = 0;

        foreach ($order->orderItems as $item) {

            $price = $item->productStockBatch->regular_price;

            $subtotal += $price * $item->quantity;
        }

        // Get shipping fee from area (or use existing if area not changed)
        $shipping = $order->area->shipping ?? $order->shipping_fee;
        $services_charge = $order->service_charges ?? 0;
        // Calculate total
        $total = $subtotal + $shipping + $services_charge;

        // Calculate profit (COD - Total)
        $profit = $order->cod_amount - $total;

        // Update order with new values
        $order->update([
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total' => $total,
            'profit' => $profit
        ]);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'profit' => $profit
        ];
    }
    public function getProductVariations(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('orders', 'edit')) {
            $productId = $request->product_id;
            $variations = productVariation::where('product_id', $productId)->get();

            $options = '<option value="">Select Variation</option>';
            foreach ($variations as $variation) {
                $options .= '<option value="' . $variation->id . '">' . $variation->variation_name . ': ' . $variation->variation_value . '</option>';
            }

            return response()->json(['options' => $options]);
        }
    }
}
