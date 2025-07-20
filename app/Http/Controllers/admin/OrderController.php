<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\productVariation;
use App\Models\State;
use App\Models\User;
use App\Models\ServiceCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\ProductStockBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'view')) {
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
                if (!empty($request->logistic_company)) {
                    $query->where('company_id', $request->logistic_company);
                }
                if (!empty($request->drivers)) {
                    $query->where('driver_id', $request->drivers);
                }
                if (!empty($request->seller)) {
                    $query->where('seller_id', $request->seller);
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

                $data = $query->orderBy('id', 'DESC')->get();
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
                $service_chargesCount = 0;

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
                        $service_chargesCount +=  $item->service_charges;
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
                    ->addColumn('BulkAction', function ($data) {
                        $BulkAction = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="bulk_action[]" value="' . $data->id . '" ></div>';
                        return $BulkAction;
                    })
                    ->addColumn('LogisticCompany', function ($data) {
                        $LogisticCompanyData =  User::where('id', $data->company_id)->first();
                        if (!empty($LogisticCompanyData)) {
                            $LogisticCompany = ucfirst($LogisticCompanyData->name);
                        } else {
                            $LogisticCompany = 'N/A';
                        }
                        return $LogisticCompany;
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
                    ->addColumn('OrderPlacedBy', function ($data) {
                        if (!empty($data->sub_seller_id)) {
                            $sellerData =  User::where('id', $data->sub_seller_id)->first();
                            $OrderPlacedBy = ucfirst($sellerData->name);
                        } else {
                            $OrderPlacedBy = "N/A";
                        }
                        return $OrderPlacedBy;
                    })
                    ->addColumn('SellerName', function ($data) {
                        if (!empty($data->seller_id)) {
                            $sellerData =  User::where('id', $data->seller_id)->first();
                            $SellerName = ucfirst($sellerData->name);
                        } else {
                            $SellerName = "N/A";
                        }
                        return $SellerName;
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

                        $status = $data->status ?? 'Unknown';
                        $label = $statusLabels[$status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($status)];

                        // Make it clickable for admin (optional: use role check here)
                        return "<div
                class='badge {$label['class']} change-status'
                data-id='{$data->id}'
                data-status='{$status}'
                style='cursor: pointer;'
                title='Click to change status'>
                {$label['text']}
            </div>";
                    })

                    ->addColumn('action', function ($data) {
                        $action = '';
                        if ($data->status == 'Pending') {
                            $action .= '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                             <i style="font-size: 16px; padding: 0;" class="fa-solid fa-ban"></i>
                       </a>';
                        }
                        $action .= '<a href="' . route('admin_orders.edit', $data->id) . '" class="btn btn-sm btn-info me-2" >
                      <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                              </a>';
                        $action .= '<a href="#" class="view btn btn-sm btn-dark me-2" data-id="' . $data->id . '"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $action .= '<a href="' . route('admin_order_details', encrypt($data->id)) . '" class="btn btn-sm btn-info me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-print"></i>
                </a>';


                        $action .= '<a href="javascript:void(0);" onclick="deleteOrder(' . $data->id . ')" class="btn btn-sm btn-danger">
                               <i class="fa-solid fa-trash"></i>
                                      </a>';


                        return $action;
                    })
                    ->with('totalCod', number_format($totalCod, 2, '.', ''))
                    ->with('deliveredCod', number_format($deliveredCod, 2, '.', ''))
                    ->with('cancelledCod', number_format($cancelledCod, 2, '.', ''))
                    ->with('totalProfit', number_format($totalProfit, 2, '.', ''))
                    ->with('totalShipping',  number_format($totalShipping, 2, '.', ''))
                    ->with('totalPendingCount', $totalPendingCount)
                    ->with('totalProcessingCount', $totalProcessingCount)
                    ->with('totalShippedCount', $totalShippedCount)
                    ->with('totalDeliveredCount', $totalDeliveredCount)
                    ->with('totalCancelledCount', $totalCancelledCount)
                    ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
                    ->with('totalFutureCount', $totalFutureCount)
                    ->with('service_chargesCount', $service_chargesCount)
                    ->with('totalOrdersCount', $totalOrdersCount)
                    ->rawColumns(['COD', 'LogisticCompany', 'BulkAction', 'Location', 'SellerName', 'OrderPlacedBy', 'customerName', 'statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name . ' Open Order Page');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            $sellerData = User::where('role', 'seller')->where('status', 'active')->orderBy('id', 'DESC')->get();
            $LogisticCompanyData = User::where('role', 'logistic_company')->where('status', 'active')->orderBy('id', 'DESC')->get();

            return view('admin.pages.products.order.all', compact('sellerData', 'StateData', 'AreaData', 'LogisticCompanyData'));
        }
    }


    // public function index(Request $request)
    // {
    //     if (ActivityLogger::hasPermission('orders', 'view')) {
    //         if ($request->ajax()) {

    //             // ✅ Eager load seller and subSeller
    //             $query = Order::with(['seller', 'subSeller']);

    //             // ✅ Apply filters
    //             if (!empty($request->state)) {
    //                 $query->where('state_id', $request->state);
    //             }
    //             if (!empty($request->area)) {
    //                 $query->where('area_id', $request->area);
    //             }
    //             if (!empty($request->status)) {
    //                 $query->where('status', $request->status);
    //             }
    //             if (!empty($request->logistic_company)) {
    //                 $query->where('company_id', $request->logistic_company);
    //             }
    //             if (!empty($request->drivers)) {
    //                 $query->where('driver_id', $request->drivers);
    //             }
    //             if (!empty($request->seller)) {
    //                 $query->where('seller_id', $request->seller);
    //             }
    //             if (!empty($request->current_date)) {
    //                 $query->whereDate('created_at', '=', $request->current_date);
    //             }
    //             if (!empty($request->start_date)) {
    //                 $query->whereDate('created_at', '>=', $request->start_date);
    //             }
    //             if (!empty($request->end_date)) {
    //                 $query->whereDate('created_at', '<=', $request->end_date);
    //             }

    //             $data = $query->orderBy('id', 'DESC')->get();

    //             // ✅ Stats calculations
    //             $totalCod = $deliveredCod = $cancelledCod = $totalProfit = $totalShipping = 0;
    //             $totalPendingCount = $totalProcessingCount = $totalShippedCount = 0;
    //             $totalDeliveredCount = $totalCancelledCount = $totalOut_for_deliveryCount = 0;
    //             $totalFutureCount = 0;
    //             $totalFutureCount = $totalServiceCharges = 0;


    //             foreach ($data as $item) {
    //                 $totalCod += $item->cod_amount;
    //                 switch ($item->status) {
    //                     case 'Pending':
    //                         $totalPendingCount++;
    //                         break;
    //                     case 'Processing':
    //                         $totalProcessingCount++;
    //                         break;
    //                     case 'Shipped':
    //                         $totalShippedCount++;
    //                         break;
    //                     case 'Delivered':
    //                         $deliveredCod += $item->cod_amount;
    //                         $totalShipping += $item->shipping_fee;
    //                         $totalProfit += $item->profit;
    //                         $totalServiceCharges += $item->service_charges ?? 0; // ✅ Add this line
    //                         $totalDeliveredCount++;
    //                         break;
    //                     case 'Cancelled':
    //                         $cancelledCod += $item->cod_amount;
    //                         $totalCancelledCount++;
    //                         break;
    //                     case 'Out_for_delivery':
    //                         $totalOut_for_deliveryCount++;
    //                         break;
    //                     case 'Future':
    //                         $totalFutureCount++;
    //                         break;
    //                 }
    //             }

    //             return Datatables::of($data)
    //                 ->addColumn('customerName', fn($data) => ucfirst($data->customer_name) ?? 'N/A')

    //                 ->addColumn(
    //                     'BulkAction',
    //                     fn($data) =>
    //                     '<div class="form-check form-check-sm form-check-custom form-check-solid">
    //                     <input class="form-check-input" type="checkbox" name="bulk_action[]" value="' . $data->id . '" >
    //                 </div>'
    //                 )

    //                 ->addColumn('LogisticCompany', function ($data) {
    //                     $company = User::find($data->company_id);
    //                     return $company ? ucfirst($company->name) : 'N/A';
    //                 })

    //                 ->addColumn('Location', function ($data) {
    //                     $state = State::find($data->state_id);
    //                     $area = Area::find($data->area_id);
    //                     return '
    //                     <div class="d-flex align-items-center">
    //                         <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
    //                         <div class="d-flex flex-column">
    //                             <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($state->state ?? '') . '</a>
    //                             <span>' . ucfirst($area->area ?? '') . '</span>
    //                         </div>
    //                     </div>';
    //                 })

    //                 ->addColumn(
    //                     'OrderPlacedBy',
    //                     fn($data) =>
    //                     $data->subSeller ? ucfirst($data->subSeller->name) : 'N/A'
    //                 )

    //                 ->addColumn(
    //                     'SellerName',
    //                     fn($data) =>
    //                     $data->seller ? ucfirst($data->seller->name) : 'N/A'
    //                 )

    //                 // ✅ Add unique ID columns
    //                 ->addColumn('custom_order_id', function ($data) {
    //                     $subSellerUniqueId = optional($data->subSeller)->unique_id ?? 'N/A';
    //                     return $subSellerUniqueId . '-' . $data->id;
    //                 })
    //                 ->addColumn('COD', function ($data) {
    //                     $cod = $data->cod_amount ? $data->cod_amount . ' AED' : 'N/A';
    //                     $changed = $data->changed_cod_amount ? 'Requested COD: ' . $data->changed_cod_amount . ' AED' : '';
    //                     return '<div class="d-flex flex-column">
    //                     <a class="text-gray-800 text-hover-primary mb-1">' . $cod . '</a>
    //                     <span>' . $changed . '</span>
    //                 </div>';
    //                 })

    //                 ->addColumn('statusView', function ($data) {
    //                     $map = [
    //                         'Pending' => 'bg-warning',
    //                         'Processing' => 'bg-primary',
    //                         'Shipped' => 'bg-info',
    //                         'Delivered' => 'bg-success',
    //                         'Cancelled' => 'bg-danger',
    //                         'Future' => 'bg-primary',
    //                         'Out_for_delivery' => 'bg-primary'
    //                     ];
    //                     $class = $map[$data->status] ?? 'bg-secondary';
    //                     return "<div class='badge $class'>{$data->status}</div>";
    //                 })

    //                 ->addColumn('action', function ($data) {
    //                     $action = '';
    //                     if ($data->status === 'Pending') {
    //                         $action .= '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
    //                         <i class="fa-solid fa-ban"></i></a>';
    //                     }
    //                     $action .= '<a href="' . route('admin_orders.edit', $data->id) . '" class="btn btn-sm btn-info me-2">
    //                         <i class="fa-solid fa-pen-to-square"></i></a>';
    //                     $action .= '<a href="#" class="view btn btn-sm btn-dark me-2" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
    //                         <i class="fa-solid fa-eye"></i></a>';
    //                     $action .= '<a href="' . route('admin_order_details', encrypt($data->id)) . '" class="btn btn-sm btn-info me-2">
    //                         <i class="fa-solid fa-print"></i></a>';
    //                     return $action;
    //                 })

    //                 ->with('totalCod', $totalCod)
    //                 ->with('deliveredCod', $deliveredCod)
    //                 ->with('cancelledCod', $cancelledCod)
    //                 ->with('totalProfit', $totalProfit)
    //                 ->with('totalShipping', $totalShipping)
    //                 ->with('totalPendingCount', $totalPendingCount)
    //                 ->with('totalProcessingCount', $totalProcessingCount)
    //                 ->with('totalShippedCount', $totalShippedCount)
    //                 ->with('totalDeliveredCount', $totalDeliveredCount)
    //                 ->with('totalCancelledCount', $totalCancelledCount)
    //                 ->with('totalOut_for_deliveryCount', $totalOut_for_deliveryCount)
    //                 ->with('totalFutureCount', $totalFutureCount)
    //                 ->with('totalServiceCharges', $totalServiceCharges)


    //                 ->rawColumns([
    //                     'COD',
    //                     'LogisticCompany',
    //                     'BulkAction',
    //                     'Location',
    //                     'SellerName',
    //                     'OrderPlacedBy',
    //                     'customerName',
    //                     'statusView',
    //                     'action'
    //                 ])
    //                 ->make(true);
    //         }

    //         ActivityLogger::UserLog(Auth::user()->name . ' Open Order Page');

    //         $StateData = State::orderBy('id', 'DESC')->get();
    //         $AreaData = Area::orderBy('id', 'DESC')->get();
    //         $sellerData = User::where('role', 'seller')->where('status', 'active')->orderBy('id', 'DESC')->get();
    //         $LogisticCompanyData = User::where('role', 'logistic_company')->where('status', 'active')->orderBy('id', 'DESC')->get();

    //         return view('admin.pages.products.order.all', compact('sellerData', 'StateData', 'AreaData', 'LogisticCompanyData'));
    //     }
    // }


    public function rto_orders(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'view')) {
            if ($request->ajax()) {
                $query = Order::query();
                if (!empty($request->state)) {
                    $query->where('state_id', $request->state);
                }
                if (!empty($request->area)) {
                    $query->where('area_id', $request->area);
                }
                if (!empty($request->status)) {
                    $query->where('rto_status', $request->status);
                }
                if (!empty($request->logistic_company)) {
                    $query->where('company_id', $request->logistic_company);
                }
                if (!empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }
                if (!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }
                $query->where('status', 'Cancelled');
                $data = $query->orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('customerName', function ($data) {
                        if (!empty($data->customer_name)) {
                            $customer_name = ucfirst($data->customer_name);
                        } else {
                            $customer_name = "N/A";
                        }
                        return $customer_name;
                    })
                    ->addColumn('LogisticCompany', function ($data) {
                        $LogisticCompanyData =  User::where('id', $data->company_id)->first();
                        if (!empty($LogisticCompanyData)) {
                            $LogisticCompany = ucfirst($LogisticCompanyData->name);
                        } else {
                            $LogisticCompany = 'N/A';
                        }
                        return $LogisticCompany;
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
                    ->addColumn('OrderPlacedBy', function ($data) {
                        if (!empty($data->sub_seller_id)) {
                            $sellerData =  User::where('id', $data->sub_seller_id)->first();
                            $OrderPlacedBy = ucfirst($sellerData->name);
                        } else {
                            $OrderPlacedBy = "N/A";
                        }
                        return $OrderPlacedBy;
                    })
                    ->addColumn('SellerName', function ($data) {
                        if (!empty($data->seller_id)) {
                            $sellerData =  User::where('id', $data->seller_id)->first();
                            $SellerName = ucfirst($sellerData->name);
                        } else {
                            $SellerName = "N/A";
                        }
                        return $SellerName;
                    })
                    ->addColumn('statusView', function ($data) {
                        $statusLabels = [
                            'received' => ['class' => 'bg-success', 'text' => 'Received'],
                            'not_received' => ['class' => 'bg-danger', 'text' => 'Not Received']
                        ];
                        if (isset($statusLabels[$data->rto_status])) {
                            return "<div class='badge {$statusLabels[$data->rto_status]['class']}'>{$statusLabels[$data->rto_status]['text']}</div>";
                        }
                        return "<div class='badge bg-secondary'>Unknown</div>";
                    })
                    ->addColumn('action', function ($data) {
                        $action = '';
                        if ($data->rto_status !== 'received' && ActivityLogger::hasPermission('orders', 'rto')) {
                            $action .= '<a onclick="changeStatus(' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                     Receive Rto
        </a>';
                        }
                        $action .= '<a href="#" class="view btn btn-sm btn-info me-2" data-id="' . $data->id . '"
                    data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
                </a>';
                        $action .= '<a href="' . route('admin_order_details', encrypt($data->id)) . '" class="btn btn-sm btn-info me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-print">
                </a>';
                        return $action;
                    })
                    ->rawColumns(['LogisticCompany', 'Location', 'SellerName', 'OrderPlacedBy', 'customerName', 'statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name . ' Open Order RTO Page');
            $StateData = State::orderBy('id', 'DESC')->get();
            $AreaData = Area::orderBy('id', 'DESC')->get();
            $LogisticCompanyData = User::where('role', 'logistic_company')->where('status', 'active')->orderBy('id', 'DESC')->get();

            return view('admin.pages.products.order.rto', compact('StateData', 'AreaData', 'LogisticCompanyData'));
        }
    }

    // public function rto_orders(Request $request)
    //     {
    //         if (ActivityLogger::hasPermission('orders', 'view')) {
    //             if ($request->ajax()) {
    //                 $query = Order::query();
    //                 if (!empty($request->state)) {
    //                     $query->where('state_id', $request->state);
    //                 }
    //                 if (!empty($request->area)) {
    //                     $query->where('area_id', $request->area);
    //                 }
    //                 if (!empty($request->status)) {
    //                     $query->where('rto_status', $request->status);
    //                 }
    //                 if (!empty($request->logistic_company)) {
    //                     $query->where('company_id', $request->logistic_company);
    //                 }
    //                 if (!empty($request->start_date)) {
    //                     $query->whereDate('created_at', '>=', $request->start_date);
    //                 }
    //                 if (!empty($request->end_date)) {
    //                     $query->whereDate('created_at', '<=', $request->end_date);
    //                 }
    //                 $query->where('status', 'Cancelled');
    //                 $data = $query->orderBy('id', 'DESC')->get();
    //                 return Datatables::of($data)
    //                     ->addColumn('customerName', function ($data) {
    //                         if (!empty($data->customer_name)) {
    //                             $customer_name = ucfirst($data->customer_name);
    //                         } else {
    //                             $customer_name = "N/A";
    //                         }
    //                         return $customer_name;
    //                     })
    //                     ->addColumn('LogisticCompany', function ($data) {
    //                         $LogisticCompanyData =  User::where('id', $data->company_id)->first();
    //                         if (!empty($LogisticCompanyData)) {
    //                             $LogisticCompany = ucfirst($LogisticCompanyData->name);
    //                         } else {
    //                             $LogisticCompany = 'N/A';
    //                         }
    //                         return $LogisticCompany;
    //                     })
    //                     ->addColumn('Location', function ($data) {
    //                         $stateData =  State::where('id', $data->state_id)->first();
    //                         $areaData =  Area::where('id', $data->area_id)->first();
    //                         $Location = '<div class="d-flex align-items-center">
    //                         <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
    //                         <div class="d-flex flex-column">
    //                             <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($stateData->state) . '</a>
    //                             <span>' . ucfirst($areaData->area) . '</span>
    //                         </div>
    //                     </div>';
    //                         return $Location;
    //                     })
    //                     ->addColumn('OrderPlacedBy', function ($data) {
    //                         if (!empty($data->sub_seller_id)) {
    //                             $sellerData =  User::where('id', $data->sub_seller_id)->first();
    //                             $OrderPlacedBy = ucfirst($sellerData->name);
    //                         } else {
    //                             $OrderPlacedBy = "N/A";
    //                         }
    //                         return $OrderPlacedBy;
    //                     })
    //                     ->addColumn('SellerName', function ($data) {
    //                         if (!empty($data->seller_id)) {
    //                             $sellerData =  User::where('id', $data->seller_id)->first();
    //                             $SellerName = ucfirst($sellerData->name);
    //                         } else {
    //                             $SellerName = "N/A";
    //                         }
    //                         return $SellerName;
    //                     })
    //                     ->addColumn('custom_order_id', function ($data) {
    //                         $subSeller = User::find($data->sub_seller_id); // or use relationship if available
    //                         $subSellerUniqueId = $subSeller->unique_id ?? 'N/A';
    //                         return $subSellerUniqueId . '-' . $data->id;
    //                     })

    //                     ->addColumn('statusView', function ($data) {
    //                         $statusLabels = [
    //                             'received' => ['class' => 'bg-success', 'text' => 'Received'],
    //                             'not_received' => ['class' => 'bg-danger', 'text' => 'Not Received']
    //                         ];
    //                         if (isset($statusLabels[$data->rto_status])) {
    //                             return "<div class='badge {$statusLabels[$data->rto_status]['class']}'>{$statusLabels[$data->rto_status]['text']}</div>";
    //                         }
    //                         return "<div class='badge bg-secondary'>Unknown</div>";
    //                     })
    //                     ->addColumn('action', function ($data) {
    //                         $action = '';
    //                         if ($data->rto_status !== 'received' && Auth::user()->type == 'admin') {
    //                             $action .= '<a onclick="changeStatus(' . $data->id . ')" class="btn btn-danger btn-sm me-2">
    //                      Receive Rto
    //         </a>';
    //                         }
    //                         $action .= '<a href="#" class="view btn btn-sm btn-info me-2" data-id="' . $data->id . '"
    //                     data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
    //                     <i style="font-size: 16px; padding: 0;" class="fa-solid fa-eye"></i>
    //                 </a>';
    //                         $action .= '<a href="' . route('admin_order_details', encrypt($data->id)) . '" class="btn btn-sm btn-info me-2">
    //                     <i style="font-size: 16px; padding: 0;" class="fa-solid fa-print">
    //                 </a>';
    //                         return $action;
    //                     })
    //                     ->rawColumns(['LogisticCompany', 'custom_order_id', 'Location', 'SellerName', 'OrderPlacedBy', 'customerName', 'statusView', 'action'])
    //                     ->make(true);
    //             }
    //             ActivityLogger::UserLog(Auth::user()->name . ' Open Order RTO Page');
    //             $StateData = State::orderBy('id', 'DESC')->get();
    //             $AreaData = Area::orderBy('id', 'DESC')->get();
    //             $LogisticCompanyData = User::where('role', 'logistic_company')->where('status', 'active')->orderBy('id', 'DESC')->get();

    //             return view('admin.pages.products.order.rto', compact('StateData', 'AreaData', 'LogisticCompanyData'));
    //         }
    //     }


    public function receive_rto(Request $request)
    {
        $order_id = $request->id;
        if (!empty($order_id)) {
            $order = Order::with('orderItems')->findOrFail($order_id);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            if ($order->rto_status != 'received') {
                foreach ($order->orderItems as $item) {
                    $productStockBatch = ProductStockBatch::where('id', $item->batch_id)->first();
                    if ($productStockBatch) {
                        $productStockBatch->quantity += $item->quantity;
                        $productStockBatch->save();
                    }
                }
            } else {
                return response()->json(3);
            }
            $order->rto_status = 'received';
            $order->save();
            return response()->json(1);
        } else {
            return response()->json(2);
        }
    }
    public function order_details(Request $request, $id)
    {
        $order_id = decrypt($id);
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
        ])->find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return view('admin.pages.products.order.order_details', compact('order_id', 'order'));
    }
    public function get_order(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'view')) {
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
    public function get_areas_shipping(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'edit')) {
            $areas = Area::where('id', '=', $request->id)->first();
            $shipping = $areas->shipping;
            return response()->json(['shipping' => $shipping]);
        }
    }
    public function status_order(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'status')) {
            $id = $request->id;
            $status = $request->status;
            $detail = Order::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'Pending']);
                    $updatedRTO = $detail->update(['rto_status' => null]);
                    ActivityLogger::UserLog('Update Order Status to Pending');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'Cancelled']);
                    $updatedRTO = $detail->update(['rto_status' => 'not_received']);
                    ActivityLogger::UserLog('Update Order Status to Cancelled');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_drivers(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'view')) {
            $drivers = User::where('company_id', '=', $request->company_id)->get();
            $options = '<option value="" disabled selected>Choose Driver</option>';
            foreach ($drivers as $driver) {
                $options .= '<option value="' . $driver->id . '">' . $driver->name . '</option>';
            }
            return response()->json(['options' => $options]);
        }
    }
    public function get_areas(Request $request)
    {
        if (ActivityLogger::hasPermission('orders', 'view')) {
            $areas = Area::where('state_id', '=', $request->state_id)->get();
            $options = '<option value="" disabled selected>Choose Area</option>';
            foreach ($areas as $area) {
                $options .= '<option value="' . $area->id . '">' . $area->area . '</option>';
            }
            return response()->json(['options' => $options]);
        }
    }
    public function assign_orders(Request $request)
    {
        if (empty($request->selected_orders) || empty($request->assign_logistic_company)) {
            return response()->json(2);
        }

        $orderIds = json_decode($request->selected_orders, true);

        try {
            // First handle Pending orders
            Order::whereIn('id', $orderIds)
                ->where('status', 'Pending')
                ->update([
                    'company_id' => $request->assign_logistic_company,
                    'status' => 'Processing',
                    'company_assign_date' => Carbon::now()
                ]);

            // Then handle already in-progress orders
            Order::whereIn('id', $orderIds)
                ->whereIn('status', ['Processing', 'Shipped', 'Out_for_delivery', 'Future'])
                ->update([
                    'company_id' => $request->assign_logistic_company,
                    'driver_id' => null,
                    'driver_assign_date' => null,
                ]);

            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }



    public function edit($id)
    {
        if (ActivityLogger::hasPermission('orders', 'edit')) {
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
            return view('admin.pages.products.order.edit', compact('order', 'states', 'areas', 'products'));
        }
    }
    // public function update(Request $request, $id)
    // {
    //     if (ActivityLogger::hasPermission('orders', 'edit')) {
    //         $order = Order::findOrFail($id);

    //         $request->validate([
    //             'customer_name' => 'required',
    //             'phone' => 'required',
    //             'state' => 'required',
    //             'areas' => 'required',
    //             'address' => 'required',
    //             'cod_amount' => 'required|numeric|min:0'
    //         ]);

    //         // Begin transaction
    //         DB::beginTransaction();

    //         try {
    //             // Update basic order info
    //             $order->update([
    //                 'customer_name' => $request->customer_name,
    //                 'phone' => $request->phone,
    //                 'whatsapp' => $request->whatsapp,
    //                 'state_id' => $request->state,
    //                 'area_id' => $request->areas,
    //                 'instructions' => $request->instructions,
    //                 'address' => $request->address,
    //                 'map_url' => $request->map_url,
    //                 'cod_amount' => $request->cod_amount,
    //             ]);

    //             // Recalculate all totals
    //             $totals = $this->recalculateOrderTotals($order);

    //             DB::commit();

    //             ActivityLogger::UserLog(Auth::user()->name . ' Updated order: ' . $id);
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Order updated successfully',
    //                 'totals' => $totals
    //             ]);
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $e->getMessage()
    //             ], 400);
    //         }
    //     }
    // }

    public function update(Request $request, $id)
    {
        if (ActivityLogger::hasPermission('orders', 'edit')) {
            $order = Order::findOrFail($id);

            $request->validate([
                'customer_name' => 'required',
                'phone' => 'required',
                'state' => 'required',
                'areas' => 'required',
                'address' => 'required',
                'cod_amount' => 'required|numeric|min:0'
            ]);

            DB::beginTransaction();

            try {
                $originalCodAmount = $order->cod_amount;

                // Step 1: Update order
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

                // Step 2: Recalculate totals (includes profit)
                $totals = $this->recalculateOrderTotals($order); // Updates DB and returns values

                // Step 3: If cod_amount changed, update transactions
                if ($originalCodAmount != $request->cod_amount) {
                    $transactions = Transaction::where('order_id', $order->id)->get();

                    foreach ($transactions as $transaction) {
                        if ($transaction->user_type === 'seller') {
                            $transaction->amount = $totals['profit']; // Use recalculated profit
                            $transaction->save();
                        }

                        if ($transaction->user_type === 'logistic_company') {
                            $transaction->amount = $totals['shipping']; // Use recalculated shipping
                            $transaction->save();
                        }
                    }
                }

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
        if (!ActivityLogger::hasPermission('orders', 'edit')) return abort(403);

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
        if (ActivityLogger::hasPermission('orders', 'edit')) {
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
        if (ActivityLogger::hasPermission('orders', 'edit')) {
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
        if (ActivityLogger::hasPermission('orders', 'edit')) {
            $productId = $request->product_id;
            $variations = productVariation::where('product_id', $productId)->get();

            $options = '<option value="">Select Variation</option>';
            foreach ($variations as $variation) {
                $options .= '<option value="' . $variation->id . '">' . $variation->variation_name . ': ' . $variation->variation_value . '</option>';
            }

            return response()->json(['options' => $options]);
        }
    }
    public function get_seller_service_charges(Request $request)
    {


        $cod_amount = (float) $request->cod_amount;

        $sellerId = $request->seller_id;
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
    //     public function print($id)
    // {
    //     $order_id = $id;
    //     $order = Order::with([
    //         'seller' => function ($query) {
    //             $query->select('id', 'name', 'unique_id');
    //         },
    //         'subSeller' => function ($query) {
    //             $query->select('id', 'name', 'unique_id');
    //         },
    //         'state' => function ($query) {
    //             $query->select('id', 'state');
    //         },
    //         'area' => function ($query) {
    //             $query->select('id', 'area');
    //         },
    //         'orderItems.product' => function ($query) {
    //             $query->select('id', 'product_name', 'product_image');
    //         },
    //         'orderItems.productVariation' => function ($query) {
    //             $query->select('id', 'variation_name', 'variation_value', 'variation_image');
    //         }
    //     ])->find($id);
    //     if (!$order) {
    //         return response()->json(['error' => 'Order not found'], 404);
    //     }
    //     return view('admin.pages.products.order.bulk_print',compact('order_id','order'));

    // }
    public function bulkPrint(Request $request)
    {
        $ids = explode(',', $request->input('ids'));

        $orders = Order::with(['orderItems.product', 'orderItems.productVariation', 'subSeller', 'state', 'area'])
            ->whereIn('id', $ids)
            ->get();

        return view('admin.pages.products.order.bulk_print', compact('orders'));
    }





    public function destroy($id)
    {

        try {
            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        // Step 1: Check if user has permission
        if (!ActivityLogger::hasPermission('orders', 'status')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission denied: You are not authorized to change order status.'
            ], 403);
        }

        // Step 2: Validate input
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|string'
        ]);

        try {
            // Step 3: Find order
            $order = Order::find($request->id);
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            // Step 4: Update status
            $oldStatus = $order->status;
            $order->status = $request->status;
            $order->save();

            // Step 5: Log status change
            ActivityLogger::UserLog("Order #{$order->id} status changed from {$oldStatus} to {$request->status}");

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
