@extends('driver.layouts.app')
@section('title','Orders')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
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
            <div id="kt_content_container" class="container-xxl">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area card">
                                    <div class="card-header border-0 pt-6 w-100">

                                            <div class="col-md-12 mt-3" id="filter_section" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-4 mt-3">
                                                        <label class="required mb-2">State</label>
                                                        <select name="state" id="state" class="js-example-basic-single form-control form-control-solid" onchange="get_area(this.value)">
                                                            <option value="" selected>Choose a State</option>
                                                            @foreach($StateData as $state)
                                                                <option value="{{$state->id}}">{{$state->state}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label class="required mb-2">Area</label>
                                                        <select name="area" id="area" class="js-example-basic-single1 form-control form-control-solid">
                                                            <option value="" selected>Choose an Area</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label class="required mb-2">Status</label>
                                                        <select name="status" id="status" class="form-control form-control-solid">
                                                            <option value="" selected>Choose a Status</option>
                                                            <option value="Pending">Pending</option>
                                                            <option value="Processing">Processing</option>
                                                            <option value="Shipped">Shipped</option>
                                                            <option value="Delivered">Delivered</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mt-3" >
                                                        <label class="required mb-2">Current Date</label>
                                                        <input type="date" class="form-control form-control-solid" name="current_date" id="current_date">
                                                    </div>
                                                    <div class="col-md-4 mt-3" >
                                                        <label class="required mb-2">Start Date</label>
                                                        <input type="date" class="form-control form-control-solid" name="start_date" id="start_date">
                                                    </div>
                                                    <div class="col-md-4 mt-3" >
                                                        <label class="required mb-2">End Date</label>
                                                        <input type="date" class="form-control form-control-solid" name="end_date" id="end_date">
                                                    </div>
                                                </div>
                                                <hr style="border: none; border-top: 1px solid black;">
                                            </div>
                                        
                                            <div class="col-md-12 mt-3">
                                                <div class="row">
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalCod">0</h4>
                                                                </div>
                                                                <p class="mb-1">Total COD</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="deliveredCod">0</h4>
                                                                </div>
                                                                <p class="mb-1">Delivered COD</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="cancelledCod">0</h4>
                                                                </div>
                                                                <p class="mb-1">Cancelled COD</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                 
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalPendingCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Pending Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalProcessingCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Processing Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-4 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalShippedCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Shipped Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalDeliveredCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Delivered Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalCancelledCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Cancelled Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalOut_for_deliveryCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Out For Delivery Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3 mb-4">
                                                        <div class="card-shadow card rounded" >
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                                    <h4 class="ms-1 mb-0" id="totalFutureCount">0</h4>
                                                                </div>
                                                                <p class="mb-1">Future Orders</p>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
                                        <div class="col-12">
                                            <hr style=" border: none; border-top: 1px solid black;">
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
													</svg>
												</span>
                                                <input type="text" data-kt-customer-table-filter="search" id="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">
                                            <button class="btn btn-flex btn-primary fw-bolder" onclick="toggleFilter()">
    <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"></path>
        </svg>
    </span>
                                                Filter
                                            </button>
                                            <button type="button" class="btn btn-danger me-3" onclick="reset_table()">Reset </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"  class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr style="display: none;">
                                                    <th class="min-w-175px"></th>
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
    <div class="modal fade" id="kt_modal_new_target" tabindex="-1"
         data-bs-backdrop="static" data-bs-keyboard="false"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end" id="view_close_button">

                </div>
                <div class="modal-body scroll-y">
                    <form method="post" id="InsertForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">
                        <div class="d-flex flex-column scroll-y" id="kt_modal_add_user_scroll"
                             data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: true}"
                             data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header"
                             data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px"
                             style="max-height: 570px;">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <div class=" text-center">
                                    <h1 class="">Out For Delivery</h1>
                                </div>
                            </div>
                            <div class="fv-row fv-plugins-icon-container" id="information">

                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <div class="card border">
                                        <div class="border-bottom p-4">
                                            <h4 class="">
                                            <span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>                                            </span>
											</span>
                                                <span class="menu-title">Info</span>  </h4>
                                        </div>
                                        <div class="card-body">
                                            <p  id="driver_assign_date">
                                            </p>
                                            <p  id="view_delivery_cod_amount">
                                            </p>
                                            <p  id="view_delivery_instructions">
                                            </p>
                                            <p id="view_delivery_state">
                                            </p>
                                            <p id="view_delivery_area">
                                            </p>
                                            <p id="view_delivery_address">
                                            </p>
                                            <div class="text-center" id="view_map_url_delivery">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <div class="card border">
                                        <div class="border-bottom p-4">
                                            <h4 class="">
                                            <span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                                            </span>
											</span>
                                                <span class="menu-title">Customer</span>  </h4>
                                        </div>
                                        <div class="card-body">
                                            <p  id="view_delivery_customer_name">
                                            </p>
                                            <p  id="view_delivery_phone">
                                            </p>
                                            <p  id="view_delivery_whatsapp">
                                            </p>
                                            <div class="text-center" id="view_customer_button">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="fv-row fv-plugins-icon-container" id="delivery_instructions" style="display:none;">
                                 <input type="hidden" name="delivery_status" id="delivery_status">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="form-label">Delivery Instructions</label>
                                <textarea class="form-control form-control-solid w-100 required-input" placeholder="Write An Instructions" name="delivery_instruction" id="delivery_instruction"></textarea>

                            </div>
{{--                            <div class="fv-row mb-7 fv-plugins-icon-container" >--}}
{{--                                    <label class="form-label">Capture Proof of Delivery</label>--}}
{{--                                    <video id="video" style="width: 100%; height: auto" autoplay></video>--}}
{{--                                    <button type="button" class="btn btn-secondary mt-2" onclick="capturePhoto()">Capture</button>--}}
{{--                                    <canvas id="canvas" width="100%" height="250" style="display:none;"></canvas>--}}
{{--                                    <img id="preview" src="" class="img-fluid mt-2" style="display:none;">--}}
{{--                                    <input type="hidden" name="image_data" id="image_data">--}}
{{--                            </div>--}}
                                 <div class="fv-row mb-7 fv-plugins-icon-container">
                                     <label class="form-label">Proof of Delivery</label>

                                     <!-- Buttons for separate actions -->
                                     <div class="d-flex gap-2 mb-3">
                                         <button type="button" class="btn btn-primary" onclick="startCamera()">📷 Take a Photo</button>
                                         <button type="button" class="btn btn-secondary" onclick="triggerFileInput()">📂 Choose a Photo</button>
                                     </div>

                                     <!-- Camera Section -->
                                     <div id="cameraSection" style="display:none;">
                                         <video id="video" style="width: 100%; height: auto;" autoplay></video>
                                         <button type="button" class="btn btn-danger mt-2" onclick="capturePhoto()">📸 Capture</button>
                                     </div>

                                     <!-- File Input (Hidden) -->
                                     <input type="file" id="fileInput" class="form-control mt-2" accept="image/*" style="display:none;" onchange="handleFileSelect(event)">

                                     <!-- Canvas (Hidden) & Image Preview -->
                                     <canvas id="canvas" style="display:none;"></canvas>
                                     <img id="preview" src="" class="img-fluid mt-2" style="display:none;">

                                     <!-- Hidden Input to Store Image Data -->
                                     <input type="hidden" name="image_data" id="image_data">
                                 </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container" id="want_to_change_cod">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="change_cod" id="change_cod">
                                    <label class="form-check-label" for="change_cod">
                                        Are You Want to Change COD
                                    </label>
                                </div>
                            </div>
                                 <div class="fv-row mb-7 fv-plugins-icon-container" id="cod_amount_section" style="display: none;">
                                     <label class="form-label">Enter Changed COD [AED]</label>
                                     <input type="number" class="form-control form-control-solid" name="changed_cod_amount" id="changed_cod_amount">
                                 </div>
                             </div>
                        </div>
                        <div class="modal-footer d-block">
                            <div class="row" id="submit_btn" style="display:none;">
                                <div class="col-12 text-center">
                            <button type="button" id="kt_modal_new_target_submit" class="btn btn-primary" onclick="insert_item()">Update</button>
                                </div>
                            </div>
                            <div class="row"  id="delivery_btn">
                                <div class="col-6">
                                    <button type="button" onclick="toggleInstructions('Delivered')" class="btn btn-success w-100">Delivered</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" onclick="toggleInstructions('Cancelled')" class="btn btn-danger w-100">Canceled</button>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="button" onclick="toggleInstructions('Future')" class="btn btn-primary w-100">Future</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
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
                            <div class="col-6 mb-3">
                                <h6>Order ID:</h6>
                                <p id="view_order_id"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6>Ordered By:</h6>
                                <p id="view_sub_seller_id"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6>Customer Name:</h6>
                                <p id="view_customer_name"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6>Phone:</h6>
                                <p id="view_phone"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>WhatsApp:</h6>
                                <p id="view_whatsapp"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>State:</h6>
                                <p id="view_state"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>Area:</h6>
                                <p id="view_area"></p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6>Address:</h6>
                                <p id="view_address"></p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6>Instructions:</h6>
                                <p id="view_instructions"></p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6>Map URL:</h6>
                                <p id="view_map_url"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>Subtotal:</h6>
                                <p id="view_subtotal"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>Shipping Fee:</h6>
                                <p id="view_shipping_fee"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>Total:</h6>
                                <p id="view_total"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>COD Amount:</h6>
                                <p id="view_cod_amount"></p>
                            </div>
                            <div class="col-4 mb-3">
                                <h6>Status:</h6>
                                <p id="view_status"></p>
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
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function () {
            toggleInstructions('nostatus')
        });
        function toggleInstructions(status) {
            let information = document.getElementById('information');
            let delivery_instructions = document.getElementById('delivery_instructions');
            let submit_btn = document.getElementById('submit_btn');
            let delivery_btn = document.getElementById('delivery_btn');
            let want_to_change_cod = document.getElementById('want_to_change_cod');
if(status === 'Delivered'){
    information.style.display = "none";
    delivery_instructions.style.display = "block";
    submit_btn.style.display = "flex";
    delivery_btn.style.display = "none";
    want_to_change_cod.style.display = "block";
    $('#delivery_status').val('Delivered');
}else if (status === 'Cancelled'){
    information.style.display = "none";
    delivery_instructions.style.display = "block";
    submit_btn.style.display = "flex";
    delivery_btn.style.display = "none";
    want_to_change_cod.style.display = "block";
    $('#delivery_status').val('Cancelled');
}else if (status === 'Future'){
    information.style.display = "none";
    delivery_instructions.style.display = "block";
    submit_btn.style.display = "flex";
    delivery_btn.style.display = "none";
    want_to_change_cod.style.display = "none";
    $('#delivery_status').val('Future');
}else{
    information.style.display = "block";
    delivery_instructions.style.display = "none";
    submit_btn.style.display = "none";
    delivery_btn.style.display = "flex";
    want_to_change_cod.style.display = "none";
    $('#delivery_status').val('');
}
        }
        $(document).ready(function () {
            $('#change_cod').change(function () {
                if ($(this).is(':checked')) {
                    $('#cod_amount_section').slideDown();
                } else {
                    $('#cod_amount_section').slideUp();
                    $('#changed_cod_amount').val(''); // Clear input value
                }
            });
        });
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
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single1').select2();
            $('.js-example-basic-single2').select2();
        });

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: {
                    url: "{{ route('all_driver_orders') }}",
                    type: 'GET',
                    data: function (d) {
                        d.state = $('#state').val();
                        d.area = $('#area').val();
                        d.start_date = $('#start_date').val();
                        d.current_date = $('#current_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status').val();
                    },
                    dataSrc: function(json) {
                        $('#totalCod').html(json.totalCod);
                        $('#deliveredCod').html(json.deliveredCod);
                        $('#cancelledCod').html(json.cancelledCod);
                        $('#totalProfit').html(json.totalProfit);
                        $('#totalPendingCount').html(json.totalPendingCount);
                        $('#totalProcessingCount').html(json.totalProcessingCount);
                        $('#totalShippedCount').html(json.totalShippedCount);
                        $('#totalDeliveredCount').html(json.totalDeliveredCount);
                        $('#totalCancelledCount').html(json.totalCancelledCount);
                        $('#totalOut_for_deliveryCount').html(json.totalOut_for_deliveryCount);
                        $('#totalFutureCount').html(json.totalFutureCount);
                        return json.data;
                    }
                },
                columns: [
                    { data: 'OrderDetails', name: 'OrderDetails',orderable: false, },
                ],
                responsive: true,
                pageLength: 5,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#state, #area, #current_date, #start_date, #end_date, #status').on('change', function () {
                table.ajax.reload();
            });
        });
        function reset_table() {
            $('#state, #area, #current_date, #start_date, #end_date, #status, #search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }
        function get_area(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{Route('get_driver_areas')}}",
                type: "get",
                data: {
                    state_id: selectedValue,
                },
                cache: false,
                success: function (dataResult) {
                    $('#area').html(dataResult.options);

                }
            });
        }
    </script>
    <script>
        $('body').on('click', '.view', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{Route('get_driver_order')}}",
                type: "GET",
                data: { id: id },
                cache: false,
                success: function (response) {
                    if (response.order) {
                        var order = response.order;
                        $('#view_order_id').text(order.id);
                        $('#view_seller_id').text(order.seller ? order.seller.name : 'N/A');
                        $('#view_sub_seller_id').text(order.sub_seller ? order.sub_seller.name : 'N/A');
                        $('#view_customer_name').text(order.customer_name);
                        $('#view_phone').text(order.phone);
                        $('#view_whatsapp').text(order.whatsapp);
                        $('#view_state').text(order.state ? order.state.state : 'N/A');
                        $('#view_area').text(order.area ? order.area.area : 'N/A');
                        $('#view_instructions').text(order.instructions);
                        $('#view_address').text(order.address);
                        $('#view_map_url').html(`<a href="${order.map_url}" target="_blank">${order.map_url}</a>`);
                        $('#view_subtotal').text(order.subtotal +' AED');
                        $('#view_shipping_fee').text(order.shipping_fee +' AED');
                        $('#view_total').text(order.total +' AED');
                        $('#view_cod_amount').text(order.cod_amount +' AED');
                        $('#view_status').text(order.status);
                        var orderItemsHtml = "";
                        $.each(order.order_items, function (index, item) {
                            if(item.product_variation){
                                var product_image= item.product_variation.variation_image;
                            }else{
                                var product_image= item.product.product_image;
                            }
                            var variation = item.product_variation
                                ? `[ ${item.product_variation.variation_name} - ${item.product_variation.variation_value} ]`
                                : "";
                            orderItemsHtml += `
                        <tr>
                            <td><img src="{{asset('storage/')}}/${product_image}" width="50"></td>
                            <td>${item.product.product_name} <br> ${variation}</td>
                            <td>${item.quantity}</td>
                        </tr>`;
                        });
                        $('#order_items_table').html(orderItemsHtml);
                        $('#edit_kt_modal_new_target').modal('show');
                    }
                },
                error: function () {
                    alert("Error fetching order details");
                }
            });
        });
        $('body').on('click', '.delivery', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{Route('get_driver_order')}}",
                type: "GET",
                data: { id: id },
                cache: false,
                success: function (response) {
                    if (response.order) {
                        var order = response.order;
                        $('#order_id').val(order.id);
                        $('#view_delivery_customer_name').text('Name: '+order.customer_name);
                        $('#view_delivery_phone').text('Phone: '+order.phone);
                        $('#view_delivery_whatsapp').text('Whatsapp: '+order.whatsapp);
                        $('#view_delivery_state').text('State: '+order.state.state );
                        $('#view_delivery_area').text('Area: '+order.area.area);
                        $('#view_delivery_address').text('Address: '+order.address);
                        $('#view_delivery_instructions').text('Instructions: '+order.instructions);
                        $('#view_close_button').html(`<button class="btn btn-flex btn-primary fw-bolder" data-bs-dismiss="modal" onclick="changeStatus(0, '${order.id}')" >
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                        Close
                    </button>`);
                        $('#view_customer_button').html(`  <a href="tel:${order.phone}" target="_blank" class="btn btn-flex btn-primary fw-bolder me-2">
                    <span class="svg-icon svg-icon-1">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>                    </span>
                                        Phone
                                    </a><a href="https://wa.me/${order.whatsapp}" target="_blank" class="btn btn-flex btn-success fw-bolder">
                    <span class="svg-icon svg-icon-1">
<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="whatsapp" class="svg-inline--fa fa-whatsapp fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>                    </span>
                                        Whatsapp
                                    </a>`);
                        $('#view_map_url_delivery').html(`  <a href="${order.map_url}" target="_blank" class="btn btn-flex btn-primary fw-bolder">
                    <span class="svg-icon svg-icon-1">
                 <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </span>
                                        Navigation
                                    </a>`);
                        $('#view_delivery_cod_amount').text('COD: '+order.cod_amount+' AED' );
                        $('#view_delivery_status').text(order.status);
                        let date = new Date(order.driver_assign_date);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        $('#driver_assign_date').text('Date: ' + formattedDate);

                    }
                },
                error: function () {
                    alert("Error fetching order details");
                }
            });
        });
    </script>
    <script>
        // function startCamera() {
        //     navigator.mediaDevices.getUserMedia({ video: { width: 1920, height: 1080 } }) // High-resolution
        //         .then(stream => {
        //             let video = document.getElementById('video');
        //             video.srcObject = stream;
        //         })
        //         .catch(error => console.error('Error accessing camera:', error));
        // }
        // function capturePhoto() {
        //     let video = document.getElementById('video');
        //     let canvas = document.getElementById('canvas');
        //     let context = canvas.getContext('2d');
        //
        //     // Set canvas size to match video feed
        //     canvas.width = video.videoWidth;
        //     canvas.height = video.videoHeight;
        //
        //     // Draw the image on canvas with high quality
        //     context.drawImage(video, 0, 0, canvas.width, canvas.height);
        //
        //     // Convert to high-quality JPEG (90% quality)
        //     let imageData = canvas.toDataURL('image/jpeg', 0.9);
        //
        //     // Display preview
        //     document.getElementById('preview').src = imageData;
        //     document.getElementById('preview').style.display = 'block';
        //     document.getElementById('image_data').value = imageData;
        // }
        // // Start camera when page loads
        // document.addEventListener("DOMContentLoaded", function () {
        //     startCamera();
        // });
        let stream = null; // To store camera stream and stop when needed

        function startCamera() {
            // Stop any existing stream before starting a new one
            stopCamera();

            navigator.mediaDevices.getUserMedia({ video: { width: 1920, height: 1080 } })
                .then(newStream => {
                    stream = newStream;
                    let video = document.getElementById('video');
                    video.srcObject = stream;
                    document.getElementById('cameraSection').style.display = 'block';
                    document.getElementById('fileInput').style.display = 'none'; // Hide file input
                })
                .catch(error => console.error('Error accessing camera:', error));
        }

        function capturePhoto() {
            let video = document.getElementById('video');
            let canvas = document.getElementById('canvas');
            let context = canvas.getContext('2d');

            // Set canvas size based on video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Capture and convert to high-quality JPEG
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            let imageData = canvas.toDataURL('image/jpeg', 0.9);

            // Show preview and store data
            document.getElementById('preview').src = imageData;
            document.getElementById('preview').style.display = 'block';
            document.getElementById('image_data').value = imageData;

            // Stop the camera after capture
            stopCamera();
        }

        function triggerFileInput() {
            stopCamera(); // Stop camera if active
            document.getElementById('fileInput').style.display = 'block';
            document.getElementById('fileInput').click();
        }

        function handleFileSelect(event) {
            let file = event.target.files[0];

            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                    document.getElementById('image_data').value = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            document.getElementById('cameraSection').style.display = 'none';
        }
        function dataURLtoFile(dataurl, filename) {
            let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, { type: mime });
        }
        function insert_item() {
            let orderId = document.getElementById('order_id').value;
            let delivery_status = document.getElementById('delivery_status').value;
            let changed_cod_amount = document.getElementById('changed_cod_amount').value;
            let instruction = document.getElementById('delivery_instruction').value.trim();
            let imageData = document.getElementById('image_data').value;

            // Validation
            if (!orderId) {
                toastr.error("Order ID is missing!");
                return;
            }
            if (!imageData) {
                toastr.error("Proof of delivery (photo) is required!");
                return;
            }

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('order_id', orderId);
            formData.append('delivery_instruction', instruction);
            formData.append('delivery_status', delivery_status);
            formData.append('changed_cod_amount', changed_cod_amount);
            let proofFile = dataURLtoFile(imageData, "proof.jpg");
            formData.append('files', proofFile);
            $.ajax({
                url: "{{route('order.markDelivered')}}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    document.getElementById("kt_modal_new_target_submit").innerHTML = "Update";
                    document.getElementById('kt_modal_new_target_submit').disabled = false;
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $('#kt_modal_new_target').modal('hide');
                        toastr.success('Status Change Successfully.');
                        document.getElementById("InsertForm").reset();
                        toggleInstructions('nostatus')
                    } else if(dataResult == 2){
                        toastr.error('Order not found');
                    }else if(dataResult == 3){
                        toastr.error('Please Provide Order Proof');
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }

                }
            });
        }

        function changeStatus(newStatus, id) {
            const data = {
                status: newStatus,
                id: id
            };

            $.ajax({
                url: "{{ route('status_driver_order') }}",
                type: "GET",
                data: {
                    status: newStatus,
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        toggleInstructions('nostatus')
                        $('#table').DataTable().ajax.reload(null, false);
                    } else if (dataResult == 2) {
                        toggleInstructions('nostatus')
                        $('#table').DataTable().ajax.reload(null, false);
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    toastr.error('An error occurred while updating the user.');
                }
            });
        }

    </script>
@endpush
