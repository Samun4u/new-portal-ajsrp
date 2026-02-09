<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('user.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
@if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
        </div>
    </div>
@endif
<div class="zMain-wrap">
    @yield('content')
</div>

@if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
@endif
@include('user.layouts.script')
</body>

</html>
