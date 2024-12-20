@extends('frontEnd.layouts.master')
@section('title', $generalsetting->meta_title)
@push('seo')
    <meta name="app-url" content="" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $generalsetting->meta_description }}" />
    <meta name="keywords" content="{{ $generalsetting->meta_keyword }}" />
    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $generalsetting->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="{{ asset($generalsetting->white_logo) }}" />
    <meta property="og:description" content="{{ $generalsetting->meta_description }}" />
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
@endpush
@section('content')
    @php
        $lang = Session::get('locale');
    @endphp
    <section class="slider-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-slider-container">
                        <div class="vertical-menu">
                            <ul>
                                @foreach ($maincategories as $key => $category)
                                    <li>
                                        <a class="{{ $lang == 'bn' ? 'bangla' : '' }}"
                                            href="{{ route('category', $category->slug) }}">
                                            <div>
                                                <img src="{{ asset($category->image) }}" alt="">
                                                @lang('category.name' . $category->id)
                                            </div>
                                            @if ($category->subcategories->count() > 0)
                                                <i class="fa fa-chevron-right"></i>
                                            @endif
                                        </a>
                                        @if ($category->subcategories->count() > 0)
                                            <ul class="vertical-sub">
                                                @foreach ($category->subcategories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('subcategory', $subcategory->slug) }}">
                                                            <div>
                                                                <img src="{{ asset($subcategory->image) }}" alt="">
                                                                {{ $subcategory->name }}
                                                            </div>
                                                            @if ($subcategory->childcategories->count() > 0)
                                                                <i class="fa fa-chevron-right"></i>
                                                            @endif
                                                        </a>
                                                        <ul class="vertical-child">
                                                            @foreach ($subcategory->childcategories as $key => $childcat)
                                                                <li><a href="{{ route('products', $childcat->slug) }}">
                                                                    <div>
                                                                        <img src="{{ asset($childcat->image) }}" alt="">
                                                                        {{ $childcat->name }}
                                                                    </div>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="main_slider owl-carousel">
                            @foreach ($sliders as $key => $value)
                                <div class="slider-item">
                                    <a href="{{ $value->link }}">
                                        <img src="{{ asset($value->image) }}" alt="" />
                                    </a>
                                </div>
                                <!-- slider item -->
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- col-end -->
            </div>
        </div>
    </section>

    <div class="marquee-section">
        <div class="container-fluid">
            <marquee class="home-page-marquee" direction="left">
                অনলাইনে অর্ডার করুন, পন্য হাতে পেয়ে মূল্য পরিশোধ করুন। সারা বাংলাদেশে ক্যাশ অন হোম ডেলিভারি।</marquee>
        </div>
    </div>
    <!-- slider end -->
    {{--
    <div class="home-category">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="category-title">
                        <h3>Top Categories</h3>
                    </div>
                    <div class="category-slider owl-carousel">
                        @foreach ($maincategories as $key => $value)
                            <div class="cat-item">
                                <div class="cat-img">
                                    <a href="{{ route('category', $value->slug) }}">
                                        <img src="{{ asset($value->image) }}" alt="">
                                    </a>
                                </div>
                                <div class="cat-name">
                                    <a href="{{ route('category', $value->slug) }}">
                                        {{ $value->name }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}

    <section class="homeproduct flash-sale-section">
        <div class="container-fluid">

            <div class="flash-sale-wrapper">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3> <a href="{{ route('bestdeals') }}"> @lang('common.flashsales') </a></h3>
                        <div class="offer_timer" id="simple_timer"></div>

                        <div class="see-all"> <a href="{{ route('bestdeals') }}" class=""><span
                                    class="d-none d-sm-inline-block">@lang('common.seemore')</span> <i
                                    class="fa fa-long-arrow-right"></i></a></div>

                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="product_slider hot__deals_slider owl-carousel">
                        @foreach ($hotdeal_top as $key => $value)
                            <div class="product_item wist_item">
                                @include('frontEnd.layouts.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="homeproduct">
        <div class="container-fluid">

            <div class="new-arrival-wrapper">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3> <a href="{{ route('bestdeals') }}"> @lang('common.newarrival') </a></h3>

                        <div class="see-all"> <a href="{{ route('bestdeals') }}" class="">@lang('common.seemore') <i
                                    class="fa fa-long-arrow-right"></i></a></div>

                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="product_slider new-arrival-slider owl-carousel">
                        @foreach ($new_arrivals as $key => $value)
                            <div class="product_item wist_item">
                                @include('frontEnd.layouts.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="homeproduct pb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3><a href=""> @lang('common.allproduct') </a></h3>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="product_slider" id="loadmore-product">
                        @include('frontEnd.layouts.pages.loadproducts')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="footer-gap"></div>
@endsection
@push('script')
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/jquery.syotimer.min.js') }}"></script>
    <script>
        $("#simple_timer").syotimer({
            date: new Date("2024-12-13T00:00:00.000+02:00"),
            layout: "dhms",
            doubleNumbers: false,
            effectType: "opacity",
            periodUnit: "d",
            periodic: true,
            periodInterval: 17,
        });
    </script>
    <script>
        $(document).ready(function() {

            // main slider
            $(".main_slider").owlCarousel({
                items: 1,
                loop: false,
                dots: false,
                autoplay: true,
                nav: true,
                autoplayHoverPause: false,
                margin: 30,
                mouseDrag: true,
                smartSpeed: 8000,
                autoplayTimeout: 3000,

                navText: ["<i class='fa-solid fa-angle-left'></i>",
                    "<i class='fa-solid fa-angle-right'></i>"
                ],
            });

            $(".hot__deals_slider").owlCarousel({
                margin: 16,
                items: 6,
                loop: true,
                dots: false,
                autoplay: false,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        margin: 8,
                        nav: false,
                    },
                    600: {
                        items: 4,
                        nav: false,
                    },
                    1000: {
                        items: 6,
                        nav: false,
                    },
                },
            });
            $(".new-arrival-slider").owlCarousel({
                margin: 15,
                items: 6,
                loop: true,
                dots: false,
                autoplay: false,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        nav: false,
                    },
                    600: {
                        items: 4,
                        nav: false,
                    },
                    1000: {
                        items: 6,
                        nav: false,
                    },
                },
            });
            $(".category-slider").owlCarousel({
                margin: 15,
                items: 8,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 3,
                        nav: false,
                    },
                    600: {
                        items: 5,
                        nav: false,
                    },
                    1000: {
                        items: 8,
                        nav: false,
                    },
                },
            });

        });
    </script>

    <script>
        let page = 1;
        let loading = false;

        function isMobile() {
            return window.innerWidth <= 768;
        }

        function getScrollTriggerHeight() {
            return isMobile() ? 700 : 500;
        }
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - getScrollTriggerHeight()) {
                if (!loading) {
                    loading = true;
                    page++;
                    loadMoreProduct(page);
                }
            }
        });

        function loadMoreProduct(page) {
            $.ajax({
                url: '{{ route('products.loadmore') }}',
                type: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    console.log(response);
                    if (response) {
                        $('#loadmore-product').append(response);
                        loading = false;
                    }
                }
            });
        }
    </script>
@endpush
