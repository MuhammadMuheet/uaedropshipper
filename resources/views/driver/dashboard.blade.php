@extends('driver.layouts.app')
@section('title','Dashboard')
@section('content')
<style>
    .nav-link {
        pointer-events: none; /* Disable pointer events to prevent hover effects */
    }
    .nav-link:hover {
        background-color: inherit; /* Prevent any background change on hover */
        color: inherit; /* Prevent color change on hover */
    }
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Dashboard
                    <span class="h-20px border-1 border-gray-200 border-start ms-3 mx-2 me-1"></span>
                    <span class="text-muted fs-7 fw-bold mt-2">Driver </span>
                    </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-4 mb-xl-10">
                    <!--begin::Lists Widget 19-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Heading-->
                        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-color:#388707">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column text-white pt-15">
                                <span class="fw-bolder fs-2x mb-3">All Orders</span>

                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar pt-5">
                                <!--begin::Menu-->
                                <button class="btn btn-sm btn-icon btn-active-color-primary btn-color-white bg-white bg-opacity-25 bg-hover-opacity-100 bg-hover-white bg-active-opacity-25 w-20px h-20px" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen052.svg-->
                                    <span class="svg-icon svg-icon-4">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect x="10" y="10" width="4" height="4" rx="2" fill="currentColor"></rect>
																<rect x="17" y="10" width="4" height="4" rx="2" fill="currentColor"></rect>
																<rect x="3" y="10" width="4" height="4" rx="2" fill="currentColor"></rect>
															</svg>
														</span>
                                    <!--end::Svg Icon-->
                                </button>
                                <!--begin::Menu 2-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-6 text-dark fw-bolder px-3 py-4">Quick Actions</div>
                                    </div>
                                    <div class="separator mb-3 opacity-75"></div>

                                    <div class="menu-item px-3">
                                        <a href="{{route('all_driver_orders')}}" class="menu-link px-3">All</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{route('shipped_driver_orders')}}" class="menu-link px-3">Shipped</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{route('delivered_driver_orders')}}" class="menu-link px-3">Delivered</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{route('canceled_driver_orders')}}" class="menu-link px-3">Canceled</a>
                                    </div>

                                    <div class="separator mt-3 opacity-75"></div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body mt-n20">
                            <div class="mt-n20 position-relative">
                                <div class="row g-3 g-lg-6">
                                    <div class="col-6">
                                        <a href="{{route('all_driver_orders')}}">
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">

                                                <div class="symbol symbol-30px me-5 mb-8">
																	<span class="symbol-label">
																		<span class="svg-icon svg-icon-3x svg-icon-dark mb-2">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>

														</span>
																	</span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">{{$all_orders}}</span>
                                                    <span class="text-gray-500 fw-bold fs-6">All</span>
                                                </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('shipped_driver_orders')}}">
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">


                                                <div class="symbol symbol-30px me-5 mb-8">
																	<span class="symbol-label">
																		<span class="svg-icon svg-icon-3x svg-icon-primary mb-2">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>														</span>
														</span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">{{$shipped_orders}}</span>
                                                    <span class="text-gray-500 fw-bold fs-6">Shipped</span>
                                                </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('delivered_driver_orders')}}">
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">

                                                <div class="symbol symbol-30px me-5 mb-8">
																	<span class="symbol-label">
																	<span class="svg-icon svg-icon-3x svg-icon-success mb-2">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>														</span>
																	</span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">{{$delivered_orders}}</span>
                                                    <span class="text-gray-500 fw-bold fs-6">Delivered</span>
                                                </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('canceled_driver_orders')}}">
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">

                                                <div class="symbol symbol-30px me-5 mb-8">
																	<span class="symbol-label">
																	<span class="svg-icon svg-icon-3x svg-icon-danger mb-2">
<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>														</span>
																	</span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">{{$canceled_orders}}</span>
                                                    <span class="text-gray-500 fw-bold fs-6">Canceled</span>
                                                </div>
                                        </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="row g-5 g-xl-10 mb-xl-10">
                        <div class="col-12 mb-5 mb-xl-0">
                            <div class="card h-md-100">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">What’s up Today</span>
                                        <span class="text-muted mt-1 fw-bold fs-7">Total {{ $appointments->count() }} Appointments</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-7 px-0">
                                    <ul class="nav nav-stretch nav-pills nav-pills-custom nav-pills-active-custom d-flex justify-content-between mb-8 px-5">
                                        @for ($i = 0; $i < 10; $i++)
                                            @php
                                                $date = now()->addDays($i);
                                                $dayShort = $date->format('D');
                                                $dayNumber = $date->format('d');
                                                $isActive = $i === 0 ? 'active' : '';
                                            @endphp
                                            <li class="nav-item p-0 ms-0">
                                                <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger {{ $isActive }}"
                                                   data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_{{ $i + 1 }}">
                                                    <span class="fs-7 fw-bold">{{ substr($dayShort, 0, 2) }}</span>
                                                    <span class="fs-6 fw-bolder">{{ $dayNumber }}</span>
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                    <div class="tab-content mb-2 px-9">
                                        @for ($i = 0; $i < 10; $i++)
                                            @php
                                                $date = now()->addDays($i)->format('Y-m-d');
                                                $isActive = $i === 0 ? 'show active' : '';
                                                $dateAppointments = $appointments->filter(fn($a) => $a->date->format('Y-m-d') === $date);
                                            @endphp
                                            <div class="tab-pane fade {{ $isActive }}" id="kt_timeline_widget_3_tab_content_{{ $i + 1 }}">
                                                @if ($dateAppointments->isEmpty())
                                                    <p>No appointments for this day.</p>
                                                @else
                                                    @foreach ($dateAppointments as $appointment)
                                                        <div class="d-flex align-items-center mb-6">
                                                            <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                                                            <div class="flex-grow-1 me-5">
                                                                <div class="text-gray-800 fw-bold fs-2">
                                                                    {{ $appointment->date->format('h:i') }}
                                                                    <span class="text-gray-400 fw-bold fs-7">{{ $appointment->date->format('A') }}</span>
                                                                </div>
                                                                <div class="text-gray-700 fw-bold fs-6">{{ $appointment->title }}</div>
                                                                <div class="text-gray-400 fw-bold fs-7">Lead by
                                                                    <a href="#" class="text-primary opacity-75-hover fw-bold">{{ $users->first()->company_name ?? 'Unknown' }}</a>
                                                                </div>
                                                            </div>
                                                            <a href="/meet/{{ $appointment->jitsi_link }}" class="btn btn-sm btn-light">Join Meeting</a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endfor
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
