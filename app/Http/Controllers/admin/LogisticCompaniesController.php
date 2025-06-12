<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class LogisticCompaniesController extends Controller
{
    public function index(Request $request) {
        if (ActivityLogger::hasPermission('logistic_companies', 'view')) {
            if ($request->ajax()) {
                $data = User::where('role','=', 'logistic_company')->orderBy('id', 'DESC')->get();
                return Datatables::of($data)

                    ->addColumn('statusView', function($data) {
                        return $data->status == 'active'
                            ? "<div class='badge bg-success'>Active</div>"
                            : "<div class='badge bg-danger'>Block</div>";
                    })
                    ->addColumn('action', function($data) {
                        $action = '';
                        $action .='
                    <a href="'.route('all_company_orders_admin', encrypt($data->id)).'" class="btn btn-dark btn-sm" style="margin-right: 5px;">
                      View Orders
                    </a>';
                        $action .='
                    <a href="'.route('all_admin_driver', encrypt($data->id)).'" class="btn btn-primary btn-sm" style="margin-right: 5px;">
                      View Drivers
                    </a>';

                        if (ActivityLogger::hasPermission('logistic_companies', 'status')) {
                            $action .= $data->status == 'block'
                                ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                                : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                        }else{
                            $action .='';
                        }
                        if (ActivityLogger::hasPermission('logistic_companies', 'edit')) {
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('logistic_companies', 'delete')) {
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
            ActivityLogger::UserLog('open Logistic Companies page');
            return view('admin.pages.logistic_company.index');
        }
    }
    public function all_driver(Request $request,$id) {
        if (ActivityLogger::hasPermission('logistic_companies', 'view')) {
            $company_id = decrypt($id);
            $company_data = User::where('id','=', $company_id)->first();
            if ($request->ajax()) {
                $data = User::where('company_id','=', decrypt($request->id))->orderBy('id', 'DESC')->get();
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
                    <a href="'.route('all_driver_admin_orders', encrypt($data->id)).'" class="btn btn-primary btn-sm" style="margin-right: 5px;">
                      View Orders
                    </a>';
                        if (ActivityLogger::hasPermission('logistic_companies', 'status')) {
                            $action .= $data->status == 'block'
                                ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                                : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                        }else{
                            $action .='';
                        }
                        if (ActivityLogger::hasPermission('logistic_companies', 'edit')) {
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('logistic_companies', 'delete')) {
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
            ActivityLogger::UserLog('open Driver page of ' .$company_data->name);
            return view('admin.pages.logistic_company.driver',compact('id','company_data'));
        }
    }
    public function all_driver_orders(Request $request,$id) {
        if (ActivityLogger::hasPermission('logistic_companies', 'view')) {
            $driver_id = decrypt($id);
            $driver_data = User::where('id','=', $driver_id)->first();
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
                if (!empty($request->drivers)) {
                    $query->where('driver_id', $request->drivers);
                }

                if (!empty($request->start_date)) {
                    $query->whereDate('driver_assign_date', '>=', $request->start_date);
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('driver_assign_date', '<=', $request->end_date);
                }

                $data = $query->where('driver_id',decrypt($request->id))->orderBy('id', 'DESC')->get();
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
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->rawColumns(['Location','OrderPlacedBy','customerName','statusView','action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(' Open Order Page of '  .$driver_data->name.' Driver');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            return view('admin.pages.logistic_company.driver_order',compact('StateData','AreaData','id','driver_data'));

        }
    }
    public function all_company_order(Request $request,$id) {
        if (ActivityLogger::hasPermission('logistic_companies', 'view')) {
            $company_id = decrypt($id);
            $company_data = User::where('id','=', $company_id)->first();
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
            if (!empty($request->drivers)) {
                $query->where('driver_id', $request->drivers);
            }

            if (!empty($request->start_date)) {
                $query->whereDate('company_assign_date', '>=', $request->start_date);
            }

            if (!empty($request->end_date)) {
                $query->whereDate('company_assign_date', '<=', $request->end_date);
            }

            $data = $query->where('company_id',decrypt($request->id))->orderBy('id', 'DESC')->get();
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
        ActivityLogger::UserLog(' Open Order Page of '  .$company_data->name.' Company');
        $StateData = State::orderBy('id', 'DESC')->get();
        $AreaData = Area::orderBy('id', 'DESC')->get();
        $DriverData = User::where('role','driver')->where('company_id',$company_id)->where('status','active')->orderBy('id', 'DESC')->get();

        return view('admin.pages.logistic_company.order',compact('StateData','AreaData','DriverData','id','company_data'));

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
    public function add_driver(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'add')) {
            $company_id = decrypt($request->company_id);
            if (empty($request->name) || empty($request->email)|| empty($request->mobile)){
                return response()->json(2);
            }
            try {
                $user = User::create([
                    'company_id' => $company_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                    'show_password' => $request->password,
                    'role' => 'driver',
                    'status' => 'active',
                ]);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function add_logistic_companies(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'add')) {
            if (empty($request->name) || empty($request->email)|| empty($request->mobile)){
                return response()->json(2);
            }
            try {
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                    'show_password' => $request->password,
                    'role' => 'logistic_company',
                    'status' => 'active',
                ]);
                ActivityLogger::UserLog('add Logistic Company '.$request->name);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function delete_logistic_companies(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'delete')) {
            $id=$request->id;
            $detail = User::find($id);
            User::destroy($detail->id);
            ActivityLogger::UserLog('delete Logistic Company '.$detail->name);
            echo 1;
            exit();
        }
    }
    public function status_logistic_companies(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'status')) {
            $id=$request->id;
            $status=$request->status;
            $detail = User::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update Logistic Company '.$detail->name.' Status to Active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update Logistic Company '.$detail->name.' Status to Block');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_logistic_companies(Request $request){
        if (ActivityLogger::hasPermission('logistic_companies', 'edit')) {
            $id=$request->id;
            $Data=User::find($id);
            return response()->json($Data);
        }
    }
    public function update_logistic_companies(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'edit')) {
            if (empty($request->edit_name) || empty($request->edit_email)|| empty($request->edit_mobile)){
                return response()->json(2);
            }
            try {
                $data = User::find($request->id);

                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'email' => $request->edit_email,
                        'mobile' => $request->edit_mobile,
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
                    ActivityLogger::UserLog('Update Logistic Company '.$request->edit_name);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function update_driver(Request $request)
    {
        if (ActivityLogger::hasPermission('logistic_companies', 'edit')) {
            if (empty($request->edit_name) || empty($request->edit_email)|| empty($request->edit_mobile)){
                return response()->json(2);
            }
            try {
                $data = User::find($request->id);
                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'email' => $request->edit_email,
                        'mobile' => $request->edit_mobile,
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
