@extends('auth.layouts.app')

@push('title')
    {{ __('Forget Password') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <a href="{{ route('frontend') }}" class="d-inline-flex mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
                    </a>
                    <h4 class="fs-32 fw-600 lh-48 text-title-black pb-24">{{ __('Forgot Password') }}?</h4>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="pb-30">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress" name="email"
                                value="{{ old(' email') }}" placeholder="{{ __(' Enter email address') }}" />
                            @error('email')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_right_image') }}"></div>
    </div>
@endsection
