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
                <button class="cart-change-button" data-id="{{ $value->id }}" data-action="increase">
                    <i class="fa fa-plus"></i>
                </button>
                <div class="cart-quantity" data-id="{{ $value->id }}">
                    {{ Session::get('locale') == 'bn' ? $numto->bnNum($item->qty) : $item->qty }}</div>
                <button class="cart-change-button" data-id="{{ $value->id }}" data-action="decrease">
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
                Buy Now
                <i class="fa fa-long-arrow-right"></i>
            </button>
        </form>
    </div>
@endif
