@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $area_id = Session::get('area_id') ?? 0;
    $shipping_area = \App\Models\District::where('id', $area_id)->first();
    if($generalsetting->min_shopping > 0) {
        if ((float) $subtotal >= $generalsetting->min_shopping) {
            Session::put('shipping', 0);
        } else {
            $shipping_fee = $shipping_area->shippingfee ?? 0;
            // Session::put('shipping', $shipping_fee);
        }
    }
    $shipping = Session::get('shipping') ?? 0;
    $coupon = Session::get('coupon_amount') ?? 0;
    $discount = Session::get('discount') ?? 0;
    $lang = Session::get('locale');
@endphp
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
                                    <button class="minus cart_decrement" data-id="{{ $value->rowId }}">-</button>
                                    <input type="text" class="{{ $lang == 'bn' ? 'bangla-number' : '' }}" value="{{ $lang == 'bn' ? $numto->bnNum($value->qty) : $value->qty }}" readonly />
                                    <button class="plus cart_increment" data-id="{{ $value->rowId }}">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="remove">
                            <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                    class="fas fa-trash text-danger"></i></a>
                        </div>
                    </div>
                </td>
                <td>
                    <span>৳ </span><strong class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">{{ $lang == 'bn' ? $numto->bnNum($value->price) : $value->price }}</strong>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="text-end px-4">@lang('common.subtotal')</th>
            <td class="px-4">
                <span id="net_total"><span>৳
                    </span><strong class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">{{ $lang == 'bn' ? $numto->bnNum($subtotal) : $subtotal }}</strong></span>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="text-end px-4">@lang('common.deliverycharge') </th>
            <td class="px-4">
                <span id="cart_shipping_cost"><span>৳ </span><strong class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">{{ $lang == 'bn' ? $numto->bnNum($shipping) : $shipping }}</strong></span>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="text-end px-4">@lang('common.discount')</th>
            <td class="px-4">
                <span id="cart_shipping_cost"><span>৳
                    </span><strong class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">{{ $lang == 'bn' ? $numto->bnNum($discount + $coupon) : ($discount + $coupon) }}</strong></span>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="text-end px-4">@lang('common.total')</th>
            <td class="px-4">
                <span id="grand_total">
                    <span>৳ </span>
                    <strong class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">{{ $lang == 'bn' ? $numto->bnNum($subtotal + $shipping - ($discount + $coupon)) : $subtotal + $shipping - ($discount + $coupon) }}</strong>
                </span>
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
        <input type="submit" value="{{ Session::get('coupon_used') ? __('common.remove') : __('common.apply') }}"
            class="border-0 shadow-none btn btn-theme" />
    </div>
</form>

<script src="{{ asset('public/frontEnd/js/jquery-3.7.1.min.js') }}"></script>
<!-- cart js start -->
<script>
    $('.cart_remove').on('click', function() {
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                type: "GET",
                data: {
                    'id': id
                },
                url: "{{ route('cart.remove') }}",
                success: function(data) {
                    if (data) {
                        $(".cartlist").html(data);
                        return cart_count();
                    }
                }
            });
        }
    });

    $('.cart_increment').on('click', function() {
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                type: "GET",
                data: {
                    'id': id
                },
                url: "{{ route('cart.increment') }}",
                success: function(data) {
                    if (data) {
                        $(".cartlist").html(data);
                        return cart_count();
                    }
                }
            });
        }
    });

    $('.cart_decrement').on('click', function() {
        var id = $(this).data('id');
        if (id) {
            $.ajax({
                type: "GET",
                data: {
                    'id': id
                },
                url: "{{ route('cart.decrement') }}",
                success: function(data) {
                    if (data) {
                        $(".cartlist").html(data);
                        return cart_count();
                    }
                }
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
            }
        });
    };
</script>
