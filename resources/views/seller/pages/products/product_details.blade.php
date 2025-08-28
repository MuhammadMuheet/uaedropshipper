@extends('seller.layouts.app')
@section('title',$product->product_name)

@section('content')
<style>
/* Product Page - Clean, responsive design */
:root {
  --bg: hsl(0 0% 100%);
  --fg: hsl(224 71% 4%);
  --muted: hsl(220 14% 96%);
  --muted-fg: hsl(215 16% 45%);
  --primary: hsl(171 100% 41%); /* Updated to a unique teal color */
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
  display: grid;
  gap: 0; /* Removed gap between main image and carousel */
}
.main-media {
  position: relative;
  background: linear-gradient(135deg, hsl(0 0% 98%), hsl(0 0% 94%));
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  aspect-ratio: 1;
}
.main-media img { width: 100%; height: 100%; object-fit: cover; }

.nav-btn {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: hsl(0 0% 100% / 0.9);
  border: 1px solid var(--border);
  border-radius: 50%;
  width: 40px; height: 40px;
  display: grid; place-items: center;
  cursor: pointer;
  transition: background 0.2s;
}
.nav-btn:hover { background: var(--bg); }
.nav-prev { left: 10px; }
.nav-next { right: 10px; }

.carousel {
  display: flex;
  gap: 10px;
  padding: 10px 0;
  overflow-x: auto;
  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
}
.carousel::-webkit-scrollbar { height: 6px; }
.carousel::-webkit-scrollbar-thumb { background: var(--muted); border-radius: 3px; }
.carousel::-webkit-scrollbar-track { background: var(--bg); }
.thumb {
  flex: 0 0 auto;
  width: 80px;
  height: 80px;
  border: 2px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.2s, transform 0.2s;
}
.thumb:hover { transform: scale(1.05); }
.thumb.active { border-color: var(--primary); }
.thumb img { width: 100%; height: 100%; object-fit: cover; }

/* Info */
.info { display: grid; gap: 25px; }
.title { font-size: 28px; font-weight: 700; margin: 0; color: var(--fg); }
.rating { display: flex; align-items: center; gap: 10px; color: var(--muted-fg); font-size: 15px; }
.price { display: flex; align-items: center; gap: 15px; }
.price .now { font-weight: 700; font-size: 24px; color: var(--primary); }
.price .was { color: var(--muted-fg); text-decoration: line-through; font-size: 18px; }
.badge { font-size: 12px; padding: 5px 12px; border-radius: 999px; background: var(--warning); color: hsl(0 0% 10%); font-weight: 600; }

.features { display: grid; gap: 12px; color: var(--muted-fg); font-size: 15px; }

.separator { height: 1px; background: var(--border); margin: 20px 0; }

.controls { display: grid; gap: 15px; }
@media (min-width: 768px) { .controls { grid-template-columns: 1fr 1fr; } }
.label { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
.select, .qty {
  height: 44px; border: 1px solid var(--border); border-radius: var(--radius); background: var(--bg); color: var(--fg);
  width: 100%; padding: 0 12px; font-size: 15px;
}
.qty-wrap { display: flex; align-items: center; gap: 10px; }
.qty-btn { width: 42px; height: 42px; border: 1px solid var(--border); border-radius: var(--radius); background: var(--bg); cursor: pointer; }
.qty-value { width: 55px; text-align: center; font-weight: 600; }

.actions { display: grid; gap: 15px; margin-top: 15px; }
.btn { height: 52px; border-radius: var(--radius); border: 1px solid transparent; cursor: pointer; font-weight: 600; font-size: 16px; padding: 0 20px; }
.btn-primary { background: var(--primary); color: var(--primary-fg); }
.btn-primary:hover { background: var(--primary-hover); }
.btn-primary:disabled { background: var(--muted); cursor: not-allowed; }

.meta { display: grid; gap: 12px; font-size: 15px; color: var(--muted-fg); }
.meta .ok { color: var(--success); font-weight: 600; }

/* Tabs */
.tabs {
  margin-top: 40px;
  padding: 30px 15px;
  background: linear-gradient(135deg, var(--muted), hsl(220 14% 98%));
  border-top: 1px solid var(--border);
}
.tab-list {
  display: flex;
  gap: 25px;
  justify-content: center;
  padding-bottom: 15px;
}
.tab-btn {
  background: transparent;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  color: var(--muted-fg);
  font-weight: 600;
  font-size: 17px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: color 0.3s;
}
.tab-btn[aria-selected="true"] {
  color: var(--primary);
}
.tab-btn[aria-selected="true"]::after {
  content: "";
  position: absolute;
  left: 50%; transform: translateX(-50%);
  bottom: -15px;
  width: 40%;
  height: 3px;
  background: var(--primary);
}
.tab-panel {
  padding: 25px 0;
  display: none;
  max-width: 800px;
  margin-inline: auto;
}
.tab-panel.active { display: block; }

/* Toast */
.toast {
  position: fixed; right: 20px; bottom: 20px; background: var(--fg); color: var(--bg);
  padding: 15px 18px; border-radius: var(--radius); display: none; gap: 12px; align-items: center;
  box-shadow: 0 10px 30px hsl(0 0% 0% / 0.2);
}
.toast.show { display: inline-flex; }
.toast .view { background: var(--bg); color: var(--fg); border: none; border-radius: 6px; padding: 6px 12px; cursor: pointer; }
</style>

<main>
    <div class="container">
        <header>
            <nav class="breadcrumb">
                <a href="{{ route('products') }}">Products</a> / {{ $product->product_name }}
            </nav>
        </header>

        <h1 class="title">{{ $product->product_name }}</h1>

        <div class="product-grid">
            <!-- Gallery Left -->
            <section class="gallery" aria-label="Product media">
                <div class="main-media">
                    <button class="nav-btn nav-prev" aria-label="Previous image">&#10094;</button>
                    <img id="mainImage" src="{{ $images[0]['src'] ?? asset('images/product.jpg') }}" alt="{{ $images[0]['alt'] ?? 'Product image' }}" loading="eager" />
                    <button class="nav-btn nav-next" aria-label="Next image">&#10095;</button>
                </div>

                @if($product->product_type === 'variable' && count($images) > 0)
                    <div class="carousel" id="thumbs" aria-label="Select product variation">
                        @foreach ($images as $index => $image)
                            <button class="thumb {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" data-price="{{ $variations[$index]['price'] ?? $defaultPrice }}" aria-label="Show variation {{ $index + 1 }}">
                                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" loading="lazy" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Info Right -->
            <section class="info" aria-label="Product information">
                <div class="rating" aria-label="Product rating">
                    <span>★★★★☆</span>
                    <span>4.7 (128 reviews)</span>
                    <a href="#reviews" style="color:inherit; text-decoration:underline">See all reviews</a>
                </div>

                <div class="price" id="product-price">
                    <span class="now">{{ $defaultPrice ? number_format($defaultPrice, 2) . ' ADE' : 'Out of Stock' }}</span>
                    @if ($defaultPrice)
                        <span class="was">{{ number_format($defaultPrice * 1.15, 2) }} ADE</span>
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
                    <div><span>• Standard Delivery</span> — <span>3–5 business days</span></div>
                    <div><span>• Express Delivery</span> — <span>1–2 business days (+$9.99)</span></div>
                    <div class="{{ $totalStock > 0 ? 'ok' : '' }}">{{ $totalStock > 0 ? 'In Stock' : 'Out of Stock' }}</div>
                    <div>30-day returns • 2-year warranty</div>
                </div>
            </section>
        </div>
    </div>

    <!-- Full-width Tabs Section -->
    <section class="tabs" id="tabs">
        <div class="tab-list" role="tablist" aria-label="Product information tabs">
            <button role="tab" class="tab-btn" aria-selected="true" aria-controls="panel-details" id="tab-details">Product Details</button>
            <button role="tab" class="tab-btn" aria-selected="false" aria-controls="panel-reviews" id="tab-reviews">Reviews</button>
            <button role="tab" class="tab-btn" aria-selected="false" aria-controls="panel-shipping" id="tab-shipping">Shipping & Returns</button>
        </div>
        <section role="tabpanel" class="tab-panel active" id="panel-details" aria-labelledby="tab-details">
            <p id="details">{!! $product->product_des !!}</p>
        </section>
        <section role="tabpanel" class="tab-panel" id="panel-reviews" aria-labelledby="tab-reviews">
            <div id="reviews">
                <p><strong>Alex</strong> — "Super comfy and great for daily runs." ★★★★★</p>
                <p><strong>Jordan</strong> — "Nice support, true to size." ★★★★☆</p>
            </div>
        </section>
        <section role="tabpanel" class="tab-panel" id="panel-shipping" aria-labelledby="tab-shipping">
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
                <span class="now">${price} ADE</span>
                <span class="was">${(parseFloat(price) * 1.15).toFixed(2)} ADE</span>
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
                    <span class="now">${price} ADE</span>
                    <span class="was">${(parseFloat(price) * 1.15).toFixed(2)} ADE</span>
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
                            <span class="now">${response.price} ADE</span>
                            <span class="was">${(response.price * 1.15).toFixed(2)} ADE</span>
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