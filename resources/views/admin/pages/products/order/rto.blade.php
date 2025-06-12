@extends('admin.layouts.app')
@section('title','Rto')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
            if (!ActivityLogger::hasPermission('orders','view')){
               abort(403, 'Unauthorized action.');
        }
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
                        <li class="breadcrumb-item text-muted">RTO</li>
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
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">State</label>
                                                    <select name="state" id="state" class="js-example-basic-single form-control form-control-solid" onchange="get_area(this.value)">
                                                        <option value="" selected>Choose a State</option>
                                                        @foreach($StateData as $state)
                                                            <option value="{{$state->id}}">{{$state->state}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-3"  >
                                                    <label class="required mb-2">Area</label>
                                                    <select name="area" id="area" class="js-example-basic-single1 form-control form-control-solid">
                                                        <option value="" selected>Choose a Area</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-3" id="start_date_display" >
                                                    <label class="required mb-2">Start Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="start_date" id="start_date">
                                                </div>
                                                <div class="col-md-3 mt-3" id="end_date_display">
                                                    <label class="required mb-2">End Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="end_date" id="end_date">
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="required mb-2">Logistic Company</label>
                                                    <select name="logistic_company" id="logistic_company" class="js-example-basic-single2 form-control form-control-solid">
                                                        <option value="" selected>Choose a Logistic Company</option>
                                                        @foreach($LogisticCompanyData as $LogisticCompany)
                                                            <option value="{{$LogisticCompany->id}}">{{$LogisticCompany->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="required mb-2">RTO Status</label>
                                                    <select name="status" id="status" class=" form-control form-control-solid">
                                                        <option value=""  selected>Choose RTO Status</option>
                                                        <option value="received">Received</option>
                                                        <option value="not_received">Not Received</option>
                                                    </select>
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
                                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target" id="assignOrdersBtn" style="display: none;">
    <span class="svg-icon svg-icon-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
            <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor"></path>
            <path d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="#C4C4C4"></path>
        </svg>
    </span>Assign Orders
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
                                                    <!--<th class="min-w-25px">Order Id</th>-->
                                                    <th class="min-w-70px">Customer Name</th>
                                                    <th class="min-w-70px">Seller Name</th>
                                                    <th class="min-w-125px">Order Placed By</th>
                                                    <th class="min-w-125px">Logistic Company</th>
                                                    <th class="min-w-125px">Location</th>
                                                    <th class="min-w-30px">COD [ADE]</th>
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
                            {{--                            <div class="col-md-6 mb-3">--}}
                            {{--                                <h6>Seller ID:</h6>--}}
                            {{--                                <p id="seller_id"></p>--}}
                            {{--                            </div>--}}
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
                    url: "{{ route('all_rto_orders') }}",
                    type: 'GET',
                    data: function (d) {
                        d.state = $('#state').val();
                        d.area = $('#area').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.logistic_company = $('#logistic_company').val();
                        d.status = $('#status').val();
                    },
                },
                columns: [
                    {data: 'id', name: 'id', orderable: false, searchable: false},
                    // {data: 'custom_order_id', name: 'custom_order_id'},
                    { data: 'customerName', name: 'customerName' },
                    { data: 'SellerName', name: 'SellerName' },
                    { data: 'OrderPlacedBy', name: 'OrderPlacedBy' },
                    { data: 'LogisticCompany', name: 'LogisticCompany' },
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
            $('#logistic_company,#status,#state,#area,#start_date,#end_date').on('change', function () {
                table.ajax.reload();
            });
        });
        function reset_table() {
            $('#drivers,#status,#state,#area,#start_date,#end_date,#search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
            $('#assignOrdersBtn').hide();
        }
        function get_area(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{Route('get_admin_areas')}}",
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
                url: "{{Route('get_admin_order')}}",
                type: "GET",
                data: { id: id },
                cache: false,
                success: function (response) {
                    if (response.order) {
                        var order = response.order;
                        $('#view_order_id').text(order.sub_seller.unique_id+'-'+order.id);
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
                        $('#view_subtotal').text(order.subtotal +' ADE');
                        $('#view_shipping_fee').text(order.shipping_fee +' ADE');
                        $('#view_total').text(order.total +' ADE');
                        $('#view_cod_amount').text(order.cod_amount +' ADE');
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
        function changeStatus(id) {
            const data = {
                id: id
            };
            $.ajax({
                url: "{{ route('receive_rto') }}",
                type: "GET",
                data: {
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Order Rto Received Successfully.');

                    } else if (dataResult == 2) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.error('Order  Not Found.');
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
