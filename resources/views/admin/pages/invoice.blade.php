<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f9fbfd;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 13px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: auto;
            max-width: 800px;
        }

        .logo img {
            width: 120px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .col {
            width: 48%;
            margin-bottom: 10px;
        }

        h6 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #666;
            margin-top: 0px !important;
        }

        .fw-bold {
            font-weight: 700;
            color: #111;
        }

        .fw-semibold {
            font-weight: 500;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
        }

        table td {
            padding: 8px 0;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
        }

        .bg-primary {
            background: #007bff;
        }

        .bg-dark {
            background: #333;
        }

        .bg-success {
            background: #28a745;
        }

        .bg-danger {
            background: #dc3545;
        }

        .total {
            text-align: right;
            font-size: 14px;
            font-weight: 700;
            margin-top: 10px;
        }

        .side-box {
            border: 1px dashed #ccc;
            border-radius: 10px;
            background: #f5f8fa;
            padding: 15px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 20px;
        }

        .side-box .badge {
            background: #28a745;
            margin-bottom: 10px;
        }

        .side-box h6 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            margin-top: 0px !important;
            color: #444;
        }

        .side-box div {
            margin-bottom: 8px;
        }

        .side-box .fw-bold {
            font-size: 13px;
        }

        .proof-img {
            max-width: 100%;
            max-height: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <div class="card">

        {{-- Prepare logo image as base64 --}}
        @php
            $logo = base64_encode(file_get_contents(public_path('logo.png')));
        @endphp

        <div class="row">
            <div class="logo">
                <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h6>Issue Date:</h6>
                <div class="fw-bold">{{ \Carbon\Carbon::parse($paymentData->created_at)->format('d/m/y') }}</div>
            </div>
        </div>

        <div class="row">
            @php
                $userData = App\Models\User::find($paymentData->user_id);
            @endphp
            <div class="col">
                <h6>Issue For:</h6>
                <div class="fw-bold">{{ ucfirst($userData->name) }}</div>
                <div class="fw-semibold">{{ $userData->email }}</div>
            </div>

            <div class="col">
                <h6>Issued By:</h6>
                <div class="fw-bold">Arrbaab</div>
                <div class="fw-semibold">admin@arrbaab.com</div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h6>Proof:</h6>

                @php
                    $proofImage = null;
                    if ($paymentData->paymentRequest && $paymentData->paymentRequest->proof) {
                        $proofPath = storage_path('app/public/' . $paymentData->paymentRequest->proof);
                        if (file_exists($proofPath)) {
                            $proofImage = base64_encode(file_get_contents($proofPath));
                        }
                    }
                @endphp

                @if ($proofImage)
                    <img src="data:image/png;base64,{{ $proofImage }}" alt="Proof Image" class="proof-img">
                @else
                    <div class="fw-bold">No Proof Uploaded</div>
                @endif
            </div>

            <div class="col">
                <h6>Additional Info:</h6>
                <div class="fw-semibold">Reference ID:</div>
                <div class="fw-bold">#{{ $paymentData->id }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>User Type</th>
                    <th style="text-align:center;">Amount Type</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>

                        {{ dd($paymentData) }}
                        @if ($paymentData->user_type == 'seller')
                            <span class="badge bg-primary">Seller</span>
                        @else
                            <span class="badge bg-dark">Logistic Company</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if ($paymentData->amount_type == 'in')
                            <span class="badge bg-success">Amount In</span>
                        @else
                            <span class="badge bg-danger">Amount Out</span>
                        @endif
                    </td>
                    <td style="text-align:right;">{{ $paymentData->amount }} AED</td>
                </tr>
            </tbody>
        </table>

        <div class="total">Total: {{ $paymentData->amount }} AED</div>

        <div class="side-box">
            <span class="badge">Paid</span>
            <h6>PAYMENT DETAILS</h6>

            <div>
                <div class="fw-semibold">Email:</div>
                <div class="fw-bold">{{ $userData->email }}</div>
            </div>

            <div>
                <div class="fw-semibold">Account:</div>
                <div class="fw-bold">{{ $userData->ac_no }}</div>
            </div>


        </div>
    </div>

</body>

</html>
