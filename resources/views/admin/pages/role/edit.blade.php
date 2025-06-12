@extends('admin.layouts.app')
@section('title', 'Edit Permission')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
          if(!ActivityLogger::hasPermission('user_role','permissions')){
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
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
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
            <div id="kt_content_container" class="container-fluid">
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
                                                        <h3 class="title mt-5 text-white" > Users</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $users = explode(',', @$permission->users);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="users[]" id="users" value="view" @if(in_array('view', $users)) checked @endif>
                                                                    <label class="form-check-label" for="users">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="users[]" id="users_add" value="add" @if(in_array('add', $users)) checked @endif>
                                                                    <label class="form-check-label" for="users_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="users[]" id="users_edit" value="edit" @if(in_array('edit', $users)) checked @endif>
                                                                    <label class="form-check-label" for="users_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="users[]" id="users_status" value="status" @if(in_array('status', $users)) checked @endif>
                                                                    <label class="form-check-label" for="users_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="users[]" id="users_delete" value="delete" @if(in_array('delete', $users)) checked @endif>
                                                                    <label class="form-check-label" for="users_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" > User Role</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $userRole = explode(',', @$permission->user_role);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_role[]" id="user_role" value="view" @if(in_array('view', $userRole)) checked @endif>
                                                                    <label class="form-check-label" for="user_role">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_role[]" id="user_role_add" value="add" @if(in_array('add', $userRole)) checked @endif>
                                                                    <label class="form-check-label" for="user_role_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_role[]" id="user_role_edit" value="edit" @if(in_array('edit', $userRole)) checked @endif>
                                                                    <label class="form-check-label" for="user_role_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_role[]" id="user_role_delete" value="delete" @if(in_array('delete', $userRole)) checked @endif>
                                                                    <label class="form-check-label" for="user_role_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_role[]" id="user_role_permissions" value="permissions" @if(in_array('permissions', $userRole)) checked @endif>
                                                                    <label class="form-check-label" for="user_role_permissions">
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
                                                        <h3 class="title mt-5 text-white" > Seller</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $sellers = explode(',', @$permission->sellers);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sellers[]" id="sellers" value="view" @if(in_array('view', $sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sellers">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sellers[]" id="sellers_add" value="add" @if(in_array('add', $sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sellers_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sellers[]" id="sellers_edit" value="edit" @if(in_array('edit', $sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sellers_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sellers[]" id="sellers_status" value="status" @if(in_array('status', $sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sellers_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sellers[]" id="sellers_delete" value="delete" @if(in_array('delete', $sellers)) checked @endif>
                                                                    <label class="form-check-label" for="sellers_delete">
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
                                                        <h3 class="title mt-5 text-white" > Logistic Companies</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $logistic_companies = explode(',', @$permission->logistic_companies);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="logistic_companies[]" id="logistic_companies" value="view" @if(in_array('view', $logistic_companies)) checked @endif>
                                                                    <label class="form-check-label" for="logistic_companies">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="logistic_companies[]" id="logistic_companies_add" value="add" @if(in_array('add', $logistic_companies)) checked @endif>
                                                                    <label class="form-check-label" for="logistic_companies_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="logistic_companies[]" id="logistic_companies_edit" value="edit" @if(in_array('edit', $logistic_companies)) checked @endif>
                                                                    <label class="form-check-label" for="logistic_companies_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="logistic_companies[]" id="logistic_companies_status" value="status" @if(in_array('status', $logistic_companies)) checked @endif>
                                                                    <label class="form-check-label" for="logistic_companies_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="logistic_companies[]" id="logistic_companies_delete" value="delete" @if(in_array('delete', $logistic_companies)) checked @endif>
                                                                    <label class="form-check-label" for="logistic_companies_delete">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" >Categories</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $categories = explode(',', @$permission->categories);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="categories[]" id="categories" value="view" @if(in_array('view', $categories)) checked @endif>
                                                                    <label class="form-check-label" for="categories">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="categories[]" id="categories_add" value="add" @if(in_array('add', $categories)) checked @endif>
                                                                    <label class="form-check-label" for="categories_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="categories[]" id="categories_edit" value="edit" @if(in_array('edit', $categories)) checked @endif>
                                                                    <label class="form-check-label" for="categories_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="categories[]" id="categories_status" value="status" @if(in_array('status', $categories)) checked @endif>
                                                                    <label class="form-check-label" for="categories_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="categories[]" id="categories_delete" value="delete" @if(in_array('delete', $categories)) checked @endif>
                                                                    <label class="form-check-label" for="categories_delete">
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
                                                        <h3 class="title mt-5 text-white" > Sub Categories</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $sub_categories = explode(',', @$permission->sub_categories);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_categories[]" id="sub_categories" value="view" @if(in_array('view', $sub_categories)) checked @endif>
                                                                    <label class="form-check-label" for="sub_categories">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_categories[]" id="sub_categories_add" value="add" @if(in_array('add', $sub_categories)) checked @endif>
                                                                    <label class="form-check-label" for="sub_categories_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_categories[]" id="sub_categories_edit" value="edit" @if(in_array('edit', $sub_categories)) checked @endif>
                                                                    <label class="form-check-label" for="sub_categories_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_categories[]" id="sub_categories_status" value="status" @if(in_array('status', $sub_categories)) checked @endif>
                                                                    <label class="form-check-label" for="sub_categories_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="sub_categories[]" id="sub_categories_delete" value="delete" @if(in_array('delete', $sub_categories)) checked @endif>
                                                                    <label class="form-check-label" for="sub_categories_delete">
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
                                                        <h3 class="title mt-5 text-white" >Products</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $products = explode(',', @$permission->products);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products[]" id="products" value="view" @if(in_array('view', $products)) checked @endif>
                                                                    <label class="form-check-label" for="products">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products[]" id="products_add" value="add" @if(in_array('add', $products)) checked @endif>
                                                                    <label class="form-check-label" for="products_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products[]" id="products_edit" value="edit" @if(in_array('edit', $products)) checked @endif>
                                                                    <label class="form-check-label" for="products_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products[]" id="products_status" value="status" @if(in_array('status', $products)) checked @endif>
                                                                    <label class="form-check-label" for="products_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="products[]" id="products_delete" value="delete" @if(in_array('delete', $products)) checked @endif>
                                                                    <label class="form-check-label" for="products_delete">
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
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="orders" value="view" @if(in_array('view', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="orders">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="orders_rto" value="rto" @if(in_array('rto', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="orders_rto">
                                                                        RTO
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
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="orders[]" id="orders_status" value="status" @if(in_array('status', $orders)) checked @endif>
                                                                    <label class="form-check-label" for="orders_status">
                                                                        Status
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                               
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                 
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-lg-6 mt-5">
                                                <div class="card card-bx">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" >Locations</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                $locations = explode(',', @$permission->locations);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="locations[]" id="locations" value="view" @if(in_array('view', $locations)) checked @endif>
                                                                    <label class="form-check-label" for="locations">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="locations[]" id="locations_add" value="add" @if(in_array('add', $locations)) checked @endif>
                                                                    <label class="form-check-label" for="locations_add">
                                                                        Add
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="locations[]" id="locations_edit" value="edit" @if(in_array('edit', $locations)) checked @endif>
                                                                    <label class="form-check-label" for="locations_edit">
                                                                        Edit
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="locations[]" id="locations_delete" value="delete" @if(in_array('delete', $locations)) checked @endif>
                                                                    <label class="form-check-label" for="locations_delete">
                                                                        Delete
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
                                                        <h3 class="title mt-5 text-white" > User Log</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                @$userLogs = @$permission->user_logs;
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="user_log" id="user_log" value="view" @if(@$userLogs == 'view') checked @endif>
                                                                    <label class="form-check-label" for="user_log">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check"></div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="card card-bx mt-5">
                                                    <div class="card-header" style="min-height: 56px !important;background-color: #096cff;">
                                                        <h3 class="title mt-5 text-white" >Payments</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @php
                                                                @$payments = @$permission->payments;
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="payments" id="payments" value="view" @if(@$payments == 'view') checked @endif>
                                                                    <label class="form-check-label" for="payments">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check"></div>
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
                                                                $setting = explode(',', @$permission->settings);
                                                            @endphp
                                                            <div class="col-6 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="setting[]" id="setting" value="profile" @if(in_array('profile', $setting)) checked @endif>
                                                                    <label class="form-check-label" for="setting">
                                                                        Profile
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="setting[]" id="smtp" value="smtp" @if(in_array('smtp', $setting)) checked @endif>
                                                                    <label class="form-check-label" for="smtp">
                                                                        SMTP
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
                url: '{{route('update_permission')}}',
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (dataResult) {
                    document.getElementById('btn').disabled = false;
                    if (dataResult == 1) {
                        toastr.success('Updated Successfully.');
                        setTimeout(function () {
                            window.location.href = "{{route('role')}}";
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

