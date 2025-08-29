<style>
    /* Custom CSS for card enhancements */
    .custom-card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Subtle shadow */
        border: none; /* Remove default border for cleaner look */
        border-radius: 10px; /* Rounded corners */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover effect */
    }

    .custom-card:hover {
        transform: translateY(-5px); /* Slight lift on hover */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); /* Stronger shadow on hover */
    }

    .custom-card-img {
        padding: 0 !important; /* Zero padding for image */
        border-top-left-radius: 10px; /* Match card's rounded corners */
        border-top-right-radius: 10px;
        overflow: hidden; /* Ensure image respects rounded corners */
    }

    .custom-card-img img {
        width: 100%;
        height: 250px;
        object-fit: cover; /* Ensure image scales nicely */
        display: block;
    }

    .custom-card-body {
        padding: 1.5rem; /* Consistent padding */
    }

    .custom-card-body h3 {
        font-weight: 500; /* Medium weight for modern look */
        font-size: 1.25rem; /* Slightly larger for hierarchy */
        margin-top: 6px; /* 5-7px top margin for title */
        margin-bottom: 1rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .custom-card-body p {
        font-size: 0.875rem; /* Smaller, cleaner font size */
        font-weight: 700; /* Bold for emphasis */
        margin-bottom: 0.5rem;
    }

    .custom-card-body .text-danger {
        color: #ff4d4f !important; /* Vibrant red for price */
    }

    .custom-card-body .text-warning {
        color: #ffca2c !important; /* Bright yellow for warning */
    }

    .custom-card-body .text-success {
        color: #28a745 !important; /* Green for in-stock */
    }

    .custom-btn {
        border-radius: 5px; /* Rounded buttons */
        font-weight: 600; /* Bold button text */
        font-size: 0.8rem; /* Smaller text size for buttons */
        transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
    }

    .custom-btn:hover {
        transform: scale(1.05); /* Slight scale on hover */
    }

    .custom-btn-info {
        background-color: #3d3d3d; /* Black background for Add to Cart */
        border-color: #000000;
        color: #ffffff; /* White text for contrast */
    }

    .custom-btn-primary {
        background-color: #007bff; /* Bootstrap primary color for View Details */
        border-color: #007bff;
    }

    .custom-btn svg {
        vertical-align: middle; /* Align icons with text */
        margin-right: 5px; /* Space between icon and text */
    }

    /* No-products card styling */
    .no-products-card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .no-products-card h4 {
        font-weight: 500;
        font-size: 1.5rem;
    }
</style>

<div class="row gy-5 g-xl-8 py-10">
    @if($products->isNotEmpty())
        @foreach($products as $product)
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-header border-0 p-0 custom-card-img">
                        <img src="{{ asset('storage/'.$product->product_image ?? 'product.jpg') }}"class="img-fluid w-100" style="height: 290px !important; object-fit: cover;">
                    </div>
                    <div class="card-body pt-0 custom-card-body">
                        <h3 title="{{$product->product_name}}" class="fs-2 pt-2">
                            {{ \Illuminate\Support\Str::limit($product->product_name, 20, '....') }}
                        </h3>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-danger fs-3">
                                    @if($product->fifo_price)
                                        {{ number_format($product->fifo_price, 2) }} ADE
                                    @else
                                        <span class="text-danger"> 0.00 ADE</span>
                                    @endif
                                </p>
                            </div>

                            @if($product->quantity <= 10 && !$product->quantity <= 0)
                                <div class="col-12">
                                    <p>
                                        <span class="text-warning">Product Stock nearly ends Remaining is: {{ $product->quantity }} Qty </span>
                                    </p>
                                </div>
                            @elseif($product->quantity <= 0)
                                <div class="col-6">
                                    <p class="text-end">
                                        <span class="text-danger"> Out of stock </span>
                                    </p>
                                </div>
                            @else
                                <div class="col-6">
                                    <p class="text-end">
                                        <span class="text-success"> In stock </span>
                                    </p>
                                </div>
                            @endif
                        </div>
                        <!-- FIFO Price Display -->
                        {{-- Removed batch/variation info --}}

                        <div class="row">
                            <div class="col-md-4 text-start">
                                <a href="#" data-id="{{$product->id}}" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target" class="edit btn mt-3 w-100 custom-btn custom-btn-info">
                                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="col-md-8 text-end">
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary mt-3 w-100 custom-btn custom-btn-primary fs-6">
                                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 16 16 12 12 8"></polyline>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-xl-12">
            <div class="card p-5 text-center no-products-card">
                <h4>No Products at that time</h4>
            </div>
        </div>
    @endif
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-4') }}
</div>
