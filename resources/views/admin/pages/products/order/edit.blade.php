@extends('admin.layouts.app')
@section('title', 'Edit Order')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Edit Order #{{ $order->id }}</h1>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Details</h3>
                    </div>
                    <div class="card-body">
                        <form id="orderForm" action="{{ route('admin_orders.update', $order->id) }}" method="POST">
                            @csrf

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <h4>Customer Information</h4>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <input type="text" class="form-control form-control-solid" name="customer_name" value="{{ $order->customer_name }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control form-control-solid" name="phone" value="{{ $order->phone }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">WhatsApp</label>
                                        <input type="text" class="form-control form-control-solid" name="whatsapp" value="{{ $order->whatsapp }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h4>Shipping Information</h4>
                                    <div class="form-group mb-3">
                                        <label class="form-label">State</label>
                                        <select class="form-select form-select-solid js-example-basic-single" name="state" id="stateSelect" required>
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ $order->state_id == $state->id ? 'selected' : '' }}>{{ $state->state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Area</label>
                                        <select class="form-select form-select-solid js-example-basic-single" name="areas" id="areaSelect" required>
                                            <option value="">Select Area</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}" {{ $order->area_id == $area->id ? 'selected' : '' }}>{{ $area->area }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control form-control-solid" name="address" required>{{ $order->address }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Map URL</label>
                                        <input type="text" class="form-control form-control-solid" name="map_url" value="{{ $order->map_url }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Instructions</label>
                                        <textarea class="form-control form-control-solid" name="instructions">{{ $order->instructions }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5 mt-5">
                                <div class="col-md-12">
                                    <h4>Order Items</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Stock</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="orderItemsTable">
                                            @foreach($order->orderItems as $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <td>
                                                        @if($item->productVariation)
                                                            <img src="{{ asset('storage/'.$item->productVariation->variation_image) }}" width="50">
                                                            {{ $item->product->product_name }} - {{ $item->productVariation->variation_name }}: {{ $item->productVariation->variation_value }}
                                                        @else
                                                            <img src="{{ asset('storage/'.$item->product->product_image) }}" width="50">
                                                            {{ $item->product->product_name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                    
                                                         <p class="mt-3">{{ number_format($item->productStockBatch->regular_price, 2) }} AED</p>
                                                    
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-solid item-quantity" value="{{ $item->quantity }}" min="1" style="width: 80px;">
                                                    </td>
                                                    <td class="stock-display">
                                                    
                                                            <p class="mt-3">    Stock: {{ $item->productStockBatch->quantity }}</p>
                                                     
                                                    </td>
                                                    <td>
                                                            <p class="mt-3">   {{ number_format($item->productStockBatch->regular_price * $item->quantity, 2) }} AED</p>
                                                    
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger remove-item">Remove</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <h4>Add New Product</h4>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product</label>
                                        <select class="form-select form-select-solid js-example-basic-single" id="productSelect">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-type="{{ $product->product_type }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="variationContainer" style="display: none;">
                                        <label class="form-label">Variation</label>
                                        <select class="form-select form-select-solid js-example-basic-single" id="variationSelect">
                                            <option value="">Select Variation</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control form-control-solid" id="newItemQuantity" min="1" value="1">
                                    </div>
                                    <button type="button" class="btn btn-primary" id="addItemBtn">Add Product</button>
                                </div>

                                <div class="col-md-6">
                                    <h4>Order Summary</h4>
                                    <table class="table">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end" id="subtotalDisplay">{{ number_format($order->subtotal, 2) }} AED</td>
                                        </tr>
                                        <tr>
                                            <td>Shipping Fee:</td>
                                            <td class="text-end" id="shippingDisplay">{{ number_format($order->shipping_fee, 2) }} AED</td>
                                        </tr>
                                        <tr>
                                            <td>Service Charges:</td>
                                            <td class="text-end" id="service_charge">{{ number_format($order->service_charges, 2) }} AED</td>
                                        </tr>
                                        <tr>
                                            <td>Total:</td>
                                            <td class="text-end" id="totalDisplay">{{ number_format($order->total, 2) }} AED</td>
                                        </tr>
                                        <tr>
                                            <td>COD Amount:</td>
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-solid" id="cod_amount" name="cod_amount"
                                                       value="{{ $order->cod_amount }}" min="0" step="0.01">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Profit (COD - Total):</td>
                                            <td class="text-end fw-bold
                @if($order->profit < 0) text-danger @else text-success @endif"
                                                id="profitDisplay">
                                                {{ number_format($order->profit, 2) }} AED
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Order</button>
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
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $(document).ready(function() {
            // Handle state change to load areas
            $('#stateSelect').change(function() {
                const stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: "{{ route('admin_get_areas_shipping') }}",
                        type: "GET",
                        data: { state_id: stateId },
                        success: function(data) {
                            $('#areaSelect').html(data.options);
                        }
                    });
                } else {
                    $('#areaSelect').html('<option value="">Select Area</option>');
                }
            });

           
            // Update item quantity with inventory feedback
            $(document).on('change', '.item-quantity', function() {
                const row = $(this).closest('tr');
                const itemId = row.data('item-id');
                const newQuantity = $(this).val();

                // Show loading indicator
                const originalValue = $(this).val();
                $(this).prop('disabled', true);
                row.find('.remove-item').prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin_orders.update_item', $order->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_id: itemId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update stock display if available
                            if (response.new_stock !== undefined) {
                                row.find('.stock-display').text('Stock: ' + response.new_stock);
                            }
                            location.reload(); // Reload to show updated totals
                        } else {
                            toastr.info(response.message);
                            $(this).val(originalValue);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                        $(this).val(originalValue);
                    },
                    complete: function() {
                        $(this).prop('disabled', false);
                        row.find('.remove-item').prop('disabled', false);
                    }
                });
            });

            $('#productSelect').change(function () {
    const productId = $(this).val();
    const productType = $(this).find(':selected').data('type');

    if (productType === 'variable') {
        $.ajax({
            url: "{{ route('admin_get_product_variations') }}",
            type: "GET",
            data: { product_id: productId },
            success: function (data) {
                $('#variationContainer').show();
                $('#variationSelect').html(data.options);
            }
        });
    } else {
        $('#variationContainer').hide();
        $('#variationSelect').html('<option value="">Select Variation</option>');
    }
});

$('#addItemBtn').click(function () {
    const productId = $('#productSelect').val();
    const variationId = $('#variationSelect').val();
    const quantity = $('#newItemQuantity').val();
    const productType = $('#productSelect').find(':selected').data('type');

    if (!productId) return toastr.error('Please select a product');
    if (productType === 'variable' && !variationId) return toastr.error('Please select a variation');

    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');

    $.ajax({
        url: "{{ route('admin_orders.add_item', $order->id) }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            product_id: productId,
            variation_id: variationId,
            quantity: quantity
        },
        success: function (response) {
            toastr.success(response.message);
            location.reload();
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON.message || 'Something went wrong');
        },
        complete: function () {
            $('#addItemBtn').prop('disabled', false).text('Add Product');
        }
    });
});

// Remove item with inventory feedback
            $(document).on('click', '.remove-item', function() {
                if (!confirm('Are you sure you want to remove this item? The stock will be restored.')) return;

                const row = $(this).closest('tr');
                const itemId = row.data('item-id');

                // Disable during processing
                $(this).prop('disabled', true);
                row.find('.item-quantity').prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin_orders.remove_item', $order->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_id: itemId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message with restored stock info if available
                            let message = 'Item removed successfully';
                            if (response.restored_stock !== undefined) {
                                message += '\nRestored stock: ' + response.restored_stock;
                            }
                            toastr.info(message);
                            location.reload();
                        } else {
                            toastr.info(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

        });
        function updateOrderSummary(totals) {
            $('#subtotalDisplay').text(totals.subtotal.toFixed(2) + ' AED');
            $('#shippingDisplay').text(totals.shipping);
            $('#totalDisplay').text(totals.total.toFixed(2) + ' AED');

            // Update profit with color coding
            const profitElement = $('#profitDisplay');
            profitElement.text(totals.profit.toFixed(2) + ' AED');

            if (totals.profit < 0) {
                profitElement.removeClass('text-success').addClass('text-danger');
            } else {
                profitElement.removeClass('text-danger').addClass('text-success');
            }
        }

        // Handle area change to update shipping
        $('#areaSelect').change(function() {
            const areaId = $(this).val();

            if (areaId) {
                $.ajax({
                    url: "{{ route('admin_get_areas_shipping') }}",
                    type: "GET",
                    data: { id: areaId },
                    success: function(response) {
                        // Update shipping fee in the form
                        $('#shippingDisplay').text(response.shipping);

                        // Trigger order update to recalculate totals
                        $('#orderForm').submit();
                    }
                });
            }
        });

        // Handle COD amount change
        $('input[name="cod_amount"]').on('input', function() {
           var cod_amount = $('#cod_amount').val();
           var seller_id = {{ $order->seller_id }};
            $.ajax({
        url: "{{ route('get_seller_service_charges_admin') }}",
        type: "GET",
        data: { 
            cod_amount: cod_amount,
            seller_id: seller_id
         },
        dataType: "json",
        success: function(res) {
            console.log(res);
            if (res.status == true) {
           
                 $('#service_charge').html(res.details.amount + ' AED');
                 $('#service_charge_input').val(res.details.amount);
                 $('#service_charge_input_id').val(res.details.id);
                $('#PlaceOrder').empty();
          var subtotal = {{ $order->subtotal }};
         var shipping = {{ $order->shipping_fee }};
     var cod = parseFloat($('#cod_amount').val()) || 0;

      var profit = cod - subtotal - shipping - res.details.amount;
        $('#profit_input').val(profit);
     if(profit < 0){
         $('#profit').html(`<h3 class="float-end text-danger">${profit.toFixed(2)} AED</h3>`);
     }else{
         $('#profit').html(`<h3 class="float-end text-success">${profit.toFixed(2)} AED</h3>`);
     }
if(profit > 0){
    $('#PlaceOrder').append(`
                  <div class="card-footer ">
                                    <button type="button" class="btn btn-primary submitBtn float-end" id="kt_modal_new_target_submit" onclick="placeOrder()">Place Order</button>
                                </div>
                `);
}else{
    $('#PlaceOrder').append(`
                  <div class="card-footer text-center">
                                    <h4 class="text-danger">You Cannot Place Order If Profit is Negative.</h4>
                                </div>
                `);
}
$('#orderForm').submit();
            }else{

            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let errorMessage = "Please fix the following errors:\n";
            for (let key in errors) {
                errorMessage += errors[key][0] + "\n";
            }
            alert(errorMessage);
        }
    });
         
        });

        // AJAX form submission for order updates
        $('#orderForm').submit(function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');

            // Save original button text
            const originalText = submitBtn.text();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Update the totals display
                        updateOrderSummary(response.totals);

                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error: ' + xhr.responseJSON.message);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });


    </script>
@endpush
