<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\Role;
use App\Models\SellerRole;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\isFalse;
use App\Models\ServiceCharge;
use Illuminate\Support\Facades\DB;
class SellerController extends Controller
{
    public function index(Request $request) {
        if (ActivityLogger::hasPermission('sellers', 'view')) {
            if ($request->ajax()) {
                $data = User::where('role','=', 'seller')->orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('statusView', function($data) {
                        if ($data->status == 'active') {
                            return "<div class='badge bg-success'>Active</div>";
                        } elseif ($data->status == 'pending') {
                            return "<div class='badge bg-warning text-dark'>Pending</div>";
                        } else {
                            return "<div class='badge bg-danger'>Blocked</div>";
                        }
                    })
                    ->addColumn('action', function($data) {
                        $action = '';
                        $action .='
                    <a href="'.route('all_seller_orders_admin', encrypt($data->id)).'" class="btn btn-dark btn-sm" style="margin-right: 5px;">
                      View Orders
                    </a>';
                        $action .='
                    <a href="'.route('sub_sellers', encrypt($data->id)).'" class="btn btn-primary btn-sm" style="margin-right: 5px;">
                      View Sub Seller
                    </a>';
                    $action .='<a href="#" class="service btn btn-sm btn-warning" data-id="'.$data->id.'" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#service_kt_modal_new_target">
                    Service Charges
                 </a>';
                        if (ActivityLogger::hasPermission('sellers', 'status')) {

                            if ($data->status == 'block') {
                                $action .= '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i>
               </a>';
                            } elseif ($data->status == 'pending') {
                                $action .= '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i>
                    <a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i>';
                            } else {
                                $action .= '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i>
               </a>';
                            }
                        }else{
                            $action .='';
                        }
                        if (ActivityLogger::hasPermission('sellers', 'edit')) {
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                 
                        }
                        if (ActivityLogger::hasPermission('sellers', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';

                        }

                        return $action;
                    })
                    ->rawColumns(['statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('open seller page');
            return view('admin.pages.sellers.index');
        }
    }
    public function get_sellers_service_charges(Request $request)
{
    $seller = User::with('serviceCharges')->findOrFail($request->id);

    return response()->json([
        'id' => $seller->id,
        'name' => $seller->name,
        'service_charges' => $seller->serviceCharges
    ]);
}
public function update_sellers_service_charges(Request $request)
{
    $request->validate([
        'start_range' => 'required|array',
        'end_range' => 'required|array',
        'amount' => 'required|array',
    ]);

    $sellerId = $request->seller_id;
    $startRanges = $request->start_range;
    $endRanges = $request->end_range;
    $amounts = $request->amount;
    $ids = $request->ids ?? [];

    // Check for overlapping ranges
    $rangeCount = count($startRanges);
    for ($i = 0; $i < $rangeCount; $i++) {
        for ($j = $i + 1; $j < $rangeCount; $j++) {
            $start1 = (float) $startRanges[$i];
            $end1 = (float) $endRanges[$i];
            $start2 = (float) $startRanges[$j];
            $end2 = (float) $endRanges[$j];

            // If ranges overlap
            if ($start1 <= $end2 && $start2 <= $end1) {
                return response()->json([
                    'status' => false,
                    'message' => "Range #".($i+1)." and Range #".($j+1)." are overlapping. Please adjust the values."
                ]);
            }
        }
    }

    DB::beginTransaction();

    try {
        $existingIds = ServiceCharge::where('user_id', $sellerId)->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($startRanges as $index => $start) {
            $id = $ids[$index] ?? null;

            $data = [
                'user_id'     => $sellerId,
                'start_range' => $start,
                'end_range'   => $endRanges[$index],
                'amount'      => $amounts[$index],
            ];

            if ($id && in_array($id, $existingIds)) {
                ServiceCharge::where('id', $id)->update($data);
                $submittedIds[] = $id;
            } else {
                $new = ServiceCharge::create($data);
                $submittedIds[] = $new->id;
            }
        }

        // Delete removed entries
        $toDelete = array_diff($existingIds, $submittedIds);
        ServiceCharge::whereIn('id', $toDelete)->delete();

        DB::commit();

        return response()->json(['status' => true, 'message' => 'Service charges saved successfully.']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => false, 'message' => 'Something went wrong.', 'error' => $e->getMessage()]);
    }
}

    public function all_seller_order(Request $request,$id) {
        if (ActivityLogger::hasPermission('seller', 'view')) {
            $seller_id = decrypt($id);
            $seller_data = User::where('id','=', $seller_id)->first();
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
                if (!empty($request->sub_seller)) {
                    $query->where('sub_seller_id', $request->sub_seller);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('created_at', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }

                $data = $query->where('seller_id',decrypt($request->id))->orderBy('id', 'DESC')->get();
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
                    if($item->status == 'Pending'){
                        $totalPendingCount += 1;
                    }elseif($item->status == 'Processing'){
                        $totalProcessingCount += 1;
                    }elseif($item->status == 'Shipped'){
                        $totalShippedCount += 1;
                    }elseif($item->status == 'Delivered'){
                        $deliveredCod += $item->cod_amount;
                        $totalShipping += $item->shipping_fee;
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
                    ->addColumn('customerName', function($data) {
                        if (!empty($data->customer_name)){
                            $customer_name = ucfirst($data->customer_name);
                        }else{
                            $customer_name = "N/A";
                        }
                        return $customer_name;
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
                        return $action;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalShipping', $totalShipping)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['Location','SellerName','OrderPlacedBy','customerName','statusView','action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(' Open Order Page of '  .$seller_data->name.' seller');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            $SubsellerData = User::where('role','sub_seller')->where('seller_id',$seller_id)->where('status','active')->orderBy('id', 'DESC')->get();
            return view('admin.pages.sellers.seller_orders',compact('StateData','AreaData','SubsellerData','id','seller_data'));

        }
    }
    public function all_sub_seller_order(Request $request,$id) {
        if (ActivityLogger::hasPermission('seller', 'view')) {
            $seller_id = decrypt($id);
            $seller_data = User::where('id','=', $seller_id)->first();
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
                    $query->whereDate('created_at', '=', $request->current_date);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }
                $data = $query->where('sub_seller_id',decrypt($request->id))->orderBy('id', 'DESC')->get();
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
                    if($item->status == 'Pending'){
                        $totalPendingCount += 1;
                    }elseif($item->status == 'Processing'){
                        $totalProcessingCount += 1;
                    }elseif($item->status == 'Shipped'){
                        $totalShippedCount += 1;
                    }elseif($item->status == 'Delivered'){
                        $deliveredCod += $item->cod_amount;
                        $totalShipping += $item->shipping_fee;
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
                    ->addColumn('customerName', function($data) {
                        if (!empty($data->customer_name)){
                            $customer_name = ucfirst($data->customer_name);
                        }else{
                            $customer_name = "N/A";
                        }
                        return $customer_name;
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
                        return $action;
                    })
                    ->with('totalCod', $totalCod)
                    ->with('deliveredCod', $deliveredCod)
                    ->with('cancelledCod', $cancelledCod)
                    ->with('totalProfit', $totalProfit)
                    ->with('totalShipping', $totalShipping)
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['Location','SellerName','OrderPlacedBy','customerName','statusView','action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(' Open Order Page of '  .$seller_data->name.'Sub seller');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            return view('admin.pages.sellers.sub_seller_order',compact('StateData','AreaData','id','seller_data'));

        }
    }
    public function get_order(Request $request)
    {

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

    public function get_areas(Request $request)
    {
        $areas = Area::where('state_id','=',$request->state_id)->get();
        $options = '<option value="" disabled selected>Choose Area</option>';
        foreach ($areas as $area) {
            $options .= '<option value="' . $area->id . '">' . $area->area . '</option>';
        }
        return response()->json(['options' => $options]);
    }
    public function sub_sellers(Request $request,$id) {
        if (ActivityLogger::hasPermission('sellers', 'view')) {
            $seller_id = decrypt($id);
            $seller_data = User::where('id','=', $seller_id)->first();
            if ($request->ajax()) {
                $data = User::where('seller_id','=', decrypt($request->id))->orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('statusView', function($data) {
                        if ($data->status == 'active') {
                            return "<div class='badge bg-success'>Active</div>";
                        } elseif ($data->status == 'pending') {
                            return "<div class='badge bg-warning text-dark'>Pending</div>";
                        } else {
                            return "<div class='badge bg-danger'>Blocked</div>";
                        }
                    })
                    ->addColumn('action', function($data) {
                        $action = '';
                        $action .='
                    <a href="'.route('all_sub_seller_orders_admin', encrypt($data->id)).'" class="btn btn-dark btn-sm" style="margin-right: 5px;">
                      View Orders
                    </a>';
                        if (ActivityLogger::hasPermission('sellers', 'status')) {
                            if ($data->status == 'block') {
                                $action .= '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i>
               </a>';
                            } elseif ($data->status == 'pending') {
                                $action .= '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i>
                    <a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i>';
                            } else {
                                $action .= '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i>
               </a>';
                            }
                        }else{
                            $action .='';
                        }
                        if (ActivityLogger::hasPermission('sellers', 'edit')) {
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('sellers', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';

                        }

                        return $action;
                    })
                    ->rawColumns(['statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('open Sub seller page of ' .$seller_data->name);
            $roles = SellerRole::where('seller_id',$seller_data->id)->get();
            return view('admin.pages.sellers.sub_sellers',compact('id','seller_data','roles'));
        }
    }
    public function add_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'add')) {
            if (empty($request->name) || empty($request->email)|| empty($request->store_name)|| empty($request->average_orders)|| empty($request->whatsapp)|| empty($request->mobile)|| empty($request->dropshipping_experience)|| empty($request->dropshipping_status)|| empty($request->bank)|| empty($request->ac_title)|| empty($request->ac_no)|| empty($request->iban) ) {
                return response()->json(2);
            }
            if(empty($request->password)){
                return response()->json(3);
            }
            $uppercase = preg_match('@[A-Z]@', $request->password);
            $lowercase = preg_match('@[a-z]@', $request->password);
            $number = preg_match('@[0-9]@', $request->password);

            if (!$uppercase || !$lowercase || !$number || strlen($request->password) < 8) {
                return response()->json(4);
            }
            if (User::where('email', $request->email)->exists()) {
                return response()->json(5);
            }
            try {
                User::create([
                    'name' => $request->name,
                    'unique_id' => $request->unique_id,
                    'email' => $request->email,
                    'store_name' => $request->store_name,
                    'average_orders' => $request->average_orders,
                    'whatsapp' => $request->whatsapp,
                    'mobile' => $request->mobile,
                    'dropshipping_experience' => $request->dropshipping_experience,
                    'dropshipping_status' => $request->dropshipping_status,
                    'bank' => $request->bank,
                    'ac_title' => $request->ac_title,
                    'ac_no' => $request->ac_no,
                    'iban' => $request->iban,
                    'password' => Hash::make($request->password),
                    'show_password' => $request->password,
                    'role' => 'seller',
                    'type' => 'seller',
                    'status' => 'active',
                ]);
                ActivityLogger::UserLog('add seller '.$request->name);

                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function add_sub_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'add')) {
                $seller_id = decrypt($request->seller_id);
            try {
                $user = User::create([
                    'seller_id' => $seller_id,
                    'name' => $request->name,
                    'unique_id' => $request->unique_id,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                    'show_password' => $request->password,
                    'type' => $request->role,
                    'role' => 'sub_seller',
                    'status' => 'active',
                ]);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function delete_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'delete')) {
            $id=$request->id;
            $detail = User::find($id);
            User::destroy($detail->id);
            echo 1;
            exit();
        }
    }
    public function status_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'status')) {
            $id=$request->id;
            $status=$request->status;
            $detail = User::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update Seller '.$detail->activity.' Status to Active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update Seller '.$detail->activity.' Status to Block');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_sellers(Request $request){
        if (ActivityLogger::hasPermission('sellers', 'edit')) {
            $id=$request->id;
            $Data=User::find($id);
            return response()->json($Data);
        }
    }
    public function update_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'edit')) {
            if (empty($request->edit_name) || empty($request->edit_email)|| empty($request->edit_store_name)|| empty($request->edit_average_orders)|| empty($request->edit_whatsapp)|| empty($request->edit_mobile)|| empty($request->edit_dropshipping_experience)|| empty($request->edit_dropshipping_status)|| empty($request->edit_bank)|| empty($request->edit_ac_title)|| empty($request->edit_ac_no)|| empty($request->edit_iban) ) {
                return response()->json(2);
            }



            try {
                $data = User::find($request->id);

                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'unique_id' => $request->edit_unique_id,
                        'store_name' => $request->edit_store_name,
                        'average_orders' => $request->edit_average_orders,
                        'whatsapp' => $request->edit_whatsapp,
                        'mobile' => $request->edit_mobile,
                        'dropshipping_experience' => $request->edit_dropshipping_experience,
                        'dropshipping_status' => $request->edit_dropshipping_status,
                        'bank' => $request->edit_bank,
                        'ac_title' => $request->edit_ac_title,
                        'ac_no' => $request->edit_ac_no,
                        'iban' => $request->edit_iban,
                    ];
                    $data->update($updateData);
                    if (!empty($request->edit_password)){
                        $uppercase = preg_match('@[A-Z]@', $request->edit_password);
                        $lowercase = preg_match('@[a-z]@', $request->edit_password);
                        $number = preg_match('@[0-9]@', $request->edit_password);

                        if (!$uppercase || !$lowercase || !$number || strlen($request->edit_password) < 8) {
                            return response()->json(4);
                        }
                        $data->password = Hash::make($request->edit_password);
                        $data->show_password = $request->edit_password;
                        $data->save();
                    }

                    ActivityLogger::UserLog('Update Seller '.$request->edit_name);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function update_sub_sellers(Request $request)
    {
        if (ActivityLogger::hasPermission('sellers', 'edit')) {
            if (empty($request->edit_name) || empty($request->edit_email)|| empty($request->edit_mobile)|| empty($request->edit_role)){
                return response()->json(2);
            }
            try {
                $data = User::find($request->id);
                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'unique_id' => $request->edit_unique_id,
                        'email' => $request->edit_email,
                        'mobile' => $request->edit_mobile,
                        'type' => $request->edit_role,
                    ];
                    $data->update($updateData);
                    if (!empty($request->edit_password)){
                        $uppercase = preg_match('@[A-Z]@', $request->edit_password);
                        $lowercase = preg_match('@[a-z]@', $request->edit_password);
                        $number = preg_match('@[0-9]@', $request->edit_password);

                        if (!$uppercase || !$lowercase || !$number || strlen($request->edit_password) < 8) {
                            return response()->json(4);
                        }
                        $data->password = Hash::make($request->edit_password);
                        $data->show_password = $request->edit_password;
                        $data->save();
                    }
                    ActivityLogger::UserLog('Update User '.$request->edit_name);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
}
