@extends('seller.layouts.app')
@section('title', 'Products')
@section('content')
    <style>
        .nav-link {
            pointer-events: none;
        }

        .nav-link:hover {
            background-color: inherit;
            color: inherit;
        }
    </style>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
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
            <div id="kt_content_container" class="container-xxl">
                <div class="row mb-3 p-3 card">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" id="search" class="form-control form-control-solid w-100 ps-15"
                                placeholder="Search" oninput="fetchProducts()" />
                        </div>
                    </div>
                </div>
                <div id="product-list">
                    @include('seller.pages.products.partials.product_list', ['products' => $products])
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_kt_modal_new_target" tabindex="-1" style="display: none;" aria-hidden="true">
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
                            <h1 class="mb-3">Add To Cart</h1>
                        </div>
                        <form method="post" id="addToCartForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="product_variation_id" id="product_variation_id">
                            <input type="hidden" name="product_batch_id" id="product_batch_id">
                            <div class="row">
                                <div id="product_name">
                                </div>
                                <div id="product-variations-image">
                                </div>
                                <div class="col-md-12 product-variations mb-4">
                                    <!-- Variations will be injected here dynamically -->
                                </div>
                                <div id="product-variations-price">
                                </div>

                                <div class="col-md-12 mb-8">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Quantity</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" name="quantity"
                                        id="quantity" min="1">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="add_to_cart()" id="edit_kt_modal_new_target_submit"
                                    class="btn btn-primary">
                                    <span class="indicator-label">Add To Cart</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function fetchProducts(page = 1) {
            let search = $('#search').val();
            $.ajax({
                url: "{{ route('products') }}?page=" + page + "&search=" + search,
                type: "GET",
                success: function(response) {
                    $('#product-list').html(response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchProducts(page);
        });
        $(document).ready(function() {
            $('#quantity').on('input', function() {
                if ($(this).val() < 1) {
                    $(this).val(1);
                }
            });

        });

        function add_to_cart() {
            var product_id = $('#product_id').val();
            var product_variation_id = $('#product_variation_id').val();
            var quantity = $('#quantity').val();
            var product_batch_id = $('#product_batch_id').val();
            if (quantity < 1) {
                toastr.error('Quantity must be at least 1.');
                return;
            }
            $.ajax({
                url: "{{ route('add_to_cart') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    product_variation_id: product_variation_id,
                    quantity: quantity,
                    product_batch_id: product_batch_id
                },
                success: function(response) {
                    if (response == 1) {
                        toastr.success('Product added to cart successfully!');
                        $('#edit_kt_modal_new_target').modal('hide');
                    } else if (response == 2) {
                        toastr.error('Product ID is required.');
                    } else if (response == 3) {
                        toastr.error('Invalid quantity.');
                    } else if (response == 4) {
                        toastr.error('Invalid variation ID.');
                    } else if (response == 5) {
                        toastr.error('Product not found.');
                    } else if (response == 6) {
                        toastr.error('Not enough stock.');
                    } else if (response == 7) {
                        toastr.error('Variation not found.');
                    } else if (response == 8) {
                        toastr.error('Not enough stock for variation.');
                    } else if (response == 9) {
                        toastr.error('Product is out of stock');
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                error: function() {
                    toastr.error('Server error. Please try again later.');
                }
            });
        }

        function get_variation_price(variation_id) {
            $.ajax({
                url: "{{ route('get_seller_product_variation_price') }}",
                type: "get",
                data: {
                    variation_id: variation_id
                },
                cache: false,
                success: function(dataResult) {
                    $('#product_variation_id').val(dataResult.variation_id);
                    $('#product_batch_id').val(dataResult.batch_id);
                    $('#product-variations-price').empty();
                    $('#product-variations-price').append(`
                <div class="col-md-12 mt-2 mb-2">
                    <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">
                        ${dataResult.price ? dataResult.price + ' ADE' : 'Out of Stock'}
                    </p>
                </div>
            `);

                    $('#product-variations-image').empty();
                    $('#product-variations-image').append(`
                <div class="col-md-12 mt-2 mb-2 text-center">
                    <img src="{{ asset('storage/') }}/${dataResult.variation_image}" class="w-50 h-auto">
                </div>
            `);
                }
            });
        }
        $('body').on('click', '.edit', function() {
            var id = $(this).data('id');
            $('#addToCartForm').trigger("reset");

            $.ajax({
                url: "{{ route('get_seller_product_data') }}",
                type: "get",
                data: {
                    id: id
                },
                cache: false,
                success: function(dataResult) {
                    $('.product-variations').empty();
                    $('#product-variations-price').empty();
                    $('#product-variations-image').empty();
                    $('#product_name').empty();
                    $('#product_variation_id').val('');
                    $('#product_batch_id').val('');
                    $('#product_id').val(dataResult.product.id);

                    // Set product name
                    $('#product_name').html(
                        `<h3 class="text-center">${dataResult.product.product_name}</h3>`
                    );

                    // SIMPLE product
                    if (dataResult.product.product_type === 'simple') {
                        const priceInfo = dataResult.fifo_batch_price;
                        $('#product_batch_id').val(dataResult.batch_id);
                        $('#product-variations-price').append(`
                    <div class="col-md-12 mt-2 mb-2">
                        <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">
                            ${priceInfo ? priceInfo.price + ' ADE' : 'Out of Stock'}
                        </p>
                    </div>
                `);

                        const productImage = dataResult.product.product_image ?
                            `{{ asset('storage/') }}/${dataResult.product.product_image}` :
                            `{{ asset('images/product.jpg') }}`;

                        $('#product-variations-image').append(`
                    <div class="col-md-12 mt-2 mb-2 text-center">
                        <img src="${productImage}" class="w-50 h-auto">
                    </div>
                `);
                    }

                    // VARIABLE product
                    if (dataResult.product.product_type === 'variable') {
                        const variationPrices = dataResult.variation_prices;
                        let options = '';

                        $.each(variationPrices, function(index, variation) {
                            if (index === 0) {
                                $('#product_variation_id').val(variation.variation_id);
                                $('#product_batch_id').val(variation.batch_id);

                                // Fallback to product image if variation image is missing
                                const variationImage = variation.variation_image ?
                                    `{{ asset('storage/') }}/${variation.variation_image}` :
                                    (dataResult.product.product_image ?
                                        `{{ asset('storage/') }}/${dataResult.product.product_image}` :
                                        `{{ asset('images/product.jpg') }}`);

                                $('#product-variations-price').append(`
                            <div class="col-md-12 mt-2 mb-2">
                                <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">
                                    ${variation.price} ADE
                                </p>
                            </div>
                        `);

                                $('#product-variations-image').append(`
                            <div class="col-md-12 mt-2 mb-2 text-center">
                                <img src="${variationImage}" class="w-50 h-auto">
                            </div>
                        `);
                            }

                            options += `<option value="${variation.variation_id}" ${index === 0 ? 'selected' : ''}>
                        ${variation.variation_value}
                    </option>`;
                        });

                        if (variationPrices.length > 0) {
                            $('.product-variations').append(`
                        <div class="col-md-12 fv-row fv-plugins-icon-container">
                            <label class="form-label">Choose Product Variation</label>
                            <select class="form-select form-select-solid required-input" name="variation_id" onchange="get_variation_price(this.value)">
                                ${options}
                            </select>
                        </div>
                    `);
                        }
                    }
                }
            });
        });
    </script>
@endpush
