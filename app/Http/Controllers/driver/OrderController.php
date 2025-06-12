<?php

namespace App\Http\Controllers\driver;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\Product;
use App\Models\productVariation;
use App\Models\State;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
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
                    $query->where('status', $request->status);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('driver_assign_date', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('driver_assign_date', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('driver_assign_date', '<=', $request->end_date);
                }
                    $data = $query->where('driver_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $totalCod = 0;
                    $deliveredCod = 0;
                    $cancelledCod = 0;
                    $totalProfit = 0;
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
                            $totalProfit += $item->profit;
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
                    ->addColumn('OrderDetails', function($data) {
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
                            $status = "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
                        }else{
                            $status = "<div class='badge bg-secondary'>Unknown</div>";
                        }
                        $stateData =  State::where('id',$data->state_id)->first();
                        $areaData =  Area::where('id',$data->area_id)->first();
                        $action = '';
                        if ($data->status == 'Shipped' || $data->status == 'Out_for_delivery' || $data->status == 'Future') {
                            $action .= '<a href="#" class="delivery btn btn-sm btn-primary mt-2 me-2" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" onclick="changeStatus(1, ' . $data->id . ')">
         Out For Delivery
        </a>';
                        }
                        $action .= '<a href="#" class="view btn btn-sm btn-info mt-2 me-2" data-id="'.$data->id.'"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $OrderDetails =  '<div class="card border p-4">
                            <div > <b>Order id: </b>' . $data->id . '</div>
                            <div>' . $data->customer_name . '</div>
                            <div> <b>State:</b> ' . ucfirst($stateData->state) . '</div>
                            <div><b>Area: </b>' . ucfirst($areaData->area) . '</div>
                            <div><b>Address: </b>' . $data->address . '</div>
                            <div>' . $data->cod_amount . ' AED</div>
                            <div>' . $status . '</div>
                            <div>' . $action . '</div>

                    </div>';
                        return $OrderDetails;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['OrderDetails'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page of driver');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            return view('driver.pages.order.all',compact('StateData','AreaData'));
        }
    public function delivered(Request $request) {

        if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }
                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                if (!empty($request->status)) {
                    $query->where('status', $request->status);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('driver_assign_date', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('driver_assign_date', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('driver_assign_date', '<=', $request->end_date);
                }
                    $data = $query->where('driver_id',Auth::user()->id)->where('status','Delivered')->orderBy('id', 'DESC')->get();
                    $totalCod = 0;
                    $deliveredCod = 0;
                    $cancelledCod = 0;
                    $totalProfit = 0;
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
                            $totalProfit += $item->profit;
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
                    ->addColumn('OrderDetails', function($data) {
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
                            $status = "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
                        }else{
                            $status = "<div class='badge bg-secondary'>Unknown</div>";
                        }
                        $stateData =  State::where('id',$data->state_id)->first();
                        $areaData =  Area::where('id',$data->area_id)->first();
                        $action = '';
                        if ($data->status == 'Shipped' || $data->status == 'Out_for_delivery' || $data->status == 'Future') {
                            $action .= '<a href="#" class="delivery btn btn-sm btn-primary mt-2 me-2" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" onclick="changeStatus(1, ' . $data->id . ')">
         Out For Delivery
        </a>';
                        }
                        $action .= '<a href="#" class="view btn btn-sm btn-info mt-2 me-2" data-id="'.$data->id.'"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $OrderDetails =  '<div class="card border p-4">
                            <div > <b>Order id: </b>' . $data->id . '</div>
                            <div>' . $data->customer_name . '</div>
                            <div> <b>State:</b> ' . ucfirst($stateData->state) . '</div>
                            <div><b>Area: </b>' . ucfirst($areaData->area) . '</div>
                            <div><b>Address: </b>' . $data->address . '</div>
                            <div>' . $data->cod_amount . ' AED</div>
                            <div>' . $status . '</div>
                            <div>' . $action . '</div>

                    </div>';
                        return $OrderDetails;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['OrderDetails'])
                    ->make(true);
            } 
        ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page of driver');
        $StateData = State::orderBy('id', 'DESC')->get();
        $AreaData = Area::orderBy('id', 'DESC')->get();
        return view('driver.pages.order.delivered',compact('StateData','AreaData'));
    }
    public function shipped(Request $request) {
        if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }
                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                if (!empty($request->status)) {
                    $query->where('status', $request->status);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('driver_assign_date', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('driver_assign_date', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('driver_assign_date', '<=', $request->end_date);
                }
                    $data = $query->where('driver_id',Auth::user()->id)->where('status','Shipped')->orderBy('id', 'DESC')->get();
                    $totalCod = 0;
                    $deliveredCod = 0;
                    $cancelledCod = 0;
                    $totalProfit = 0;
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
                            $totalProfit += $item->profit;
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
                    ->addColumn('OrderDetails', function($data) {
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
                            $status = "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
                        }else{
                            $status = "<div class='badge bg-secondary'>Unknown</div>";
                        }
                        $stateData =  State::where('id',$data->state_id)->first();
                        $areaData =  Area::where('id',$data->area_id)->first();
                        $action = '';
                        if ($data->status == 'Shipped' || $data->status == 'Out_for_delivery' || $data->status == 'Future') {
                            $action .= '<a href="#" class="delivery btn btn-sm btn-primary mt-2 me-2" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" onclick="changeStatus(1, ' . $data->id . ')">
         Out For Delivery
        </a>';
                        }
                        $action .= '<a href="#" class="view btn btn-sm btn-info mt-2 me-2" data-id="'.$data->id.'"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $OrderDetails =  '<div class="card border p-4">
                            <div > <b>Order id: </b>' . $data->id . '</div>
                            <div>' . $data->customer_name . '</div>
                            <div> <b>State:</b> ' . ucfirst($stateData->state) . '</div>
                            <div><b>Area: </b>' . ucfirst($areaData->area) . '</div>
                            <div><b>Address: </b>' . $data->address . '</div>
                            <div>' . $data->cod_amount . ' AED</div>
                            <div>' . $status . '</div>
                            <div>' . $action . '</div>

                    </div>';
                        return $OrderDetails;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['OrderDetails'])
                    ->make(true);
            } 
        ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page of driver');
        $StateData = State::orderBy('id', 'DESC')->get();
        $AreaData = Area::orderBy('id', 'DESC')->get();
        return view('driver.pages.order.shipped',compact('StateData','AreaData'));
    }
    public function canceled(Request $request) {

              if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }
                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                if (!empty($request->status)) {
                    $query->where('status', $request->status);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('driver_assign_date', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('driver_assign_date', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('driver_assign_date', '<=', $request->end_date);
                }
                    $data = $query->where('driver_id',Auth::user()->id)->where('status','Cancelled')->orderBy('id', 'DESC')->get();
                    $totalCod = 0;
                    $deliveredCod = 0;
                    $cancelledCod = 0;
                    $totalProfit = 0;
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
                            $totalProfit += $item->profit;
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
                    ->addColumn('OrderDetails', function($data) {
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
                            $status = "<div class='badge {$statusLabels[$data->status]['class']}'>{$statusLabels[$data->status]['text']}</div>";
                        }else{
                            $status = "<div class='badge bg-secondary'>Unknown</div>";
                        }
                        $stateData =  State::where('id',$data->state_id)->first();
                        $areaData =  Area::where('id',$data->area_id)->first();
                        $action = '';
                        if ($data->status == 'Shipped' || $data->status == 'Out_for_delivery' || $data->status == 'Future') {
                            $action .= '<a href="#" class="delivery btn btn-sm btn-primary mt-2 me-2" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" onclick="changeStatus(1, ' . $data->id . ')">
         Out For Delivery
        </a>';
                        }
                        $action .= '<a href="#" class="view btn btn-sm btn-info mt-2 me-2" data-id="'.$data->id.'"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $OrderDetails =  '<div class="card border p-4">
                            <div > <b>Order id: </b>' . $data->id . '</div>
                            <div>' . $data->customer_name . '</div>
                            <div> <b>State:</b> ' . ucfirst($stateData->state) . '</div>
                            <div><b>Area: </b>' . ucfirst($areaData->area) . '</div>
                            <div><b>Address: </b>' . $data->address . '</div>
                            <div>' . $data->cod_amount . ' AED</div>
                            <div>' . $status . '</div>
                            <div>' . $action . '</div>

                    </div>';
                        return $OrderDetails;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['OrderDetails'])
                    ->make(true);
            } 
        ActivityLogger::UserLog(Auth::user()->name. ' Open Order Page of driver');
        $StateData = State::orderBy('id', 'DESC')->get();
        $AreaData = Area::orderBy('id', 'DESC')->get();
        return view('driver.pages.order.canceled',compact('StateData','AreaData'));
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
    public function get_areas(Request $request)
    {

            $areas = Area::where('state_id','=',$request->state_id)->get();
            $options = '<option value="" disabled selected>Choose Area</option>';
            foreach ($areas as $area) {
                $options .= '<option value="' . $area->id . '">' . $area->area . '</option>';
            }
            return response()->json(['options' => $options]);

    }
    public function markDelivered(Request $request)
    {
        if (empty($request->order_id)) {
            return response()->json(2);
        }

        if ($request->hasFile('files')) {
            $proof_img = $request->file('files')->store('products/order_proofs', 'public');
        } else {
            return response()->json(3);
        }

        try {
            $order = Order::with('orderItems')->findOrFail($request->order_id);
         if ($request->delivery_status == 'Cancelled'){
             $order->rto_status = 'not_received';
         }
         if ($request->delivery_status == 'Delivered'){
             $seller = User::where('id',$order->seller_id)->first();
             $logistic_company = User::where('id',$order->company_id)->first();
             $seller->wallet += $order->profit; 
             $seller->save();
             $seller_transaction = Transaction::create([
                'user_id' => $seller->id,
                'user_type' => 'seller',
                'amount_type' => 'in',
                'amount' => $order->profit,
                'order_id' => $order->id,

            ]);
             $logistic_company->wallet += $order->shipping_fee; 
             $logistic_company->save();
             $logistic_company_transaction = Transaction::create([
                'user_id' => $logistic_company->id,
                'user_type' => 'logistic_company',
                'amount_type' => 'in',
                'amount' => $order->shipping_fee,
                'order_id' => $order->id,

            ]);
        }
            $order->status = $request->delivery_status;
            $order->changed_cod_amount = $request->changed_cod_amount;
            $order->proof_image = $proof_img;
            $order->delivery_instruction = $request->delivery_instruction;
            $order->delivery_date = Carbon::now();
            $order->save();

            ActivityLogger::UserLog('Order #' . $order->id . ' marked as ' . $request->delivery_status);

            return response()->json(1);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function status_order(Request $request)
    {

            $id=$request->id;
            $status=$request->status;
            $detail = Order::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'Out_for_delivery']);
                    ActivityLogger::UserLog('Update Order '.$detail->id.' Status to Out For Delivery');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'Shipped']);
                    ActivityLogger::UserLog('Update Order '.$detail->id.' Status to Shipped');
                    echo 1;
                    exit();
                }
            }
    }
}
