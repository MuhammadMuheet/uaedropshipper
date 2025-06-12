@extends('admin.layouts.app')
@section('title','User Logs')
@section('content')
    <style>
        .select2-container{
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple{
            padding: 0.75rem 1.25rem;
        }
        .select2-selection{
            color: #5e6278;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #e4e6ef;
            appearance: none;
            border-radius: .475rem;
            padding: .75rem 1rem;
        }
    </style>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">User Logs</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">User Logs</li>
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
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-md-3 mt-3">
                                                    <label class="required mb-2">User</label>
                                                    <select name="user_id" id="user_id" class="js-example-basic-single form-control form-control-solid">
                                                        <option value="" selected>Choose a User</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                    <div class="col-md-3 mt-3" >
                                                        <label class="required mb-2">Current Date</label>
                                                        <input type="date" class="form-control form-control-solid" name="current_date" id="current_date">
                                                    </div>
                                                <div class="col-md-3 mt-3" id="start_date_display" >
                                                    <label class="required mb-2">Start Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="start_date" id="start_date">
                                                </div>
                                                <div class="col-md-3 mt-3" id="end_date_display">
                                                    <label class="required mb-2">End Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="end_date" id="end_date">
                                                </div>
                                            
                                            </div> 
                                        </div>                              
                                        <div class="col-12">
                                            <hr style=" border: none; border-top: 1px solid black;">
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
													</svg>
												</span>
                                                <!--end::Svg Icon-->
                                                <input type="text" data-kt-customer-table-filter="search" id="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search " />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-md-end text-center">
                                        <button type="button" class="btn btn-danger me-3" onclick="reset_table()">Reset </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"  class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th class="min-w-125px">id</th>
                                                    <th class="min-w-125px">Profile</th>
                                                    <th class="min-w-125px">Ip Address</th>
                                                    <th class="min-w-125px">Activity</th>
                                                    <th class="min-w-125px">Date</th>
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
@endsection
@push('js')
    <script type="text/javascript">
 $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('all_user_logs') }}",
                    type: 'GET',
                    data: function (d) {
                        d.user_id = $('#user_id').val();
                        d.current_date = $('#current_date').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'ProfileView', name: 'ProfileView' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'Activity', name: 'Activity' },
                    { data: 'Date', name: 'Date' },
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(0).html(incrementedId);
                },
                order: [[0, 'desc']],
                responsive: true,
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#user_id,#current_date,#start_date,#end_date').on('change', function () {
                table.ajax.reload();
            });
        });
        function reset_table() {
            $('#sub_seller,#status,#state,#area,#current_date,#start_date,#end_date,#search').val('').trigger('change');
            $('#table').DataTable().ajax.reload();
        }
    </script>

@endpush
