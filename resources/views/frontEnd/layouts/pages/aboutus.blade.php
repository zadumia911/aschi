@extends('frontEnd.layouts.master')
@section('title','About Us')
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
                                <li><a href="#">About Us</a></li>
                            </ul>
                        </div>
                        <!-- Bread Title -->
                        <!--<div class="bread-title"><h2>About Us</h2></div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / End Breadcrumb -->
    
    <!-- About Us -->
    <section class="about-us section-space">
        <div class="container">
                <div class="about-content section-title default text-left">
                @foreach($aboutus as $key=>$value)
                <div class="section-top">
                    <h1><span>{{$value->title}}</span><b>{{$value->subtitle}}</b></h1>
                </div>
                <div class="section-bottom">
                    <div class="text">
                        {{$value->text}}
                    </div>
                    
                </div>
                @endforeach
            </div>
        </div>
    </section>  
    <!--/ End About Us -->
@endsection