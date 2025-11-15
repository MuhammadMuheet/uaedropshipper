@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Orders</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Orders</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area card">
                                    <div class="card-header border-0 pt-6 w-100">
                                        <div class="col-md-12 mt-3" id="filter_section" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">Seller</label>
                                                    <select name="seller" id="seller"
                                                        class="js-example-basic-single2 form-control form-control-solid">
                                                        <option value="" selected>Choose a Seller</option>
                                                        @foreach ($sellerData as $seller)
                                                            <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">Logistic Company</label>
                                                    <select name="logistic_company" id="logistic_company"
                                                        class="js-example-basic-single2 form-control form-control-solid"
                                                        onchange="get_drivers(this.value)">
                                                        <option value="" selected>Choose a Logistic Company</option>
                                                        @foreach ($LogisticCompanyData as $LogisticCompany)
                                                            <option value="{{ $LogisticCompany->id }}">
                                                                {{ $LogisticCompany->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">Drivers</label>
                                                    <select name="drivers" id="drivers"
                                                        class="js-example-basic-single1 form-control form-control-solid">
                                                        <option value="" selected>Choose a Driver</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">Status</label>
                                                    <select name="status" id="status"
                                                        class=" form-control form-control-solid">
                                                        <option value="" selected>Choose a Status</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Processing">Processing</option>
                                                        <option value="Shipped">Shipped</option>
                                                        <option value="Delivered">Delivered</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                        <option value="Future">Future</option>
                                                        <option value="ReturnRTO">RTO Received</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="required mb-2">State</label>
                                                    <select name="state" id="state"
                                                        class="js-example-basic-single form-control form-control-solid"
                                                        onchange="get_area(this.value)">
                                                        <option value="" selected>Choose a State</option>
                                                        @foreach ($StateData as $state)
                                                            <option value="{{ $state->id }}">{{ $state->state }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="required mb-2">Area</label>
                                                    <select name="area" id="area"
                                                        class="js-example-basic-single1 form-control form-control-solid">
                                                        <option value="" selected>Choose a Area</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">Current Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="current_date" id="current_date">
                                                </div>
                                                <div class="col-md-4 mt-3" id="start_date_display">
                                                    <label class="required mb-2">Start Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="start_date" id="start_date">
                                                </div>
                                                <div class="col-md-4 mt-3" id="end_date_display">
                                                    <label class="required mb-2">End Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="end_date" id="end_date">
                                                </div>

                                            </div>
                                            <hr style="border: none; border-top: 1px solid black;">
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="row">

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total COD</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="deliveredCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Delivered COD</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="cancelledCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Cancelled COD</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalProfit">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Profit</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalShipping">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Shipping Fee</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="service_chargesCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Service Charges</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalOrdersCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">All Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalPendingCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Pending Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalProcessingCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Processing Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalShippedCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Shipped Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalDeliveredCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Delivered Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalCancelledCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Cancelled Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalOut_for_deliveryCount">0
                                                                </h4>
                                                            </div>
                                                            <p class="mb-1">Out For Delivery Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalFutureCount">0</h4>
                                                            </div>
                                                            <p class="mb-1">Future Orders</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalwallet">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Wallet</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                            height="2" rx="1"
                                                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                        <path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <input type="text" data-kt-customer-table-filter="search"
                                                    id="search" class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">
                                            <button type="button" class="btn btn-light-success me-3" id="exportAllBtn">
                                                <i class="fas fa-file-excel"></i> Export All
                                            </button>
                                            <button type="button" class="btn btn-light-success me-3"
                                                id="exportSelectedBtn" style="display: none;">
                                                <i class="fas fa-file-excel"></i> Export Selected
                                            </button>
                                            <button type="button" class="btn btn-light-info me-3" id="bulkPrintBtn"
                                                style="display: none;">
                                                <i class="fas fa-print"></i> Print Selected
                                            </button>
                                            <button type="button" class="btn btn-light-primary me-3"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_new_target"
                                                id="assignOrdersBtn" style="display: none;">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="12.75" y="4.25" width="12"
                                                            height="2" rx="1"
                                                            transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
                                                        <path
                                                            d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                                            fill="#C4C4C4"></path>
                                                    </svg>
                                                </span>Assign Orders
                                            </button>
                                            <button class="btn btn-flex btn-primary fw-bolder" onclick="toggleFilter()">
                                                <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                Filter
                                            </button>
                                            <button type="button" class="btn btn-danger me-3"
                                                onclick="reset_table()">Reset </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"
                                                class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="min-w-25px">
                                                            <div
                                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="main_action" data-kt-check="true"
                                                                    data-kt-check-target="#table .form-check-input"
                                                                    value="1">
                                                            </div>
                                                        </th>
                                                        <th class="min-w-25px">id</th>
                                                        <!--<th class="min-w-25px">Order id</th>-->
                                                        <th class="min-w-70px">Customer Name</th>
                                                        <th class="min-w-70px">Seller Name</th>
                                                        <th class="min-w-125px">Order Placed By</th>
                                                        <th class="min-w-125px">Logistic Company</th>
                                                        <th class="min-w-125px">Location</th>
                                                        <th class="min-w-30px">COD </th>
                                                        <th class="min-w-40px">Status</th>
                                                        <th class="min-w-125px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_new_target" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        action="#">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Assign Orders To Logistic Company</h1>
                        </div>
                        <form method="post" id="InsertForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="required mb-2">Logistic Company</label>
                                    <select name="assign_logistic_company" id="assign_logistic_company"
                                        class="js-example-basic-single2 form-control form-control-solid">
                                        <option value="" selected>Choose a Logistic Company</option>
                                        @foreach ($LogisticCompanyData as $LogisticCompany)
                                            <option value="{{ $LogisticCompany->id }}">{{ $LogisticCompany->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="insert_item()" id="kt_modal_new_target_submit"
                                    class="btn btn-primary">
                                    <span class="indicator-label">Add</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_kt_modal_new_target" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div id="kt_modal_new_target_form">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Order Details</h1>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Order ID:</h6>
                                <p id="view_order_id"></p>
                            </div>
                            {{--                            <div class="col-md-6 mb-3"> --}}
                            {{--                                <h6>Seller ID:</h6> --}}
                            {{--                                <p id="seller_id"></p> --}}
                            {{--                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <h6>Ordered By:</h6>
                                <p id="view_sub_seller_id"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Customer Name:</h6>
                                <p id="view_customer_name"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Phone:</h6>
                                <p id="view_phone"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>WhatsApp:</h6>
                                <p id="view_whatsapp"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>State:</h6>
                                <p id="view_state"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Area:</h6>
                                <p id="view_area"></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6>Address:</h6>
                                <p id="view_address"></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6>Instructions:</h6>
                                <p id="view_instructions"></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6>Map URL:</h6>
                                <p id="view_map_url"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Subtotal:</h6>
                                <p id="view_subtotal"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Shipping Fee:</h6>
                                <p id="view_shipping_fee"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Total:</h6>
                                <p id="view_total"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>COD Amount:</h6>
                                <p id="view_cod_amount"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Status:</h6>
                                <p id="view_status"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>Driver :</h6>
                                <p id="view_driver"></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6>Proof (It shows when your order proceed completely):</h6>
                                <p id="view_proof_image"></p>
                                <p id="view_delivery_instruction"></p>
                            </div>
                        </div>

                        <hr>

                        <h3 class="mb-3">Order Items</h3>
                        <table class="table table-hover table-row-dashed fs-6 gy-5 my-0  no-footer">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="order_items_table">
                                <!-- Order items will be injected here via AJAX -->
                            </tbody>
                        </table>

                        <div class="text-center">
                            <button type="button" class="btn btn-primary me-3" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStatusForm">
                        @csrf
                        <input type="hidden" id="update_order_id">
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Select New Status</label>
                            <select id="order_status" class="form-select">
                                <option value="Processing">Processing</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div id="deliveredFields" style="display: none;">
                            <div class="mb-3">
                                <label for="proof_image" class="form-label">Upload Delivery Proof</label>
                                <input type="file" id="proof_image" name="proof_image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                <input type="date" id="delivery_date" name="delivery_date" class="form-control">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script type="text/javascript">
        function toggleFilter() {
            var filterSection = document.getElementById("filter_section");
            if (filterSection.style.display === "none") {
                $('#filter_section').slideDown();
                // filterSection.style.display = "block";
            } else {
                // filterSection.style.display = "none";
                $('#filter_section').slideUp();
            }
        }

        function toggleAssignButton() {
            if ($('input[name="bulk_action[]"]:checked').length > 0) {
                $('#assignOrdersBtn').show();
                $('#bulkPrintBtn').show();
                $('#exportSelectedBtn').show();
            } else {
                $('#assignOrdersBtn').hide();
                $('#bulkPrintBtn').hide();
                $('#exportSelectedBtn').hide();
            }
        }

        function toggleBulkAssignButton() {
            if ($('#main_action:checked').length > 0) {
                $('#assignOrdersBtn').show();
                $('#bulkPrintBtn').show();
                $('#exportSelectedBtn').show();
            } else {
                $('#assignOrdersBtn').hide();
                $('#bulkPrintBtn').hide();
                $('#exportSelectedBtn').hide();
            }
        }
        $(document).ready(function() {
            $(document).on('change', 'input[name="bulk_action[]"]', function() {
                toggleAssignButton();
            });
            $(document).on('change', '#main_action', function() {
                toggleBulkAssignButton();
            });
            toggleAssignButton();
            toggleBulkAssignButton()
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single1').select2();
            $('.js-example-basic-single2').select2();
        });
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('all_admin_orders') }}",
                    type: 'GET',
                    data: function(d) {
                        d.state = $('#state').val();
                        d.area = $('#area').val();
                        d.current_date = $('#current_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.logistic_company = $('#logistic_company').val();
                        d.drivers = $('#drivers').val();
                        d.seller = $('#seller').val();

                        d.status = $('#status').val();
                    },
                    dataSrc: function(json) {
                        $('#totalCod').html(json.totalCod);
                        $('#deliveredCod').html(json.deliveredCod);
                        $('#cancelledCod').html(json.cancelledCod);
                        $('#totalProfit').html(parseFloat(json.totalProfit).toFixed(2));
                        $('#totalShipping').html(json.totalShipping);
                        $('#totalPendingCount').html(json.totalPendingCount);
                        $('#totalOrdersCount').html(json.totalOrdersCount);
                        $('#totalProcessingCount').html(json.totalProcessingCount);
                        $('#totalShippedCount').html(json.totalShippedCount);
                        $('#totalDeliveredCount').html(json.totalDeliveredCount);
                        $('#totalCancelledCount').html(json.totalCancelledCount);
                        $('#totalOut_for_deliveryCount').html(json.totalOut_for_deliveryCount);
                        $('#totalFutureCount').html(json.totalFutureCount);
                        $('#service_chargesCount').html(json.service_chargesCount);
                        $('#totalwallet').html(json.totalWallet);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'BulkAction',
                        name: 'BulkAction',
                        orderable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    // {data: 'custom_order_id',
                    //     name: 'custom_order_id'  },
                    {
                        data: 'customerName',
                        name: 'customerName'
                    },
                    {
                        data: 'SellerName',
                        name: 'SellerName'
                    },
                    {
                        data: 'OrderPlacedBy',
                        name: 'OrderPlacedBy'
                    },
                    {
                        data: 'LogisticCompany',
                        name: 'LogisticCompany'
                    },
                    {
                        data: 'Location',
                        name: 'Location'
                    },
                    {
                        data: 'COD',
                        name: 'COD'
                    },
                    {
                        data: 'statusView',
                        name: 'statusView'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(1).html(incrementedId);
                },
                responsive: true,
                pageLength: 50,
                lengthMenu: [
                    [50, 100, 200, 300, 400, 500, 1000, 1500],
                    [50, 100, 200, 300, 400, 500, 1000, 1500]
                ],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#logistic_company,#drivers,#seller,#status,#state,#area,#current_date,#start_date,#end_date').on(
                'change',
                function() {
                    table.ajax.reload();
                });
        });

        function reset_table() {
            $('#logistic_company,#drivers,#status,#seller,#state,#area,#current_date,#start_date,#end_date,#search').val('')
                .trigger('change');
            $('#table').DataTable().ajax.reload();
            $('#assignOrdersBtn').hide();
        }

        function get_area(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{ Route('get_admin_areas') }}",
                type: "get",
                data: {
                    state_id: selectedValue,
                },
                cache: false,
                success: function(dataResult) {
                    $('#area').html(dataResult.options);

                }
            });
        }

        function get_drivers(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{ Route('get_admin_orders_drivers') }}",
                type: "get",
                data: {
                    company_id: selectedValue,
                },
                cache: false,
                success: function(dataResult) {
                    $('#drivers').html(dataResult.options);

                }
            });
        }
    </script>
    <script>
        $('#exportAllBtn').on('click', function() {
            window.location.href = "{{ route('orders.export') }}";
        });

        // Export Selected
        $('#exportSelectedBtn').on('click', function() {
            var selectedOrders = [];
            $("input[name='bulk_action[]']:checked").each(function() {
                selectedOrders.push($(this).val());
            });

            if (selectedOrders.length === 0) {
                toastr.error("Please select at least one order.");
                return;
            }

            // Create a form and submit it
            var form = $('<form>', {
                action: "{{ route('orders.export.selected') }}",
                method: "POST",
                target: "_blank"
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: "{{ csrf_token() }}"
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'order_ids',
                value: JSON.stringify(selectedOrders)
            }));

            $('body').append(form);
            form.submit();
            form.remove();
        });

        // Show/hide export selected button based on checkbox selection
        $(document).on('change', 'input[name="bulk_action[]"], #main_action', function() {
            if ($('input[name="bulk_action[]"]:checked').length > 0) {
                $('#exportSelectedBtn').show();
            } else {
                $('#exportSelectedBtn').hide();
            }
        });
        $('body').on('click', '.view', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ Route('get_admin_order') }}",
                type: "GET",
                data: {
                    id: id
                },
                cache: false,
                success: function(response) {
                    if (response.order) {
                        var order = response.order;
                        $('#view_order_id').text(order.sub_seller.unique_id + '-' + order.id);
                        $('#view_seller_id').text(order.seller ? order.seller.name : 'N/A');
                        $('#view_sub_seller_id').text(order.sub_seller ? order.sub_seller.name : 'N/A');
                        $('#view_customer_name').text(order.customer_name);
                        $('#view_phone').text(order.phone);
                        $('#view_whatsapp').text(order.whatsapp);
                        $('#view_state').text(order.state ? order.state.state : 'N/A');
                        $('#view_area').text(order.area ? order.area.area : 'N/A');
                        $('#view_instructions').text(order.instructions);
                        $('#view_address').text(order.address);
                        $('#view_map_url').html(
                            `<a href="${order.map_url}" target="_blank">${order.map_url}</a>`);
                        $('#view_subtotal').text(order.subtotal + ' ADE');
                        $('#view_shipping_fee').text(order.shipping_fee + ' ADE');
                        $('#view_total').text(order.total + ' ADE');
                        $('#view_cod_amount').text(order.cod_amount + ' ADE');
                        const updatedDate = order.updated_at.split('T')[
                            0];
                        $('#view_status').text(order.status + ' (' + updatedDate + ')');
                        $('#view_driver').text(order?.driver?.name || 'No Driver Assign');
                        if (order.proof_image != '' && order.proof_image != null) {
                            $('#view_proof_image').html(
                                `<img src="{{ asset('storage/') }}/${order.proof_image}" target="_blank" style="width:200px; height:200px">`
                            );

                        }
                        if (order.delivery_instruction != '' && order.delivery_instruction != null) {
                            $('#view_delivery_instruction').text(order.delivery_instruction);

                        }
                        var orderItemsHtml = "";
                        $.each(order.order_items, function(index, item) {
                            if (item.product_variation) {
                                var product_image = item.product_variation.variation_image;
                            } else {
                                var product_image = item.product.product_image;
                            }
                            var variation = item.product_variation ?
                                `[ ${item.product_variation.variation_name} - ${item.product_variation.variation_value} ]` :
                                "";
                            orderItemsHtml += `
                        <tr>
                            <td><img src="{{ asset('storage/') }}/${product_image}" width="50"></td>
                            <td>${item.product.product_name} <br> ${variation}</td>
                            <td>${item.quantity}</td>
                        </tr>`;
                        });
                        $('#order_items_table').html(orderItemsHtml);
                        $('#edit_kt_modal_new_target').modal('show');
                    }
                },
                error: function() {
                    alert("Error fetching order details");
                }
            });
        });

        function changeStatus(newStatus, id) {
            const data = {
                status: newStatus,
                id: id
            };
            $.ajax({
                url: "{{ route('status_order') }}",
                type: "GET",
                data: {
                    status: newStatus,
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Order Cancel Successfully.');

                    } else if (dataResult == 2) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Order Pending Successfully.');
                    } else {
                        toastr.error('Something Went Wrong.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    toastr.error('An error occurred while updating the user.');
                }
            });
        }

        // function insert_item() {
        //     var form_Data = new FormData(document.getElementById("InsertForm"));

        //     // Collect selected order IDs
        //     var selectedOrders = [];
        //     $("input[name='bulk_action[]']:checked").each(function() {
        //         selectedOrders.push($(this).val());
        //     });

        //     if (selectedOrders.length === 0) {
        //         toastr.error("Please select at least one order.");
        //         return;
        //     }
        //     form_Data.append("selected_orders", JSON.stringify(selectedOrders));
        //     $("#kt_modal_new_target_submit").text("Loading").prop("disabled", true);
        //     $.ajax({
        //         url: "{{ route('assign_orders') }}",
        //         type: "POST",
        //         data: form_Data,
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success: function(dataResult) {
        //             console.log(dataResult);
        //             $("#kt_modal_new_target_submit").text("Add").prop("disabled", false);

        //             if (dataResult == 1) {
        //                 $('#table').DataTable().ajax.reload(null, false);
        //                 $('#kt_modal_new_target').modal('hide');
        //                 toastr.success('Orders assigned successfully.');
        //                 $("#InsertForm")[0].reset();
        //                 $('#assignOrdersBtn').hide();
        //             } else if (dataResult == 2) {
        //                 toastr.error('Select a logistic company.');
        //             } else {
        //                 toastr.error('Something went wrong.');
        //             }
        //         }
        //     });
        // }


        function insert_item() {
            var form_Data = new FormData(document.getElementById("InsertForm"));

            var selectedOrders = [];

            // Get all bulk_action checkboxes selected
            $("input[name='bulk_action[]']:checked").each(function() {
                selectedOrders.push($(this).val());
            });

            // If no bulk_action[] selected, check if single order_id hidden input exists
            if (selectedOrders.length === 0) {
                var singleOrderId = $("input[name='order_id']").val();
                if (singleOrderId) {
                    selectedOrders.push(singleOrderId);
                } else {
                    toastr.error("Please select at least one order.");
                    return;
                }
            }

            // Append the selected orders as JSON string
            form_Data.append("selected_orders", JSON.stringify(selectedOrders));

            $("#kt_modal_new_target_submit").text("Loading").prop("disabled", true);

            $.ajax({
                url: "{{ route('assign_orders') }}",
                type: "POST",
                data: form_Data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    $("#kt_modal_new_target_submit").text("Add").prop("disabled", false);

                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $('#kt_modal_new_target').modal('hide');
                        toastr.success('Orders assigned successfully.');
                        $("#InsertForm")[0].reset();
                        $('#assignOrdersBtn').hide();
                    } else if (dataResult == 2) {
                        toastr.error('Select a logistic company.');
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        }





        function deleteOrder(orderId) {
            const csrf_token = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin_orders.destroy', ['id' => ':id']) }}";
                    url = url.replace(':id', orderId);

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: csrf_token
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#table').DataTable().ajax.reload(null, false); // ✅ Refreshes table
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message || 'Something went wrong.';
                            toastr.error(msg);
                        }
                    });
                }
            });
        }
    </script>
    <script>
        $('#bulkPrintBtn').on('click', function() {
            var selected = $("input[name='bulk_action[]']:checked").map(function() {
                return this.value;
            }).get();

            if (selected.length === 0) {
                alert('Please select at least one order.');
                return;
            }

            var url = "{{ route('admin.orders.bulk.print') }}?ids=" + selected.join(',');
            window.open(url, '_blank');
        });









        // ✅ When the "Change Status" badge is clicked
        $(document).on('click', '.change-status', function() {
            const orderId = $(this).data('id');
            const currentStatus = $(this).data('status');

            console.log('Clicked order ID:', orderId);
            console.log('Current status:', currentStatus);

            $('#updateStatusForm')[0].reset();
            $('#update_order_id').val(orderId);
            $('#order_status').val(currentStatus).trigger('change');
            $('#deliveredFields').hide();
            $('#updateStatusModal').modal('show');
        });

        // ✅ When status is changed
        $('#order_status').on('change', function() {
            const selectedStatus = $(this).val();
            const orderId = $('#update_order_id').val();

            console.log('Status changed to:', selectedStatus);
            console.log('Order ID:', orderId);

            if (selectedStatus === 'Delivered') {
                $('#deliveredFields').slideDown();
            } else {
                $('#deliveredFields').slideUp();
                $('#proof_image').val('');
                $('#delivery_date').val('');
            }

            if (selectedStatus === 'Processing') {
                console.log('Processing selected, preparing to show logistic modal...');

                $('#updateStatusModal').one('hidden.bs.modal', function() {
                    console.log('UpdateStatusModal closed — injecting order ID:', orderId);

                    $('#InsertForm').find('input[name="order_id"]').remove();

                    // ✅ Confirm orderId is NOT empty
                    if (!orderId) {
                        console.warn('⚠️ orderId is empty. Logistic modal may not receive correct value.');
                    }

                    $('#InsertForm').append(`<input type="hidden" name="order_id" value="${orderId}">`);
                    $('#kt_modal_new_target').modal('show');
                });

                $('#updateStatusModal').modal('hide');
            }
        });

        // ✅ Reset modal on close
        $('#updateStatusModal').on('hidden.bs.modal', function() {
            console.log('Resetting updateStatusModal...');
            $('#updateStatusForm')[0].reset();
            $('#update_order_id').val('');
            $('#deliveredFields').hide();
            $('#proof_image').val('');
            $('#delivery_date').val('');
        });

        // ✅ Form submit with validation
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();

            const status = $('#order_status').val();
            const proofImage = $('#proof_image').val();
            const deliveryDate = $('#delivery_date').val();
            const orderId = $('#update_order_id').val();

            console.log('Submitting form for status:', status, 'Order ID:', orderId);

            if (status === 'Delivered') {
                if (!proofImage) {
                    toastr.error('Please upload a delivery proof image.');
                    return;
                }
                if (!deliveryDate) {
                    toastr.error('Please select a delivery date.');
                    return;
                }
            }

            const formData = new FormData(this);
            formData.append('id', orderId);
            formData.append('status', status);

            $.ajax({
                url: '{{ route('orders.update.status') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Status updated successfully:', response);
                    $('#updateStatusModal').modal('hide');
                    $('#table').DataTable().ajax.reload(null,
                    false); // Reload without resetting pagination
                    toastr.success('Status updated successfully.');
                },
                error: function(xhr) {
                    // CURRENT: toastr.error('Failed to update status.');
                    // IMPROVE TO SHOW ACTUAL MESSAGE FROM BACKEND:
                    let message = 'Failed to update status.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                }
            });
        });
    </script>
@endpush