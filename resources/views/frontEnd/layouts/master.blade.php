<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') - {{ $generalsetting->name }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" alt="Websolution IT" />
    <meta name="author" content="Websolution IT" />
    <link rel="canonical" href="" />
    @stack('seo') @stack('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/all.min.css') }}" />
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/animations.min.css' />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/mobile-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/wsit-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css?v=1.0.7') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/responsive.css?v=1.0.0') }}" />
    @foreach ($pixels as $pixel)
        <!-- Facebook Pixel Code -->
        <script>
            !(function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = "2.0";
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "{{ $pixel->code }}");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display: none;"
                src="https://www.facebook.com/tr?id={{ $pixel->code }}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endforeach

    @foreach ($gtm_code as $gtm)
        <!-- Google tag (gtag.js) -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    "gtm.start": new Date().getTime(),
                    event: "gtm.js"
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != "dataLayer" ? "&l=" + l : "";
                j.async = true;
                j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
                f.parentNode.insertBefore(j, f);
            })
            (window, document, "script", "dataLayer", "GTM-{{ $gtm->code }}");
        </script>
        <!-- End Google Tag Manager -->
    @endforeach
</head>
@php
    $lang = Session::get('locale');
@endphp

<body class="gotop">
    @if ($lang == 'bn')
        <style>
            :root {
                --en-font: "Hind Siliguri", sans-serif;
            }
        </style>
    @endif
    @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{ asset($generalsetting->dark_logo) }}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach ($maincategories as $scategory)
                <li class="parent-category">
                    <a href="{{ route('category', $scategory->slug) }}" class="menu-category-name">
                        <img src="{{ asset($scategory->image) }}" alt="" class="side_cat_img" />
                        {{ $scategory->name }}
                    </a>
                    @if ($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-caret-down"></i>
                        </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach ($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{ route('subcategory', $subcategory->slug) }}"
                                    class="menu-subcategory-name">{{ $subcategory->name }}</a>
                                @foreach ($subcategory->childcategories as $childcat)
                            <li class="childcategory"><a href="{{ route('products', $childcat->slug) }}"
                                    class="menu-childcategory-name">{{ $childcat->name }}</a></li>
                        @endforeach
                </li>
            @endforeach
        </ul>
        </li>
        @endforeach
        </ul>
        <div class="mobilemenu-bottom">
            <ul>
                @if (Auth::guard('customer')->user())
                    <li class="for_order">
                        <a href="{{ route('customer.account') }}">
                            <i class="fa-regular fa-user"></i>
                            {{ Str::limit(Auth::guard('customer')->user()->name, 14) }}
                        </a>
                    </li>
                @else
                    <li class="for_order">
                        <a href="{{ route('customer.login') }}">Login / Sign Up</a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('home') }}"> @lang('common.home') </a>
                </li>
                <li>
                    <a href="{{ route('categories') }}"> Categories </a>
                </li>
                <li>
                    <a href=""> New Products </a>
                </li>
                <li>
                    <a href=""> Brands </a>
                </li>

                <li>
                    <a href="{{ route('blogs') }}"> News Feed</a>
                </li>
                <li>
                    <a href="{{ route('recent_view') }}"> Recent View</a>
                </li>
                <li>
                    <a href="{{ route('customer.order_track') }}"> Order Track </a>
                </li>

                <li>
                    <a href="{{ route('contact') }}">Contact Us </a>
                </li>
            </ul>
        </div>
    </div>
    <header id="navbar_top">
        <!-- mobile header start -->
        <div class="mobile-header sticky">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a class="toggle">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->dark_logo) }}"
                            alt="" /></a>
                </div>
                <div class="menu-bag">
                    <button class="mobile-search-toggle">
                        @include('frontEnd.layouts.svg.search')
                    </button>
                    <a href="{{ route('wishlist.show') }}" class="margin-shopping">
                        @include('frontEnd.layouts.svg.wishlist')
                    </a>
                </div>
            </div>
        </div>
        <div class="mobile-search main-search" style="display: none;">
            <form>
                <button><i data-feather="search"></i></button>

                <input type="search" placeholder="Search Product..." value=""
                    class="msearch_click msearch_keyword" name="keyword" />
            </form>
            <div class="search_result"></div>
        </div>
        <!-- mobile header end -->

        <!-- main header start -->
        <div class="main-header">
            <div class="logo-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header">
                                <div class="main-logo">
                                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->dark_logo) }}"
                                            alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form>
                                        <button><i data-feather="search"></i></button>
                                        <input type="search" placeholder="Search Product..."
                                            class="search_keyword search_click" value="" name="keyword" />
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="header-list-items">

                                    {{-- <li class="track_btn">
                                                <a href="{{route('customer.order_track')}}"> <i class="fa fa-truck"></i> Order Track</a>
                                            </li> --}}
                                    @if (Auth::guard('customer')->user())
                                        <a href="{{ route('customer.account') }}" class="account-button">
                                            <i class="fa-regular fa-user"></i>
                                            {{ Str::limit(Auth::guard('customer')->user()->name, 14) }}
                                        </a>
                                    @else
                                        <a class="login-button" href="{{ route('customer.login') }}">
                                            <i class="fa-regular fa-user"></i>
                                            <div class="account-text-wrapper">
                                                <span class="account-login-span">@lang('common.login')</span>
                                                <span class="account-signup-span">@lang('common.register')</span>
                                            </div>
                                        </a>
                                        </li>
                                    @endif

                                    <div class="cart-dialog" id="cart-qty">
                                        <a href="{{ route('customer.checkout') }}">
                                            <p class="margin-shopping">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                @if (Cart::instance('shopping')->content()->count() > 0)
                                                    <span
                                                        class="cart-count-circle">{{ Cart::instance('shopping')->count() }}</span>
                                                @endif
                                            </p>
                                        </a>
                                        @if (Cart::instance('shopping')->content()->count() > 0)
                                            <div class="cshort-summary">
                                                <ul>
                                                    @foreach (Cart::instance('shopping')->content() as $key => $value)
                                                        <li>
                                                            <a href=""><img
                                                                    src="{{ asset($value->options->image) }}"
                                                                    alt="" /></a>
                                                        </li>
                                                        <li><a href="">{{ Str::limit($value->name, 30) }}</a>
                                                        </li>
                                                        <li>Qty: {{ $value->qty }}</li>
                                                        <li>
                                                            <p>৳{{ $value->price }}</p>
                                                            <button class="remove-cart cart_remove"
                                                                data-id="{{ $value->rowId }}"><i
                                                                    data-feather="x"></i></button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <p><strong>@lang('common.subtotal') : ৳{{ $subtotal }}</strong></p>
                                                <a href="{{ route('customer.checkout') }}" class="go_cart"
                                                    style="color:white !important;">@lang('common.processorder') </a>
                                            </div>
                                        @endif
                                    </div>
                                    <a class="wishlist-button" href="{{ route('wishlist.show') }}">
                                        @include('frontEnd.layouts.svg.wishlist')
                                    </a>

                                    <!-- lang options -->
                                    <div class="lang-wrapper">
                                        <button type="button" data-bs-toggle="dropdown">
                                            {{ Session::get('locale') == 'en' ? '🇺🇸 EN' : '🇧🇩 BN' }}
                                            {{-- 🇺🇸 EN / English --}}
                                        </button>
                                        <ul class="lang-options dropdown-menu">
                                            <li class="{{ Session::get('locale') == 'bn' ? 'active' : '' }}"><a
                                                    href="{{ url('locale/bn') }}">🇧🇩 BN / Bangla</a></li>
                                            <li class="{{ Session::get('locale') == 'en' ? 'active' : '' }}"><a
                                                    href="{{ url('locale/en') }}">🇺🇸 EN / English</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--logo area end -->
                <div class="menu-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="catagory_menu">
                                    <ul>
                                        <li class="cat_bar ">
                                            @include('frontEnd.layouts.svg.home')
                                            <a href="{{ route('home') }}"> @lang('common.home') </a>
                                        </li>
                                        <li class="cat_bar ">
                                            <a href="{{ route('categories') }}"> @lang('common.categories') </a>
                                        </li>
                                        <li class="cat_bar ">
                                            <a href=""> @lang('common.newproducts') </a>
                                        </li>
                                        <li class="cat_bar ">
                                            <a href=""> @lang('common.brands') </a>
                                        </li>
                                        <li class="cat_bar ">
                                            <a href="{{ route('blogs') }}"> @lang('common.newsfeed')</a>
                                        </li>
                                        <li class="cat_bar ">
                                            <a href="{{ route('recent_view') }}"> @lang('common.recentview')</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- menu area end -->
            </div>
            <!-- main-header end -->
    </header>
    <div id="content">
        @yield('content')
    </div>
    <!-- content end -->
    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-12">
                        <div class="footer-about">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset($generalsetting->dark_logo) }}" alt="" />
                            </a>
                            <p>{{ $contact->address }}</p>
                            <p><a href="tel:{{ $contact->hotline }}"
                                    class="footer-hotlint">{{ $contact->hotline }}</a></p>
                            <p><a href="mailto:{{ $contact->hotmail }}"
                                    class="footer-hotlint">{{ $contact->hotmail }}</a></p>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-3 col-6">
                        <div class="footer-menu">
                            <ul>
                                <li class="title "><a>@lang('common.usefullink')</a></li>
                                @foreach ($pages as $page)
                                    <li><a
                                            href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-2 col-6">
                        <div class="footer-menu">
                            <ul>
                                <li class="title"><a>Link</a></li>
                                <li><a href="{{ route('customer.register') }}">Register</a></li>
                                <li><a href="{{ route('customer.login') }}">Login</a></li>
                                <li><a href="{{ route('customer.forgot.password') }}">Forgot Password?</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- col end -->
                    <div class="col-sm-3 col-12">
                        <div class="footer-menu">
                            <ul>
                                <li class="title text-center"><a>@lang('common.stayconnect')</a></li>
                            </ul>
                            <ul class="social_link">
                                @foreach ($socialicons as $value)
                                    <li>
                                        <a href="{{ $value->link }}"><i class="{{ $value->icon }}"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul>
                                <li class="title text-center mb-0"><a class="mb-0">@lang('common.downloadapp')</a></li>
                                <li class="delivery-partner">
                                    <img src="{{ asset('public/frontEnd/images/app.png') }}" alt="">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="copyright">
                            <p>@lang('common.copyright')
                                Developed By <a href="https://websolutionit.com" target="_blank">Websolution IT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--=====-->
    <div class="fixed_whats">
        <a href="https://api.whatsapp.com/send?phone={{ $contact->whatsapp }}" target="_blank"><i
                class="fa-brands fa-whatsapp"></i></a>
    </div>

    <div class="footer_nav">
        <ul>
            <li>
                <a href="{{ route('home') }}">
                    @include('frontEnd.layouts.svg.home')
                </a>
            </li>
            <li>
                <a href="{{ route('categories') }}" >
                    @include('frontEnd.layouts.svg.category')
                </a>
            </li>
            <li class="mobile_cart">
                <a href="{{ route('customer.checkout') }}">
                    @include('frontEnd.layouts.svg.cart')
                    <span class="mobilecart-qty">{{ Cart::instance('shopping')->count() }}</span>
                </a>
            </li>


            <li>
                <a href="{{ route('contact') }}">
                    <i data-feather="message-square"></i>
                </a>
            </li>

            @if (Auth::guard('customer')->user())
                <li>
                    <a href="{{ route('customer.account') }}">
                        @include('frontEnd.layouts.svg.user')
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('customer.login') }}">
                        @include('frontEnd.layouts.svg.user')
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>

    <!-- /. fixed sidebar -->

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>
    <script src="{{ asset('public/frontEnd/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/dist/boxicons.js"></script>
    <script src="{{ asset('public/frontEnd/js/wsit-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu-init.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wow.min.js') }}"></script>
    <!-- feather icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('public/frontEnd/js/script.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script src="{{ asset('public/backEnd/') }}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!} @stack('script')
    <script>
        $(".quick_view").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('quickview') }}",
                    success: function(data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });
    </script>
    <!-- quick view end -->
    <!-- cart js start -->
    <script>
        $(document).on('click', '.addcartbutton', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            const targetElement = $(`.product-item-${id}`);
            var qty = 1;
            if (id) {
                $.ajax({
                    cache: "false",
                    type: "GET",
                    url: "{{ url('add-to-cart') }}/" + id + "/" + qty,
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            toastr.success("Success", "Product add to cart successfully");
                            targetElement.html(data.updatedHtml);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_store").on("click", function() {
            var id = $(this).data("id");
            var qty = $(this).parent().find("input").val();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        qty: qty ? qty : 1
                    },
                    url: "{{ route('cart.store') }}",
                    success: function(data) {
                        if (data) {
                            toastr.success("Success", "Product add to cart succfully");
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_remove").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + cart_summary();
                        }
                    },
                });
            }
        });

        $(".cart_increment").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.increment') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{ route('cart.count') }}",
                success: function(data) {
                    if (data) {
                        $("#cart-qty").html(data);
                    } else {
                        $("#cart-qty").empty();
                    }
                },
            });
        }

        function mobile_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mobile.cart.count') }}",
                success: function(data) {
                    if (data) {
                        $(".mobilecart-qty").html(data);
                    } else {
                        $(".mobilecart-qty").empty();
                    }
                },
            });
        }

        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }
    </script>
    <!-- cart js end -->
    <script>
        $('.wishlist_store').on('click', function() {
            var id = $(this).data('id');
            var qty = $(this).parent().find('input').val();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        'id': id,
                        'qty': qty ? qty : 1
                    },
                    url: "{{ route('wishlist.store') }}",
                    success: function(data) {
                        if (data) {
                            toastr.success('Your product added to wishlist successfully!');
                            return wishlist_count();
                        }
                    }
                });
            }
        });
    </script>
    <script>
        $(".search_click").on("keyup change", function() {
            var keyword = $(".search_keyword").val();
            console.log(keyword);
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
        $(".msearch_click").on("keyup change", function() {
            var keyword = $(".msearch_keyword").val();
            console.log(keyword);
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
    </script>
    <!-- search js start -->

    <script>
        $(".district").on("change", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('districts') }}",
                success: function(res) {
                    if (res) {
                        $(".area").empty();
                        $(".area").append('<option value="">Select..</option>');
                        $.each(res, function(key, value) {
                            $(".area").append('<option value="' + key + '" >' + value +
                                "</option>");
                        });
                    } else {
                        $(".area").empty();
                    }
                },
            });
        });
    </script>
    <script>
        $(".toggle").on("click", function() {
            $("#page-overlay").show();
            $(".mobile-menu").addClass("active");
        });

        $("#page-overlay").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function() {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
        });
        $(".mobile-search-toggle").on("click", function() {
            $(".mobile-search").toggle();

        });
    </script>
    <script>
        $(document).ready(function() {
            $(".parent-category").each(function() {
                const menuCatToggle = $(this).find(".menu-category-toggle");
                const secondNav = $(this).find(".second-nav");

                menuCatToggle.on("click", function() {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("fast");
                    $(this).closest(".parent-category").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function() {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function() {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("fast");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
        });
    </script>

    <script>
        var menu = new MmenuLight(document.querySelector("#menu"), "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            // theme: 'dark',
            title: "ক্যাটাগরি",
        });

        var drawer = menu.offcanvas({
            // position: 'left'
        });
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>

    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $(".scrolltop:hidden").stop(true, true).fadeIn();
            } else {
                $(".scrolltop").stop(true, true).fadeOut();
            }
        });
        $(function() {
            $(".scroll").click(function() {
                $("html,body").animate({
                    scrollTop: $(".gotop").offset().top
                }, "1000");
                return false;
            });
        });
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass("active");
            $("body").css("overflow-y", "hidden");
        });
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass("active");
            $("body").css("overflow-y", "auto");
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".logoslider").owlCarousel({
                margin: 0,
                loop: true,
                dots: false,
                nav: false,
                autoplay: true,
                autoplayTimeout: 6000,
                animateOut: "fadeOut",
                animateIn: "fadeIn",
                smartSpeed: 3000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false,
                        dots: false,
                    },
                    600: {
                        items: 1,
                        nav: false,
                        dots: false,
                    },
                    1000: {
                        items: 1,
                        nav: false,
                        loop: true,
                        dots: false,
                    },
                },
            });
        });
    </script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>

    <!-- Google Tag Manager (noscript) -->
    @foreach ($gtm_code as $gtm)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-{{ $gtm->code }}" height="0"
                width="0" style="display: none; visibility: hidden;"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endforeach

    <script>
        function copyCouponCode() {
            var couponCode = document.getElementById("couponCode").innerText;
            var tempInput = document.createElement("input");
            tempInput.value = couponCode;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            toastr.success('Coupon Code copied successfully!');
        }
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass('active');
            $("body").css("overflow-y", "hidden");
        })
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass('active');
            $("body").css("overflow-y", "auto");
        })
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.cart-change-button', function(e) {
                e.preventDefault();

                const productId = $(this).data('id');
                const action = $(this).data('action');
                const targetElement = $(`.product-item-${productId}`);
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId,
                        action: action,
                    },
                    success: function(response) {
                        if (response.success) {
                            targetElement.html(response.updatedHtml);
                            return cart_count() + mobile_cart();
                        } else {
                            alert(response.message || 'Failed to update cart');
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the cart.');
                    },
                });
            });
        });
    </script>
</body>

</html>
