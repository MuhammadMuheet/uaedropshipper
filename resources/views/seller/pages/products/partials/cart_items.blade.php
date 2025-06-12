@if($cartItems->isNotEmpty())
@foreach($cartItems as $cart)
    <tr>
        <td class="text-start">
            @if($cart->productVariation)
            <img src="{{ asset('storage/'.$cart->productVariation->variation_image) }}" class="mb-2" style="width: 10% !important;">
            @else
                <img src="{{ asset('storage/'.$cart->product->product_image) }}" class="mb-2" style="width: 10% !important;">
            @endif
            <strong>{{ $cart->product->product_name }}</strong> <br>
            @if($cart->productVariation)
                <span>{{ $cart->productVariation->variation_name }}:
            {{ $cart->productVariation->variation_value }}</span> <br>
            @endif

            <div class="quantity-container d-flex " style="width: 40% !important;">
                <button class="btn btn-sm btn-primary decrease-qty me-2"
                        data-id="{{ $cart->id }}"
                        data-type="{{ $cart->product->product_type }}"
                        data-product-id="{{ $cart->product_id }}"
                        data-variation-id="{{ $cart->productVariation->id ?? '' }}"   
                        data-stock-id="{{ $cart->productStockBatch->id ?? '' }}"
                        data-stock="{{ $cart->productStockBatch ? $cart->productStockBatch->quantity : '' }}">-</button>

                <input type="text" class="form-control text-center cart-quantity me-2"
                       value="{{ $cart->quantity }}"
                       data-id="{{ $cart->id }}">

                <button class="btn btn-sm btn-primary increase-qty me-2"
                        data-id="{{ $cart->id }}"
                        data-type="{{ $cart->product->product_type }}"
                        data-product-id="{{ $cart->product_id }}"
                        data-variation-id="{{ $cart->productVariation->id ?? '' }}"
                         data-stock-id="{{ $cart->productStockBatch->id ?? '' }}"
                        data-stock="{{ $cart->productStockBatch ? $cart->productStockBatch->quantity : '' }}">+</button>
            </div>
        </td>
        <td class="text-end">
        <span class="cart-price" data-id="{{ $cart->id }}" data-unit-price="{{ $cart->productStockBatch ? $cart->productStockBatch->regular_price : '0' }}">
            {{ number_format($cart->productStockBatch->regular_price * $cart->quantity, 2) }} ADE
        </span>
        </td>
    </tr>
@endforeach
@else
    <tr>
        <td class="text-center">
            <h4>Add Product to cart <a href="{{route('products')}}">Add Products</a></h4>
        </td>
    </tr>
@endif
