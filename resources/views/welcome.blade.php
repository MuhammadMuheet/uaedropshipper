<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="description" content="Ramcar: leading tax &amp; accounting practice management software. Boost efficiency, automate tasks, and improve client satisfaction." />
    <meta name="keywords" content="Ramcar, Tax,leading tax &amp; accounting practice management software. Boost efficiency, automate tasks, and improve client satisfaction." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="UAE Dropshipper" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="UAE Dropshipper" />
    <link rel="canonical" href="/" />
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.png')}}"/>
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
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #fff;">Welcome to UAE Dropshipper</h1>

                    <p class="fw-bold" style="color: #fff;">
                        Your trusted partner for dropshipping success in the UAE and beyond. We provide comprehensive solutions for modern e-commerce entrepreneurs.
                    </p>

                </div>
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain justify-content-center bgi-position-y-bottom min-h-100px min-h-lg-350px" >
                    <div class="d-flex align-items-center">
                        <a href="https://www.linkedin.com/company/pitgpk/" class="symbol symbol-circle symbol-45px w-45px bg-light me-3">
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
                <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <a href="/" class="py-9 mb-5">
                                <img alt="Logo" src="{{asset('logo.png')}}" class="h-60px" />
                            </a>
                        </div>
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Sign In to Your Account</h1>
                            <div class="text-gray-400 fw-bold fs-4">New Here?
                                <a href="{{route('user_register')}}" class="link-primary fw-bolder">Create an Account</a></div>
                        </div>

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">

                                <strong> {{ session('info') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"/>

                        </div>
                        <div class="fv-row mb-10">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">Forgot Password ?</a>
                            </div>
                            <input class="form-control form-control-lg form-control-solid" type="password" name="password"  id="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5" >
                                <span class="indicator-label">Login</span>
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
@error('email')
<script>
    notie.alert({type: 'error', text: '{{ $message }}', time: 4});
</script>
@enderror
@error('password')
<script>
    notie.alert({type: 'error', text: '{{ $message }}', time: 4});
</script>
@enderror
</body>
</html>
