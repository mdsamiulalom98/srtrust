@php
    $lang = Session::get('locale');
@endphp
<div class="product_item_inner">

    <div class="pro_img">
        <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" />
        </a>
        @if ($value->old_price)
            <div class="discount">
                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 1) }}% </p>
            </div>
        @endif
    </div>
    <div class="pro_des">
        <div class="pro_name">
            <a class="{{ $lang == 'bn' ? 'bangla' : '' }}" href="{{ route('product', $value->slug) }}">{{ Str::limit(__('products.name' . $value->id), 70) }}</a>
        </div>
        {{--
        @if ($value->reviews)
        <div class="details-ratting-wrapper review_pro">
            @php
                $averageRating = $value->reviews->avg('ratting');
                $filledStars = floor($averageRating);
                $emptyStars = 5 - $filledStars;
                @endphp

                @if ($averageRating >= 0 && $averageRating <= 5)
                @for ($i = 1; $i <= $filledStars; $i++)
                <i class="fas fa-star"></i>
                @endfor

                @if ($averageRating == $filledStars)
                @else
                    <i class="far fa-star-half-alt"></i>
                    @endif

                    @for ($i = 1; $i <= $emptyStars; $i++)
                        <i class="far fa-star"></i>
                    @endfor

                    <span> ({{ number_format($averageRating, 1) }})</span>
                @else
                    <span>Invalid rating range</span>
            @endif
        </div>
        @endif
        --}}

        <div class="pro_price">
            @if ($value->variable_count > 0 && $value->type == 0)
                <p class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">
                    ৳ {{ $lang == 'bn' ? $numto->bnNum($value->variable->new_price) : $value->variable->new_price }}
                    @if ($value->variable->old_price)
                        <del>৳ {{ $lang == 'bn' ? $numto->bnNum($value->variable->old_price) : $value->variable->old_price }}</del>
                    @endif
                </p>
            @else
                <p class="{{ $lang == 'bn' ? 'bangla-number' : '' }}">
                    ৳ {{ $lang == 'bn' ? $numto->bnNum($value->new_price) : $value->new_price }}
                    @if ($value->old_price)
                        <del>৳ {{ $lang == 'bn' ? $numto->bnNum($value->old_price) : $value->old_price }}</del>
                    @endif
                </p>
            @endif
        </div>
        @if (
            (isset($value->variable) && ($value->variable->stock === null || $value->variable->stock < 1)) ||
                (!isset($value->variable) && ($value->stock === null || $value->stock < 1)))
            <div class="pro_btn">
                <a class="stock_out_btn">Out of stock</a>
            </div>
        @else
            {{--
        <div class="pro_btn">
            @if ($value->variable_count > 0 && $value->type == 0)
            <div class="cart_btn">
                <a href="{{ route('product', $value->slug) }}" data-id="{{ $value->id }}"
                    class="addcartbutton"> অর্ডার করুন </a>
                </div>
                @else
                <div class="cart_btn">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $value->id }}" />
                        <button type="submit">অর্ডার করুন </button>
                    </form>
                </div>
                @endif
            </div>
            --}}
            <div class="product-cart-wrapper product-item-{{ $value->id }}">
                @php
                    $cartItems = Cart::instance('shopping')->content();
                    $productInCart = null;

                    if ($cartItems->contains('id', $value->id)) {
                        $productInCart = $cartItems->where('id', $value->id)->first();
                    }
                @endphp
                @if ($productInCart)
                    @foreach ($cartItems as $item)
                        @if ($item->id == $value->id)
                            <div class="product-cart-buttons">
                                <button class="cart-change-button" data-id="{{ $value->id }}"
                                    data-action="increase">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="cart-quantity" data-id="{{ $value->id }}">{{ $lang == 'bn' ? $numto->bnNum($item->qty) : $item->qty }}</div>
                                <button class="cart-change-button" data-id="{{ $value->id }}"
                                    data-action="decrease">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="product-cart-buttons">
                        <button class="addcartbutton" data-id="{{ $value->id }}">
                            <i class="fa fa-plus"></i>
                        </button>
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $value->id }}" />
                            <button class="" type="submit">
                                @lang('common.buynow')
                                <i class="fa fa-long-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
