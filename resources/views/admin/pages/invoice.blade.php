          @extends('admin.layouts.app')
@section('title','Invoice')
@section('content')
    @php
        use App\Helpers\ActivityLogger;
        use Carbon\Carbon;
            if (!ActivityLogger::hasPermission('payments','view')){
               abort(403, 'Unauthorized action.');
        }
    @endphp          
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Payments</h1>
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">All</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Invoice</li>
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
<div class="card">     
    <!--begin::Body-->
    <div class="card-body p-lg-20">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
    <div class="mt-n1">                                    
        <!--begin::Top-->
        <div class="d-flex flex-stack pb-10">
            <!--begin::Logo-->
            <a href="#">
                <img alt="Logo" style="width: 200px;"  src="{{asset('logo.png')}}"/>
            </a>
        </div>
        <div class="m-0">            
            <!--end::Label-->  

            <!--begin::Row-->
            <div class="row g-5 mb-11">
                <!--end::Col-->
                <div class="col-sm-6">
                    <!--end::Label-->
                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:</div>                
                    <!--end::Label-->  

                    <!--end::Col-->
                    <div class="fw-bold fs-6 text-gray-800">{{ Carbon::parse($paymentData->created_at)->format('d/m/y') }}</div>                
                    <!--end::Col-->  
                </div>                
             
            </div>                
            <!--end::Row-->   

            <!--begin::Row-->
            <div class="row g-5 mb-12">
                <!--end::Col-->
                <div class="col-sm-6">
                    <!--end::Label-->
                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div>                
                    <!--end::Label-->  
@php
     $userData = App\Models\User::where('id',$paymentData->user_id)->first();
 @endphp
                    <!--end::Text-->
                    <div class="fw-bold fs-6 text-gray-800">{{ucfirst($userData->name)}}</div>                
                    <!--end::Text-->  

                     <!--end::Description-->
                     <div class="fw-semibold fs-7 text-gray-600">
                     {{ucfirst($userData->email)}}
                    </div>                
                    <!--end::Description-->  
                </div>                
                <!--end::Col-->  

                <!--end::Col-->
                <div class="col-sm-6">
                    <!--end::Label-->
                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:</div>                
                    <!--end::Label-->  

                    <!--end::Text-->
                    <div class="fw-bold fs-6 text-gray-800">Arrbaab</div>                
                    <!--end::Text-->  

                     <!--end::Description-->
                     <div class="fw-semibold fs-7 text-gray-600">                      
                     admin@arrbaab.com                        
                    </div>                
                    <!--end::Description-->   
                </div>                
                <!--end::Col-->  
            </div>                
            <!--end::Row-->   


            <!--begin::Content-->
            <div class="flex-grow-1">
                <!--begin::Table-->
                <div class="table-responsive border-bottom mb-9">
                    <table class="table mb-3">
                        <thead>
                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                <th class="min-w-175px pb-2">User Type</th>
                                <th class="min-w-70px text-end pb-2">Amount Type</th>
                                <th class="min-w-100px text-end pb-2">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="fw-bold text-gray-700 fs-5 text-end">
                                <td class="text-start pt-6">                                                            
                                 @if($paymentData->user_type == 'seller') <div class='badge bg-primary'>Seller</div> @else <div class='badge bg-dark'>Logistic Company</div> @endif
                                </td>

                                <td class="pt-6">  @if($paymentData->amount_type == 'in')<div class='badge bg-success'>Amount In</div> @else <div class='badge bg-danger'>Amount Out</div> @endif </td>
                                <td class="pt-6">{{$paymentData->amount}} AED</td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <!--end::Table-->                     

                <!--begin::Container-->
                <div class="d-flex justify-content-end">
                    <!--begin::Section-->
                    <div class="mw-300px">
                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-3">
                            <!--begin::Accountname-->
                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Subtotal:</div>
                            <!--end::Accountname-->

                            <!--begin::Label-->
                            <div class="text-end fw-bold fs-6 text-gray-800">$ 20,600.00</div> 
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-3">
                            <!--begin::Accountname-->
                            <div class="fw-semibold pe-10 text-gray-600 fs-7">VAT 0%</div>
                            <!--end::Accountname-->

                            <!--begin::Label-->
                            <div class="text-end fw-bold fs-6 text-gray-800">0.00</div> 
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-3">
                            <!--begin::Accountnumber-->
                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Subtotal + VAT</div>
                            <!--end::Accountnumber-->

                            <!--begin::Number-->
                            <div class="text-end fw-bold fs-6 text-gray-800">$ 20,600.00</div> 
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex flex-stack">
                            <!--begin::Code-->
                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Total</div>
                            <!--end::Code-->

                            <!--begin::Label-->
                            <div class="text-end fw-bold fs-6 text-gray-800">$ 20,600.00</div> 
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->
                    </div>   
                    <!--end::Section-->                        
                </div>   
                <!--end::Container-->                 
            </div>
            <!--end::Content-->          
        </div>
        <!--end::Wrapper-->           
    </div>     
    <!--end::Invoice 2 content-->         
            </div>  
            <!--end::Content-->

            <!--begin::Sidebar-->
            <div class="m-0">
                <!--begin::Invoice 2 sidebar-->
<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
    <!--begin::Labels-->
    <div class="mb-8">       
        <span class="badge badge-light-success me-2">Approved</span> 
      
        <span class="badge badge-light-warning">Pending Payment</span>          
    </div>                
    <!--end::Labels-->   
    
    <!--begin::Title-->
    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">PAYMENT DETAILS</h6>
    <!--end::Title-->   

    <!--begin::Item-->
    <div class="mb-6">       
        <div class="fw-semibold text-gray-600 fs-7">Paypal:</div> 
      
        <div class="fw-bold text-gray-800 fs-6">codelabpay@codelab.co</div>          
    </div>                
    <!--end::Item-->   

    <!--begin::Item-->
    <div class="mb-6">       
        <div class="fw-semibold text-gray-600 fs-7">Account:</div> 
      
        <div class="fw-bold text-gray-800 fs-6">
            Nl24IBAN34553477847370033 <br/>
            AMB NLANBZTC
        </div>          
    </div>                
    <!--end::Item-->  

    <!--begin::Item-->
    <div class="mb-15">     
        <div class="fw-semibold text-gray-600 fs-7">Payment Term:</div>

        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
            14 days
            
            <span class="fs-7 text-danger d-flex align-items-center">
                <span class="bullet bullet-dot bg-danger mx-2"></span> 
                
                Due in 7 days
            </span>
        </div>                  
    </div>                
    <!--end::Item-->  

    <!--begin::Title-->
    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">PROJECT OVERVIEW</h6>
    <!--end::Title-->   

    <!--begin::Item-->
    <div class="mb-6">     
        <div class="fw-semibold text-gray-600 fs-7">Project Name</div>

        <div class="fw-bold fs-6 text-gray-800">
            SaaS App Quickstarter
            
            <a href="#" class="link-primary ps-1">View Project</a href="#">
        </div>                  
    </div>                
    <!--end::Item-->  

     <!--begin::Item-->
     <div class="mb-6">       
        <div class="fw-semibold text-gray-600 fs-7">Completed By:</div> 
      
        <div class="fw-bold text-gray-800 fs-6">Mr. Dewonte Paul</div>          
    </div>                
    <!--end::Item-->  

    <!--begin::Item-->
    <div class="m-0">     
        <div class="fw-semibold text-gray-600 fs-7">Time Spent:</div>

        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
            230 Hours
            
            <span class="fs-7 text-success d-flex align-items-center">
                <span class="bullet bullet-dot bg-success mx-2"></span> 
                
                35$/h Rate
            </span>
        </div>                  
    </div>                

</div>                               </div>  
    
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