@php
    $coupon_code = Session::get('coupon_used');
@endphp
<ul class="voucher-box">
    @foreach ($coupons as $key => $value)
        <li class="voucher-item">
            <div class="voucher-item-inner">
                <div class="voucher-flex">
                    <div class="left">
                        <img src="{{ asset($generalsetting->white_logo) }}" alt="">
                        <p class="voucher-title">Voucher Code</p>
                        <p class="voucher-code">{{ $value->coupon_code }}</p>
                    </div>
                    <div class="right">
                        <p class="voucher-upto">UP TO ৳{{ $value->amount }} OFF</p>
                        <p class="voucher-minbuy">Min. Spend ৳{{ $value->buy_amount }}</p>
                        <p class="voucher-validity">Valid until
                            {{ \Carbon\Carbon::parse($value->expiry_date)->format('Y-m-d') }} | 11:59 PM</p>
                        <button class="voucher-copy-button" {{ $coupon_code == $value->coupon_code ? 'disabled' : '' }}
                            data-coupon="{{ $value->coupon_code }}">
                            {{ $coupon_code == $value->coupon_code ? 'Copied' : 'Copy' }}</button>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>
