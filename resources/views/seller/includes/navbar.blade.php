@php
    use App\Helpers\ActivityLogger;
@endphp
<div id="kt_header" style="" class="header align-items-stretch">
    <!--begin::Container-->
    <div class="container-xxl d-flex align-items-stretch justify-content-between">
        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
            <a href="/">
                <img alt="Logo" src="{{asset('logo.png')}}" class="h-20px h-lg-30px" />
            </a>
        </div>
        <!--end::Aside mobile toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="/login" class="d-lg-none">
                <img src="{{asset('logo.png')}}" alt="avatar" class="h-30px">
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <!--begin::Menu wrapper-->
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('seller_dashboard')) active @endif "
                               href="{{route('seller_dashboard')}}">
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
                                            </svg>
                                            </span>
											</span>
										</span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                        @if(ActivityLogger::hasSellerPermission('sub_sellers','view') || ActivityLogger::hasSellerPermission('seller_role','view'))
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1 @if(Request::url() == route('all_sub_sellers')||Request::url() == route('seller_role')) hover show @endif">
												<span class="menu-link py-3">
													<span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <span class="svg-icon svg-icon-muted svg-icon-1x">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </span>
                            </span>
                        </span>
                        <span class="menu-title">Sub Sellers</span>
                        <span class="menu-arrow"></span>
												</span>
                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                @if(ActivityLogger::hasSellerPermission('sub_sellers','view'))
                                <div class="menu-item">
                                    <a class="menu-link py-3 @if(Request::url() == route('all_sub_sellers')) active @endif " href="{{route('all_sub_sellers')}}">
															<span class="menu-bullet">
																<span class="bullet bullet-dot"></span>
															</span>
                                        <span class="menu-title">All</span>
                                    </a>
                                </div>
                                @endif
                                    @if(ActivityLogger::hasSellerPermission('seller_role','view'))
                                <div class="menu-item">
                                    <a class="menu-link py-3 @if(Request::url() == route('seller_role')) active @endif " href="{{route('seller_role')}}">
															<span class="menu-bullet">
																<span class="bullet bullet-dot"></span>
															</span>
                                        <span class="menu-title">Role</span>
                                    </a>
                                </div>
                                    @endif
                            </div>
                        </div>
                        @endif
                        @if(ActivityLogger::hasSellerPermission('products','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('products')) active @endif "
                               href="{{route('products')}}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                            </span>
											</span>
										</span>
                                <span class="menu-title">Products</span>
                            </a>
                        </div>
                        @endif
                        @if(ActivityLogger::hasSellerPermission('cart','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_cart')) active @endif "
                               href="{{route('all_cart')}}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
                                          <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>

                                            </span>
											</span>
										</span>
                                <span class="menu-title">Cart</span>
                            </a>
                        </div>
                        @endif
                        @if(ActivityLogger::hasSellerPermission('orders','checkout'))
                        <div class="menu-item">
                        
                            <a class="menu-link @if(Request::url() == route('checkout')) active @endif "
                               href="{{ route('checkout') }}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
                                          <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>

                                            </span>
											</span>
										</span>
                                <span class="menu-title">Checkout</span>
                            </a>
                        </div>
                        @endif
                        @if(ActivityLogger::hasSellerPermission('orders','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_orders')) active @endif "
                               href="{{route('all_orders')}}">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
                                            <span class="svg-icon svg-icon-muted svg-icon-1x">
                                          <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>

                                            </span>
											</span>
										</span>
                                <span class="menu-title">Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(ActivityLogger::hasSellerPermission('payments','view'))
                        <div class="menu-item">
                            <a class="menu-link @if(Request::url() == route('all_seller_payments')) active @endif "
                               href="{{route('all_seller_payments')}}">
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
                        @if(ActivityLogger::hasSellerPermission('settings','profile'))
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1 @if(Request::url() == route('seller_profile')||Request::url() == route('seller_security')) hover show @endif">
												<span class="menu-link py-3">
													<span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <span class="svg-icon svg-icon-muted svg-icon-1x">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                         stroke-width="2" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round" class="css-i6dzq1">
                                        <circle cx="12" cy="12" r="3">
                                        </circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                        </path>
                                    </svg>
                                </span>
                            </span>
                        </span>
                        <span class="menu-title">Settings</span>
                        <span class="menu-arrow"></span>
												</span>
                            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                <div class="menu-item">
                                    <a class="menu-link py-3 @if(Request::url() == route('seller_profile')) active @endif " href="{{route('seller_profile')}}">
															<span class="menu-bullet">
																<span class="bullet bullet-dot"></span>
															</span>
                                        <span class="menu-title">Profile</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link py-3 @if(Request::url() == route('seller_security')) active @endif " href="{{route('seller_security')}}">
															<span class="menu-bullet">
																<span class="bullet bullet-dot"></span>
															</span>
                                        <span class="menu-title">Security</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::Navbar-->
            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">

                <div class="d-flex align-items-center ms-1 ms-lg-3" style="display: none !important;">
                    <!--begin::Drawer toggle-->
                    <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" id="kt_activities_toggle">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                        <span class="svg-icon svg-icon-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect x="8" y="9" width="3" height="10" rx="1.5" fill="currentColor" />
													<rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="currentColor" />
													<rect x="18" y="11" width="3" height="8" rx="1.5" fill="currentColor" />
													<rect x="3" y="13" width="3" height="6" rx="1.5" fill="currentColor" />
												</svg>
											</span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Drawer toggle-->
                </div>
                <!--end::Activities-->
                <!--begin::Notifications-->
                <div class="d-flex align-items-center ms-1 ms-lg-3" style="display: none !important;">
                    <!--begin::Menu- wrapper-->
                    <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
                        <span class="svg-icon svg-icon-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z" fill="currentColor" />
													<path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z" fill="currentColor" />
													<path opacity="0.3" d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z" fill="currentColor" />
													<path opacity="0.3" d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z" fill="currentColor" />
												</svg>
											</span>
                        <!--end::Svg Icon-->
                    </div>

                </div>

                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        @if(!empty(Auth::user()->image))
                            <img src="{{ asset('storage/users/' . Auth::user()->image) }}" alt="avatar" >
                        @else
                            <img src="https://avatar.iran.liara.run/username?username={{ Auth::user()->name }}" alt="avatar" >
                        @endif
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    @if(!empty(Auth::user()->image))
                                        <img src="{{ asset('storage/users/' . Auth::user()->image) }}" alt="avatar" >
                                    @else
                                        <img src="https://avatar.iran.liara.run/username?username={{ Auth::user()->name }}" alt="avatar" >
                                    @endif

                                </div>
                                <div class="d-flex flex-column" style="overflow: auto;">
                                    <div class="fw-bolder d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                        @php
                                            $type =  Illuminate\Support\Facades\Auth::user()->type;
                                       if ($type != 'seller'){
                                             $data = App\Models\SellerRole::where('id', $type)->first();
                                               $role = $data->name;
                                       }else{
                                           $role = 'seller';
                                       }
                                        @endphp
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2 text-uppercase">{{ $role }}</span></div>
                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        @if(ActivityLogger::hasSellerPermission('settings','profile'))
                        <div class="menu-item px-5 my-1">
                            <a href="{{route('seller_profile')}}" class="menu-link px-5">Account Settings</a>
                        </div>
                        @endif
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                          @auth
    <a class="dropdown-item" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Sign Out
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endauth
                        </div>

                    </div>
                </div>
                <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
                    <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                        <!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
                        <span class="svg-icon svg-icon-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor" />
													<path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor" />
												</svg>
											</span>
                        <!--end::Svg Icon-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
