<div class="category-items-wrapper">
    @foreach ($subcategories as $key => $value)
        <div class="category-item">
            <a href="">
                <img src="{{ asset($value->image->image) }}" alt="">
            </a>
            <h2>{{ $value->name }}</h2>
        </div>
    @endforeach
</div>
