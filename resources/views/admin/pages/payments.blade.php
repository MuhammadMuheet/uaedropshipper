@extends('admin.layouts.app')
@section('title', 'Payments')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
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
                        <li class="breadcrumb-item text-muted">Payments</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area card">
                                    <div class="card-header border-0 pt-6 w-100">
                                        <div class="col-md-12 mt-3" id="filter_section" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">Seller</label>
                                                    <select name="seller" id="seller"
                                                        class="js-example-basic-single2 form-control form-control-solid">
                                                        <option value="" selected>Choose a Seller</option>
                                                        @foreach ($sellerData as $seller)
                                                            <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">Logistic Company</label>
                                                    <select name="logistic_company" id="logistic_company"
                                                        class="js-example-basic-single2 form-control form-control-solid"
                                                        onchange="get_drivers(this.value)">
                                                        <option value="" selected>Choose a Logistic Company</option>
                                                        @foreach ($LogisticCompanyData as $LogisticCompany)
                                                            <option value="{{ $LogisticCompany->id }}">
                                                                {{ $LogisticCompany->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label class="required mb-2">User Type</label>
                                                    <select name="usertype" id="usertype"
                                                        class=" form-control form-control-solid">
                                                        <option value="" selected>Choose a User Type</option>
                                                        <option value="seller">Seller</option>
                                                        <option value="logistic_company">Logistic Company</option>
                                                    </select>
                                                </div>
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
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalTransactions">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Transactions</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalAmountIn">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Amount In</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalWallet">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Wallet</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalAmountOut">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Amount Out</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalSellerTransactions">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Seller Transactions</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-4">
                                                    <div class="card-shadow card rounded">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2 pb-1">
                                                                <h4 class="ms-1 mb-0" id="totalCompanyTransactions">0</h4>
                                                            </div>
                                                            <p class="mb-1">Total Company Transactions</p>
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
                                                <input type="text" data-kt-customer-table-filter="search"
                                                    id="search" class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">

                                            {{-- <button type="button" class="btn btn-primary  me-4" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_new_target">
                                                <span class="btn-text-inner">Pay Now</span>
                                            </button> --}}
                                            <button type="button" class="btn btn-primary"
                                                onclick="openPaymentRequestsModal()">
                                                View Payment Requests
                                            </button>


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
                                                        <th class="min-w-125px">Name</th>
                                                        <th class="min-w-125px">Account Type</th>
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
    <div class="modal fade" id="kt_modal_new_target" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                        action="#">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Pay Now</h1>
                        </div>
                        <form method="post" id="InsertForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required"> User Type</span>
                                    </label>
                                    <select class="form-select form-select-solid" name="user_type" id="user_type"
                                        onchange="get_area(this.value)">
                                        <option value="" selected disabled>Choose User Type</option>
                                        <option value="seller">Seller</option>
                                        <option value="logistic_company">Logistic Company</option>
                                    </select>
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">

                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Users</span>
                                    </label>
                                    <select name="user_id" id="user_id"
                                        class="js-example-basic-single3 form-control form-control-solid">
                                        <option value="" selected>Choose a User</option>
                                    </select>
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">

                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Amount</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" name="amount"
                                        id="amount" placeholder="Enter Amount" required>
                                </div>

                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="insert_item()" id="kt_modal_new_target_submit"
                                    class="btn btn-primary">
                                    <span class="indicator-label">Pay Now</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="paymentRequestsModal" tabindex="-1" aria-labelledby="paymentRequestsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentRequestsModalLabel">Payment Requests</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="requestsTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Seller</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
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
        $(document).ready(function() {
            $('.js-example-basic-single3').select2({
                dropdownParent: $('#kt_modal_new_target')
            });
        });
        document.getElementById("amount").addEventListener("keydown", function(e) {
            if (["e", "E", "+", "-"].includes(e.key)) {
                e.preventDefault();
            }
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
            $("#editForm").on("keypress", function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    update_item();
                }
            });
        });
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [1, 'desc']
                ],
                ajax: {
                    url: "{{ route('all_admin_payments') }}",
                    type: 'GET',
                    data: function(d) {
                        d.usertype = $('#usertype').val();
                        d.current_date = $('#current_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.logistic_company = $('#logistic_company').val();
                        d.seller = $('#seller').val();
                    },
                    dataSrc: function(json) {
                        $('#totalTransactions').html(json.totalTransactions);
                        $('#totalAmountIn').html(json.totalAmountIn);
                        $('#totalWallet').html(json.totalWallet);
                        $('#totalAmountOut').html(json.totalAmountOut);
                        $('#totalSellerTransactions').html(json.totalSellerTransactions);
                        $('#totalCompanyTransactions').html(json.totalCompanyTransactions);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'UserName',
                        name: 'UserName'
                    },
                    {
                        data: 'UserType',
                        name: 'UserType'
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
            $('#logistic_company,#seller,#usertype,#current_date,#start_date,#end_date').on('change', function() {
                table.ajax.reload();
            });
        });

        function reset_table() {
            $('#logistic_company,#usertype,#seller,#current_date,#start_date,#end_date,#search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }
    </script>
    <script>
        function get_area(selectedValue) {
            console.log(selectedValue)
            $.ajax({
                url: "{{ Route('get_admin_transaction_user_type') }}",
                type: "get",
                data: {
                    user_type: selectedValue,
                },
                cache: false,
                success: function(dataResult) {
                    $('#user_id').html(dataResult.options);

                }
            });
        }

        function insert_item() {
            var form_Data = new FormData(document.getElementById("InsertForm"));
            document.getElementById("kt_modal_new_target_submit").innerHTML = "Loading";
            document.getElementById('kt_modal_new_target_submit').disabled = false;
            $.ajax({
                url: "{{ route('admin_give_payment') }}",
                type: "POST",
                data: form_Data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    document.getElementById("kt_modal_new_target_submit").innerHTML = "Add";
                    document.getElementById('kt_modal_new_target_submit').disabled = false;
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $('#kt_modal_new_target').modal('hide');
                        toastr.success('Amount Pay Successfully.');
                        document.getElementById("InsertForm").reset();
                        $('#js-example-basic-single3').val(null).trigger('change');
                    } else if (dataResult == 2) {
                        toastr.error('Enter an Amount');
                    } else if (dataResult == 3) {
                        toastr.error('USer Not Found');
                    } else if (dataResult == 4) {
                        toastr.error('Amount is greater than wallet balance.');
                    } else {
                        toastr.error('Something Went Wrong.');
                    }

                }
            });
        }




        function togglePaymentRequests() {
            const section = $('#paymentRequestsSection');
            if (section.is(':visible')) {
                section.slideUp();
            } else {
                section.slideDown();
                loadRequestsTable();
            }
        }

        function openPaymentRequestsModal() {
            const modal = new bootstrap.Modal(document.getElementById('paymentRequestsModal'));
            modal.show();
            loadRequestsTable();
        }

        let requestsTable = null;

        function loadRequestsTable() {
            if (requestsTable) {
                requestsTable.ajax.reload();
                return;
            }

            requestsTable = $('#requestsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('payment_requests.list') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'seller_name'
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $('td:eq(0)', row).html(dataIndex + 1);
                }
            });
        }



        function handlePaymentAction(requestId, action, amount = null, userId = null) {
            $.ajax({
                url: "{{ route('payment_request_action') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    request_id: requestId,
                    action: action,
                    user_id: userId,
                    amount: amount
                },
                success: function(response) {
                    alert(response.message);
                    $('#requestsTable').DataTable().ajax.reload(); // reload table
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseJSON?.message || "Something went wrong.");
                }
            });
        }
    </script>
@endpush
