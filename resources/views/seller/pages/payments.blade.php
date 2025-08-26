@extends('seller.layouts.app')
@section('title', 'Payments')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
        if (!ActivityLogger::hasSellerPermission('payments', 'view')) {
            abort(403, 'Unauthorized action.');
        }
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
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
                        <li class="breadcrumb-item text-muted">Payments</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area card">
                                    <div class="card-header border-0 pt-6 w-100">
                                        <div class="col-md-12 mt-3" id="filter_section" style="display: none;">
                                            <div class="row">

                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">Current Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="current_date" id="current_date">
                                                </div>
                                                <div class="col-md-4 mt-3" id="start_date_display">
                                                    <label class="required mb-2">Start Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="start_date" id="start_date">
                                                </div>
                                                <div class="col-md-4 mt-3" id="end_date_display">
                                                    <label class="required mb-2">End Date</label>
                                                    <input type="date" class="form-control form-control-solid"
                                                        name="end_date" id="end_date">
                                                </div>

                                            </div>
                                            <hr style="border: none; border-top: 1px solid black;">
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalTransactions">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Transactions</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalAmountIn">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Amount In</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalWallet">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Wallet</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalAmountOut">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Amount Out</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                            height="2" rx="1"
                                                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                        <path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <input type="text" data-kt-customer-table-filter="search" id="search"
                                                    class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">

                                            <button class="btn btn-flex btn-primary fw-bolder" onclick="toggleFilter()">
                                                <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                Filter
                                            </button>
                                            <button type="button" class="btn btn-danger me-3"
                                                onclick="reset_table()">Reset </button>

                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#paymentRequestModal">Request Payment</button>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"
                                                class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="min-w-125px">id</th>
                                                        <th class="min-w-125px">Amount Type</th>
                                                        <th class="min-w-125px">Amount</th>
                                                        <th class="min-w-125px">Date</th>
                                                        <th class="min-w-125px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
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

    <div class="modal fade" id="paymentRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="paymentRequestForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Request Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Amount</label>
                        <input type="number" step="0.01" min="1" name="amount" class="form-control"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Send Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single1').select2();
            $('.js-example-basic-single2').select2();
        });


        function toggleFilter() {
            var filterSection = document.getElementById("filter_section");
            if (filterSection.style.display === "none") {
                $('#filter_section').slideDown();
                // filterSection.style.display = "block";
            } else {
                // filterSection.style.display = "none";
                $('#filter_section').slideUp();
            }
        }

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [1, 'desc']
                ],
                ajax: {
                    url: "{{ route('all_seller_payments') }}",
                    type: 'GET',
                    data: function(d) {
                        d.current_date = $('#current_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                    dataSrc: function(json) {
                        $('#totalTransactions').html(json.totalTransactions);
                        $('#totalAmountIn').html(json.totalAmountIn);
                        $('#totalWallet').html(json.totalWallet);
                        $('#totalAmountOut').html(json.totalAmountOut);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'AmountType',
                        name: 'AmountType'
                    },
                    {
                        data: 'Amount',
                        name: 'Amount'
                    },
                    {
                        data: 'Date',
                        name: 'Date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(0).html(incrementedId);
                },
                responsive: true,
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#current_date,#start_date,#end_date').on('change', function() {
                table.ajax.reload();
            });
        });

        function reset_table() {
            $('#current_date,#start_date,#end_date,#search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }




        $('#paymentRequestForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('send_payment_request') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success('Request Sent');
                    $('#paymentRequestModal').modal('hide');
                    $('#paymentRequestForm')[0].reset();
                    $('#table').DataTable().ajax.reload();
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.error) {
                        toastr.error(err.responseJSON.error);
                    } else {
                        toastr.error('Something went wrong');
                    }
                }
            });
        });
    </script>
@endpush
