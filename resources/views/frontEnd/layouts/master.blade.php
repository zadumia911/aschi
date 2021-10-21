<!--
Theme Name: Spark Delivery 
Author: Spark Delivery   
Author URI: https://sparkdelivery.com.bd;
Description: Courier service
Version: 62.0.0
-->
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content='pavilan'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title Tag  -->
    <title>@yield('title','Trusted and Reliable Delivery Service')</title>
    <!-- Favicon -->
    @foreach($whitelogo as $value)
    <link rel="icon" type="image/favicon.png" href="{{asset($value->image)}}">
    @endforeach

    <!-- Web Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/animate.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/cubeportfolio.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/font-awesome.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/magnific-popup.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/owl-carousel.min.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/slicknav.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/dist/css/toastr.min.css">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/reset.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/frontEnd/')}}/css/responsive.css">
</head>

<body id="bg">
    <!-- Boxed Layout -->
    <div id="page" class="site boxed-layout">
        <!-- Header -->
        <header class="header">            
            <!-- Middle Header -->
            <div class="middle-header">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="middle-inner">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-12">
                                        <!-- Logo -->
                                        <div class="logo">
                                            <!-- Image Logo -->
                                            <div class="img-logo">
                                                <a href="{{url('/')}}">
                                                   @foreach($whitelogo as $wlogo)
                                                  <img src="{{asset($wlogo->image)}}" alt="">
                                                  @endforeach
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mobile-nav"></div>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-12">
                                        <div class="menu-area">
                                            <!-- Main Menu -->
                                            <nav class="navbar navbar-expand-lg">
                                                <div class="navbar-collapse">
                                                    <div class="nav-inner">
                                                        <div class="menu-home-menu-container">
                                                            <!-- Naviagiton -->
                                                            <ul id="nav" class="nav main-menu menu navbar-nav">
                                                                <!-- <li><a href="{{url('about-us')}}">About Us</a></li> -->
                                                                <li><a href="{{url('features')}}">Our Services</a></li>
                                                                <li><a href="{{url('price')}}">Charges</a></li>
                                                                <li><a href="{{url('faq')}}">FAQ</a></li>
                                                                <li><a href="{{url('contact-us')}}">Contact Us</a></li>
                                                                <!-- <li>
                                                                    <a  class="ml-5  mt-3 p-0"href="https://play.google.com/store/apps/details?id=com.quicktech.sparkdelivery&fbclid=IwAR108J1k0nUQGvsmT5brCHrB2XAd7setLL18fgRfZuflf8icq8TfdyPl45g" target="_blank">
                                                                    <img src="{{asset('public/frontEnd/')}}/img/google-play-badge.png" style="width:120px; " alt="#">
                                                                    </a>
                                                                </li> -->
                                                                <div class="button">
                                                                    <a href="{{url('merchant/register')}}" class="quickTech-btn register">Register</a>
                                                                    <a href="{{url('merchant/login')}}" class="quickTech-btn login">Login</a>
                                                                </div>
                                                            </ul>
                                                            <!--/ End Naviagiton -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                            <!--/ End Main Menu -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Middle Header -->

        </header>
        <!--/ End Header -->
        @yield('content')
        <!-- Footer -->
        <footer class="footer" style="background-image: url({{asset('public/frontEnd/images/footer.svg')}});">
            <!-- Footer Top -->
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Services</h3>
                                <ul>
                                    <li><a href="{{url('/')}}">Home Delivery</a></li>
                                    <li><a href="{{url('/')}}">Warehousing</a></li>
                                    <li><a href="{{url('/')}}">Pick and Drop</a></li>
                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Earn</h3>
                                <ul>
                                    <li><a href="{{url('/')}}">Become Merchant</a></li>
                                    <li><a href="{{url('/')}}">Become Rider</a></li>
                                    <li><a href="{{url('/')}}">Become Delivery Man</a></li>
                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>
                        <div class="col-lg-2 col-md-6 col-12">
                            <!-- Footer Links -->
                            <div class="single-widget f-link widget">
                                <h3 class="widget-title">Company</h3>
                                <ul>
                                    <li><a href="{{url('about-us')}}">About Us</a></li>
                                    <!-- <li><a href="{{url('price')}}">Charges</a></li>
                                    <li><a href="{{url('features')}}">Services</a></li>
                                    <li><a href="{{url('blog')}}">Blog</a></li>
                                    <li><a href="{{url('termscondition')}}">Terms & conditions</a></li>
                                    <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                                    <li><a href="{{asset('public/frontEnd/images/coverage_area.xlsx')}}" download>Coverage area</a></li>
                                    <li><a href="{{url('faq')}}">FAQ</a></li> -->
                                    <li><a href="{{url('contact-us')}}">Contact us</a></li>
                                    <li><a href="{{url('/')}}">Our Goal</a></li>
                                </ul>
                            </div>
                            <!--/ End Footer Links -->
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Footer Contact -->
                            <div class="single-widget footer_contact widget">
                                <h3 class="widget-title">Contact</h3>
                                <p>Don’t miss any updates of our Offer</p>
                                <div class="newsletter">
                                    <form action="" class="d-flex flex-nowrap">
                                        <div class="form-group h-100 m-0 p-2 w-100">
                                            <input type="email" placeholder="Email Address" class="form-control px-1 bg-transparent h-100 border-0 without-focus" />
                                        </div>
                                        <button type="button" class="bg-white btn font-20 font-light m-1">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                            <!--/ End Footer Contact -->
                        </div>
                    </div>

                </div>
            </div>
            <!-- Copyright -->
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="copyright-content">
                                <div class="img-logo text-left bg-light">
                                    <a href="{{url('/')}}">
                                       @foreach($whitelogo as $wlogo)
                                      <img src="{{asset($wlogo->image)}}" alt="">
                                      @endforeach
                                    </a>
                                </div>
                                <ul class="address-widget-list">
                                    <li class="footer-mobile-number"><i class="fa fa-phone"></i>+88 01303-355623</li>
                                    <li class="footer-mobile-number"><i class="fa fa-envelope"></i>contact@aschi.com.bd</li>
                                    <li class="footer-mobile-number"><i class="fa fa-map-marker"></i>536, Shameem Sharani, West Shewrapara 1216 Dhaka, Dhaka Division, Bangladesh</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="align-items-center copyright-content d-flex justify-content-center">
                                <!-- Copyright Text -->
                                <p>© Copyright Aschi 2021</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="align-items-center copyright-content d-flex justify-content-end">
                                <ul class="social-widget-list">
                                    <li class="footer-mobile-number"><i class="fa fa-facebook"></i></li>
                                    <li class="footer-mobile-number"><i class="fa fa-instagram"></i></li>
                                    <li class="footer-mobile-number"><i class="fa fa-twitter"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Copyright -->
        </footer>

        <!-- Jquery JS -->
        <script src="{{asset('public/frontEnd/')}}/js/jquery.min.js"></script>
        <script src="{{asset('public/frontEnd/')}}/js/jquery-migrate-3.0.0.js"></script>
        <!-- Popper JS -->
        <script src="{{asset('public/frontEnd/')}}/js/popper.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="{{asset('public/frontEnd/')}}/js/bootstrap.min.js"></script>
        <!-- Modernizr JS -->
        <script src="{{asset('public/frontEnd/')}}/js/modernizr.min.js"></script>
        <!-- ScrollUp JS -->
        <script src="{{asset('public/frontEnd/')}}/js/scrollup.js"></script>
        <!-- FacnyBox JS -->
        <script src="{{asset('public/frontEnd/')}}/js/jquery-fancybox.min.js"></script>
        <!-- Cube Portfolio JS -->
        <script src="{{asset('public/frontEnd/')}}/js/cubeportfolio.min.js"></script>
        <!-- Slick Nav JS -->
        <script src="{{asset('public/frontEnd/')}}/js/slicknav.min.js"></script>
        <!-- Slick Nav JS -->
        <script src="{{asset('public/frontEnd/')}}/js/slicknav.min.js"></script>
        <!-- Slick Slider JS -->
        <script src="{{asset('public/frontEnd/')}}/js/owl-carousel.min.js"></script>
        <!-- Easing JS -->
        <script src="{{asset('public/frontEnd/')}}/js/easing.js"></script>
        <!-- Magnipic Popup JS -->
        <script src="{{asset('public/frontEnd/')}}/js/magnific-popup.min.js"></script>
        <!-- Active JS -->
        <script src="{{asset('public/frontEnd/')}}/js/active.js"></script>
        <script src="{{asset('public/backEnd/')}}/dist/js/toastr.min.js"></script>
        {!! Toastr::message() !!}
</body>

</html>
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