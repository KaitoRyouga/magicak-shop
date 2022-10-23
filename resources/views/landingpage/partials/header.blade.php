<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
{{--    <link rel="shortcut icon" href="landingpage/img/favicon_magicak.png" type="image/x-icon">--}}
    <link rel="shortcut icon" href="landingpage/img/logo/Magicak_gold.png" type="image/x-icon">
    <title>Magicak</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset("landingpage/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/bootstrap-selector/css/bootstrap-select.min.css") }}" rel="stylesheet" type="text/css" />
    <!--icon font css-->
    <link href="{{ asset("landingpage/vendors/themify-icon/themify-icons.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/flaticon/flaticon.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/animation/animate.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/owl-carousel/assets/owl.carousel.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/slick/slick.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/slick/slick-theme.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/magnify-pop/magnific-popup.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/nice-select/nice-select.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/vendors/scroll/jquery.mCustomScrollbar.min.css") }}" rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset("landingpage/vendors/elagent/style.css") }}" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset("landingpage/css/style.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("landingpage/css/responsive.css") }}" rel="stylesheet" type="text/css" />

</head>

<body data-spy="scroll" data-target="header" data-offset="80">
{{--<div id="preloader">--}}
{{--    <div id="ctn-preloader" class="ctn-preloader">--}}
{{--        <div class="animation-preloader">--}}
{{--            <div class="spinner"></div>--}}
{{--            <div class="txt-loading">--}}
{{--                    <span data-text-preloader="M" class="letters-loading">--}}
{{--                        M--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="A" class="letters-loading">--}}
{{--                        A--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="G" class="letters-loading">--}}
{{--                        G--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="I" class="letters-loading">--}}
{{--                        I--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="C" class="letters-loading">--}}
{{--                        C--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="A" class="letters-loading">--}}
{{--                        A--}}
{{--                    </span>--}}
{{--                <span data-text-preloader="K" class="letters-loading">--}}
{{--                        K--}}
{{--                    </span>--}}
{{--            </div>--}}
{{--            <p class="text-center">Loading</p>--}}
{{--        </div>--}}
{{--        <div class="loader">--}}
{{--            <div class="row">--}}
{{--                <div class="col-3 loader-section section-left">--}}
{{--                    <div class="bg"></div>--}}
{{--                </div>--}}
{{--                <div class="col-3 loader-section section-left">--}}
{{--                    <div class="bg"></div>--}}
{{--                </div>--}}
{{--                <div class="col-3 loader-section section-right">--}}
{{--                    <div class="bg"></div>--}}
{{--                </div>--}}
{{--                <div class="col-3 loader-section section-right">--}}
{{--                    <div class="bg"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<?php if(isset($homepage)) { ?>
<header class="header_area">
    <nav class="navbar navbar-expand-lg menu_one" id="landing_page">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="landingpage/img/logo/Magicak_black.png" srcset="landingpage/img/logo/Magicak_black.png 2x" alt="logo" class="magicak_logo"></a>
            <a class="btn_get btn_hover mobile_btn ml-auto" href="#get-app">Get Started</a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu_toggle">
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="hamburger-cross">
                                <span></span>
                                <span></span>
                            </span>
                        </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav mr-auto ml-auto menu">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/aboutus">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="/support">Support</a></li>
                    <li class="nav-item"><a class="nav-link" href="/partners">Partners</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                </ul>
                <div class="tracking_btn">
                    <a class="er_btn btn_hover" href="{{ URL::to('admin/') }}"><i class="icon_building"></i>Register</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php } else { ?>
<header class="header_area">
    <nav class="navbar navbar-expand-lg menu_one menu_four">
        <div class="container">
            <a class="navbar-brand sticky_logo" href="#">
                <img src="landingpage/img/logo/Magicak_white.png" srcset="landingpage/img/logo/Magicak_white.png 2x"
                                                              alt="logo" class="magicak_logo">
                <img src="landingpage/img/logo/Magicak_black.png" srcset="landingpage/img/logo/Magicak_black.png2x" alt="" class="magicak_logo"></a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu_toggle">
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="hamburger-cross">
                                <span></span>
                                <span></span>
                            </span>
                        </span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav menu w_menu ml-auto mr-auto">
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/services">Services</a></li>
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/aboutus">About Us</a></li>
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/support">Support</a></li>
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/partners">Partners</a></li>
                    <li class="nav-item dropdown submenu mega_menu mega_menu_two"><a class="nav-link" href="/contact">Contact</a></li>
                </ul>
                <a class="btn_get btn_hover hidden-sm hidden-xs" href="#">Register</a>
            </div>
        </div>
    </nav>
</header>
<?php } ?>
