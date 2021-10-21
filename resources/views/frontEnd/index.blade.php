@extends('frontEnd.layouts.master')
@section('title','Trusted and Reliable Delivery Service')
@section('content')

<!-- Hero Section -->
<section class="hero-section hero-bg-img style1" style="background-image: url({{asset('public/frontEnd/images/hero-background.svg')}});">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                    @foreach($banner as $key=>$value)
                    <div class="col-lg-7 col-md-6">
                        <div class="feature-content">
                            <div class="feature-text text-white">
                                <h1>{{$value->title}}</h1>
                            </div>
                            <div class="text-white"><p class="sub-ft-txt">{{$value->subtitle}}</p></div>
                            <div class=""><a href="" id="gotoregister" role="button" class="btn bg-white text-uppercase">BECOME A MERCHANT</a></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 pr-0 bg-send">
                        <img src="{{asset($value->image)}}" alt="">
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-12">
                <div class="tracking-btn-box">
                    <div class="mb-2">
                        <span class="h5 text-uppercase track-span">Track your consignment</span>
                        <span class="consignment-span">Now you can easily track your consignment</span>
                    </div>
                    <form action="{{url('/track/parcel/')}}" method="post">
                    @csrf
                        <input type="text" placeholder="Enter your Tracking ID" name="trackparcel" class="trackId">
                        <input type="submit" value="Track" class="trackBtn">
                    </form>
                    
                </div>
            </div>
        </div>
        
    </div>
</section>
<!--/ End Hero Section -->
<!-- Tracking -->
<!--<section class="spark-tracking call-action overlay" style="background: #000;">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-sm-12">-->
<!--                <div class="call-inner">-->
<!--                    <h2>Track your order </h2>-->
                   
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-sm-12">-->
<!--                <div class="button2">-->
<!--                    <form action="{{url('/track/parcel/')}}" method="post">-->
<!--                    @csrf-->
<!--                        <input type="text" placeholder="Enter Track ID" name="trackparcel" class="trackId">-->
<!--                        <input type="submit" value="Track" class="trackBtn">-->
<!--                    </form>-->
                    
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!--/ End Tracking -->
 <!--Features Area -->
<section class="service-accordion-section">
    <div class="container pt-5 px-lg-3 px-md-0">
        <div class="py-5 mx-auto">
            <div class="col-12 py-2">
                <div class="row">
                    <div class="col-md-6 pl-0 pr-4">
                        <div class="w-100">
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-one" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="cursor-pointer py-3 d-flex justify-content-between align-items-center collapsed">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" width="32" height="24" viewBox="0 0 32 24" xml:space="preserve">
                                                    <g transform="matrix(0.05228758,0,0,0.05579799,0,-5.0742123)">
                                                        <path
                                                            fill="#1dc68c"
                                                            d="M 21.474,377.522 V 117.138 c 0,-14.469 11.729,-26.199 26.199,-26.199 h 260.25 c 14.469,0 26.198,11.73 26.198,26.199 v 260.385 c 0,4.823 -3.909,8.733 -8.733,8.733 H 30.207 c -4.824,0 -8.733,-3.91 -8.733,-8.734 z m 210.16,89.202 c 0,30.01 -24.329,54.338 -54.338,54.338 -30.009,0 -54.338,-24.328 -54.338,-54.338 0,-30.011 24.329,-54.338 54.338,-54.338 30.009,0 54.338,24.327 54.338,54.338 z m -27.17,0 c 0,-15.005 -12.164,-27.169 -27.169,-27.169 -15.005,0 -27.17,12.164 -27.17,27.169 0,15.005 12.165,27.17 27.17,27.17 15.005,0 27.169,-12.165 27.169,-27.17 z M 130.495,412.385 H 8.733 C 3.91,412.385 0,416.295 0,421.118 v 26.495 c 0,4.823 3.91,8.733 8.733,8.733 h 97.598 c 2.548,-17.484 11.373,-32.928 24.164,-43.961 z m 385.443,54.339 c 0,30.01 -24.329,54.338 -54.338,54.338 -30.01,0 -54.338,-24.328 -54.338,-54.338 0,-30.011 24.328,-54.338 54.338,-54.338 30.009,-0.001 54.338,24.327 54.338,54.338 z m -27.168,0 c 0,-15.005 -12.165,-27.169 -27.17,-27.169 -15.006,0 -27.169,12.164 -27.169,27.169 0,15.005 12.164,27.17 27.169,27.17 15.005,0 27.17,-12.165 27.17,-27.17 z M 612,421.118 v 26.495 c 0,4.823 -3.91,8.733 -8.733,8.733 h -70.704 c -5.057,-34.683 -34.906,-61.427 -70.961,-61.427 -36.062,0 -65.912,26.745 -70.969,61.427 H 248.261 c -2.549,-17.483 -11.373,-32.928 -24.164,-43.961 H 359.091 V 162.594 c 0,-9.646 7.82,-17.466 17.466,-17.466 h 82.445 c 23.214,0 44.911,11.531 57.9,30.77 l 53.15,78.721 c 7.796,11.547 11.962,25.161 11.962,39.094 v 118.672 h 21.253 c 4.823,0 8.733,3.91 8.733,8.733 z M 523.408,256.635 480.907,196.242 c -1.636,-2.324 -4.3,-3.707 -7.142,-3.707 H 407.47 c -4.822,0 -8.733,3.91 -8.733,8.733 v 60.393 c 0,4.824 3.91,8.733 8.733,8.733 h 108.798 c 7.074,0 11.212,-7.973 7.14,-13.759 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Daily Pick up, No limitations</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseOne" aria-labelledby="feature-one" class="collapse" style="">
                                    <div class="card-body border-top">
                                        Steadfast Courier gives you the opportunity of unlimited pickup. You can give any amount of parcels regardless of their size and weight. Also you don’t have to bring your parcels to our office! Our
                                        trusted pickup man will visit your location and pick up your parcels on behalf of you. You can request for pickup for every day of the week.
                                    </div>
                                </div>
                            </div>
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-two" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="cursor-pointer py-3 d-flex justify-content-between align-items-center">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 32.000001 24.000001" xml:space="preserve" width="32" height="24">
                                                    <g transform="matrix(0.04575195,0,0,0.04399403,2.0000106,-1.4620868)">
                                                        <path
                                                            fill="#1dc68c"
                                                            d="M 588.63,113.193 213.812,33.871 c -15.858,-3.355 -31.576,6.876 -34.931,22.734 l -7.121,45.762 432.477,91.519 7.121,-45.762 c 3.355,-15.852 -6.87,-31.575 -22.728,-34.931 z"
                                                        ></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 431.009,203.591 c -4.378,-15.766 -20.854,-25.085 -36.615,-20.714 L 323.24,202.63 155.498,167.13 137.05,254.295 21.786,286.287 c -15.76,4.372 -25.079,20.848 -20.708,36.609 l 64.958,234.078 c 4.378,15.76 20.855,25.085 36.615,20.708 L 475.259,474.279 c 15.76,-4.378 25.079,-20.848 20.708,-36.615 l -11.15,-40.184 41.789,8.835 c 15.858,3.361 31.576,-6.87 34.931,-22.728 L 587.976,258.65 437.45,226.797 Z m 43.031,118.968 9.215,-43.552 c 1.384,-6.521 7.85,-10.727 14.37,-9.35 l 43.552,9.221 c 6.527,1.384 10.733,7.843 9.356,14.37 l -9.215,43.552 c -1.384,6.521 -7.849,10.733 -14.37,9.35 l -43.552,-9.215 c -6.533,-1.389 -10.74,-7.855 -9.356,-14.376 z M 28.27,309.646 131.385,281.04 l 243.299,-67.517 26.181,-7.274 c 0.478,-0.129 0.955,-0.19 1.421,-0.19 2.1,0 4.611,1.378 5.345,4.017 l 3.074,11.07 9.631,34.704 L 37.148,362.186 24.443,316.418 c -0.796,-2.872 0.956,-5.976 3.827,-6.772 z m 444.331,134.495 c 0.49,1.776 -0.024,3.245 -0.545,4.164 -0.514,0.918 -1.506,2.119 -3.282,2.608 L 96.173,554.316 c -0.471,0.129 -0.955,0.196 -1.421,0.196 -2.1,0 -4.611,-1.384 -5.345,-4.023 L 51.519,413.955 434.707,307.613 l 23.371,84.208 z"
                                                        ></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 156.379,453.484 c -1.788,-6.429 -8.499,-10.225 -14.928,-8.443 l -43.515,12.08 c -6.423,1.782 -10.225,8.499 -8.437,14.928 l 12.074,43.509 c 1.788,6.429 8.499,10.225 14.928,8.437 l 43.515,-12.074 c 6.429,-1.782 10.225,-8.499 8.443,-14.928 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Faster Payment Service</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseTwo" aria-labelledby="feature-two" class="collapse">
                                    <div class="card-body border-top">
                                        At Steadfast Courier you can request for your payment every six days of the week. We Courier provides multiple payment methods such as cash, Bkash or Rocket payment. Also you can collect the money
                                        simply by transferring your current balance to your preferred Bank. Our faster and secure payment service will provide the ultimate solution to your payment problem which can occur using a logistics
                                        service.
                                    </div>
                                </div>
                            </div>
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-three" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="cursor-pointer py-3 d-flex justify-content-between align-items-center collapsed">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 32.000001 24.000001" xml:space="preserve" width="32" height="24">
                                                    <g transform="matrix(0.08000133,0,0,0.08000133,4,1.00665e-6)">
                                                        <path
                                                            fill="#1dc68c"
                                                            d="M 149.995,0 C 67.156,0 0,67.158 0,149.995 c 0,82.837 67.156,150 149.995,150 82.839,0 150,-67.163 150,-150 C 299.995,67.158 232.834,0 149.995,0 Z m 64.847,178.524 H 151.25 c -0.215,0 -0.415,-0.052 -0.628,-0.06 -0.213,0.01 -0.412,0.06 -0.628,0.06 -5.729,0 -10.374,-4.645 -10.374,-10.374 V 62.249 c 0,-5.729 4.645,-10.374 10.374,-10.374 5.729,0 10.374,4.645 10.374,10.374 v 95.527 h 54.47 c 5.729,0 10.374,4.645 10.374,10.374 0,5.729 -4.641,10.374 -10.37,10.374 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Real-Time Tracking</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseThree" aria-labelledby="feature-three" class="collapse" style="">
                                    <div class="card-body border-top">
                                        Steadfast Courier provides an unique tracking code for your every consignments. Through our website you can learn the current status of the products and stay up to date.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pr-0 pl-4">
                        <div class="w-100">
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-four" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" class="cursor-pointer py-3 d-flex justify-content-between align-items-center collapsed">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" width="32" height="26" viewBox="0 0 32 26" xml:space="preserve">
                                                    <g transform="matrix(0.03368894,0,0,0.03041577,1,-0.5426301)">
                                                        <path fill="#1dc68c" d="m 208.1,180.56 355,-96.9 -18.8,-38 C 532,20.96 502,10.76 477.3,23.06 l -317.8,157.5 z"></path>
                                                        <path fill="#1dc68c" d="m 673.3,86.46 c -4.399,0 -8.8,0.6 -13.2,1.8 L 576.701,111.06 322,180.56 h 289.1 126 l -15.6,-57.2 c -6,-22.3 -26.2,-36.9 -48.2,-36.9 z"></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 789.2,215.56 h -11.4 -15.5 -15.5 -118.3 -434.7 -57 -48 -8.9 -29.8 c -15.8,0 -29.9,7.3 -39.1,18.8 -4.2,5.3 -7.4,11.4 -9.2,18.1 -1.1,4.2 -1.8,8.6 -1.8,13.1 v 6 57 494.1 c 0,27.601 22.4,50 50,50 h 739.1 c 27.601,0 50,-22.399 50,-50 V 683.16 H 542.4 c -46.9,0 -85,-38.1 -85,-85 v -45.8 -15.5 -15.5 -34.4 c 0,-23 9.199,-43.899 24.1,-59.199 13.2,-13.601 30.9,-22.801 50.7,-25.101 3.3,-0.399 6.7,-0.6 10.1,-0.6 h 255.2 15.5 15.5 10.6 v -136.5 c 0.1,-27.6 -22.3,-50 -49.9,-50 z"
                                                        ></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 874.2,449.86 c -5,-4.6 -10.9,-8.1 -17.5,-10.4 -5.101,-1.699 -10.5,-2.699 -16.2,-2.699 h -1.3 -1 -15.5 -55.9 -224.4 c -27.601,0 -50,22.399 -50,50 v 24.899 15.5 15.5 55.4 c 0,27.6 22.399,50 50,50 h 296.8 1.3 c 5.7,0 11.1,-1 16.2,-2.7 6.6,-2.2 12.5,-5.8 17.5,-10.4 10,-9.1 16.3,-22.3 16.3,-36.899 v -111.3 c 0,-14.601 -6.3,-27.802 -16.3,-36.901 z m -227.4,102.5 c 0,13.8 -11.2,25 -25,25 h -16.6 c -13.8,0 -25,-11.2 -25,-25 v -16.6 c 0,-8 3.7,-15.101 9.6,-19.601 4.3,-3.3 9.601,-5.399 15.4,-5.399 h 4.2 12.4 c 13.8,0 25,11.199 25,25 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Cash on Delivery</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseFour" aria-labelledby="feature-four" class="collapse" style="">
                                    <div class="card-body border-top">
                                        At Steadfast Courier we will collect the cash on behalf of you. Our trusted delivery man will deliver your parcel to your customer and collect the money. And then with our various payment methods we
                                        will give your money back to you. Also we are giving you the opportunity of sending a non-conditioned parcel to delivery as well.
                                    </div>
                                </div>
                            </div>
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-five" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive" class="cursor-pointer py-3 d-flex justify-content-between align-items-center">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" width="32" height="24" viewBox="0 0 32 24" xml:space="preserve">
                                                    <g transform="matrix(0.05472725,0,0,0.05051706,1,-1.846045)">
                                                        <path
                                                            fill="#1dc68c"
                                                            d="M 534.75,49.965 C 525.805,41.02 515.056,36.543 502.489,36.543 H 45.681 C 33.119,36.543 22.368,41.02 13.417,49.965 4.471,58.913 0,69.663 0,82.226 v 310.633 c 0,12.566 4.471,23.315 13.417,32.265 8.951,8.945 19.702,13.414 32.264,13.414 h 155.318 c 0,7.231 -1.524,14.661 -4.57,22.269 -3.044,7.614 -6.09,14.273 -9.136,19.981 -3.042,5.715 -4.565,9.897 -4.565,12.56 0,4.948 1.807,9.24 5.424,12.847 3.615,3.621 7.898,5.435 12.847,5.435 h 146.179 c 4.949,0 9.233,-1.813 12.848,-5.435 3.62,-3.606 5.427,-7.898 5.427,-12.847 0,-2.468 -1.526,-6.611 -4.571,-12.415 -3.046,-5.801 -6.092,-12.566 -9.134,-20.267 -3.046,-7.71 -4.569,-15.085 -4.569,-22.128 h 155.318 c 12.56,0 23.309,-4.469 32.254,-13.414 8.949,-8.949 13.422,-19.698 13.422,-32.265 V 82.226 C 548.176,69.663 543.699,58.913 534.75,49.965 Z m -23.123,269.803 c 0,2.475 -0.903,4.613 -2.711,6.424 -1.81,1.804 -3.952,2.707 -6.427,2.707 H 45.681 c -2.473,0 -4.615,-0.903 -6.423,-2.707 -1.807,-1.817 -2.712,-3.949 -2.712,-6.424 V 82.226 c 0,-2.475 0.902,-4.615 2.712,-6.423 1.809,-1.805 3.951,-2.712 6.423,-2.712 h 456.815 c 2.471,0 4.617,0.904 6.42,2.712 1.808,1.809 2.711,3.949 2.711,6.423 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Online Management</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseFive" aria-labelledby="feature-five" class="collapse">
                                    <div class="card-body border-top">
                                        With our simple and easy to use user interface you can get all the information you need in your own user dashboard. You can view your user dashboard to stay updated. Whether it’s about a parcel or
                                        payment, you can get all of your information with just simple clicks.
                                    </div>
                                </div>
                            </div>
                            <div class="card feature-accordion mb-4 border-0">
                                <div id="feature-six" class="card-header py-2 bg-white border-bottom-0">
                                    <div data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix" class="cursor-pointer py-3 d-flex justify-content-between align-items-center">
                                        <div class="d-block">
                                            <span>
                                                <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0px" y="0px" viewBox="0 0 32 24" xml:space="preserve" width="32" height="24">
                                                    <g transform="matrix(0.06543075,0,0,0.05484461,1.6837515,4.008e-6)">
                                                        <path fill="#1dc68c" d="m 264.6,300.4 h -92 C 97,300.4 35.4,362 35.4,437.6 h 366.8 c -0.4,-75.2 -62,-137.2 -137.6,-137.2 z"></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 235,237.6 c 7.2,0 14,2.4 19.2,6.8 h 33.2 c 16.4,-17.2 26.4,-40 26.4,-65.6 0,-52.8 -42.8,-95.2 -95.2,-95.2 -52.4,0 -95.2,42.4 -95.2,95.2 0,35.6 19.6,66.8 48.8,83.2 3.2,-14 15.6,-24.4 30,-24.4 z"
                                                        ></path>
                                                        <path
                                                            fill="#1dc68c"
                                                            d="m 77.8,186.8 c 1.2,10 6,19.2 12.8,26 5.2,5.2 12,9.2 19.6,11.6 -0.4,-1.6 -0.4,-3.2 -0.4,-4.4 v -77.6 c 0,-1.6 0,-3.2 0.4,-4.8 -7.6,2 -14,6 -19.6,11.6 -0.4,0.4 -1.2,1.2 -1.6,1.6 v -3.2 c 0,-7.6 0.8,-15.2 2,-22.4 1.2,-7.6 3.2,-14.8 6,-21.6 2.4,-7.2 5.6,-14 9.6,-20.4 3.6,-6.4 8,-12.8 12.8,-18.4 0.4,-0.4 0.8,-1.2 0.8,-1.6 3.6,0.4 7.2,-0.8 9.6,-3.2 24.4,-24.4 56.4,-36.4 88,-36.4 31.6,0 64,12 88,36.4 2.8,2.8 6.4,3.6 9.6,3.2 0.4,0.4 0.4,1.2 0.8,1.6 4.8,5.6 9.2,12 12.8,18.4 3.6,6.4 6.8,13.2 9.6,20.4 2.4,7.2 4.4,14.4 6,22 1.2,7.2 2,14.8 2,22.4 v 3.2 c -0.4,-0.8 -1.2,-1.2 -1.6,-1.6 -5.2,-5.2 -12,-9.2 -19.6,-11.6 0.4,1.6 0.4,3.2 0.4,4.8 V 220 c 0,1.6 0,3.2 -0.4,4.4 2,-0.4 4,-1.2 6,-2 v 25.2 c 0,2.8 -0.4,5.6 -0.8,7.6 -0.4,2 -1.2,3.2 -2,4.4 -0.8,0.8 -2,1.6 -3.2,2 -1.6,0.4 -3.2,0.8 -5.2,0.8 h -0.4 -73.6 c -2.4,-4 -6.4,-6.8 -11.6,-6.8 h -32.8 c -7.2,0 -12.8,6 -12.8,12.8 0.4,7.2 6,12.8 13.2,12.8 H 235 c 4.8,0 9.2,-2.8 11.6,-6.8 h 74.4 2.8 c 2.8,0 5.2,-0.8 7.6,-1.6 3.2,-1.2 5.6,-2.8 8,-5.2 2,-2.4 3.6,-5.2 4.8,-8.8 0.8,-3.2 1.6,-7.2 1.6,-11.6 v -32 -1.2 c 0.4,-0.4 0.8,-0.8 1.2,-1.2 6.8,-6.8 11.6,-16 12.8,-26 0.4,-0.4 0.8,-0.4 0.8,-0.8 1.2,-1.2 2,-3.2 2,-4.8 V 148 c 0,-8.4 -0.8,-16.8 -2,-24.8 -2.4,-8.4 -4.4,-16.4 -7.2,-24.4 -2.8,-8 -6.4,-15.6 -10.8,-22.8 -4,-7.2 -8.8,-14 -14.4,-20.4 -0.4,-0.8 -1.2,-1.2 -2,-1.6 C 327,50.4 325.8,46 323,43.2 294.2,14.4 256.6,0 218.6,0 180.6,0 143,14.4 113.8,43.2 c -2.8,2.8 -4,7.2 -3.2,10.8 -0.8,0.4 -1.2,0.8 -2,1.6 C 103,62 98.2,68.8 94.2,76 c -4,7.2 -7.6,14.8 -10.8,22.8 -2.8,8 -5.2,16 -6.4,24.4 -1.6,8 -2,16.4 -2,24.8 v 33.2 c 0,2 0.8,3.6 2,4.8 0.4,0.4 0.8,0.4 0.8,0.8 z"
                                                        ></path>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="pl-2 font-18 font-h-md-16 font-medium">Advanced Customer Service</span>
                                        </div>
                                        <span class="tgl-icon">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="collapseSix" aria-labelledby="feature-six" class="collapse">
                                    <div class="card-body border-top">
                                        Our Call Center Executives are always ready 24/7 to help you with your problems. They are fast, well trained, reliable and always ready to solve your problems. Also you can contact us on our Facebook
                                        page as well. Our Facebook page admins are always active to give you feedbacks.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>













<section class="features-area section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                        <h1 class="text-dark"><span>Our</span><b>Services</b></h1>
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>We Love to Serve Delightful Experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($features as $key=>$value)
            <div class="col-lg-3 col-md-6 col-12">
                 <!--Single Feature -->
                <div class="single-feature">
                    <div class="icon-head"><i class="fa {{$value->icon}}"></i></div>
                    <h4><a href="{{url('/features/details/'.$value->id)}}">{{$value->title}} </a></h4>
                    <p>{{str_limit($value->subtitle,140)}}</p>
                    <div class="button">
                        <a href="{{url('/features/details/'.$value->id)}}" class="quickTech-btn"><i class="fa fa-arrow-circle-o-right"></i>More Detail</a>
                    </div>
                </div>
                <!--/ End Single Feature -->
            </div>
            @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Features Area -->
<!-- Spark Delivery-price -->
<section class="quickTech-price  section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                        <h1><span>Our</span><b>Charges</b></h1>
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>We Love to Serve Delightful Experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($prices as $key=>$value)
            <div class="col-lg-4 col-md-4 col-12">
                <!-- Single quickTech-price -->
                <div class="single-quickTech-price">
                    <div class="quickTech-price-head">
                        <img src="{{asset($value->image)}}" alt="#">
                        <div class="icon-bg">{{$value->price}} tk</div>
                    </div>
                    <div class="quickTech-price-content">
                        <h4><a href="#">{{$value->name}}</a></h4>
                        <p>{!!$value->text!!}</p>
                        <a class="btn" href="#"><i class="fa fa-arrow-circle-o-right"></i>Book Now</a>
                    </div>
                </div>
                <!--/ End Single quickTech-price -->
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--/ End quickTech-price -->

<!-- Testimonials -->
<section class="testimonials section-space")">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title default">
                    <div class="section-top justify-content-center">
                        <b style="font-size:28px;">What our merchants are saying so far...</b>
                        <hr>
                    </div>
                </div>
                <div class="testimonial-inner">
                    <div class="testimonial-slider">
                        @foreach($clientsfeedback as $key=>$value)
                        <!-- Single Testimonial -->
                        <div class="single-slider">
                            <ul class="star-list">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <p>{{$value->text}}</p>
                            <!-- Client Info -->
                            <div class="t-info">
                                <div class="t-left">
                                    <div class="client-head"><img src="{{asset('public/frontEnd/')}}/img/review.jpg" alt="#"></div>
                                    <h2>{{$value->description}} <span>{{$value->name}}</span></h2>
                                </div>
                                <div class="t-right">
                                    <div class="quote"><i class="fa fa-quote-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- / End Single Testimonial -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Testimonials -->


<!-- Blog  -->
<!--<section class="blog-layout news-default section-space">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">-->
<!--                <div class="section-title default text-center">-->
<!--                    <div class="section-top">-->
<!--                        <h1><span>Latest</span><b> Blog</b></h1>-->
<!--                    </div>-->
<!--                    <div class="section-bottom">-->
<!--                        <div class="text">-->
<!--                            <p>Get our latest news </p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row ">-->
<!--            @foreach($blogs as $key=>$value)-->
<!--            <div class="col-lg-4 col-md-6 col-12">-->
                <!-- Single Blog -->
<!--                <div class="single-news ">-->
<!--                    <div class="news-head overlay">-->
<!--                        <img src="{{asset($value->image)}}" alt="#">-->

<!--                    </div>-->
<!--                    <div class="news-body">-->
<!--                        <div class="news-content">-->
<!--                            <h3 class="news-title"><a href="{{url('blog/details/'.$value->id)}}">{{$value->title_en}}</a></h3>-->
<!--                            <div class="news-text">-->
<!--                                <p>{{str_limit($value->text_en,50)}}</p>-->
<!--                            </div>-->
<!--                            <a href="{{url('blog/details/'.$value->id)}}" class="more">বিস্তারিত জানুন... <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <!--/ End Single Blog -->
<!--            </div>-->
<!--            @endforeach-->
<!--        </div>-->
     
<!--    </div>-->
<!--</section>-->
<!-- Client Area -->
<div class="clients section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">
                <div class="section-title default text-center">
                    <div class="section-top">
                        <h1 class="text-dark"><span>Merchants</span><b>Reviews</b></h1>
                    </div>
                    <div class="section-bottom">
                        <div class="text">
                            <p>What our customers say</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="partner-slider">
                    @foreach($clientsfeedback as $key=>$value)
                    <!-- Single client -->
                    <div class="single-slider">
                        <div class="single-client">
                            <a href="#" target="_blank"><img src="{{asset($value->image)}}" alt="#"></a>
                        </div>
                    </div>
                    <!--/ End Single client -->
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ End Client Area -->
<!--FAQ area-->
<!--<div class="faq">-->
<!--    <div class="container my-3">-->
<!--        <div class="row">-->
<!--            <div class="col-12">-->
<!--                <h2 class="text-center">You have question?</h2>-->
<!--                <hr>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-4">-->
<!--                <h4 class="text-center"></h4>-->
<!--                <hr>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--END FAQ-->
<!-- Client Area -->
<!--coverage area-->
<!--<div class="bg-light">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12">-->
<!--                <div class="section-title default text-center">-->
<!--                    <div class="section-top">-->
<!--                        <h1 class="text-dark"><span>Coverage</span><b>Area</b></h1>-->
<!--                    </div>-->
<!--                    <div class="section-bottom">-->
<!--                        <div class="text">-->
<!--                            <p>Download our coverage area and detail pricing from here</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row mb-4">-->
<!--            <div class="col-md-4">-->
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                	<thead>-->
<!--					    <tr>-->
<!--					        <td></td>-->
<!--					        <td></td>-->
<!--						      <td><a href="{{asset('public/frontEnd/images/Areawise%20pricing.xlsx')}}" download class="btn btn-success mb-4"> Download (Coverage area exelsheet)</a></td>-->
<!--						</tr>-->
<!--				    </thead>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--end coverage area-->
<!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "109961004701121");
      chatbox.setAttribute("attribution", "biz_inbox");

      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v11.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>

@endsection