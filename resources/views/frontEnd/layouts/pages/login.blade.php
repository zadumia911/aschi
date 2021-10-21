@extends('frontEnd.layouts.master')
@section('title','Login')
@section('content')
<!-- Breadcrumb -->
        <div class="breadcrumbs" style="background:#db0022;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="bread-inner">
                            <!-- Bread Menu -->
                            <div class="bread-menu">
                                <ul>
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="#">Log In</a></li>
                                </ul>
                            </div>
                            <!-- Bread Title -->
                            <!--<div class="bread-title"><h2>Merchant Log In </h2></div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / End Breadcrumb -->
        
        <!-- Contact Us -->
        <section class="contact-us section-space">
            <div class="container pt-5 pb-3 mt-xl-5 mt-3 px-lg-3 px-md-0">
    <div class="col-12 pt-4 px-xl-5 px-4">
        <div class="row mx-xl-4 rl-panel">
            <div class="col-6 bg-rl">
                <div class="pt-5 pl-lg-5 pl-4 mt-5" style="background-color: #e95f06">
                    <h4 class="text-white font-regular">Welcome to</h4>
                    <h4 class="text-white font-regular">Steadfast Courier</h4>
                    <div class="pt-5 mt-4"><img src="{{asset('public/frontEnd/images/rl-img.png')}}" alt="image" class="img-fluid" /></div>
                </div>
            </div>
            <div class="col-6 pr-md-0 pr-lg-3" >
                <div class="px-lg-5 px-md-0 pt-5 mt-3">
                    <div class="w-100 login-panel">
                        <div class="py-4 px-3"><h3 class="text-left text-color-13 font-h-md-23 font-regular">Log in to acess</h3></div>
                        <form method="POST" action="{{url('merchant/login')}}">
                            @csrf
                            <div class="form-group px-2 mx-2 mb-4"><input type="email" name="email" placeholder="Email" value="" required="required" autofocus="autofocus" class="form-control pl-3 arroba mb-1 h-auto bg-transparent" /></div>
                            <div class="form-group px-2 mx-2 mb-3"><input type="password" name="password" placeholder="Password" required="required" class="form-control pl-3 padlock mb-1 h-auto bg-transparent" /></div>
                            <div class="form-group d-flex flex-wrap justify-content-between px-2 mx-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" id="remembercheck" class="custom-control-input" /> <label for="remembercheck" class="custom-control-label custom-control-line-height font-14">Remember me</label>
                                </div>
                                <div><a href="https://steadfast.com.bd/password/reset" class="font-14">Forgot Password?</a></div>
                            </div>
                            <div class="px-2 mx-2 pb-3 pt-2"><button type="submit" class="btn btn-block font-regular bg-color-lightseagreen text-white font-20 py-2">LOGIN</button></div>
                            <div class="px-3 mt-1">
                                <div class="w-100 text-left font-14 text-dark pt-2">Don’t have an account? <a href="https://steadfast.com.bd/register" class="text-red">Register</a></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <!-- Contact Form -->
                        <div class="contact-form-area m-top-30">
                            <h4>Log In</h4>
                            <form action="{{url('merchant/login')}}" method="post" class="form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12 col-md-6 col-12">
                                        <div class="form-group">
                                            <div class="icon"><i class="fa fa-user"></i></div>
                                            <input type="text" name="phoneNumber" placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group button">
                                            <button type="submit" class="quickTech-btn theme-2">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--/ End contact Form -->
                    </div>
                    
                </div>
            </div>
        </section>  
        <!--/ End Contact Us -->
@endsection