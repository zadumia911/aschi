@extends('frontEnd.layouts.master')
@section('title','Register')
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
                            <li><a href="#"> Sign Up</a></li>
                        </ul>
                    </div>
                    <!-- Bread Title -->
                    <!--<div class="bread-title"><h2>Merchant Sign Up</h2></div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / End Breadcrumb -->
<!-- Contact Us -->
<section class="contact-us section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <!-- Contact Form -->
                <div class="contact-form-area m-top-30">
                    <h4> Sign Up</h4>
                   
                <form action="{{url('auth/merchant/register')}}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-briefcase"></i></div>
                                    <input type="text" name="companyName" placeholder="Name of Business " value="{{old('companyName')}}">
                                     @if ($errors->has('companyName'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('companyName') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-user"></i></div>
                                    <input type="text" name="firstName" placeholder="Your Name " value="{{old('firstName')}}">
                                     @if ($errors->has('firstName'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('firstName') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-mobile"></i></div>
                                    <input type="text" name="phoneNumber" placeholder="Phone" value="{{old('phoneNumber')}}">
                                     @if ($errors->has('phoneNumber'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('phoneNumber') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-envelope"></i></div>
                                    <input type="email" name="emailAddress" placeholder="Email"  value="{{old('emailAddress')}}">
                                     @if ($errors->has('emailAddress'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('emailAddress') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-map-marker"></i></div>
                                    <input type="text" name="pickLocation" placeholder="Pickup Address " value="{{old('pickLocation')}}">
                                     @if ($errors->has('pickLocation'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('pickLocation') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-link"></i></div>
                                    <input type="text" name="socialLink" placeholder="Please Enter Business URL " value="{{old('socialLink')}}">
                                     @if ($errors->has('socialLink'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('socialLink') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="icon"><i class="fa fa-user-circle-o"></i></div>
                                    <input type="text" name="username" placeholder="Username" value="{{old('username')}}">
                                     @if ($errors->has('username'))
    	                              <span class="invalid-feedback">
    	                                <strong>{{ $errors->first('username') }}</strong>
    	                              </span>
    	                             @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group button">
                                    <button type="submit" class="quickTech-btn theme-2">Sign Up</button>
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
