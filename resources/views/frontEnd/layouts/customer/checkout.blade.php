@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout') @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
@endpush @section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('discount') ?? 0;
        $cart = Cart::instance('shopping')->content();
        $customer = Auth::guard('customer')->user();
        $area_id = Session::get('area_id') ?? 0;
        $shipping_area = \App\Models\District::where('id', $area_id)->first();
        if ((float) $subtotal >= 500) {
            Session::put('shipping', 0);
        } else {
            $shipping_fee = $shipping_area->shippingfee ?? 0;
            Session::put('shipping', $shipping_fee);
        }
        $shipping = Session::get('shipping') ?? 0;
        $coupon = Session::get('coupon_amount') ?? 0;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-5 cus-order-2  mb-3">
                <div class="checkout-shipping">
                    <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h6 class = "check-position">@lang('common.checkouttitleone') <strong>{{ $contact->hotline }}</strong>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="name"><i class="fa-solid fa-user"></i>@lang('common.name')
                                                *</label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ $customer->name ?? old('name') }}"
                                                placeholder="@lang('common.name')" required />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="phone"><i class="fa-solid fa-phone"></i> @lang('common.phonenumber')
                                                *</label>
                                            <input type="text" minlength="11" id="number" maxlength="11"
                                                pattern="0[0-9]+"
                                                title="please enter number only and 0 must first character"
                                                title="Please enter an 11-digit number." id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ $customer->phone ?? old('phone') }}"
                                                placeholder="@lang('common.phonenumber')" required />
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="address"><i class="fa-solid fa-map-location-dot"></i>
                                                @lang('common.address') *</label>
                                            <input type="address" placeholder="@lang('common.address')" id="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address" value="{{ $customer->address ?? old('address') }}"
                                                required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <!--<label for="district">District *</label>-->
                                            <select id="district"
                                                class="form-control form-select select2 district @error('district') is-invalid @enderror"
                                                name="district" value="{{ old('district') }}" required>
                                                <option value="">@lang('common.district')</option>
                                                @foreach ($districts as $key => $district)
                                                    <option value="{{ $district->district }}">
                                                        {{ $district->district }}</option>
                                                @endforeach
                                            </select>
                                            @error('district')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <!--<label for="area">Upazila/Area *</label>-->
                                            <select id="area"
                                                class="form-control select2 form-select area  @error('area') is-invalid @enderror"
                                                name="area" required>
                                                <option value="">@lang('common.area')</option>
                                            </select>
                                            @error('area')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="radio_payment">
                                            <label id="payment_method">@lang('common.payment')</label>
                                        </div>
                                        <div class="payment-methods">

                                            <div class="form-check p_cash payment_method" data-id="cod">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio1" value="Cash On Delivery" checked required />
                                                <label class="form-check-label" for="inlineRadio1">
                                                    @lang('common.cashondelivery')
                                                </label>
                                            </div>
                                            @if ($bkash_gateway)
                                                <div class="form-check p_bkash payment_method" data-id="bkash">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio2" value="bkash" required />
                                                    <label class="form-check-label" for="inlineRadio2">
                                                        @lang('common.bkash')
                                                    </label>
                                                </div>
                                            @endif
                                            @if ($shurjopay_gateway)
                                                <div class="form-check p_shurjo payment_method" data-id="nagad">
                                                    <input class="form-check-input" type="radio"
                                                        name="payment_method" id="inlineRadio3" value="shurjopay"
                                                        required />
                                                    <label class="form-check-label" for="inlineRadio3">
                                                        Nagad
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="order_place" type="submit">@lang('common.orderplace')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card end -->

                    </form>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-7 cust-order-1">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>@lang('common.orderinfo')</h5>
                        </div>
                        <div class="card-body cartlist">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;">@lang('common.product')</th>
                                        <th style="width: 40%;">@lang('common.price')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <div class="cart-info-wrapper">
                                                    <div class="image">
                                                        <img src="{{ asset($value->options->image) }}" />
                                                    </div>
                                                    <div class="info">
                                                        <a href="{{ route('product', $value->options->slug) }}">
                                                            @lang('products.name' . $value->id)</a>
                                                        @if ($value->options->product_size)
                                                            <p>Size: {{ $value->options->product_size }}</p>
                                                        @endif
                                                        @if ($value->options->product_color)
                                                            <p>Color: {{ $value->options->product_color }}</p>
                                                        @endif
                                                        <div class="qty-cart vcart-qty">
                                                            <div class="quantity">
                                                                <button class="minus cart_decrement"
                                                                    data-id="{{ $value->rowId }}">-</button>
                                                                <input type="text" value="{{ $value->qty }}"
                                                                    readonly />
                                                                <button class="plus cart_increment"
                                                                    data-id="{{ $value->rowId }}">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="remove">
                                                        <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                                class="fas fa-trash text-danger"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span>৳ </span><strong>{{ $value->price }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="1" class="text-end px-4">@lang('common.subtotal')</th>
                                        <td class="px-4">
                                            <span id="net_total"><span>৳
                                                </span><strong>{{ $subtotal }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="1" class="text-end px-4">@lang('common.deliverycharge') </th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span>৳
                                                </span><strong>{{ $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="1" class="text-end px-4">@lang('common.discount')</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span>৳
                                                </span><strong>{{ $discount + $coupon }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="1" class="text-end px-4">@lang('common.total')</th>
                                        <td class="px-4">
                                            <span id="grand_total"><span>৳
                                                </span><strong>{{ $subtotal + $shipping - ($discount + $coupon) }}</strong></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <form
                                action="@if (Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif"
                                class="checkout-coupon-form" method="POST">
                                @csrf
                                <div class="coupon">
                                    <input type="text" name="coupon_code"
                                        placeholder="{{ Session::get('coupon_used') ?? __('common.applycoupon') }}"
                                        class="border-0 shadow-none form-control" />
                                    <input type="submit"
                                        value="{{ Session::get('coupon_used') ? __('common.remove') : __('common.apply') }}"
                                        class="border-0 shadow-none btn btn-theme" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->


        </div>
    </div>
</section>
@endsection
@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>

<script>
    $("#area").on("change", function() {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            data: {
                id: id
            },
            url: "{{ route('shipping.charge') }}",
            dataType: "html",
            success: function(response) {
                $(".cartlist").html(response);
            },
        });
    });
</script>

<script type="text/javascript">
    dataLayer.push({
        ecommerce: null
    }); // Clear the previous ecommerce object.
    dataLayer.push({
        event: "view_cart",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brand }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({
        ecommerce: null
    });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brands }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<!-- jQuery -->
@endpush
