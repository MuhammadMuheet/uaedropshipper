@php
    use Carbon\Carbon;
 @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{@$order->subSeller->unique_id}}-{{$order->id}}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="icon" type="image/webp" href="{{asset('favicon.png')}}"/>
      <style>
        /*@page {*/
        /*    size: 4in 6.5in;*/
        /*    margin: 0;*/
        /*}*/

        /*@media print {*/
        /*    body {*/
        /*        width: 4in;*/
        /*        height: 6.5in;*/
        /*        margin: 0;*/
        /*        padding: 0;*/
        /*    }*/

        /*    .order-slip {*/
        /*        width: 100%;*/
        /*        height: 100%;*/
        /*        box-sizing: border-box;*/
        /*    }*/
        /*}*/
    </style>
    <style>
        body {
            font-family: Poppins, Helvetica, sans-serif;
            background-color: #f8f9fa;
        }
        .order-slip {
            width: 100% !important;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
            font-size: 1.1rem;
        }
        .info-label {
            font-weight: 600;
            min-width: 140px;
            display: inline-block;
        }
        .barcode-container {
            height: 80px;
        }
        .barcode {
            max-height: 100%;
            width: auto;
        }
        .table-sm th, .table-sm td {
            padding: 0.5rem;
        }
    </style>
</head>
<body style="margin:0; padding:0;">

<div class="order-slip " id="printableArea">
    <div style="margin:0; padding:0;">
    <!-- Header with logo, barcode and QR code in one line -->
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <div class="col-md-3">
            <img src="{{asset('logo.png')}}" width="150" alt="Company Logo" class="img-fluid">
        </div>
        <div class="barcode-container d-flex align-items-center col-md-6 justify-content-center flex-column">
            <span class="info-label text-center">Date: {{Carbon::parse($order->created_at)->format('d-m-Y')}}</span>
            <h2><span class="info-label text-center">Ref ID: {{@$order->subSeller->unique_id}}-{{$order->id}}</span></h2>
            <!--<img src="assets/img/images.png" alt="Barcode" class="barcode">-->
        </div>
        <div class="text-end col-md-3 ">
            <img src="{{asset('storage/order_qrcodes/'.$order->qr_code)}}" width="100" alt="QR Code">
            <div class="small text-muted mt-1">Scan for details</div>
        </div>
    </div>

    <!-- Main content sections -->
    <div class="row g-0">
        <!-- Left column - Order and Customer Info -->
        <div class="col-md-6 border-end">
            <!-- Order Information -->
            <div class="section-title">ORDER INFORMATION</div>
            <div class="p-3">

                <div class="mb-2">
                    <h1><span class="info-label text-center">COD:{{$order->cod_amount}} AED</span></h1>
                </div>
            </div>

            <!-- Customer Information -->

            <hr class="p-0 m-0">
            <div class="p-3 d-flex flex-column">
                <span class="info-label">Shipping Address:</span>
                <span>{{$order->address}}</span>
            </div>
        </div>

        <!-- Right column - Shipping Info -->
        <div class="col-md-6">
            <!-- Shipping Information -->
            <div class="section-title">CUSTOMER DETAILS</div>
            <div class="p-3">
                <div class="mb-2">
                    <span class="info-label">Customer Name:</span>
                    <span>{{$order->customer_name}}</span>
                </div>
                <div class="mb-2">
                    <span class="info-label">Phone:</span>
                    <span>{{ $order->phone }}</span>
                </div>
                <div class="mb-2">
                    <span class="info-label">WhatsApp Number:</span>
                    <span>{{ $order->whatsapp ?? 'N/A' }}</span>
                </div>
                <div class="mb-2">
                    <span class="info-label">City:</span>
                    <span>{{ $order->state->state ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="info-label">Area:</span>
                    <span>{{ $order->area->area ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Section -->
    <div class="section-title">
        <span class="info-label">Customer Note:</span>
        <span class="fw-light">{{ $order->instructions ?? 'None' }}</span>
    </div>
    <div class="p-3">
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
            <tr>
                <th width="75%">ITEMS</th>
                <th width="25%" >QR Code for RTO</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>@foreach ($order->orderItems as $item){{ $item->product->product_name }}    @if ($item->productVariation)<small class="text-muted">[ {{ $item->productVariation->variation_name }} - {{ $item->productVariation->variation_value }} ]</small>@endif QTY({{ $item->quantity }}) @if(!$loop->last) , @endif   @endforeach </td>
                <td class="justify-content-center text-center"><img src="{{asset('storage/order_qrcodes/'.$order->qr_code_rto)}}" width="100" alt="QR Code"></td>
            </tr>
            </tbody>

        </table>
    </div>
    <!-- Footer -->
    <div class="small text-center p-3 bg-light border-top">
        Thank you for your order!
    </div>
    </div>
</div>



</body>
</html>