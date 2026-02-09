<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ getOption('app_name') }} - @stack('title' ?? '')</title>
    @hasSection('meta')
        @stack('meta')
    @else
        @php
            $metaData = getMeta('home');
        @endphp

            <!-- Open Graph meta tags for social sharing -->
        <meta property="og:type" content="{{ __('zaisub') }}">
        <meta property="og:title" content="{{ $metaData['meta_title'] ?? getOption('app_name') }}">
        <meta property="og:description" content="{{ $metaData['meta_description'] ?? getOption('app_name') }}">
        <meta property="og:image" content="{{ $metaData['og_image'] ?? getSettingImage('app_logo') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{getOption('app_name') }}">

        <!-- Twitter Card meta tags for Twitter sharing -->
        <meta name="twitter:card" content="{{ __('zaisub') }}">
        <meta name="twitter:title" content="{{ $metaData['meta_title'] ?? getOption('app_name') }}">
        <meta name="twitter:description" content="{{ $metaData['meta_description'] ?? getOption('app_name') }}">
        <meta name="twitter:image" content="{{ $metaData['og_image'] ?? getSettingImage('app_logo') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">

    <!-- css file  -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.responsive.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/summernote/summernote-lite.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/plugins.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/scss/style.css')}}?v=1" />
    <link rel="stylesheet" href="{{asset('common/css/common.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/scss/extra-style.css') }}" />
    <style>
        /* Add to your existing styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .zMainContent {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Existing footer styles */
        footer {
            margin-top: auto; /* Pushes footer to bottom */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        /* Keep other existing styles */
        }

        footer a {
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        /* Social Media Icons */
        footer a i {
            font-size: 20px;
            transition: transform 0.3s ease-in-out;
        }

        /* Hover Effects */
        .hover-effect:hover {
            color: #ffc107 !important;
            text-decoration: underline !important;
        }

        footer a i:hover {
            transform: scale(1.2);
            color: #ffc107 !important;
        }

        /* Mobile Optimization */
        @media (max-width: 576px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
            }

            .footer-container p {
                margin-bottom: 5px;
            }
        }
    </style>
    @include('sadmin.setting.partials.dynamic-color')


    @stack('style')
</head>
