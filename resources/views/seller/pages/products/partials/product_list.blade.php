<div class="row gy-5 g-xl-8">
    @if($products->isNotEmpty())
        @foreach($products as $product)
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header border-0 p-5">
                        <img src="{{ asset('storage/'.$product->product_image ?? 'product.jpg') }}" class="w-100 " style="height: 250px !important;">
                    </div>
                    <div class="card-body pt-0">
                        <h3 style="font-weight: normal" title="{{$product->product_name}}">
                            {{ \Illuminate\Support\Str::limit($product->product_name, 20, '....') }}
                        </h3>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-danger" style="font-weight: 900 !important; font-size: 14px !important;">
                            @if($product->fifo_price)
                                {{ number_format($product->fifo_price, 2) }} AED
                                @else
                                <span class="text-danger"> 0.00 AED</span>
                            @endif
                        </p>
                    </div>

                            @if($product->quantity <= 10 && !$product->quantity <= 0)
                            <div class="col-12">
                            <p  style="font-weight: 900 !important; font-size: 14px !important;">
                            <span class="text-warning">Product Stock nearly ends Remaining is: {{ $product->quantity }} Qty </span>
                            </p>
                    </div>
                            @elseif($product->quantity <= 0)
                            <div class="col-6">
                            <p class="text-end" style="font-weight: 900 !important; font-size: 14px !important;">
                            <span class="text-danger"> Out of stock </span>
                            </p>
                            </div>
                            @else
                            <div class="col-6">
                            <p class="text-end" style="font-weight: 900 !important; font-size: 14px !important;">
                            {{ $product->quantity }} Qty
                            </p>
                            </div>
                            @endif

                        </div>
                        <!-- FIFO Price Display -->
                        {{-- Removed batch/variation info --}}

                        <div class="row">
                            <div class="col-md-6 text-start">
                                <a href="#" data-id="{{$product->id}}" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target" class="edit btn btn-info mt-3 w-100">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="#" class="btn btn-primary mt-3 w-100">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 16 16 12 12 8"></polyline>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-xl-12">
            <div class="card p-5 text-center">
                <h4>No Products at that time</h4>
            </div>
        </div>
    @endif
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-4') }}
</div>
