@php
    use App\Helpers\ActivityLogger;
@endphp
<div id="kt_aside" class="real-sidebar aside aside-light aside-hoverable" data-kt-drawer="true"
     data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="/">
            <img src="{{asset('logo.png')}}" width="100" alt="avatar" class="logo" style="    width: 100%;
    height: auto;">
        </a>
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
									<path opacity="0.5"
                                          d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                                          fill="currentColor"/>
									<path
                                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                                        fill="currentColor"/>
								</svg>
							</span>
        </div>
    </div>
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
             data-kt-scroll-offset="0">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link @if(Request::url() == route('admin_dashboard')) active @endif "
                       href="{{route('admin_dashboard')}}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6 7H3C2.4 7 2 6.6 2 6V3C2 2.4 2.4 2 3 2H6C6.6 2 7 2.4 7 3V6C7 6.6 6.6 7 6 7Z"
                                                fill="currentColor"/>
                                            <path opacity="0.3"
                                                  d="M13 7H10C9.4 7 9 6.6 9 6V3C9 2.4 9.4 2 10 2H13C13.6 2 14 2.4 14 3V6C14 6.6 13.6 7 13 7ZM21 6V3C21 2.4 20.6 2 20 2H17C16.4 2 16 2.4 16 3V6C16 6.6 16.4 7 17 7H20C20.6 7 21 6.6 21 6ZM7 13V10C7 9.4 6.6 9 6 9H3C2.4 9 2 9.4 2 10V13C2 13.6 2.4 14 3 14H6C6.6 14 7 13.6 7 13ZM14 13V10C14 9.4 13.6 9 13 9H10C9.4 9 9 9.4 9 10V13C9 13.6 9.4 14 10 14H13C13.6 14 14 13.6 14 13ZM21 13V10C21 9.4 20.6 9 20 9H17C16.4 9 16 9.4 16 10V13C16 13.6 16.4 14 17 14H20C20.6 14 21 13.6 21 13ZM7 20V17C7 16.4 6.6 16 6 16H3C2.4 16 2 16.4 2 17V20C2 20.6 2.4 21 3 21H6C6.6 21 7 20.6 7 20ZM14 20V17C14 16.4 13.6 16 13 16H10C9.4 16 9 16.4 9 17V20C9 20.6 9.4 21 10 21H13C13.6 21 14 20.6 14 20ZM21 20V17C21 16.4 20.6 16 20 16H17C16.4 16 16 16.4 16 17V20C16 20.6 16.4 21 17 21H20C20.6 21 21 20.6 21 20Z"
                                                  fill="currentColor"/>
                                            </svg></span>
                                                <!--end::Svg Icon-->
                                                <!--end::Svg Icon-->
											</span>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                @if(ActivityLogger::hasPermission('users','view') || ActivityLogger::hasPermission('sellers','view') || ActivityLogger::hasPermission('logistic_companies','view') || ActivityLogger::hasPermission('user_role','view') || ActivityLogger::hasPermission('user_logs','view'))
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('all-users')|| Request::url() == route('all_seller_orders_admin',@$id ?? '')|| Request::url() == route('all_sub_seller_orders_admin',@$id ?? '')||Request::url() == route('all-sellers')||Request::url() == route('sub_sellers', @$id ?? '') ||Request::url() == route('all_company_orders_admin', @$id ?? '')||Request::url() == route('all_logistic_companies')||Request::url() == route('all_admin_driver', @$id ?? '')||Request::url() == route('all_driver_admin_orders', @$id ?? '')||Request::url() == route('role')||Request::url() == route('all_user_logs')) hover show @endif">
                                <span class="menu-link">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                                <span class="svg-icon svg-icon-muted svg-icon-1x">
                                                   <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <!--end::Svg Icon-->
										</span>
										<span class="menu-title">Users</span>
										<span class="menu-arrow"></span>
									</span>
                        <div class="menu-sub menu-sub-accordion menu-active-bg">
                            @if(ActivityLogger::hasPermission('users','view') || ActivityLogger::hasPermission('user_role','view'))
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('all-users')|| Request::url() == route('role')) hover show @endif">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Sub Admins</span>
												<span class="menu-arrow"></span>
											</span>
                                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                                        @if(ActivityLogger::hasPermission('users','view'))
                                            <div class="menu-item">
                                                <a class="menu-link @if(Request::url() == route('all-users')) active @endif " href="{{route('all-users')}}">
                                                     			<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                    <span class="menu-title">All</span>
                                                </a>
                                            </div>
                                        @endif
                                        @if(ActivityLogger::hasPermission('user_role','view'))
                                            <div class="menu-item">
                                                <a class="menu-link @if(Request::url() == route('role')) active @endif "
                                                   href="{{route('role')}}">
                                                     			<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                    <span class="menu-title">Roles</span>
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endif
                                @if(ActivityLogger::hasPermission('sellers','view'))
                                    <div class="menu-item">
                                        <a class="menu-link @if(Request::url() == route('all-sellers') ||Request::url() == route('sub_sellers', @$id ?? '')|| Request::url() == route('all_seller_orders_admin',@$id ?? '')|| Request::url() == route('all_sub_seller_orders_admin',@$id ?? '')) active @endif "
                                           href="{{route('all-sellers')}}">
                                                     			<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">Sellers</span>
                                        </a>
                                    </div>
                                @endif
                                @if(ActivityLogger::hasPermission('logistic_companies','view'))
                                    <div class="menu-item">
                                        <a class="menu-link @if(Request::url() == route('all_logistic_companies')||Request::url() == route('all_admin_driver', @$id ?? '') ||Request::url() == route('all_company_orders_admin', @$id ?? '')||Request::url() == route('all_driver_admin_orders', @$id ?? '')) active @endif "
                                           href="{{route('all_logistic_companies')}}">
                                                     			<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">Logistic Companies</span>
                                        </a>
                                    </div>
                                @endif
                            @if(ActivityLogger::hasPermission('user_logs','view'))
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::url() == route('all_user_logs')) active @endif "
                                       href="{{route('all_user_logs')}}">
                                                     			<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                        <span class="menu-title">Logs</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                @if(ActivityLogger::hasPermission('products','view') ||ActivityLogger::hasPermission('products','add') || ActivityLogger::hasPermission('categories','view') || ActivityLogger::hasPermission('sub_categories','view'))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('all_products')||Request::url() == route('all_product_stocks')|| Request::url() == route('update_product',@$id ?? '') ||Request::url() == route('all_categories') || Request::url() == route('all_sub_categories')|| Request::url() == route('add_product')) hover show @endif">
                <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <span class="svg-icon svg-icon-muted svg-icon-1x">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                </span>
                            </span>
                        </span>
                        <span class="menu-title">Products</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        @if(ActivityLogger::hasPermission('categories','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_categories')) active @endif "
                               href="{{route('all_categories')}}">
                                                 <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                <span class="menu-title">Categories</span>
                            </a>
                        </div>
                        @endif
                            @if(ActivityLogger::hasPermission('sub_categories','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_sub_categories')) active @endif "
                               href="{{route('all_sub_categories')}}">
                                                 <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                <span class="menu-title">Sub Categories</span>
                            </a>
                        </div>
                            @endif
                            @if(ActivityLogger::hasPermission('products','add'))
                            <div class="menu-item">
                                <a class="menu-link @if(Request::url() == route('add_product')) active @endif "
                                   href="{{route('add_product')}}">
                                                     <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                    <span class="menu-title">Add Product</span>
                                </a>
                            </div>
                                @endif
                            @if(ActivityLogger::hasPermission('products','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_products') || Request::url() == route('update_product',@$id ?? '')) active @endif "
                               href="{{route('all_products')}}">
                                                 <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                <span class="menu-title">All Products</span>
                            </a>
                        </div>
                            @endif
                            @if(ActivityLogger::hasPermission('products','view'))
                            <div class="menu-item">
                                <a class="menu-link @if(Request::url() == route('all_product_stocks')) active @endif "
                                   href="{{route('all_product_stocks')}}">
                                                     <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                    <span class="menu-title">All Product Stocks</span>
                                </a>
                            </div>
                                @endif
                          
                    </div>
                </div>
                @endif
@if(ActivityLogger::hasPermission('orders','view'))
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('all_admin_orders') || Request::url() == route('all_rto_orders')) hover show @endif">
                <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <span class="svg-icon svg-icon-muted svg-icon-1x">
                                                                   <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                </span>
                            </span>
                        </span>
                        <span class="menu-title">Orders</span>
                        <span class="menu-arrow"></span>
                    </span>
                        <div class="menu-sub menu-sub-accordion menu-active-bg">
                            @if(ActivityLogger::hasPermission('orders','view'))
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::url() == route('all_admin_orders')) active @endif "
                                       href="{{route('all_admin_orders')}}">
                                                 <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">All</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::url() == route('all_rto_orders')) active @endif "
                                       href="{{route('all_rto_orders')}}">
                                                 <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">RTO</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                @endif
                @if(ActivityLogger::hasPermission('payments','view'))
                <div class="menu-item">
                    <a class="menu-link @if(Request::url() == route('all_admin_payments')) active @endif "
                       href="{{route('all_admin_payments')}}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                        </span>
											</span>
										</span>
                        <span class="menu-title">Payments</span>
                    </a>
                </div>
                @endif
    @if(ActivityLogger::hasPermission('locations','view'))
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('all_states') || Request::url() == route('all_areas')) hover show @endif">
    <span class="menu-link">
            <span class="menu-icon">
                <span class="svg-icon svg-icon-2">
                    <span class="svg-icon svg-icon-muted svg-icon-1x">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </span>
                </span>
            </span>
            <span class="menu-title">Locations</span>
            <span class="menu-arrow"></span>
        </span>
        <div class="menu-sub menu-sub-accordion menu-active-bg">
            <div class="menu-item">
                <a class="menu-link @if(Request::url() == route('all_states')) active @endif "
                   href="{{route('all_states')}}">
                                     <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                    <span class="menu-title">State</span>
                </a>
            </div>
        </div>
        <div class="menu-sub menu-sub-accordion menu-active-bg">
            <div class="menu-item">
                <a class="menu-link @if(Request::url() == route('all_areas')) active @endif "
                   href="{{route('all_areas')}}">
                                     <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                    <span class="menu-title">Area</span>
                </a>
            </div>
        </div>
    </div>
    @endif
    {{--                <div class="menu-item">--}}
{{--                    <a class="menu-link @if(Request::url() == route('media.index')) active @endif "--}}
{{--                       href="{{route('media.index')}}">--}}
{{--										<span class="menu-icon">--}}
{{--											<span class="svg-icon svg-icon-2">--}}
{{--                                            <span class="svg-icon svg-icon-muted svg-icon-1x"><svg--}}
{{--                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"--}}
{{--                                                    viewBox="0 0 24 24" fill="none">--}}
{{--                                            <path--}}
{{--                                                d="M6 7H3C2.4 7 2 6.6 2 6V3C2 2.4 2.4 2 3 2H6C6.6 2 7 2.4 7 3V6C7 6.6 6.6 7 6 7Z"--}}
{{--                                                fill="currentColor"/>--}}
{{--                                            <path opacity="0.3"--}}
{{--                                                  d="M13 7H10C9.4 7 9 6.6 9 6V3C9 2.4 9.4 2 10 2H13C13.6 2 14 2.4 14 3V6C14 6.6 13.6 7 13 7ZM21 6V3C21 2.4 20.6 2 20 2H17C16.4 2 16 2.4 16 3V6C16 6.6 16.4 7 17 7H20C20.6 7 21 6.6 21 6ZM7 13V10C7 9.4 6.6 9 6 9H3C2.4 9 2 9.4 2 10V13C2 13.6 2.4 14 3 14H6C6.6 14 7 13.6 7 13ZM14 13V10C14 9.4 13.6 9 13 9H10C9.4 9 9 9.4 9 10V13C9 13.6 9.4 14 10 14H13C13.6 14 14 13.6 14 13ZM21 13V10C21 9.4 20.6 9 20 9H17C16.4 9 16 9.4 16 10V13C16 13.6 16.4 14 17 14H20C20.6 14 21 13.6 21 13ZM7 20V17C7 16.4 6.6 16 6 16H3C2.4 16 2 16.4 2 17V20C2 20.6 2.4 21 3 21H6C6.6 21 7 20.6 7 20ZM14 20V17C14 16.4 13.6 16 13 16H10C9.4 16 9 16.4 9 17V20C9 20.6 9.4 21 10 21H13C13.6 21 14 20.6 14 20ZM21 20V17C21 16.4 20.6 16 20 16H17C16.4 16 16 16.4 16 17V20C16 20.6 16.4 21 17 21H20C20.6 21 21 20.6 21 20Z"--}}
{{--                                                  fill="currentColor"/>--}}
{{--                                            </svg></span>--}}
{{--                                                <!--end::Svg Icon-->--}}
{{--                                                <!--end::Svg Icon-->--}}
{{--											</span>--}}
{{--                                            <!--end::Svg Icon-->--}}
{{--										</span>--}}
{{--                        <span class="menu-title">Media</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
    @if(ActivityLogger::hasPermission('settings','profile') || ActivityLogger::hasPermission('settings','smtp'))
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(Request::url() == route('profile')||Request::url() == route('security')||Request::url() == route('smtp')) hover show @endif">
    <span class="menu-link">
            <span class="menu-icon">
                <span class="svg-icon svg-icon-2">
                    <span class="svg-icon svg-icon-muted svg-icon-1x"><svg
                            viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="2" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" class="css-i6dzq1"><circle cx="12"
                                                                               cy="12"
                                                                               r="3"></circle><path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></span>
                </span>
            </span>
            <span class="menu-title">Settings</span>
            <span class="menu-arrow"></span>
        </span>
            <div class="menu-sub menu-sub-accordion menu-active-bg">
                @if(ActivityLogger::hasPermission('settings','profile'))
                    <div class="menu-item">
                        <a class="menu-link @if(Request::url() == route('profile')||Request::url() == route('security')) active @endif "
                           href="{{route('profile')}}">
                                     <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Profile</span>
                        </a>
                    </div>
                @endif
                @if(ActivityLogger::hasPermission('settings','smtp'))
                    <div class="menu-item">
                        <a class="menu-link @if(Request::url() == route('smtp')||Request::url() == route('smtp')) active @endif "
                           href="{{route('smtp')}}">
                                     <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">SMTP</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
</div>
</div>
</div>
