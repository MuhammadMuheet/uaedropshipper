@extends('admin.layouts.app')
@section('title','Areas')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
            if (!ActivityLogger::hasPermission('locations','view')){
               abort(403, 'Unauthorized action.');
        }
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Areas</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Areas</li>
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
                                            <button type="button" class="btn btn-success me-4" data-bs-toggle="modal" data-bs-target="#importModal">
                                                <span class="btn-text-inner">Import from Excel</span>
                                            </button>
                                        
                                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target_bulk" id="assignOrdersBtn" style="display: none;">
    <span class="svg-icon svg-icon-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
            <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor"></path>
            <path d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="#C4C4C4"></path>
        </svg>
    </span>Bulk Update
                                            </button>
                                            <button type="button" class="btn btn-primary  me-4" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">
                                                <span class="btn-text-inner">Add</span>
                                            </button>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"  class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th class="min-w-25px">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                            <input class="form-check-input" type="checkbox" id="main_action" data-kt-check="true" data-kt-check-target="#table .form-check-input" value="1">
                                                        </div>
                                                    </th>
                                                    <th class="min-w-125px">id</th>
                                                    <th class="min-w-125px">State</th>
                                                    <th class="min-w-125px">Areas</th>
                                                    <th class="min-w-125px">Shipping [AED]</th>
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
    <div class="modal fade" id="importModal" tabindex="-1" style="display: none;" aria-hidden="true">
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
                    <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Import Areas from Excel</h1>
                            <p class="text-muted">Upload an Excel file with columns: State Name, Area, Shipping Cost (optional)</p>
                        </div>
                        <form method="post" id="importForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Excel File</span>
                                    </label>
                                    <input type="file" class="form-control form-control-solid" name="import_file" accept=".xlsx,.xls,.csv" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="importAreas()" id="importSubmit" class="btn btn-primary">
                                    <span class="indicator-label">Import</span>
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
    <div class="modal fade" id="kt_modal_new_target_bulk" tabindex="-1" style="display: none;" aria-hidden="true">
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
                            <h1 class="mb-3">Update Bulk Areas Shipping</h1>
                        </div>
                        <form  method="post" id="InsertFormBulk" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="required mb-2">Shipping Fee</label>
                                    <input type="number" name="bulk_shipping_fee" id="bulk_shipping_fee" placeholder="Shipping Fee" class="form-control form-control-solid">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" onclick="insert_item_bulk()" id="kt_modal_new_target_submit_bulk" class="btn btn-primary">
                                    <span class="indicator-label">Update</span>
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
                            <h1 class="mb-3">Add Area</h1>
                        </div>
                        <form  method="post" id="InsertForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Choose State</span>
                                    </label>
                                    <select class="js-example-basic-single form-select form-select-solid"  name="state_id">
                                        <option value="" selected disabled>Choose State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->state}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Area</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Area" name="area">
                                </div>
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Shipping Cost</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" placeholder="Enter Shipping Cost" name="shipping" id="shipping">
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
                            <h1 class="mb-3">Update Area</h1>
                        </div>
                        <form  method="post" id="editForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class=" col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Choose State</span>
                                    </label>
                                    <select class="js-example-basic-single1 form-select form-select-solid"  name="edit_state_id" id="edit_state_id">
                                        <option value="" selected disabled>Choose State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->state}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Area</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Enter Area" name="edit_area" id="edit_area">
                                </div>
                                <div class="col-md-12 mb-8 fv-row fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Shipping Cost</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" placeholder="Enter Shipping Cost" name="edit_shipping" id="edit_shipping">
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
function importAreas() {
    var formData = new FormData(document.getElementById("importForm"));
    $("#importSubmit").text("Importing...").prop("disabled", true);
    
    $.ajax({
        url: "{{ route('import_areas') }}",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            $("#importSubmit").text("Import").prop("disabled", false);
            
            if (response.success) {
                $('#importModal').modal('hide');
                $('#table').DataTable().ajax.reload(null, false);
                
                let msg = response.message;
                if (response.stats) {
                    msg += `<br>Created: ${response.stats.created}, Updated: ${response.stats.updated}, Failed: ${response.stats.failed}`;
                }
                
                toastr.success(msg, 'Import Results', {timeOut: 10000, extendedTimeOut: 5000});
                
                if (response.failed_rows && response.failed_rows.length > 0) {
                    console.log("Failed rows:", response.failed_rows);
                    // Optionally show failed rows to user
                }
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            $("#importSubmit").text("Import").prop("disabled", false);
            toastr.error(xhr.responseJSON?.message || "Import failed");
        }
    });
}
        function toggleAssignButton() {
            if ($('input[name="bulk_action[]"]:checked').length > 0) {
                $('#assignOrdersBtn').show();
            } else {
                $('#assignOrdersBtn').hide();
            }
        }
        function toggleBulkAssignButton() {
            if ($('#main_action:checked').length > 0) {
                $('#assignOrdersBtn').show();
            } else {
                $('#assignOrdersBtn').hide();
            }
        }
        $(document).ready(function () {
            $(document).on('change', 'input[name="bulk_action[]"]', function () {
                toggleAssignButton();
            });
            $(document).on('change', '#main_action', function () {
                toggleBulkAssignButton();
            });
            toggleAssignButton();
            toggleBulkAssignButton()
        });
        $(document).ready(function () {
            $("#InsertForm").on("keypress", function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    insert_item();
                }
            });
            $("#editForm").on("keypress", function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    update_item();
                }
            });
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                dropdownParent: $('#kt_modal_new_target')
            });
            $('.js-example-basic-single1').select2({
                dropdownParent: $('#edit_kt_modal_new_target')
            });
        });
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                ajax: {
                    url: "{{ route('all_areas') }}",
                    type: 'GET'
                },
                columns: [
                    {data: 'BulkAction', name: 'BulkAction', orderable: false},
                    {data: 'id', name: 'id'},
                    { data: 'State', name: 'State' },
                    { data: 'Area', name: 'Area' },
                    { data: 'Shipping', name: 'Shipping' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start;
                    var incrementedId = start + dataIndex + 1;
                    $('td', row).eq(1).html(incrementedId);
                },
                responsive: true,
                pageLength: 50,
                lengthMenu: [[50, 100, 200,300,400, 500, 1000, 1500], [50, 100, 200,300,400, 500, 1000, 1500]],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
                $('#assignOrdersBtn').hide();
            });
        });
    </script>
    <script>

        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');
            $('#edit_kt_modal_new_target').trigger("reset");
            $.ajax({
                url: "{{Route('get_admin_areas_edit')}}",
                type: "get",
                data: {
                    edit: 1,
                    id: id,
                },
                cache: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    $('input[name=id]').val(dataResult.id);
                    $('#edit_area').val(dataResult.area);
                    $('#edit_shipping').val(dataResult.shipping);
                    $('#edit_state_id').val(dataResult.state_id).change();

                }
            });
        });

        function update_item() {
            var form_Data = new FormData(document.getElementById("editForm"));
            $.ajax({
                url: "{{route('update_areas')}}",
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
                        toastr.success('Area Updated Successfully.');
                        document.getElementById("editForm").reset();
                    } else if(dataResult == 2){
                        toastr.error('Enter a Area');
                    }else if(dataResult == 3){
                        toastr.error('Area Not Found!');
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
                url: '{{route('delete_areas')}}',
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
                url: "{{route('add_areas')}}",
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
                        toastr.success('Area Add Successfully.');
                        document.getElementById("InsertForm").reset();
                        // setTimeout(function () {
                        //     window.location.reload();
                        // }, 1000);
                    } else if(dataResult == 2){
                        toastr.error('Enter a Area');
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }

                }
            });
        }
        function insert_item_bulk() {
            var form_Data = new FormData(document.getElementById("InsertFormBulk"));
            var selectedareas = [];
            $("input[name='bulk_action[]']:checked").each(function () {
                selectedareas.push($(this).val());
            });
            if (selectedareas.length === 0) {
                toastr.error("Please select at least one area.");
                return;
            }
            form_Data.append("selectedareas", JSON.stringify(selectedareas));
            $("#kt_modal_new_target_submit_bulk").text("Loading").prop("disabled", true);
            $.ajax({
                url: "{{route('bulk_update_area')}}",
                type: "POST",
                data: form_Data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    console.log(dataResult);
                    $("#kt_modal_new_target_submit_bulk").text("Update").prop("disabled", false);
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        $('#kt_modal_new_target_bulk').modal('hide');
                        toastr.success('Shipping Fee Updated Successfully');
                        $("#InsertFormBulk")[0].reset();
                        $('#assignOrdersBtn').hide();
                    } else if (dataResult == 2) {
                        toastr.error('Please Add Shipping Fee');
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        }
    </script>
@endpush
