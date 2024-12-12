@extends('frontEnd.layouts.master')
@section('title','Login')
@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="form-content">
                    <p class="auth-title">@lang('common.customerlogin')  </p>
                    <form action="{{route('customer.signin')}}" method="POST"  data-parsley-validate="">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="phone">@lang('common.mobilenumber') *</label>
                            <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror"  placeholder="@lang('common.mobilenumber')" name="phone" value="{{ old('phone') }}"  required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- col-end -->
                        <div class="form-group mb-3">
                            <label for="password">@lang('common.password') *</label>
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="@lang('common.password')" name="password" value="{{ old('password') }}"  required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- col-end -->
                        <a href="{{route('customer.forgot.password')}}" class="forget-link"><i class="fa-solid fa-unlock"></i>@lang('common.forgotpassword')</a>
                        <div class="form-group mb-3">
                            <button class="submit-btn"> @lang('common.login') </button>
                        </div>
                     <!-- col-end -->
                     </form>
                     <div class="register-now no-account">
                        <p>@lang('common.noaccount')  <a href="{{route('customer.register')}}"><i data-feather="edit-3"></i> @lang('common.registernow')</a></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
@endpush
