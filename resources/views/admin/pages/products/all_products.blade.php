@extends('admin.layouts.app')
@section('title','All Products')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Products</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Products</li>
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
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
													</svg>
												</span>
                                                <!--end::Svg Icon-->
                                                <input type="text" data-kt-customer-table-filter="search" id="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">
                                            @if(ActivityLogger::hasPermission('products','add'))
                                                <a href="{{route('add_product')}}" class="btn btn-primary  me-4" >
                                                    <span class="btn-text-inner">Add</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table" class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th >id</th>
                                                    <th class="min-w-65px">Product Image</th>
                                                    <th class="min-w-125px">Product Name</th>
                                                    <th class="min-w-125px">Product SKU</th>
                                                    <th class="min-w-75px">Product Type</th>
                                                    <th class="min-w-75px">Status</th>
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
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    </button>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Product Details</h1>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Product Image</label>
                        <div class="col-lg-8">
                            <span id="product_img" class="fw-bolder fs-6 text-gray-800"></span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Product Name</label>
                        <div class="col-lg-8">
                            <span id="product_name" class="fw-bolder fs-6 text-gray-800"></span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">SKU</label>
                        <div class="col-lg-8">
                            <span id="product_sku" class="fw-bolder fs-6 text-gray-800"></span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Short Description</label>
                        <div class="col-lg-8 " style="overflow-y: auto; height: 70px;">
                            <span id="product_short_des" class=" fs-6 text-gray-800"></span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Description</label>
                        <div class="col-lg-8" style="overflow-y: auto; height: 70px;">
                            <span id="product_des" class=" fs-6 text-gray-800"></span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Stock</label>
                        <div class="col-lg-8">
                            <span id="product_stock" class="fw-bolder fs-6 text-gray-800"></span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Sale Price</label>
                        <div class="col-lg-8">
                            <span id="product_sale_price" class="fw-bolder fs-6 text-gray-800"></span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-12 fw-bold text-muted">Product Variations</label>
                        <div class="col-lg-12">
                            <div id="variation_list"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary me-3" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('all_products') }}",
                    type: 'GET'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'productImage', name: 'productImage' },
                    { data: 'productName', name: 'productName' },
                    { data: 'productSku', name: 'productSku' },
                    { data: 'productType', name: 'productType' },
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
        });
        function changeStatus(newStatus, id) {
            const data = {
                status: newStatus,
                id: id
            };
            $.ajax({
                url: "{{ route('status_product') }}",
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
                        toastr.success('Product Block Successfully.');

                    } else if (dataResult == 2) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Product Active Successfully.');
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
        $(document).ready(function () {
            $('body').on('click', '.view', function () {
                var id = $(this).data('id');

                $('#edit_kt_modal_new_target').trigger("reset");

                $.ajax({
                    url: "{{route('get_product')}}", // Ensure this is the correct route
                    type: "GET",
                    data: { id: id },
                    cache: false,
                    success: function (response) {
                        console.log(response);

                        if (response.product) {
                            $('#product_img').html(`<a href="{{asset('storage/')}}/${response.product.product_image}" data-lightbox="roadtrip"><img src="{{asset('storage/')}}/${response.product.product_image}" style="width:30%; height:auto"></a>`);
                            updateField('#product_name', response.product.product_name);
                            updateField('#product_sku', response.product.product_sku);
                            updateHtmlField('#product_short_des', atob(response.product.product_short_des));
                            updateHtmlField('#product_des', atob(response.product.product_des));
                            updateField('#product_stock', response.product.product_stock);
                            updateField('#product_sale_price', response.product.product_sale_price);
                        }

                        // Handle Variations
                        if (response.product_variations.length > 0) {
                            let variationHtml = `<table class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer"><tr>`;

                            // Define columns dynamically
                            let columns = {
                                "Image": "variation_image",
                                "Variation Name": "variation_name",
                                "Value": "variation_value",
                                "SKU": "variation_sku",
                                "Stock": "variation_stock",
                                "Reg Price": "variation_reg_price",
                                "Sale Price": "variation_sale_price",


                            };
                            let activeColumns = {};
                            response.product_variations.forEach(variation => {
                                Object.keys(columns).forEach(key => {
                                    if (variation[columns[key]] && variation[columns[key]] !== "") {
                                        activeColumns[key] = columns[key];
                                    }
                                });
                            });

                            Object.keys(activeColumns).forEach(header => {
                                variationHtml += `<th>${header}</th>`;
                            });

                            variationHtml += `</tr>`;
                            response.product_variations.forEach(variation => {
                                variationHtml += `<tr>`;
                                Object.values(activeColumns).forEach(colKey => {
                                    if (colKey === "variation_image") {
                                        variationHtml += `<td><a href="{{asset('storage/')}}/${variation[colKey]}" data-lightbox="roadtrip"><img src="{{asset('storage/')}}/${variation[colKey]}" width="50" height="50" onerror="this.style.display='none'"> </a></td>`;
                                    } else {
                                        variationHtml += `<td>${variation[colKey]}</td>`;
                                    }
                                });
                                variationHtml += `</tr>`;
                            });
                            variationHtml += `</table>`;
                            $('#variation_list').html(variationHtml);
                        } else {
                            $('#variation_list').html("<p class='text-center'>No variations available</p>");
                        }

                        $('#edit_kt_modal_new_target').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Function to update fields dynamically and hide empty ones
            function updateField(selector, value) {
                if (value && value.trim() !== "") {
                    $(selector).closest('.row').show();
                    $(selector).text(value);
                } else {
                    $(selector).closest('.row').hide();
                }
            }
            function updateHtmlField(selector, value) {
                if (value && value.trim() !== "") {
                    $(selector).closest('.row').show();
                    $(selector).html(value); // Set HTML content
                } else {
                    $(selector).closest('.row').hide();
                }
            }
        });



        function deleteItem(deleteid) {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
            var csrf_token = $("input[name=csrf]").val();
            $.ajax({
                url: '{{route('delete_product')}}',
                type: 'GET', data: {
                    id: deleteid,
                }, success: function (data) {
                    if (data == 1) {
                        toastr.info('Successfully deleted.');
                        $('#table').DataTable().ajax.reload(null, false);

                    } else {
                        toastr.error('Something went wrong.');

                    }
                }
            });
        }
    </script>
@endpush
