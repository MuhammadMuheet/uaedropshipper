<?php

namespace App\Http\Controllers\logistic_company;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request) {
            if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }

                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                 if (!empty($request->status)) {
                    if($request->status == 'Delivered' || $request->status == 'Future'){
                        if (!empty($request->current_date)) {
                            $query->whereDate('delivery_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('delivery_date', '>=', $request->start_date);
                        }
        
                        if (!empty($request->end_date)) {
                            $query->whereDate('delivery_date', '<=', $request->end_date);
                        }
                    }else if($request->status == 'Processing'){
                        if (!empty($request->current_date)) {
                            $query->whereDate('company_assign_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('company_assign_date', '>=', $request->start_date);
                        }
        
                        if (!empty($request->end_date)) {
                            $query->whereDate('company_assign_date', '<=', $request->end_date);
                        }
                    }else if($request->status == 'Shipped'|| $request->status == 'Out_for_delivery'){
                        if (!empty($request->current_date)) {
                            $query->whereDate('driver_assign_date', '=', $request->current_date);
                        }
                        if (!empty($request->start_date)) {
                            $query->whereDate('driver_assign_date', '>=', $request->start_date);
                        }
        
                        if (!empty($request->end_date)) {
                            $query->whereDate('driver_assign_date', '<=', $request->end_date);
                        }
                    }else{
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
                if (!empty($request->drivers)) {
                    $query->where('driver_id', $request->drivers);
                }
                    if (empty($request->status) && !empty($request->current_date)) {
                    $query->whereDate('company_assign_date', '=', $request->current_date);
                }

                if (empty($request->status) && !empty($request->start_date)) {
                    $query->whereDate('company_assign_date', '>=', $request->start_date);
                }

                if (empty($request->status) && !empty($request->end_date)) {
                    $query->whereDate('company_assign_date', '<=', $request->end_date);
                }
                $data = $query->where('company_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
                $totalCod = 0;
                $deliveredCod = 0;
                $cancelledCod = 0;
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
                    if($item->status == 'Pending'){
                        $totalPendingCount += 1;
                    }elseif($item->status == 'Processing'){
                        $totalProcessingCount += 1;
                    }elseif($item->status == 'Shipped'){
                        $totalShippedCount += 1;
                    }elseif($item->status == 'Delivered'){
                        $deliveredCod += $item->cod_amount;
                        $totalShipping += $item->shipping_fee;
                        $totalDeliveredCount += 1;
                    }elseif($item->status == 'Cancelled'){
                        $cancelledCod += $item->cod_amount;
                        $totalCancelledCount += 1;
                    }elseif($item->status == 'Out_for_delivery'){
                        $totalOut_for_deliveryCount += 1;
                    }elseif($item->status == 'Future'){
                        $totalFutureCount += 1;
                    }
                }
                return Datatables::of($data)
                    ->addColumn('customerName', function($data) {
                        if (!empty($data->customer_name)){
                            $customer_name = ucfirst($data->customer_name);
                        }else{
                            $customer_name = "N/A";
                        }
                        return $customer_name;
                    })
                    ->addColumn('BulkAction', function($data) {
                        $BulkAction = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="bulk_action[]" value="'.$data->id.'" ></div>';
                        return $BulkAction;
                    })
                    ->addColumn('Driver', function($data) {
                        $DriverData =  User::where('id',$data->driver_id)->first();
                        if (!empty($DriverData)){
                            $Driver = ucfirst($DriverData->name);
                        }else{
                            $Driver = 'N/A';
                        }
                        return $Driver;
                    })
                    ->addColumn('Location', function($data) {
                        $stateData =  State::where('id',$data->state_id)->first();
                        $areaData =  Area::where('id',$data->area_id)->first();
                        $Location = '<div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($stateData->state) . '</a>
                            <span>' . ucfirst($areaData->area) . '</span>
                        </div>
                    </div>';
                        return $Location;
                    })
                    ->addColumn('OrderPlacedBy', function($data) {
                        if (!empty($data->sub_seller_id)){
                            $sellerData =  User::where('id',$data->sub_seller_id)->first();
                            $OrderPlacedBy = ucfirst($sellerData->name);
                        }else{
                            $OrderPlacedBy = "N/A";
                        }
                        return $OrderPlacedBy;
                    })
                    ->addColumn('SellerName', function($data) {
                        if (!empty($data->seller_id)){
                            $sellerData =  User::where('id',$data->seller_id)->first();
                            $SellerName = ucfirst($sellerData->name);
                        }else{
                            $SellerName = "N/A";
                        }
                        return $SellerName;
                    })
                    ->addColumn('statusView', function($data) {
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
                    ->addColumn('action', function($data) {
                        $action = '';
                        $action .= '<a href="#" class="view btn btn-sm btn-info me-2" data-id="'.$data->id.'"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';    
                if($data->status == 'Processing' || $data->status == 'Shipped' || $data->status == 'Future'){
                    $action .= '<button type="button" class="edit btn btn-sm btn-primary " data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target_edit" > Update Driver</button>';
                }   
                        return $action;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalShipping', $totalShipping)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['Driver','BulkAction','Location','SellerName','OrderPlacedBy','customerName','statusView','action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            $DriverData = User::where('role','driver')->where('company_id',Auth::user()->id)->where('status','active')->orderBy('id', 'DESC')->get();

            return view('logistic_company.pages.order.all',compact('StateData','AreaData','DriverData'));

    }
    public function get_order(Request $request)
    {

            $id = $request->id;
            $order = Order::with([
                'seller' => function ($query) {
                    $query->select('id', 'name');
                },
                'subSeller' => function ($query) {
                    $query->select('id', 'name');
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
    public function get_company_order_details(Request $request)
    {

            $id = $request->id;
            $order = Order::with([
                'seller' => function ($query) {
                    $query->select('id', 'name');
                },
                'subSeller' => function ($query) {
                    $query->select('id', 'name');
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
    public function get_areas(Request $request)
    {
            $areas = Area::where('state_id','=',$request->state_id)->get();
            $options = '<option value="" disabled selected>Choose Area</option>';
            foreach ($areas as $area) {
                $options .= '<option value="' . $area->id . '">' . $area->area . '</option>';
            }
            return response()->json(['options' => $options]);
    }
    public function assign_orders(Request $request)
    {
        if (empty($request->selected_orders) || empty($request->assign_driver)) {
            return response()->json(2);
        }
        $orderIds = json_decode($request->selected_orders, true);
        try {
             Order::whereIn('id', $orderIds)
                ->where('status', 'Processing')
                ->update([
                    'driver_id' => $request->assign_driver,
                    'status' => 'Shipped',
                    'driver_assign_date' => Carbon::now()
                ]);
            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function update_orders(Request $request)
    {
        if (empty($request->order_id) || empty($request->driver_id)) {
            return response()->json(2);
        }
        try {
            Order::where('id', $request->order_id)
    ->where(function ($query) {
        $query->where('status', 'Processing')
              ->orWhere('status', 'Shipped')
              ->orWhere('status', 'Future');
    })
    ->update([
        'driver_id' => $request->driver_id,
        'status' => 'Shipped',
        'driver_assign_date' => Carbon::now(),
    ]);
            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
