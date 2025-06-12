@extends('admin.layouts.app')
@section('title','Seller Orders')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Orders</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ucfirst($seller_data->name)}}</li>
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
                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">State</label>
                                                    <select name="state" id="state" class="js-example-basic-single form-control form-control-solid" onchange="get_area(this.value)">
                                                        <option value="" selected>Choose a State</option>
                                                        @foreach($StateData as $state)
                                                            <option value="{{$state->id}}">{{$state->state}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3"  >
                                                    <label class="required mb-2">Area</label>
                                                    <select name="area" id="area" class="js-example-basic-single1 form-control form-control-solid">
                                                        <option value="" selected>Choose a Area</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">Status</label>
                                                    <select name="status" id="status" class=" form-control form-control-solid">
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
                                                <div class="col-md-4 mt-3" id="start_date_display" >
                                                    <label class="required mb-2">Start Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="start_date" id="start_date">
                                                </div>
                                                <div class="col-md-4 mt-3" id="end_date_display">
                                                    <label class="required mb-2">End Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="end_date" id="end_date">
                                                </div>

                                            </div>
                                            <hr style=" border: none; border-top: 1px solid black;">
                                        </div>
                                 
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-6 col-md-2 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total COD</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="deliveredCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Delivered COD</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="cancelledCod">0</h4>
                                                            </div>
                                                            <p class="mb-1">Cancelled COD</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-2 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalProfit">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Profit</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-2 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalShipping">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Shipping Fee</p>
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
                                                <tr>
                                                    <th class="min-w-25px">id</th>
                                                    <th class="min-w-70px">Customer Name</th>
                                                    <th class="min-w-70px">Seller Name</th>
                                                    <th class="min-w-125px">Order Placed By</th>
                                                    <th class="min-w-125px">Location</th>
                                                    <th class="min-w-30px">COD [AED]</th>
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
                            <div class="col-md-6 mb-3">
                                <h6>Order ID:</h6>
                                <p id="view_order_id"></p>
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
                                <h6>COD Amount:</h6>
                                <p id="view_cod_amount"></p>
                            </div>
                            <div class="col-md-4 mb-3">
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
                ajax: {
                    url: "{{ route('all_sub_seller_orders_admin', $id) }}",
                    type: 'GET',
                    data: function (d) {
                        d.state = $('#state').val();
                        d.area = $('#area').val();
                        d.current_date = $('#current_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.sub_seller = $('#sub_seller').val();
                        d.status = $('#status').val();
                    },
                    dataSrc: function(json) {
                        $('#totalCod').html(json.totalCod);
                        $('#deliveredCod').html(json.deliveredCod);
                        $('#cancelledCod').html(json.cancelledCod);
                        $('#totalProfit').html(json.totalProfit);
                        $('#totalShipping').html(json.totalShipping);
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
                    {data: 'id', name: 'id'},
                    { data: 'customerName', name: 'customerName' },
                    { data: 'SellerName', name: 'SellerName' },
                    { data: 'OrderPlacedBy', name: 'OrderPlacedBy' },
                    { data: 'Location', name: 'Location' },
                    { data: 'cod_amount', name: 'cod_amount' },
                    { data: 'statusView', name: 'statusView' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(0).html(incrementedId);
                },
                responsive: true,
                pageLength: 50,
                lengthMenu: [[50, 100, 200,300,400, 500, 1000, 1500], [50, 100, 200,300,400, 500, 1000, 1500]],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#sub_seller,#status,#state,#area,#current_date,#start_date,#end_date').on('change', function () {
                table.ajax.reload();
            });
        });
        function reset_table() {
            $('#sub_seller,#status,#state,#area,#current_date,#start_date,#end_date,#search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }
        function get_area(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{Route('get_seller_order_admin')}}",
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
                url: "{{Route('get_seller_order_admin')}}",
                type: "GET",
                data: { id: id },
                cache: false,
                success: function (response) {
                    if (response.order) {
                        var order = response.order;
                        $('#view_order_id').text(order.sub_seller.unique_id+'-'+order.id);
                        $('#view_customer_name').text(order.customer_name);
                        $('#view_phone').text(order.phone);
                        $('#view_whatsapp').text(order.whatsapp);
                        $('#view_state').text(order.state ? order.state.state : 'N/A');
                        $('#view_area').text(order.area ? order.area.area : 'N/A');
                        $('#view_instructions').text(order.instructions);
                        $('#view_address').text(order.address);
                        $('#view_map_url').html(`<a href="${order.map_url}" target="_blank">${order.map_url}</a>`);
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

    </script>
@endpush
