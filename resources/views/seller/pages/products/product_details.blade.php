@extends('seller.layouts.app')
@section('title', $product->product_name)

@section('content')
<style>
/* Product Page - Clean, professional, and responsive design */
:root {
    --bg: hsl(0 0% 100%);
    --fg: hsl(224 71% 4%);
    --muted: hsl(220 14% 96%);
    --muted-fg: hsl(215 16% 45%);
    --primary: hsl(171 100% 41%); /* Teal for primary actions */
    --primary-hover: hsl(171 100% 36%);
    --primary-fg: hsl(0 0% 100%);
    --border: hsl(214 32% 91%);
    --success: hsl(142 76% 36%);
    --warning: hsl(47 95% 53%);
    --radius: 8px;
}

* { box-sizing: border-box; }
html { -webkit-text-size-adjust: 100%; }
body {
    margin: 0;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
    background: var(--bg);
    color: var(--fg);
    line-height: 1.6;
}
img { display: block; max-width: 100%; height: auto; }

.container {
    max-width: 1200px;
    margin-inline: auto;
    padding: 20px 15px;
}

header { padding: 10px 0; }
.breadcrumb { font-size: 14px; color: var(--muted-fg); }
.breadcrumb a { color: inherit; text-decoration: none; }
.breadcrumb a:hover { text-decoration: underline; }

.product-grid {
    display: grid;
    gap: 30px;
}
@media (min-width: 768px) {
    .product-grid { grid-template-columns: 1fr 1fr; }
}

/* Gallery */
.gallery {
    display: flex;
    flex-direction: column;
    gap: 0; /* No gap between main image and thumbnails */
}
.main-media {
    position: relative;
    background: linear-gradient(135deg, hsl(0 0% 98%), hsl(0 0% 94%));
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    aspect-ratio: 1;
    margin-bottom: 10px; /* Minimal spacing to keep thumbnails close */
}
.main-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease; /* Smooth zoom on hover */
    z-index: 1; /* Ensure image stays below navigation buttons */
}
.main-media img:hover {
    transform: scale(1.05); /* Subtle zoom effect */
}

.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: hsl(0 0% 100% / 0.9);
    border: 1px solid var(--border);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s, opacity 0.2s;
    z-index: 2; /* Higher z-index to stay above image */
    opacity: 0.8; /* Slightly transparent for sleek look */
}
.nav-btn:hover {
    background: var(--bg);
    transform: translateY(-50%) scale(1.1);
    opacity: 1; /* Fully visible on hover */
}
.nav-prev { left: 10px; }
.nav-next { right: 10px; }

.carousel {
    display: flex;
    gap: 8px; /* Tighter gap between thumbnails */
    padding: 0; /* Remove padding to align with main image */
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}
.carousel::-webkit-scrollbar { height: 6px; }
.carousel::-webkit-scrollbar-thumb { background: var(--muted); border-radius: 3px; }
.carousel::-webkit-scrollbar-track { background: var(--bg); }
.thumb {
    flex: 0 0 auto;
    width: 60px; /* Smaller thumbnails for a sleek look */
    height: 60px;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
}
.thumb:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow on hover */
}
.thumb.active {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2); /* Highlight active thumbnail */
}
.thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Info */
.info {
    display: grid;
    gap: 20px; /* Slightly reduced for compactness */
}
.title {
    font-size: 26px; /* Slightly smaller for elegance */
    font-weight: 600;
    margin: 0;
    color: var(--fg);
}
.rating {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--muted-fg);
    font-size: 14px;
}
.price {
    display: flex;
    align-items: center;
    gap: 12px;
}
.price .now {
    font-weight: 700;
    font-size: 22px; /* Slightly smaller for balance */
    color: var(--primary);
}
.price .was {
    color: var(--muted-fg);
    text-decoration: line-through;
    font-size: 16px;
}
.badge {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    background: var(--warning);
    color: hsl(0 0% 10%);
    font-weight: 600;
}

.features {
    display: grid;
    gap: 10px;
    color: var(--muted-fg);
    font-size: 14px;
}

.separator {
    height: 1px;
    background: var(--border);
    margin: 15px 0;
}

.controls {
    display: grid;
    gap: 15px;
}
@media (min-width: 768px) {
    .controls { grid-template-columns: 1fr 1fr; }
}
.label {
    font-size: 14px; /* Smaller for elegance */
    font-weight: 600;
    margin-bottom: 6px;
}
.select, .qty {
    height: 40px; /* Slightly smaller for compactness */
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--bg);
    color: var(--fg);
    width: 100%;
    padding: 0 10px;
    font-size: 14px;
}
.qty-wrap {
    display: flex;
    align-items: center;
    gap: 0; /* No gap between buttons and value */
}
.qty-btn {
    width: 40px;
    height: 40px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--bg);
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s, transform 0.2s;
}
.qty-btn:hover {
    background: var(--muted);
    transform: scale(1.05);
}
.qty-value {
    width: 50px;
    text-align: center;
    font-weight: 600;
    border: 1px solid var(--border);
    border-left: none; /* Seamless connection with buttons */
    border-right: none;
    height: 40px;
    line-height: 40px; /* Center vertically */
}

.actions {
    display: grid;
    gap: 12px;
    margin-top: 12px;
}
.btn {
    height: 48px; /* Slightly smaller for elegance */
    border-radius: var(--radius);
    border: 1px solid transparent;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    padding: 0 18px;
}
.btn-primary {
    background: var(--primary);
    color: var(--primary-fg);
}
.btn-primary:hover {
    background: var(--primary-hover);
}
.btn-primary:disabled {
    background: var(--muted);
    cursor: not-allowed;
}

.meta {
    display: grid;
    gap: 10px;
    font-size: 14px;
    color: var(--muted-fg);
}
.meta .ok {
    color: var(--success);
    font-weight: 600;
}

/* Tabs */
.tabs {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.tab-list {
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 1.5rem;
}
.tab-btn {
    font-size: 1rem;
    font-weight: 500;
    color: #495057;
    padding: 0.75rem 1.5rem;
    border: none;
    background: none;
    transition: color 0.3s ease, background-color 0.3s ease;
}
.tab-btn:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}
.tab-btn.active {
    color: #007bff;
    background-color: #ffffff;
    border-bottom: 3px solid #007bff;
    border-radius: 4px 4px 0 0;
}
.tab-panel {
    display: none;
    padding: 1.5rem;
    background-color: #ffffff;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    max-height: 450px; /* Fixed height for tab content */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #007bff #f1f1f1;
}
.tab-panel::-webkit-scrollbar {
    width: 8px;
}
.tab-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.tab-panel::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 4px;
}
.tab-panel::-webkit-scrollbar-thumb:hover {
    background: #0056b3;
}
.tab-panel.active {
    display: block;
}
.tab-panel p, .tab-panel ul {
    font-size: 0.95rem;
    color: #343a40;
    line-height: 1.6;
}
.tab-panel ul {
    padding-left: 1.5rem;
}
.tab-panel ul li {
    margin-bottom: 0.5rem;
}
.tab-panel strong {
    color: #212529;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .container {
        padding: 15px 10px;
    }
    .title {
        font-size: 22px;
    }
    .price .now {
        font-size: 20px;
    }
    .price .was {
        font-size: 14px;
    }
    .main-media {
        aspect-ratio: 4/3; /* Adjusted for mobile */
    }
    .thumb {
        width: 50px;
        height: 50px;
    }
    .tab-btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .tabs {
        padding: 1rem;
    }
    .tab-panel {
        padding: 1rem;
        max-height: 300px;
    }
    .qty-wrap {
        gap: 0;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
    }
    .qty-value {
        width: 45px;
        height: 36px;
        line-height: 36px;
    }
}

/* Toast */
.toast {
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: var(--fg);
    color: var(--bg);
    padding: 12px 16px;
    border-radius: var(--radius);
    display: none;
    gap: 10px;
    align-items: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}
.toast.show {
    display: inline-flex;
}
.toast .view {
    background: var(--bg);
    color: var(--fg);
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

<main>
    <div class="container">
        <header>
            <nav class="breadcrumb">
                <a href="{{ route('products') }}">Products</a> / {{ $product->product_name }}
            </nav>
        </header>
        <div class="product-grid">
            <!-- Gallery Left -->
            <section class="gallery" aria-label="Product media">
                <div class="main-media">
                    <button class="nav-btn nav-prev" aria-label="Previous image">&#10094;</button>
                    <img id="mainImage" src="{{ $images[0]['src'] ?? asset('images/product.jpg') }}" alt="{{ $images[0]['alt'] ?? 'Product image' }}" loading="eager" class="img-fluid" />
                    <button class="nav-btn nav-next" aria-label="Next image">&#10095;</button>
                </div>

                @if($product->product_type === 'variable' && count($images) > 0)
                    <div class="carousel" id="thumbs" aria-label="Select product variation">
                        @foreach ($images as $index => $image)
                            <button class="thumb {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" data-price="{{ $variations[$index]['price'] ?? $defaultPrice }}" aria-label="Show variation {{ $index + 1 }}">
                                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" loading="lazy" class="img-fluid" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Info Right -->
            <section class="info" aria-label="Product information">
                <h1 class="title">{{ $product->product_name }}</h1>
                <div class="rating" aria-label="Product rating">
                    <span>★★★★☆</span>
                    <span>4.7 (128 reviews)</span>
                    <a href="#reviews" style="color:inherit; text-decoration:underline">See all reviews</a>
                </div>

                <div class="price" id="product-price">
                    <span class="now">{{ $defaultPrice ? number_format($defaultPrice, 2) . ' AED' : 'Out of Stock' }}</span>
                    @if ($defaultPrice)
                        <span class="was">{{ number_format($defaultPrice * 1.15, 2) }} AED</span>
                        <span class="badge">13% off</span>
                    @endif
                </div>

                <div class="features" aria-label="Key features">
                    {!! $product->product_short_des !!}
                </div>

                <div class="separator"></div>

                <!-- Controls -->
                <div class="controls">
                    @if ($product->product_type === 'variable')
                        <div>
                            <div class="label">Variation</div>
                            <select id="variation" class="select" aria-label="Select variation">
                                @foreach ($variations as $index => $variation)
                                    <option value="{{ $variation['id'] }}" data-image="{{ $variation['image'] }}" data-price="{{ $variation['price'] ? number_format($variation['price'], 2) : null }}" data-batch-id="{{ $variation['batch_id'] }}" {{ $index === 0 ? 'selected' : '' }}>
                                        {{ $variation['name'] }}: {{ $variation['value'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <div class="label">Quantity</div>
                        <div class="qty-wrap">
                            <button class="qty-btn" id="decrease" aria-label="Decrease quantity">-</button>
                            <div class="qty-value" id="qty">1</div>
                            <button class="qty-btn" id="increase" aria-label="Increase quantity">+</button>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <button id="addToCart" class="btn btn-primary" data-product-id="{{ $product->id }}" data-batch-id="{{ $defaultBatchId }}" {{ !$totalStock ? 'disabled' : '' }}>Add to Cart</button>
                </div>

                <div class="separator"></div>

                <!-- Delivery / stock -->
                <div class="meta">
                    <div><strong>Delivery Options</strong></div>
                    <div><span>• Fast Delivery</span> — <span> 1 – 3 business days (+$9.99)</span></div>
                    <div class="{{ $totalStock > 0 ? 'ok' : '' }}">{{ $totalStock > 0 ? 'In Stock' : 'Out of Stock' }}</div>
                    <div>30-day returns • 2-year warranty</div>
                </div>
            </section>
        </div>
    </div>

    <!-- Full-width Tabs Section -->
    <section class="container tabs my-5" id="tabs">
        <div class="tab-list nav nav-tabs" role="tablist" aria-label="Product information tabs">
            <button role="tab" class="nav-link tab-btn active" aria-selected="true" aria-controls="panel-details" id="tab-details" data-bs-toggle="tab">Product Details</button>
            <button role="tab" class="nav-link tab-btn" aria-selected="false" aria-controls="panel-reviews" id="tab-reviews" data-bs-toggle="tab">Reviews</button>
            <button role="tab" class="nav-link tab-btn" aria-selected="false" aria-controls="panel-shipping" id="tab-shipping" data-bs-toggle="tab">Shipping & Returns</button>
        </div>
        <section role="tabpanel" class="tab-panel tab-pane active" id="panel-details" aria-labelledby="tab-details">
            <p id="details">{!! $product->product_des !!}</p>
        </section>
        <section role="tabpanel" class="tab-panel tab-pane" id="panel-reviews" aria-labelledby="tab-reviews">
            <div id="reviews">
                <p><strong>Alex</strong> — "Super comfy and great for daily runs." ★★★★★</p>
                <p><strong>Jordan</strong> — "Nice support, true to size." ★★★★☆</p>
            </div>
        </section>
        <section role="tabpanel" class="tab-panel tab-pane" id="panel-shipping" aria-labelledby="tab-shipping">
            <ul>
                <li>Standard delivery: 3–5 business days</li>
                <li>Express delivery: 1–2 business days (+$9.99)</li>
                <li>30-day free returns</li>
            </ul>
        </section>
    </section>
</main>

<div class="toast" id="toast" role="status" aria-live="polite">
    <span id="toastText">Added to cart.</span>
    <button class="view" id="viewCart">View Cart</button>
</div>

@push('js')
<script>
(function(){
    // Gallery
    const images = @json($images);
    let current = 0;
    const mainImage = document.getElementById('mainImage');
    const thumbs = Array.from(document.querySelectorAll('.thumb'));
    const prevBtn = document.querySelector('.nav-prev');
    const nextBtn = document.querySelector('.nav-next');
    const priceEl = document.getElementById('product-price');
    const addToCartBtn = document.getElementById('addToCart');

    function render(index, preventScroll = true) {
        current = (index + images.length) % images.length;
        const img = images[current];
        mainImage.src = img.src;
        mainImage.alt = img.alt;
        thumbs.forEach((t, i) => t.classList.toggle('active', i === current));
        if (preventScroll) {
            thumbs[current].scrollIntoView({ behavior: 'auto', inline: 'nearest', block: 'nearest' });
        }

        // Update price and batch ID based on variation
        const thumb = thumbs[current];
        const price = thumb ? thumb.dataset.price : ($defaultPrice ? number_format($defaultPrice, 2) : null);
        if (price) {
            priceEl.innerHTML = `
                <span class="now">${price} AED</span>
                <span class="was">${(parseFloat(price) * 1.15).toFixed(2)} AED</span>
                <span class="badge">13% off</span>
            `;
            addToCartBtn.dataset.batchId = thumb ? $variations[current]['batch_id'] : $defaultBatchId;
            addToCartBtn.disabled = false;
        } else {
            priceEl.innerHTML = '<span class="now">Out of Stock</span>';
            addToCartBtn.disabled = true;
        }
    }

    prevBtn.addEventListener('click', () => render(current - 1));
    nextBtn.addEventListener('click', () => render(current + 1));
    thumbs.forEach(t => t.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default scrolling behavior
        render(parseInt(t.dataset.index || '0', 10));
    }));

    // Quantity Controls
    const qtyEl = document.getElementById('qty');
    const decrease = document.getElementById('decrease');
    const increase = document.getElementById('increase');
    let qty = 1;
    function updateQty() {
        qtyEl.textContent = String(qty);
    }
    decrease.addEventListener('click', () => { qty = Math.max(1, qty - 1); updateQty(); });
    increase.addEventListener('click', () => { qty = qty + 1; updateQty(); });

    // Tabs
    const tabButtons = Array.from(document.querySelectorAll('.tab-btn'));
    const panels = Array.from(document.querySelectorAll('.tab-panel'));
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.setAttribute('aria-selected', 'false'));
            panels.forEach(p => p.classList.remove('active'));
            btn.setAttribute('aria-selected', 'true');
            const panel = document.getElementById(btn.getAttribute('aria-controls'));
            panel.classList.add('active');
        });
    });

    // Variation Selection and Price Update
    const variationSelect = document.getElementById('variation');
    if (variationSelect) {
        variationSelect.addEventListener('change', function(e) {
            e.preventDefault(); // Prevent scrolling
            const selectedOption = this.options[this.selectedIndex];
            const variationId = this.value;
            const image = selectedOption.dataset.image;
            const price = selectedOption.dataset.price;
            const batchId = selectedOption.dataset.batchId;

            // Update main image
            if (image) {
                mainImage.src = image;
                mainImage.alt = `Product variation image`;
            }

            // Update price
            priceEl.innerHTML = '';
            if (price) {
                priceEl.innerHTML = `
                    <span class="now">${price} AED</span>
                    <span class="was">${(parseFloat(price) * 1.15).toFixed(2)} AED</span>
                    <span class="badge">13% off</span>
                `;
                addToCartBtn.dataset.batchId = batchId;
                addToCartBtn.disabled = false;
            } else {
                priceEl.innerHTML = '<span class="now">Out of Stock</span>';
                addToCartBtn.disabled = true;
            }

            // Fetch variation price dynamically
            $.ajax({
                url: "{{ route('get_seller_product_variation_price') }}",
                type: "GET",
                data: { variation_id: variationId },
                success: function(response) {
                    if (response.price) {
                        priceEl.innerHTML = `
                            <span class="now">${response.price} AED</span>
                            <span class="was">${(response.price * 1.15).toFixed(2)} AED</span>
                            <span class="badge">13% off</span>
                        `;
                        addToCartBtn.dataset.batchId = response.batch_id;
                        addToCartBtn.disabled = false;
                    } else {
                        priceEl.innerHTML = '<span class="now">Out of Stock</span>';
                        addToCartBtn.disabled = true;
                    }
                },
                error: function() {
                    priceEl.innerHTML = '<span class="now">Error fetching price</span>';
                    addToCartBtn.disabled = true;
                }
            });
        });
    }

    // Add to Cart
    const toast = document.getElementById('toast');
    const toastText = document.getElementById('toastText');
    const viewCart = document.getElementById('viewCart');
    let hideTimer = null;

    function showToast(text) {
        toastText.textContent = text;
        toast.classList.add('show');
        if (hideTimer) clearTimeout(hideTimer);
        hideTimer = setTimeout(() => toast.classList.remove('show'), 4000);
    }

    addToCartBtn.addEventListener('click', () => {
        const productId = addToCartBtn.dataset.productId;
        const batchId = addToCartBtn.dataset.batchId;
        const variationId = variationSelect ? variationSelect.value : null;

        if (!batchId) {
            showToast('Please select a variation or ensure the product is in stock');
            return;
        }

        $.ajax({
            url: "{{ route('add_to_cart') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId,
                product_variation_id: variationId,
                quantity: qty,
                product_batch_id: batchId
            },
            success: function(response) {
                if (response == 1) {
                    showToast(`${qty === 1 ? '1 item' : `${qty} items`} added to your cart`);
                } else if (response == 2) {
                    showToast('Product ID is required.');
                } else if (response == 3) {
                    showToast('Invalid quantity.');
                } else if (response == 4) {
                    showToast('Invalid variation ID.');
                } else if (response == 5) {
                    showToast('Product not found.');
                } else if (response == 6) {
                    showToast('Not enough stock.');
                } else if (response == 7) {
                    showToast('Variation not found.');
                } else if (response == 8) {
                    showToast('Not enough stock for variation.');
                } else if (response == 9) {
                    showToast('Product is out of stock.');
                } else {
                    showToast('Something went wrong.');
                }
            },
            error: function() {
                showToast('Server error. Please try again later.');
            }
        });
    });

    viewCart.addEventListener('click', () => {
        window.location.href = "{{ route('all_cart') }}";
    });

    // Initial render
    render(0);
})();
</script>
@endpush
@endsection
