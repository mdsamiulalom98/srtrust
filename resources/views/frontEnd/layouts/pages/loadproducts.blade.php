@foreach($products as $key => $value)
    <div class="product_item wist_item">
    <div class="product_item_inner">
        @if ($value->old_price)
            <div class="discount">
                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% </p> ছাড় 
            </div>
        @endif
        <div class="pro_img">
            <a href="{{ route('product', $value->slug) }}">
                <img src="{{ asset($value->image ? $value->image->image : '') }}"
                    alt="{{ $value->name }}" />
            </a>
        </div>
        <div class="pro_des">
            <div class="pro_name">
                <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
            </div>
            <div class="pro_price">
                @if ($value->variable_count > 0 && $value->type == 0)
                    <p>
                        @if ($value->variable->old_price)
                            <del>৳ {{ $value->variable->old_price }}</del>
                        @endif
                        ৳ {{ $value->variable->new_price }}
                    </p>
                @else
                    <p>
                        @if ($value->old_price)
                            <del>৳ {{ $value->old_price }}</del>
                        @endif
                        ৳ {{ $value->new_price }}
                    </p>
                @endif
            </div>
            <div class="pro_btn">
                @if ($value->variable_count > 0 && $value->type == 0)
                <div class="cart_btn">
                   <a href="{{ route('product', $value->slug) }}" data-id="{{ $value->id }}" class="addcartbutton"> অর্ডার করুন </a>
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
        </div>
    </div>
</div>
@endforeach