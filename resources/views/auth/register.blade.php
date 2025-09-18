<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>Register</title>
    <meta charset="utf-8" />
    <meta name="description" content="Ramcar: leading tax &amp; accounting practice management software. Boost efficiency, automate tasks, and improve client satisfaction." />
    <meta name="keywords" content="Ramcar, Tax,leading tax &amp; accounting practice management software. Boost efficiency, automate tasks, and improve client satisfaction." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Techneketax - leading tax &amp; accounting practice management software" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="Techneketax | leading tax &amp; accounting practice management software" />
    <link rel="canonical" href="/" />
    <link rel="icon" type="image/x-icon" href="{{asset('logo.png')}}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('assets/dash/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/dash/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
    <style>
        .invalid-feedback{
            display: block !important;
        }
    </style>
</head>
<body id="kt_body" class="bg-body">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-image: url({{asset('auth.gif')}}); background-position: center;
background-repeat: no-repeat;
background-size: cover; ">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y" style="background-color: rgba(1,1,1,0.7);">
                <!--begin::Content-->
                <div class="d-flex flex-column text-center mt-20 p-10 pt-lg-20">
                    <!--begin::Logo-->
                    <a href="/" class="py-9 mb-5">
                        <img alt="Logo" src="{{asset('logo.png')}}" class="h-100px" />
                    </a>
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #fff;">Welcome to UAE Dropshipper!</h1>

                    <p class="fw-bold" style="color: #fff;">
                        Register to discover exclusive properties, manage your favorites, and stay updated on the latest real estate opportunities.</p>

                </div>
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain justify-content-center bgi-position-y-bottom min-h-100px min-h-lg-350px" >
                    <div class="d-flex align-items-center">
                        <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                            <img alt="Logo" src="{{asset('assets/dash/assets/media/icons/linkedin.svg')}}" class="p-4">
                        </a>
                        <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                            <img alt="Logo" src="{{asset('assets/dash/assets/media/icons/twitter.svg')}}" class="p-4">
                        </a>
                        <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                            <img alt="Logo" src="{{asset('assets/dash/assets/media/icons/facebook.svg')}}" class="p-4">
                        </a>
                        <a href="#" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
                            <img alt="Logo" src="{{asset('assets/dash/assets/media/icons/github.svg')}}" class="p-4">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-600px p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" method="POST" id="InsertForm" >
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <a href="/" class="py-9 mb-5">
                                <img alt="Logo" src="{{asset('logo.png')}}" class="h-60px" />
                            </a>
                        </div>
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Register to Your Account</h1>
                            <div class="text-gray-400 fw-bold fs-4">Already have an account?
                                <a href="{{route('login')}}" class="link-primary fw-bolder">Sign in here</a></div>
                        </div>

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">

                                <strong> {{ session('info') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="fv-row row mb-10">
                            <div class="col-xl-6">
                            <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus/>

                        </div>
                            <div class="col-xl-6">
                            <label class="form-label fs-6 fw-bolder text-dark">Store Name</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" name="store_name" value="{{ old('store_name') }}" required autocomplete="store_name" />
                        </div>
                        </div>

                        <div class="row fv-row mb-10">
                            <div class="col-xl-6">
                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"/>
                        </div>
                            <div class="col-xl-6">
                                <label class="form-label fs-6 fw-bolder text-dark">Monthly Average Orders</label>
                                <select class="form-select form-select-lg form-select-solid" name="average_orders" required>
                                    <option value="">Select</option>
                                    <option value="50-500" >50-500</option>
                                    <option value="501-1500" >501-1500</option>
                                    <option value="1501-3000" >1501-3000</option>
                                    <option value="3001-5000" >3001-5000</option>
                                </select>
                            </div>
                        </div>
                        <div class="row fv-row mb-10">
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Whatsapp</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Whatsapp" required>

                            </div>
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Mobile</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile" required>

                            </div>
                        </div>

                        <div class="row fv-row mb-10">
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Dropshipping Experience</label>
                                    <select class="form-select form-select-lg form-select-solid" name="dropshipping_experience" required>
                                        <option value="">Select</option>
                                        <option value="newbie" >Newbie (0-6 Month)</option>
                                        <option value="intermediate" >Intermediate (6-12 Month)</option>
                                        <option value="advanced" >Advanced (1-2 Year)</option>
                                        <option value="expert" >Expert (2+ Year)</option>
                                    </select>
                            </div>
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Current Dropshipping Status</label>
                                    <select class="form-select form-select-lg form-select-solid" name="dropshipping_status" required>
                                        <option value="">Select</option>
                                        <option value="alreadyDropshipping" >Already Dropshipping</option>
                                        <option value="newlyStarting" >Newly Starting</option>
                                    </select>
                            </div>
                        </div>

                        <div class="row fv-row mb-10">
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Bank Name</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="bank" value="{{ old('bank') }}" placeholder="Bank Name" required>

                            </div>
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Account Title</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="ac_title" value="{{ old('ac_title') }}" placeholder="Account Title" required>

                            </div>
                        </div>

                        <div class="row fv-row mb-10">
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">Account Number</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="ac_no" value="{{ old('ac_no') }}" placeholder="Account Number" required>
                            </div>
                            <div class="col-xl-6">
                                    <label class="form-label fs-6 fw-bolder text-dark">IBAN</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="iban" value="{{ old('iban') }}" placeholder="IBAN" maxlength="24">
                            </div>
                        </div>
                        <div class="fv-row row mb-10">
                            <div class="col-xl-6">
                                <label class="form-label fs-6 fw-bolder text-dark">Password</label>
                            <input class="form-control form-control-lg form-control-solid" type="password" name="password" required autocomplete="new-password" id="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            </div>
                            <div class="col-xl-6">
                            <label class="form-label fs-6 fw-bolder text-dark">Confirm Password</label>
                            <input class="form-control form-control-lg form-control-solid" type="password" name="password_confirmation" required autocomplete="new-password"  id="password-confirm" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" id="register_submit" onclick="insert_item()" class="btn btn-lg btn-primary w-100 mb-5" >
                                <span class="indicator-label">Register</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/dash/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/dash/assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('assets/dashboard/src/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://unpkg.com/notie"></script>

<script>
    function insert_item() {
        var form_Data = new FormData(document.getElementById("InsertForm"));
        document.getElementById("register_submit").innerHTML = "Loading";
        document.getElementById('register_submit').disabled = false;
        $.ajax({
            url: "{{route('seller_register')}}",
            type: "POST",
            data: form_Data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (dataResult) {
                console.log(dataResult);
                document.getElementById("register_submit").innerHTML = "Register";
                document.getElementById('register_submit').disabled = false;
                if (dataResult == 1) {
                    notie.alert({type: 'success', text: 'We have received your registration data. Our admin team will review your submission, and your account will be activated upon approval.', time: 4});
                    document.getElementById("InsertForm").reset();
                } else if(dataResult == 2){
                    notie.alert({type: 'error', text: 'Please Fill Required Fields', time: 4});
                } else if(dataResult == 3){
                    notie.alert({type: 'error', text: 'Please Fill Password', time: 4});
                } else if(dataResult == 4){
                    notie.alert({type: 'error', text: 'Please Fill Confirm Password', time: 4});
                } else if(dataResult == 5){
                    notie.alert({type: 'error', text: "Password doesn't match", time: 4});
                } else if(dataResult == 6){
                    notie.alert({type: 'error', text: 'A minimum 8 characters password contains a combination of uppercase and lowercase letter and number.', time: 4});
                }else {
                    toastr.error('Something Went Wrong.');
                }
            }
        });
    }

</script>
</body>
</html>
