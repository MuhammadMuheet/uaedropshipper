@extends('public.layouts.app')
@section('title', @$order->subSeller->unique_id . '-' . $order->id)
@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'sub_admin') {
            abort(403, 'Unauthorized action.');
        }
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar py-5" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="text-dark fw-bold fs-3">Order Return</h1>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card card-flush shadow-sm">
                    <div class="card-body py-5 px-10">
                        <div class="row mb-5">
                            <div class="col-md-3 mb-3">
                                <strong>REF Id:</strong>
                                <p>{{ @$order->subSeller->unique_id }}-{{ $order->id }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Customer Name:</strong>
                                <p>{{ $order->customer_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Company Name:</strong>
                                <p>{{ $order->logisticCompany->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Driver Name:</strong>
                                <p>{{ $order->driver->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong>
                                <p>{{ $order->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>WhatsApp:</strong>
                                <p>{{ $order->whatsapp ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>State:</strong>
                                <p>{{ $order->state->state ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Area:</strong>
                                <p>{{ $order->area->area ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Address:</strong>
                                <p>{{ $order->address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Instructions:</strong>
                                <p>{{ $order->instructions ?? 'None' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Map URL:</strong>
                                <p><a href="{{ $order->map_url ?? 'N/A' }}"
                                        target="_blank">{{ $order->map_url ?? 'N/A' }}</a></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Subtotal:</strong>
                                <p>{{ $order->subtotal ?? 'N/A' }} ADE</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Shipping Fee:</strong>
                                <p>{{ $order->shipping_fee ?? 'N/A' }} ADE</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Total:</strong>
                                <p>{{ $order->total ?? 'N/A' }} ADE</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>COD Amount:</strong>
                                <p>{{ $order->cod_amount ?? 'N/A' }} ADE</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Status:</strong>
                                <p>{{ $order->status ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3">Order Items</h4>
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/' . ($item->productVariation->variation_image ?? $item->product->product_image)) }}"
                                                alt="Product" width="60">
                                        </td>
                                        <td>
                                            {{ $item->product->product_name }}
                                            @if ($item->productVariation)
                                                <br>
                                                <small class="text-muted">[ {{ $item->productVariation->variation_name }} -
                                                    {{ $item->productVariation->variation_value }} ]</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-end mt-5">
                            <a href="{{ route('login') }}" class="btn btn-dark me-2">Back</a>
                            @auth


                                <button type="button" onclick="Return_rto('received', {{ $order_id }})"
                                    class="btn btn-danger">
                                    <span class="indicator-label">Receive RTO</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function Return_rto(status, order_id) {
            console.log('demo');
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('status', status);
            formData.append('order_id', order_id);
            $.ajax({
                url: "{{ route('order_public_rto_submit') }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        window.location.href = "{{ route('login') }}";
                    } else if (dataResult == 2) {
                        toastr.error('Order Not Found !');
                    } else if (dataResult == 3) {
                        toastr.error('This Order RTO is already received');
                    } else if (dataResult == 4) {
                        toastr.error("This Order is not Cancelled, you can't receive RTO");
                    } else {
                        toastr.error('Something Went Wrong.');
                    }

                }
            });
        }
    </script>
@endpush
