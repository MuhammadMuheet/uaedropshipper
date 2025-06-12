@extends('seller.layouts.app')
@section('title', 'Edit Permission')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
          if(!ActivityLogger::hasSellerPermission('seller_role','permissions')){
             abort(403, 'Unauthorized action.');
          }
    @endphp
    @push('css')
        <style>
            .border_dash{
                border-width: 1px;
                border-style: dashed;
                color: #7e8299;
                border-color: #e4e6ef;
                background-color: #f1faff !important;
                border-radius: 10px;
            }
            .question-block {
                position: relative;
            }

            .btn-close {
                position: absolute;
                top: 10px;
                right: 10px;
                z-index: 10;
            }
            .select2-selection{
                background-color: #f5f8fa;
                border-color: #f5f8fa;
                color: #5e6278;
                padding: .75rem 1rem;
                font-size: 1.1rem;
                font-weight: 500;
                line-height: 1.5;
                background-clip: padding-box;
                appearance: none;
                border-radius: .475rem;
            }
        </style>
    @endpush
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Edit Permission</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">Dashboard</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">Edit Permission</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-dark">Role is {{ucfirst($role->name)}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="layout-px-spacing">
                    <div class="middle-content container-fluid p-0">
                        <div id="call-ringing"></div>
                        <div class="account-settings-container layout-top-spacing">
                            <div class="content-body">
                                <div class="container-fluid">
                                    <form method="post" id="UserForm" class="user-form" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="role_id" id="role_id" value="{{$role->id}}">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" > Sub Sellers</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $sub_sellers = explode(',', @$permission->sub_sellers);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_sellers[]" id="sub_sellers" value="view" @if(in_array('view', $sub_sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sub_sellers">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_sellers[]" id="sub_sellers_add" value="add" @if(in_array('add', $sub_sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sub_sellers_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_sellers[]" id="sub_sellers_edit" value="edit" @if(in_array('edit', $sub_sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sub_sellers_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_sellers[]" id="sub_sellers_status" value="status" @if(in_array('status', $sub_sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sub_sellers_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_sellers[]" id="sub_sellers_delete" value="delete" @if(in_array('delete', $sub_sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sub_sellers_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" > Seller Role</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $seller_role = explode(',', @$permission->seller_role);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="seller_role[]" id="seller_role" value="view" @if(in_array('view', $seller_role)) checked @endif>
                                                                    <label class="form-check-label" for="seller_role">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="seller_role[]" id="seller_role_add" value="add" @if(in_array('add', $seller_role)) checked @endif>
                                                                    <label class="form-check-label" for="seller_role_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="seller_role[]" id="seller_role_edit" value="edit" @if(in_array('edit', $seller_role)) checked @endif>
                                                                    <label class="form-check-label" for="seller_role_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="seller_role[]" id="seller_role_delete" value="delete" @if(in_array('delete', $seller_role)) checked @endif>
                                                                    <label class="form-check-label" for="seller_role_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="seller_role[]" id="seller_role_permissions" value="permissions" @if(in_array('permissions', $seller_role)) checked @endif>
                                                                    <label class="form-check-label" for="seller_role_permissions">
                                                                        Permissions
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" > Cart</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $cart = explode(',', @$permission->cart);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="cart[]" id="cart" value="view" @if(in_array('view', $cart)) checked @endif>
                                                                    <label class="form-check-label" for="cart">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="cart[]" id="cart_add" value="add" @if(in_array('add', $cart)) checked @endif>
                                                                    <label class="form-check-label" for="cart_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="cart[]" id="cart_edit" value="edit" @if(in_array('edit', $cart)) checked @endif>
                                                                    <label class="form-check-label" for="cart_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="cart[]" id="cart_delete" value="delete" @if(in_array('delete', $cart)) checked @endif>
                                                                    <label class="form-check-label" for="cart_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" >Orders</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $orders = explode(',', @$permission->orders);
                                                            @endphp
                                                            <div class="col-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="checkout" value="checkout" @if(in_array('checkout', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="checkout">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="orders_add" value="add" @if(in_array('add', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="orders_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="orders_edit" value="edit" @if(in_array('edit', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="orders_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-lg-6">
                                                <div class="card card-bx mt-5">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" >Products</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                @$products = @$permission->products;
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products" id="products" value="view" @if(@$products == 'view') checked @endif>
                                                                    <label class="form-check-label" for="products">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-lg-6">
                                                <div class="card card-bx mt-5">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" > Settings</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $setting = @$permission->settings;
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="settings" id="setting" value="profile" @if(@$setting == 'profile') checked @endif>
                                                                    <label class="form-check-label" for="setting">
                                                                        Profile
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <button type="button" id="btn" onclick="update_item()" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
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
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        function update_item() {
            document.getElementById('btn').disabled = true;
            var formData = new FormData(document.getElementById("UserForm"));
            $.ajax({
                url: '{{route('seller_update_permission')}}',
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    document.getElementById('btn').disabled = false;
                    if (dataResult == 1) {
                        toastr.success('Save Successfully.');
                        setTimeout(function () {
                            window.location.href = "{{route('seller_role')}}";
                        }, 1000);
                    } else {
                        toastr.error('Something Went Wrong.');
                        document.getElementById('btn').disabled = false;
                    }
                }
            });
        }
    </script>
@endpush

