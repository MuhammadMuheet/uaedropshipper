@extends('seller.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="dashboard-container container-xxl">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8 dashboard-mobile-responsiveness">
            <h1 class="mb-1" style="color: var(--text-primary); font-weight: 700; font-size: 2rem;">Arrbaab Dashboard</h1>
            <p style="color: var(--text-secondary); margin: 0;">Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div class="col-md-4 text-end">
            <form action="{{ route('seller_dashboard') }}" method="GET">
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->toDateString() }}" required>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->toDateString() }}" required>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <p class="stat-title">Total Revenue</p>
                            <p class="stat-value">{{ number_format($totalRevenue ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-end {{ ($revenueTrend ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="fas fa-trending-{{ ($revenueTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span class="ms-1 fw-medium" style="font-size: 0.875rem;">
                            {{ ($revenueTrend ?? 0) >= 0 ? '+' : '' }}{{ number_format($revenueTrend ?? 0, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <p class="stat-title">Total Orders</p>
                            <p class="stat-value">{{ $totalOrders ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="text-end {{ ($ordersTrend ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="fas fa-trending-{{ ($ordersTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span class="ms-1 fw-medium" style="font-size: 0.875rem;">
                            {{ ($ordersTrend ?? 0) >= 0 ? '+' : '' }}{{ number_format($ordersTrend ?? 0, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <p class="stat-title">Active Customers</p>
                            <p class="stat-value">{{ $activeCustomers ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="text-end {{ ($customersTrend ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="fas fa-trending-{{ ($customersTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span class="ms-1 fw-medium" style="font-size: 0.875rem;">
                            {{ ($customersTrend ?? 0) >= 0 ? '+' : '' }}{{ number_format($customersTrend ?? 0, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <p class="stat-title">Delivery Ratio</p>
                            <p class="stat-value">{{ number_format($deliveryRatio ?? 0, 2) }}%</p>
                        </div>
                    </div>
                    <div class="text-end {{ ($deliveryTrend ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                        <i class="fas fa-trending-{{ ($deliveryTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span class="ms-1 fw-medium" style="font-size: 0.875rem;">
                            {{ ($deliveryTrend ?? 0) >= 0 ? '+' : '' }}{{ number_format($deliveryTrend ?? 0, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="mb-4">
        <div class="chart-card">
            <h3 class="mb-4" style="color: var(--text-primary); font-weight: 600; font-size: 1.25rem;">
                <i class="fas fa-chart-bar text-primary-custom me-2"></i>
                Sales & Earnings Analytics
            </h3>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="analytics-box sales-analytics">
                        <div class="text-center">
                            <div class="analytics-icon sales-icon">
                                <i class="fas fa-chart-line" style="color: white; font-size:18px;"></i>
                            </div>
                            <h4 style="color: var(--text-primary); font-weight: 600;">Sales Growth</h4>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">Platform performance</p>
                            <h2 class="text-primary-custom fw-bold">
                                {{ ($salesGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($salesGrowth ?? 0, 1) }}%
                            </h2>
                            <small style="color: var(--text-secondary);">vs previous period</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="analytics-box earnings-analytics">
                        <div class="text-center">
                            <div class="analytics-icon earnings-icon">
                                <i class="fas fa-coins" style="color: white; font-size:18px;"></i>
                            </div>
                            <h4 style="color: var(--text-primary); font-weight: 600;">Total Earnings</h4>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">Revenue for selected period</p>
                            <h2 class="text-success fw-bold">{{ number_format($totalEarnings ?? 0, 2) }}</h2>
                            <small style="color: var(--text-secondary);">
                                {{ ($earningsGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($earningsGrowth ?? 0, 1) }}% increase
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="row g-4 mb-4">
        @if(!$isSubSeller)
        <div class="col-lg-6 col-md-6">
            <div class="management-card">
                <div class="d-flex align-items-end pb-4">
                    <h5 class="mb-3 card-title text-primary fw-bold col-9">
                        <i class="fas fa-credit-card text-primary-custom me-2"></i>Transactions
                    </h5>
                        <div class="text-end col-3">
                            <a href="/seller/all-payments">
                            <button type="button" class="btn btn-primary btn-sm" >
                                View All
                            </button>
                        </a>
                        </div>
                </div>

                <div class="management-item bg-success-light mb-3">
                    <span style="color: var(--text-primary); font-weight: 500; font-size: 0.875rem;">Total Transactions</span>
                    <span class="text-success fw-bold">{{ number_format($totalAmountIn ?? 0, 2) }} AED</span>
                </div>
                <div class="management-item bg-primary-light mb-3">
                    <span style="color: var(--text-primary); font-weight: 500; font-size: 0.875rem;">Total
                       Amount In Wallet</span>
                    <span class="text-primary-custom fw-bold">{{ number_format($totalWallet ?? 0, 2) }} AED</span>
                </div>
                <div class="management-item bg-warning-light">
                    <span style="color: var(--text-primary); font-weight: 500; font-size: 0.875rem;">Total PayOut Send</span>
                    <span class="text-warning fw-bold">{{ number_format($totalAmountOut ?? 0, 2) }} AED</span>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="management-card card shadow-sm">
                <div class="d-flex align-items-end pb-4">
                    <h5 class="mb-3 card-title text-primary fw-bold col-9">
                        <i class="fas fa-star text-primary me-2"></i> Top Selling Areas
                    </h5>
                    @if($topAreas->count() > 0)
                    <div class="text-end col-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#topAreasModal">
                            View All
                        </button>
                    </div>
                    @endif
                </div>
                @forelse($topAreas as $index => $area)
                    <div class="management-item d-flex justify-content-between align-items-center p-3 mb-3 rounded @if($index == 0) bg-warning-light @elseif($index == 1) bg-success-light @else bg-purple-light @endif">
                        <span class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                            {{ ucfirst($area->state ? $area->state->state : 'N/A') }} - {{ ucfirst($area->area) }} - Orders = {{ $area->order_count }}
                        </span>
                        <span class="badge @if($index == 0) bg-warning @elseif($index == 1) bg-success @else bg-purple @endif fw-bold" style="color: black">
                            {{ number_format(($area->order_count / ($totalOrders ?: 1)) * 100, 1) }}%
                        </span>
                    </div>
                @empty
                    <div class="management-item alert alert-info text-center mb-0">
                        No orders found for this period.
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Modal for Top 10 Selling Areas -->
        <div class="modal fade" id="topAreasModal" tabindex="-1" aria-labelledby="topAreasModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="topAreasModalLabel">Top 10 Selling Areas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>State</th>
                                        <th>Area</th>
                                        <th>Orders</th>
                                        <th>Delivered Orders</th>
                                        <th>Cancelled Orders</th>
                                        <th>Percentage by Areas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topTenAreas as $index => $area)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($area->state ? $area->state->state : 'N/A') }}</td>
                                            <td>{{ ucfirst($area->area) }}</td>
                                            <td>{{ $area->order_count }}</td>
                                            <td>{{ $area->delivered_count }} ({{ number_format(($area->delivered_count / ($area->order_count ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ $area->cancelled_count }} ({{ number_format(($area->cancelled_count / ($area->order_count ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ number_format(($area->order_count / ($totalOrders ?: 1)) * 100, 1) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No orders found for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-4">
            <div class="management-card card shadow-sm">
                <div class="d-flex align-items-end pb-4">
                    <h5 class="mb-3 card-title text-primary fw-bold col-9">
                        <i class="fas fa-star text-primary me-2"></i> Top Selling States
                    </h5>
                    @if($topStates->count() > 0)
                    <div class="text-end col-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#topStatesModal">
                            View All
                        </button>
                    </div>
                    @endif
                </div>
                @forelse($topStates as $index => $state)
                    <div class="management-item d-flex justify-content-between align-items-center p-3 mb-3 rounded @if($index == 0) bg-warning-light @elseif($index == 1) bg-success-light @else bg-purple-light @endif">
                        <span class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                            {{ ucfirst($state->state) }} - Orders = {{ $state->order_count }}
                        </span>
                        <span class="badge @if($index == 0) bg-warning @elseif($index == 1) bg-success @else bg-purple @endif fw-bold" style="color: black">
                            {{ number_format(($state->order_count / ($totalOrders ?: 1)) * 100, 1) }}%
                        </span>
                    </div>
                @empty
                    <div class="management-item alert alert-info text-center mb-0">
                        No orders found for this period.
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Modal for Top 10 Selling States -->
        <div class="modal fade" id="topStatesModal" tabindex="-1" aria-labelledby="topStatesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="topStatesModalLabel">Top 10 Selling States</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>State</th>
                                        <th>Orders</th>
                                        <th>Delivered Orders</th>
                                        <th>Cancelled Orders</th>
                                        <th>Percentage by States</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topTenStates as $index => $state)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($state->state) }}</td>
                                            <td>{{ $state->order_count }}</td>
                                            <td>{{ $state->delivered_count }} ({{ number_format(($state->delivered_count / ($state->order_count ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ $state->cancelled_count }} ({{ number_format(($state->cancelled_count / ($state->order_count ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ number_format(($state->order_count / ($totalOrders ?: 1)) * 100, 1) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No orders found for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-4">
            <div class="management-card card shadow-sm">
                <div class="d-flex align-items-end pb-4">
                    <h5 class="mb-3 card-title text-primary fw-bold col-9">
                        <i class="fas fa-box text-primary me-2"></i> Top Ordered Products (Delivered)
                    </h5>
                    @if($topProducts->count() > 0)
                    <div class="text-end col-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#topProductsModal">
                            View All
                        </button>
                    </div>
                    @endif
                </div>
                @forelse($topProducts as $index => $product)
                    <div class="management-item d-flex justify-content-between align-items-center p-3 mb-3 rounded @if($index == 0) bg-warning-light @elseif($index == 1) bg-success-light @else bg-purple-light @endif">
                        <span class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                            {{ ucfirst($product->product_name) }}
                            @if($product->variation_name)
                                ({{ $product->variation_name }}: {{ $product->variation_value }})
                            @endif
                            - Qty: {{ $product->delivered_quantity }}
                        </span>
                        <span class="badge @if($index == 0) bg-warning @elseif($index == 1) bg-success @else bg-purple @endif fw-bold" style="color: black">
                            {{ number_format(($product->delivered_quantity / ($totalDeliveredQuantity ?: 1)) * 100, 1) }}%
                        </span>
                    </div>
                @empty
                    <div class="management-item alert alert-info text-center mb-0">
                        No products delivered in this period.
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Modal for Top 10 Ordered Products -->
        <div class="modal fade" id="topProductsModal" tabindex="-1" aria-labelledby="topProductsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="topProductsModalLabel">Top 10 Delivered Products</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Variation</th>
                                        <th>Price</th>
                                        <th>Total Quantity</th>
                                        <th>Delivered Quantity</th>
                                        <th>Cancelled Quantity</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topTenProducts as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ ucfirst($product->product_name) }}</td>
                                            <td>
                                                @if ($product->variation_name)
                                                    {{ $product->variation_name }}: {{ $product->variation_value }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ number_format($product->regular_price, 2) }} AED</td>
                                            <td>{{ $product->total_quantity }}</td>
                                            <td>{{ $product->delivered_quantity }} ({{ number_format(($product->delivered_quantity / ($product->total_quantity ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ $product->cancelled_quantity }} ({{ number_format(($product->cancelled_quantity / ($product->total_quantity ?: 1)) * 100, 1) }}%)</td>
                                            <td>{{ number_format(($product->delivered_quantity / ($totalDeliveredQuantity ?: 1)) * 100, 1) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No products delivered in this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Orders -->
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="orders-table management-card card shadow-sm">
                <div class="p-3" style="background: var(--card-bg); border-bottom: 1px solid var(--border-color);">
                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                        <i class="fas fa-shopping-cart text-primary-custom me-2"></i>
                        Recent Orders
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="background: var(--primary-light); padding: 1rem;">Order ID</th>
                                <th style="background: var(--primary-light); padding: 1rem;">Customer Name</th>
                                <th style="background: var(--primary-light); padding: 1rem;">Status</th>
                                <th style="background: var(--primary-light); padding: 1rem;">Order Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td class="fw-semibold" style="padding: 1rem;">
                                        {{ $order->sub_seller ? ($order->sub_seller->unique_id ?? 'N/A') . '-' . $order->id : '#ORD-' . $order->id }}
                                    </td>
                                    <td style="padding: 1rem;">{{ ucfirst($order->customer_name) ?? 'N/A' }}</td>
                                    <td style="padding: 1rem;">
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '_', $order->status)) }}">{{ $order->status }}</span>
                                    </td>
                                    <td class="fw-bold" style="padding: 1rem;">AED {{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.stat-card, .management-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Theme toggle functionality
            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            }

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
            }

            // Add click animation to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    this.style.backgroundColor = 'var(--primary-light)';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 200);
                });
            });
        });
    </script>
</div>
@endsection
@push('js')
@endpush