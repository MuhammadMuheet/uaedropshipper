@extends('admin.layouts.app')
@section('title','All Product Stocks')
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
                        <li class="breadcrumb-item text-muted">All Product Stocks</li>
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
                                        <div class="col-md-12 mt-3 " id="filter_section" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6 mt-3 mb-3">
                                                    <label class="required mb-2">Proudcts</label>
                                                    <select name="product" id="product" class="js-example-basic-single form-control form-control-solid" onchange="get_product_variation(this.value)">
                                                        <option value="" selected>Choose a Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                        @endforeach
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mt-3 mb-3">
                                                    <label class="required mb-2">Product Variations</label>
                                                    <select name="product_variation" id="product_variation" class="js-example-basic-single form-control form-control-solid">
                                                        <option value="" selected>Choose a Product Variations</option>
                                                    </select>
                                                </div>
                                                <hr style="border: none; border-top: 1px solid black;">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalPurchasePrice">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Purchase Price</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalSalePrice">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Sale Price</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded" >
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalQuantity">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Stock</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 ">
                                            <hr style="border: none; border-top: 1px solid black;">
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                       
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
                                            <table id="table" class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th >id</th>
                                                    <th class="min-w-125px">Product Name</th>
                                                    <th class="min-w-75px">Product Type</th>
                                                    <th class="min-w-75px">Stock</th>
                                                    <th class="min-w-75px">Avg Purchase</th>
                                                    <th class="min-w-75px">Avg Sale</th>
                                                    <th class="min-w-75px">Total Purchase</th>
                                                    <th class="min-w-75px">Total Sale</th>
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
            $('.js-example-basic-single').select2();
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
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('all_product_stocks') }}",
                    type: 'GET',
                    data: function (d) {
                        d.product = $('#product').val();
                        d.product_variation = $('#product_variation').val();
                    },
                    dataSrc: function(json) {
                        $('#totalPurchasePrice').html(json.totalPurchasePrice);
                        $('#totalSalePrice').html(json.totalSalePrice);
                        $('#totalQuantity').html(json.totalQuantity);
                        return json.data;
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'productName', name: 'productName' },
        { data: 'productType', name: 'productType' },
        { data: 'quantity', name: 'quantity' },
        { data: 'avgPurchase', name: 'avgPurchase' },
        { data: 'avgSale', name: 'avgSale' },
        { data: 'totalPurchase', name: 'totalPurchase' },
    { data: 'totalSale', name: 'totalSale' },
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

            $('#product,#product_variation').on('change', function () {
                   table.ajax.reload();
            });
            
        });
        function reset_table() {
            $('#product,#product_variation').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }
        function get_product_variation(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{Route('get_admin_product_variation_stock')}}",
                type: "get",
                data: {
                    product_id: selectedValue,
                },
                cache: false,
                success: function (dataResult) {
                    $('#product_variation').html(dataResult.options);

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

    </script>
@endpush
