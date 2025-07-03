@extends('seller.layouts.app')
@section('title', 'Cart')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Cart</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Cart</li>
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
                                                <input type="text" data-kt-customer-table-filter="search" id="search"
                                                    class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search " />
                                            </div>
                                        </div>
                                        @php
                                            if (Illuminate\Support\Facades\Auth::user()->role == 'seller') {
                                                $cartItems = \App\Models\Cart::where(
                                                    'seller_id',
                                                    auth()->id(),
                                                )->count();
                                            } else {
                                                $cartItems = \App\Models\Cart::where(
                                                    'sub_seller_id',
                                                    auth()->id(),
                                                )->count();
                                            }
                                        @endphp
                                        @if ($cartItems > 0)
                                            <div class="col-md-6 mt-3 text-md-end text-center">
                                                <a href="{{ route('checkout') }}" class="btn btn-primary me-4">
                                                    <span class="btn-text-inner">Proceed To Checkout</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"
                                                class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="min-w-125px">id</th>
                                                        <th class="min-w-125px">Product Name</th>
                                                        <th class="min-w-125px">Product Quantity</th>
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
                            <h1 class="mb-3">Update Cart</h1>
                        </div>
                        <form method="post" id="updateCartForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="cart_id" id="cart_id">
                            <div class="row">
                                <div id="product_name">
                                </div>
                                <div id="product-variations-image">
                                </div>

                                <div id="product-variations-price">
                                </div>

                                <div class="col-md-12 mb-8">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Quantity</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" name="quantity"
                                        id="quantity" min="1" value="1">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="update_cart()" id="edit_kt_modal_new_target_submit"
                                    class="btn btn-primary">
                                    <span class="indicator-label">Update Cart</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{--            </div> --}}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#InsertForm").on("keypress", function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    insert_item();
                }
            });
            $("#editForm").on("keypress", function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    update_item();
                }
            });
        });
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('all_cart') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'productName',
                        name: 'productName',

                    }, {
                        data: 'quantity',
                        name: 'quantity',
                        render: function(data, type, row) {
                            return `
            <div class="d-flex align-items-center justify-content-center">
                <button class="btn btn-sm btn-light decrement-btn" data-id="${row.id}" data-current="${data}">-</button>
                <input type="text" class="form-control form-control-sm text-center mx-2" style="width: 60px;" value="${data}" readonly />
                <button class="btn btn-sm btn-light increment-btn" data-id="${row.id}" data-current="${data}">+</button>
            </div>
        `;
                        }
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(0).html(incrementedId);
                },
                responsive: true,
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
    <script>
        $('body').on('click', '.edit', function() {
            var id = $(this).data('id');
            $('#edit_kt_modal_new_target').trigger("reset");
            $('.product-variations').empty();
            $('#product-variations-price').empty();
            $('#product-variations-image').empty();
            $('#product_name').empty();
            $('#cart_id').val('');
            document.getElementById("updateCartForm").reset();
            $.ajax({
                url: "{{ Route('get_cart') }}",
                type: "get",
                data: {
                    edit: 1,
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    $('#cart_id').val(dataResult.id);
                    $('#quantity').val(dataResult.quantity);
                    get_product(dataResult.product_id)
                    get_variation_price(dataResult.product_variation_id);
                    setTimeout(function() {
                        $('#variation_id').val(dataResult.product_variation_id);
                    }, 1000);
                }
            });
        });

        function deleteItem(deleteid) {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
            var csrf_token = $("input[name=csrf]").val();
            $.ajax({
                url: '{{ route('delete_cart') }}',
                type: 'GET',
                data: {
                    id: deleteid,
                },
                success: function(response) {
                    if (response == 1) {
                        toastr.success('Item removed from cart and stock updated.');
                        location.reload();
                    } else if (response == 2) {
                        toastr.error('Cart item not found.');
                    } else if (response == 3) {
                        toastr.error('Product not found.');
                    } else if (response == 4) {
                        toastr.error('Product variation not found.');
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        }
        $(document).ready(function() {
            $('#quantity').on('input', function() {
                if ($(this).val() < 1) {
                    $(this).val(1);
                }
            });

        });


        // Increment
        $('body').on('click', '.increment-btn', function() {
            let cartId = $(this).data('id');
            let currentQty = parseInt($(this).data('current'));
            let newQty = currentQty + 1;

            $('#cart_id').val(cartId);
            $('#quantity').val(newQty);

            update_cart(); // ← Using your existing function
        });

        // Decrement
        $('body').on('click', '.decrement-btn', function() {
            let cartId = $(this).data('id');
            let currentQty = parseInt($(this).data('current'));
            let newQty = currentQty - 1;

            if (newQty < 1) {
                toastr.error('Quantity must be at least 1.');
                return;
            }

            $('#cart_id').val(cartId);
            $('#quantity').val(newQty);

            update_cart(); // ← Using your existing function
        });

        function update_cart() {
            var cart_id = $('#cart_id').val();
            var quantity = $('#quantity').val();
            if (quantity < 1) {
                toastr.error('Quantity must be at least 1.');
                return;
            }
            $.ajax({
                url: "{{ route('update_cart') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cart_id,
                    quantity: quantity
                },
                success: function(response) {
                    if (response == 1) {
                        toastr.success('Cart updated successfully!');
                        location.reload();
                    } else if (response == 2) {
                        toastr.error('Cart ID is required.');
                    } else if (response == 3) {
                        toastr.error('Invalid quantity.');
                    } else if (response == 4) {
                        toastr.error('Invalid variation ID.');
                    } else if (response == 5) {
                        toastr.error('Cart item not found.');
                    } else if (response == 6) {
                        toastr.error('Product not found.');
                    } else if (response == 7) {
                        toastr.error('Not enough stock.');
                    } else if (response == 8) {
                        toastr.error('Product variation not found.');
                    } else if (response == 9) {
                        toastr.error('Not enough stock for variation.');
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
                url: "{{ Route('get_seller_product_variation_price') }}",
                type: "get",
                data: {
                    variation_id: variation_id
                },
                cache: false,
                success: function(dataResult) {
                    $('#product-variations-price').empty();
                    $('#product-variations-price').append(`
                    <div class="col-md-12 mt-2 mb-2">
                            <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">${dataResult.product_variations.variation_reg_price} ADE</p>
                    </div>
                `);
                    $('#product-variations-image').empty();
                    $('#product-variations-image').append(`
                    <div class="col-md-12 mt-2 mb-2 text-center">
                        <img src="{{ asset('storage/') }}/${dataResult.product_variations.variation_image}" class="w-50  h-auto">
                    </div>
                `);
                }
            });
        }

        function get_product(id) {
            $.ajax({
                url: "{{ route('get_seller_product_data') }}",
                type: "get",
                data: {
                    id: id
                },
                cache: false,
                success: function(dataResult) {
                    $('#product_name').html(`<h3 class="text-center">${dataResult.product.product_name}</h3>`);
                    $('#product_id').val(dataResult.product.id);
                    if (dataResult.product.product_type == 'simple') {
                        $('#product-variations-price').append(`
                    <div class="col-md-12 mt-2 mb-2">
                        <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">
                            ${dataResult.product.product_reg_price} ADE
                        </p>
                    </div>
                `);
                        $('#product-variations-image').append(`
                    <div class="col-md-12 mt-2 mb-2 text-center">
                        <img src="{{ asset('storage/') }}/${dataResult.product.product_image}" class="w-50 h-auto">
                    </div>
                `);
                    }
                }
            });
        }
    </script>
@endpush
