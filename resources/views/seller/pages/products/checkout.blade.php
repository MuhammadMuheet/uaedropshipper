@extends('seller.layouts.app')
@section('title', 'Checkout')

@push('css')
    <style>
        .select2-selection{
            background-color: #f5f8fa;
            border-color: #f5f8fa;
            color: #5e6278;
            padding: .75rem 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.5;
            background-clip: padding-box;
            appearance: none;
            border-radius: .475rem;
        }
    </style>
@endpush
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Checkout</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="flex-lg-auto min-w-lg-300px">
                     <div class="row">
                         <div class="col-lg-6">
                             <div class="card">
                                 <div class="row" style="padding: 2rem 2.25rem;">
                                     <div class="col-md-6 mt-3">
                                         <h6 class="title mt-5">Customer Details</h6>
                                     </div>
                                     <div class="col-md-6 mt-3 text-end">
                                        <button type="button" class="btn btn-danger" onclick="clearCustomerDetails()">Clear Form</button>
                                    </div>
                                 </div>
                                 <div class="separator separator-dashed"></div>

                                     <div class="card-body">
                                         <div class="row">
                                             <div class="col-md-6 mt-3">
                                                 <label class="form-label">Customer Name</label>
                                                 <input type="text" class="form-control form-control-solid required-input" name="cus_name" id="cus_name" value="{{ old('cus_name') }}" placeholder="Customer Name">
                                             </div>
                                             <div class="col-md-6 mt-3">
                                                 <label class="form-label">Telephone No</label>
                                                 <input type="tel" class="form-control form-control-solid required-input" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Telephone No">
                                             </div>
                                             <div class="col-md-6 mt-3">
                                                 <label class="form-label">Whatsapp No</label>
                                                 <input type="tel" class="form-control form-control-solid required-input" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" placeholder="Whatsapp No">
                                             </div>
                                             <div class=" col-md-6 mt-3">
                                                     <label class="form-label" for="state">Choose State</label>
                                                     <select class="js-example-basic-single form-select form-select-solid" id="state" name="state"  onchange="get_area(this.value)">
                                                         <option value="">Choose State</option>
                                                         @foreach($states as $state)
                                                             <option value="{{$state->id}}">{{$state->state}}</option>
                                                         @endforeach
                                                     </select>
                                             </div>
                                             <div class=" col-md-12 mt-3">
                                                 <label class="form-label" for="areas">Choose Area</label>
                                                 <select class="js-example-basic-single1 form-select form-select-solid" id="areas" name="areas" onchange="get_area_shipping(this.value)">
                                                     <option value="">Choose Area</option>
                                                 </select>
                                             </div>
                                             <div class="col-md-12 mt-3">
                                                 <label class="form-label">Instructions</label>
                                                 <textarea class="form-control form-control-solid" name="instructions" id="instructions"  placeholder="Write Instructions">{{ old('instructions') }}</textarea>
                                             </div>
                                             <div class="col-md-12 mt-3">
                                                 <label class="form-label">Map Url</label>
                                                 <input type="url" class="form-control form-control-solid" name="map_url" id="map_url" placeholder="Map Url">
                                             </div>
                                             <div class="col-md-12 mt-3">
                                                 <label class="form-label">Address</label>
                                                 <textarea class="form-control form-control-solid" name="address" id="address" placeholder="Write Address">{{ old('address') }}</textarea>
                                             </div>

                                         </div>
                                     </div>
                                     <div class="card-footer"></div>

                             </div>
                         </div>
                         <div class="col-lg-6">
                             <div class="card">
                                 <div class="row" style="padding: 2rem 2.25rem;">
                                     <div class="col-6 mt-3">
                                         <h6 class="title mt-5">Order Summary</h6>
                                     </div>
                                     <div class="col-6 mt-3 text-end">
                                         <h6 class="title mt-5">Amount [AED]</h6>
                                     </div>
                                 </div>
                                 <div class="separator separator-dashed"></div>
                                     <div class="card-body">
                                         <div class="row">
                                             <div class="col-sm-12 mt-3">
                                                 <table class="table table-hover table-row-dashed fs-6 gy-5 my-0 no-footer">
                                                     <tbody id="cart-items-container">
                                                     <!-- Cart Items will be loaded here via AJAX -->
                                                     </tbody>
                                                 </table>
                                             </div>
                                             <div class="separator separator-dashed"></div>
                                             <div class="col-sm-12 mt-3">
                                                 <table class="table table-hover table-row-dashed fs-6 gy-5 my-0 no-footer">
                                                     <tbody>
                                                    <tr>
                                                        <td class="text-start"><h3>Subtotal</h3></td>
                                                        <td class="text-end fw-bold" id="subtotal">0 AED</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start"><h3>Shipping Fees</h3></td>
                                                        <td class="text-end fw-bold" id="shipping">0 AED</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start"><h3>Service Charges</h3></td>
                                                        <td class="text-end fw-bold" id="service_charge">0 AED</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start"><h3>Total</h3></td>
                                                        <td class="text-end fw-bold" id="total">0 AED</td>
                                                    </tr>

                                                     </tbody>
                                                 </table>
                                             </div>
                                             <div class="col-sm-8 mt-3">
                                                 <label class="form-label">COD Amount</label>
                                                 <input type="hidden"  name="subtotal_input" id="subtotal_input" >
                                                 <input type="hidden"  name="shipping_input" id="shipping_input" >
                                                 <input type="hidden"  name="total_input" id="total_input" >
                                                 <input type="hidden"  name="profit_input" id="profit_input" >
                                                    <input type="hidden"  name="service_charge_input" id="service_charge_input" >
                                                    <input type="hidden"  name="service_charge_input_id" id="service_charge_input_id" >
                                                 <input type="number" class="form-control form-control-solid required-input" name="cod_amount" id="cod_amount" placeholder="COD Amount" min="0" value="0">
                                             </div>
                                             <div class="col-sm-4 mt-3">
                                                 <label class="form-label mt-5"></label>
                                                 <button type="button" class="btn btn-primary d-block" id="calculate_profit" onclick="calculate_profit()">Calculate</button>
                                             </div>
                                             <div class="col-sm-12 mt-3">
                                                 <table class="table table-hover table-row-dashed fs-6 gy-5 my-0 no-footer">
                                                     <tbody>
                                                     <tr>
                                                         <td class="text-start"><h3>Profit</h3></td>
                                                         <td class="text-end fw-bold" id="profit">0 AED</td>

                                                     </tr>

                                                     </tbody>
                                                 </table>
                                             </div>
                                         </div>
                                         <div id="PlaceOrder"></div>
                                     </div>

                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    // Save form data to localStorage when inputs change
$(document).ready(function() {
    // Load saved data if it exists
    if(localStorage.getItem('checkoutFormData')) {
        const savedData = JSON.parse(localStorage.getItem('checkoutFormData'));
        $('#cus_name').val(savedData.cus_name || '');
        $('#phone').val(savedData.phone || '');
        $('#whatsapp').val(savedData.whatsapp || '');
        $('#state').val(savedData.state || '').trigger('change');
        console.log(savedData.areas);
        // For area, we need to wait for state to load first
        if(savedData.state) {
            setTimeout(() => {
                $('#areas').val(savedData.areas).trigger('change');

            }, 500);
            get_area_shipping(savedData.areas)
        }

        $('#instructions').val(savedData.instructions || '');
        $('#address').val(savedData.address || '');
        $('#map_url').val(savedData.map_url || '');
    }

    // Save data on input change
    $('.form-control, .form-select').on('input change', function() {
        const formData = {
            cus_name: $('#cus_name').val(),
            phone: $('#phone').val(),
            whatsapp: $('#whatsapp').val(),
            state: $('#state').val(),
            areas: $('#areas').val(),
            instructions: $('#instructions').val(),
            address: $('#address').val(),
            map_url: $('#map_url').val()
        };
        localStorage.setItem('checkoutFormData', JSON.stringify(formData));
    });
});

// Clear localStorage when order is placed successfully
function clearCheckoutFormData() {
    localStorage.removeItem('checkoutFormData');
}
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.js-example-basic-single1').select2();
    });
    function get_area(selectedValue) {
        console.log(selectedValue)
        $.ajax({
            url: "{{Route('get_areas')}}",
            type: "get",
            data: {
                state_id: selectedValue,
            },
            cache: false,
            success: function (dataResult) {
                $('#areas').html(dataResult.options);

            }
        });
    }
    function get_cart_subtotal() {
        $.ajax({
            url: "{{ route('get_cart_subtotal') }}",
            type: "GET",
            cache: false,
            success: function (dataResult) {
                $('#subtotal').html(dataResult.subtotal + ' AED');
                $('#subtotal_input').val(dataResult.subtotal);
                setTimeout(function () {
                    get_total()
                }, 1000);
            }
        });
    }

    function get_area_shipping(selectedValue) {
        console.log(selectedValue)
        $.ajax({
            url: "{{Route('get_areas_shipping')}}",
            type: "get",
            data: {
                id: selectedValue,
            },
            cache: false,
            success: function (dataResult) {
                $('#shipping').html(dataResult.shipping+' AED');
                $('#shipping_input').val(dataResult.shipping);
                setTimeout(function () {
                    get_total()
                }, 1000);
            }
        });
    }
    function get_total() {
        $('#total').html('');
        $('#total_input').val('');
        var shipping = parseFloat($('#shipping_input').val()) || 0;
        var subtotal = parseFloat($('#subtotal_input').val()) || 0;
        var service_charge = parseFloat($('#service_charge_input').val()) || 0;

      var total = shipping + subtotal + service_charge;
        if(!isNaN(total) && total != ''){
            $('#total').html(total.toFixed(2)+ ' AED');
        }else{
            $('#total').html('0 AED');
        }
        $('#total_input').val(total);
    }
    $(document).ready(function () {
        loadCartData();
        get_cart_subtotal()
        setTimeout(function () {
            get_total()
        }, 1000);

    });

    function loadCartData() {
        $.ajax({
            url: "{{ route('get_cart_data') }}",
            type: "GET",
            success: function (response) {
                $("#cart-items-container").html(response.html);
            }
        });
    }
    $('body').on('click', '.increase-qty, .decrease-qty', function () {
        let inputField = $(this).siblings('.cart-quantity');
        let cartId = $(this).data('id');
        let productType = $(this).data('type');
        let productId = $(this).data('product-id');
        let variationId = $(this).data('variation-id');
        let stock = parseInt($(this).data('stock'));

        let currentQty = parseInt(inputField.val());

        if ($(this).hasClass('increase-qty')) {
            if (currentQty < stock) {
                inputField.val(currentQty + 1);
            } else {
                alert("Stock limit reached!");
                return;
            }
        } else {
            if (currentQty > 1) {
                inputField.val(currentQty - 1);
            } else {
                alert("Quantity cannot be less than 1!");
                return;
            }
        }

        // Fetch updated quantity after button click
        let updatedQty = parseInt(inputField.val());
        console.warn(updatedQty);

        updateCart(cartId, updatedQty, productType, productId, variationId);
    });

    // Handle manual quantity input
    $('body').on('input', '.cart-quantity', function () {
        let inputField = $(this);
        let cartId = inputField.data('id');
        let productId = inputField.siblings('.increase-qty').data('product-id');
        let variationId = inputField.siblings('.increase-qty').data('variation-id');
        let productType = inputField.siblings('.increase-qty').data('type');
        let stock = parseInt(inputField.siblings('.increase-qty').data('stock'));
        let enteredQty = inputField.val();
        if (!/^\d+$/.test(enteredQty) || parseInt(enteredQty) < 1) {
            inputField.val(1);
            enteredQty = 1;
        } else if (parseInt(enteredQty) > stock) {
            inputField.val(stock);
            enteredQty = stock;
            alert("Stock limit reached!");
        }

        updateCart(cartId, enteredQty, productType, productId, variationId);
    });
    function updateCart(cartId, quantity, productType, productId, variationId) {
        $.ajax({
            url: "{{ route('update_cart_ajax') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                cart_id: cartId,
                quantity: quantity,
                product_id: productId,
                product_variation_id: variationId
            },
            success: function (response) {
                if (response.status === 1) {
                    loadCartData();
                    get_cart_subtotal()
                    setTimeout(function () {
                        get_total()
                    }, 1000);
                } else {
                    alert(response.message);
                }
            }
        });
    }
    function calculate_profit (){
        cod_amount = $('#cod_amount').val();

$.ajax({
        url: "{{ route('get_seller_service_charges') }}",
        type: "GET",
        data: { cod_amount: cod_amount },
        dataType: "json",
        success: function(res) {

            console.log(res);

            if (res.status == true) {


                $('#PlaceOrder').empty();
     var shipping = parseFloat($('#shipping_input').val()) || 0;
        var subtotal = parseFloat($('#subtotal_input').val()) || 0;
     var cod = parseFloat($('#cod_amount').val()) || 0;
                var profit = 0;
                  if(res.details.id == null){
                        $('#service_charge').html('0 AED');
                    $('#service_charge_input').val(0);
                    $('#service_charge_input_id').val('');
                    var profit = parseFloat(cod) - shipping - subtotal;
                 }else{
                    $('#service_charge').html(res.details.amount + ' AED');
                 $('#service_charge_input').val(res.details.amount);
                 $('#service_charge_input_id').val(res.details.id);
                 var profit = parseFloat(cod) - shipping - subtotal - parseFloat(res.details.amount);
                  }

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
get_total()
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
    }
    // function placeOrder() {
    //     let formData = {
    //         cus_name: $('#cus_name').val(),
    //         phone: $('#phone').val(),
    //         whatsapp: $('#whatsapp').val(),
    //         state: $('#state').val(),
    //         areas: $('#areas').val(),
    //         instructions: $('#instructions').val(),
    //         address: $('#address').val(),
    //         map_url: $('#map_url').val(),
    //         subtotal: $('#subtotal_input').val(),
    //         shipping: $('#shipping_input').val(),
    //         total: $('#total_input').val(),
    //         cod_amount: $('#cod_amount').val(),
    //         profit: $('#profit_input').val(),
    //         _token: "{{ csrf_token() }}"
    //     };
    //     $.ajax({
    //         url: "{{ route('place_order') }}",
    //         type: "POST",
    //         data: formData,
    //         dataType: "json",
    //         success: function(dataResult) {
    //             console.log(dataResult);
    //             document.getElementById("kt_modal_new_target_submit").innerHTML = "Add";
    //             document.getElementById('kt_modal_new_target_submit').disabled = false;
    //             if (dataResult == 1) {
    //                 toastr.success('Order Placed Successfully.');
    //                 setTimeout(function () {
    //                     window.location.href = '{{route('all_orders')}}';
    //                 }, 1000);

    //             } else if(dataResult == 2){
    //                 toastr.error('Enter a User');
    //             }
    //             else {
    //                 toastr.error('Something Went Wrong.');
    //             }
    //         },
    //         error: function(xhr) {
    //             let errors = xhr.responseJSON.errors;
    //             let errorMessage = "Please fix the following errors:\n";
    //             for (let key in errors) {
    //                 errorMessage += errors[key][0] + "\n";
    //             }
    //             alert(errorMessage);
    //         }
    //     });
    // }
    function placeOrder() {
    let formData = {
        cus_name: $('#cus_name').val(),
        phone: $('#phone').val(),
        whatsapp: $('#whatsapp').val(),
        state: $('#state').val(),
        areas: $('#areas').val(),
        instructions: $('#instructions').val(),
        address: $('#address').val(),
        map_url: $('#map_url').val(),
        subtotal: $('#subtotal_input').val(),
        shipping: $('#shipping_input').val(),
        total: $('#total_input').val(),
        cod_amount: $('#cod_amount').val(),
        profit: $('#profit_input').val(),
        service_charge_input: $('#service_charge_input').val(),
        service_charge_input_id: $('#service_charge_input_id').val(),
        _token: "{{ csrf_token() }}"
    };

    $.ajax({
        url: "{{ route('place_order') }}",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(dataResult) {
            console.log(dataResult);
            document.getElementById("kt_modal_new_target_submit").innerHTML = "Add";
            document.getElementById('kt_modal_new_target_submit').disabled = false;
            if (dataResult == 1) {
                // Clear the saved form data

                toastr.success('Order Placed Successfully.');
                setTimeout(function () {
                    window.location.href = '{{route('all_orders')}}';
                }, 1000);
            } else if(dataResult == 2){
                toastr.error('Enter a User');
            }
            else {
                toastr.error('Something Went Wrong.');
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
}
function clearCustomerDetails() {
    $('#cus_name').val('');
    $('#phone').val('');
    $('#whatsapp').val('');
    $('#state').val('').trigger('change');
    $('#areas').val('').trigger('change');
    $('#instructions').val('');
    $('#address').val('');
    $('#map_url').val('');
    // Clear localStorage
    clearCheckoutFormData();
}
</script>
@endpush
