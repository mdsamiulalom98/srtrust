@extends('frontEnd.layouts.master')
@section('title', 'Wishlist')
@section('content')
    <div class="wishlist-wrapper">

        <section class="vcart-section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="vcart-inner">
                            <div class="cart-title">
                                <h4>@lang('common.wishlist')</h4>
                            </div>
                            <div class="vcart-content" id="wishlist">

                                @foreach ($products as $key => $value)
                                <div class="product_item wist_item">
                                    @include('frontEnd.layouts.partials.product')
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
