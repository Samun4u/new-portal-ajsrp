@extends('auth.layouts.app')

@push('title')
{{ __('Registration') }}
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/jpswalsh/academicons@1/css/academicons.min.css">
    <style>

 /* Fix height issue for large screens */
/* Parent section full screen flex layout */

[data-background]
{
    background-size: 400px;
}

    .signLog-section .left .wrap {
            padding: 50px 20px;
    }
    /* .max-w-167
    {
        max-width: 6.4375rem;
    }
    .mb-30 {
        margin-bottom: -0.125rem !important;
    } */

        .signLog-section {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .zMain-signLog-content {
        width: 100%;
        max-width: 400px;
        margin: auto;
        padding: 20px;
    }

    

    /* Scaling fix: if content beshi hoy screen er moddhe fit korbe */
    @media (max-height: 800px) {
        .zMain-signLog-content {
            transform: scale(0.9);
            transform-origin: top center;
        }
    }

    @media (max-height: 700px) {
        .zMain-signLog-content {
            transform: scale(0.8);
            transform-origin: top center;
        }
    }


        .btn-google {
            background: #DB4437;
            color: white;
        }

        .btn-google:hover {
            background: #c33d30;
            color: white;
        }

        .btn-linkedin {
            background: #0077B5;
            color: white;
        }

        .btn-linkedin:hover {
            background: #005f8c;
            color: white;
        }

        .btn-orcid {
            background: #A6CE39;
            color: white;
        }

        .btn-orcid:hover {
            background: #89a82f;
            color: white;
        }

        .icon-size {
            font-size: 20px !important;
        }
        
        /* Responsive improvements */
        .social-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
        }
        
        .social-btn span {
            flex-grow: 1;
            text-align: center;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 10px 0;
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .separator:not(:empty)::before {
            margin-right: 15px;
        }
        
        .separator:not(:empty)::after {
            margin-left: 15px;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .left {
                padding: 20px;
            }
            
            .social-btn {
                padding: 10px;
                font-size: 14px;
            }
            
            .zMain-signLog-content {
                padding: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .social-btn {
                font-size: 12px;
            }
            
            .separator {
                margin: 15px 0;
            }

            .signLog-section .left .wrap {
                padding: 60px 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="signLog-section">
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    {{-- <a href="{{ route('frontend') }}" class="d-flex max-w-167 mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" class="w-100" alt="{{ getOption('app_name') }}" />
                    </a> --}}
                    <div class="pb-30">
                        <h4 class="fs-32 fw-600 lh-48 text-title-black pb-5">{{ __('Sign up') }}</h4>
                        <p class="fs-14 fw-400 lh-22 text-para-text">{{ __('Already have an account') }}? <a
                                href="{{ route('login') }}" class="fw-500 text-main-color text-decoration-underline">{{
                            __('Sign In') }}</a></p>
                    </div>
                    <form action="{{ route('register') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="pb-20">
                            <label for="inputFullName" class="zForm-label">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control zForm-control" id="inputFullName" name="name"
                                   placeholder="{{ __('Enter full name') }}" value="{{ old('name') }}" />
                            @error('name')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-20">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress"
                                   value="{{ old('email') }}" name="email" placeholder="{{ __('Enter email address') }}" />
                            @error('email')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-30">
                            <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                            <div class="passShowHide">
                                <input type="password" class="form-control zForm-control passShowHideInput"
                                       id="inputPassword" placeholder="{{ __('Enter your password') }}" name="password" />
                                <button type="button" toggle=".passShowHideInput"
                                        class="toggle-password fa-solid fa-eye"></button>
                                @error('password')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="pb-30">
                            <button type="submit" class="border-0 w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white mb-3" style="margin-bottom: 10px;">{{ __('Sign Up') }}</button>
                            <div class="separator">{{ __('Or') }}</div>
                            
                            <a href="{{ route('google-login') }}" class="btn social-btn btn-google">
                                <span>{{ __('Sign in with Google') }}</span>
                                <i class="fab fa-google icon-size ml-2"></i>
                            </a>
                            <a href="{{ route('linkedin.login') }}" class="btn social-btn btn-linkedin">
                                <span>{{ __('Sign in with LinkedIn') }}</span>
                                <i class="fab fa-linkedin icon-size ml-2"></i> 
                            </a>
                            <a href="{{ route('orcid.login') }}" class="btn social-btn btn-orcid">
                                <span>{{ __('Sign in with ORCID') }}</span> 
                                <i class="ai ai-orcid icon-size ml-2"></i> 
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('app_logo') }}"></div>
    </div>
@endsection
