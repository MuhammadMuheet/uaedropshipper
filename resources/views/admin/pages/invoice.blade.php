@extends('admin.layouts.app')
@section('title', 'Invoice')

@section('content')
    @php
        use App\Helpers\ActivityLogger;
        use Carbon\Carbon;
        if (!ActivityLogger::hasPermission('payments', 'view')) {
            abort(403, 'Unauthorized action.');
        }
    @endphp

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">

                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Payments</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Invoice</li>
                    </ul>
                </div>

                {{-- ✅ DOWNLOAD PDF BUTTON (TOP-RIGHT) --}}
                <div>
                    <a href="{{ route('admin.payments.invoice.download', encrypt($paymentData->id)) }}"
                        class="btn btn-primary">
                        <i class="fa fa-download"></i> Download PDF
                    </a>
                </div>

            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                <div class="card">
                                    <div class="card-body p-lg-20">
                                        <div class="d-flex flex-column flex-xl-row">
                                            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                                                <div class="mt-n1">
                                                    <div class="d-flex flex-stack pb-10">
                                                        <a href="#">
                                                            <img alt="Logo" style="width: 200px;"
                                                                src="{{ asset('logo.png') }}" />
                                                        </a>
                                                    </div>

                                                    <div class="m-0">
                                                        <div class="row g-5 mb-11">
                                                            <div class="col-sm-6">
                                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:
                                                                </div>
                                                                <div class="fw-bold fs-6 text-gray-800">
                                                                    {{ Carbon::parse($paymentData->created_at)->format('d/m/y') }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-5 mb-12">
                                                            <div class="col-sm-6">
                                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:
                                                                </div>
                                                                @php
                                                                    $userData = App\Models\User::find(
                                                                        $paymentData->user_id,
                                                                    );
                                                                @endphp
                                                                <div class="fw-bold fs-6 text-gray-800">
                                                                    {{ ucfirst($userData->name) }}</div>
                                                                <div class="fw-semibold fs-7 text-gray-600">
                                                                    {{ ucfirst($userData->email) }}
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:
                                                                </div>
                                                                <div class="fw-bold fs-6 text-gray-800">Arrbaab</div>
                                                                <div class="fw-semibold fs-7 text-gray-600">
                                                                    admin@arrbaab.com
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1">
                                                            <div class="table-responsive border-bottom mb-9">
                                                                <table class="table mb-3">
                                                                    <thead>
                                                                        <tr class="border-bottom fs-6 fw-bold text-muted">
                                                                            <th class="min-w-175px pb-2">User Type</th>
                                                                            <th class="min-w-70px text-end pb-2">Amount Type
                                                                            </th>
                                                                            <th class="min-w-100px text-end pb-2">Amount
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="fw-bold text-gray-700 fs-5 text-end">
                                                                            <td class="text-start pt-6">
                                                                                @if ($paymentData->user_type == 'seller')
                                                                                    <div class='badge bg-primary'>Seller
                                                                                    </div>
                                                                                @else
                                                                                    <div class='badge bg-dark'>Logistic
                                                                                        Company</div>
                                                                                @endif
                                                                            </td>
                                                                            <td class="pt-6">
                                                                                @if ($paymentData->amount_type == 'in')
                                                                                    <div class='badge bg-success'>Amount In
                                                                                    </div>
                                                                                @else
                                                                                    <div class='badge bg-danger'>Amount Out
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                            <td class="pt-6">{{ $paymentData->amount }}
                                                                                AED</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="d-flex justify-content-end">
                                                                <div class="mw-300px">
                                                                    <div class="d-flex flex-stack">
                                                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">
                                                                            Total</div>
                                                                        <div class="text-end fw-bold fs-6 text-gray-800">
                                                                            {{ $paymentData->amount }} AED</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="m-0">
                                                <div
                                                    class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                                                    <div class="mb-8">
                                                        <span class="badge badge-light-success me-2">Paid</span>
                                                    </div>

                                                    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">PAYMENT
                                                        DETAILS</h6>

                                                    <div class="mb-6">
                                                        <div class="fw-semibold text-gray-600 fs-7">Email:</div>
                                                        <div class="fw-bold text-gray-800 fs-6">
                                                            {{ ucfirst($userData->email) }}</div>
                                                    </div>

                                                    <div class="mb-6">
                                                        <div class="fw-semibold text-gray-600 fs-7">Account:</div>
                                                        <div class="fw-bold text-gray-800 fs-6">
                                                            {{ ucfirst($userData->ac_no) }}</div>
                                                    </div>

                                                    <div class="m-0">
                                                        <div class="fw-semibold text-gray-600 fs-7">Time Spent:</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
