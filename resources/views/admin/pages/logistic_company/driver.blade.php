@extends('admin.layouts.app')
@section('title','Drivers')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Drivers</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ucfirst($company_data->name)}}</li>
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
                                            @if(ActivityLogger::hasPermission('logistic_companies','add'))
                                                <button type="button" class="btn btn-primary  me-4" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">
                                                    <span class="btn-text-inner">Add</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"  class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th class="min-w-125px">id</th>
                                                    <th class="min-w-125px">Name</th>
                                                    <th class="min-w-125px">Email</th>
                                                    <th class="min-w-125px">Mobile</th>
                                                    <th class="min-w-125px">Status</th>
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
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
								</svg>
							</span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Add Driver</h1>
                        </div>
                        <form  method="post" id="InsertForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden"  name="company_id" value="{{$id}}">
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Name</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Name" name="name">
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Email</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Email" name="email">
                                </div>

                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Mobile</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Mobile" name="mobile">
                                </div>

                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Password</span>
                                    </label>
                                    <input type="password" class="form-control form-control-solid" placeholder="Enter Password" name="password">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="insert_item()" id="kt_modal_new_target_submit" class="btn btn-primary">
                                    <span class="indicator-label">Add</span>
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
    <div class="modal fade" id="edit_kt_modal_new_target" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
								</svg>
							</span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Update Driver</h1>
                        </div>
                        <form  method="post" id="editForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Name</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Name" name="edit_name" id="edit_name">
                                </div>
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Email</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Email" name="edit_email" id="edit_email">
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Mobile</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Mobile" name="edit_mobile">
                                </div>

                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Password</span>
                                    </label>
                                    <input type="password" class="form-control form-control-solid" placeholder="Enter Password" name="edit_password">
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">Password  </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="********" id="edit_show_password" disabled>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="update_item()" id="edit_kt_modal_new_target_submit" class="btn btn-primary">
                                    <span class="indicator-label">Update</span>
                                    <span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                ajax: {
                    url: "{{ route('all_admin_driver', $id) }}",
                    type: 'GET'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'statusView', name: 'statusView' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(0).html(incrementedId);
                },
                responsive: true,
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading....</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
    <script>
        function changeStatus(newStatus, id) {
            const data = {
                status: newStatus,
                id: id
            };
            $.ajax({
                url: "{{ route('status_logistic_companies') }}",
                type: "GET",
                data: {
                    status: newStatus,
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Driver Block Successfully.');

                    } else if (dataResult == 2) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('Driver Active Successfully.');
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    toastr.error('An error occurred while updating the user.');
                }
            });
        }

        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');

            $('#edit_kt_modal_new_target').trigger("reset");

            $.ajax({
                url: "{{Route('get_logistic_companies')}}",
                type: "get",
                data: {
                    edit: 1,
                    id: id,
                },
                cache: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    $('#edit_name').val(dataResult.edit_name);
                    $('input[name=id]').val(dataResult.id);
                    $('input[name=edit_name]').val(dataResult.name);
                    $('input[name=edit_email]').val(dataResult.email);
                    $('input[name=edit_mobile]').val(dataResult.mobile);
                    $('#edit_show_password').val(dataResult.show_password);
                    $('select[name=edit_role]').val(dataResult.type).change();
                }
            });
        });

        function update_item() {
            var form_Data = new FormData(document.getElementById("editForm"));
            $.ajax({
                url: "{{route('update_admin_driver')}}",
                type: "POST",
                data: form_Data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    $("#edit_kt_modal_new_target_submit").removeAttr("disabled");
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $("#edit_kt_modal_new_target_submit").removeAttr("enabled");
                        $('#edit_kt_modal_new_target').modal('hide');
                        toastr.success('Driver Updated Successfully.');
                        document.getElementById("editForm").reset();

                    } else if(dataResult == 2){
                        toastr.error('Please Fill All Required Fields');
                    }else if(dataResult == 3){
                        toastr.error('Driver Not Found!');
                    }else if(dataResult == 4){
                        toastr.error('A minimum 8 characters password contains a combination of uppercase and lowercase letter and number.');
                    } else if(dataResult == 5){
                        toastr.error('This email is already in use');
                    }else {
                        toastr.error('Something Went Wrong.');
                    }
                }
            });
        }
        function deleteItem(deleteid) {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
            var csrf_token = $("input[name=csrf]").val();
            $.ajax({
                url: '{{route('delete_sellers')}}',
                type: 'GET', data: {
                    id: deleteid,
                }, success: function (data) {
                    if (data == 1) {
                        toastr.info('Successfully deleted.');
                        $('#table').DataTable().ajax.reload(null, false);

                    } else {
                        toastr.error('Something went wrong.');

                    }
                }
            });
        }
        function insert_item() {
            var form_Data = new FormData(document.getElementById("InsertForm"));
            document.getElementById("kt_modal_new_target_submit").innerHTML = "Loading";
            document.getElementById('kt_modal_new_target_submit').disabled = false;
            $.ajax({
                url: "{{route('add_admin_driver')}}",
                type: "POST",
                data: form_Data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    document.getElementById("kt_modal_new_target_submit").innerHTML = "Add";
                    document.getElementById('kt_modal_new_target_submit').disabled = false;
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $('#kt_modal_new_target').modal('hide');
                        toastr.success('Driver Add Successfully.');
                        document.getElementById("InsertForm").reset();

                    } else if(dataResult == 2){
                        toastr.error('Please Fill All Required Fields');
                    }else if(dataResult == 4){
                        toastr.error('A minimum 8 characters password contains a combination of uppercase and lowercase letter and number.');
                    } else if(dataResult == 5){
                        toastr.error('This email is already in use');
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }

                }
            });
        }
    </script>
@endpush
