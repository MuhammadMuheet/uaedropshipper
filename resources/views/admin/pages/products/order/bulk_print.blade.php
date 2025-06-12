<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bulk Order Slips</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .page-break {
                page-break-after: always;
            }
            .d-print-none {
                display: none !important;
            }
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

@foreach($orders as $order)
    @include('admin.pages.products.order.single_slip', ['order' => $order])
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
<script>
    window.onload = function () {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    };
</script>

</body>
</html>