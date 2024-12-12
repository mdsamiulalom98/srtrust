@extends('frontEnd.layouts.master')
@section('title', 'Categories')

@section('content')

    <div class="categories-page-wrapper">
        <div class="container-fluid">
            <div class="page-title">
                <h2>@lang('common.allcategories')</h2>
            </div>

            <div class="page-content-wrapper">
                <div class="page-left-wrapper">
                    <div class="page-left-inner">
                        @foreach ($categories as $key => $value)
                            <div class="main-category-item {{ $key == 0 ? 'active' : '' }}" data-id="{{ $value->id }}">
                                <div class="inner">
                                    <img src="{{ asset($value->image) }}" alt="">
                                    <h2>{{ $value->name }}</h2>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="page-right-wrapper" id="subcategoryWrapper">
                    @include('frontEnd.layouts.ajax.categories')
                </div>
            </div>
        </div>
    </div>




@endsection

@push('script')

    <script>
        $(".main-category-item").on("click", function() {
            $(".main-category-item").removeClass('active');
            $(this).addClass('active');
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('category.items') }}",
                    success: function(data) {
                        if (data) {
                            $("#subcategoryWrapper").html(data);
                        }
                    },
                });
            }
        });
    </script>
@endpush
