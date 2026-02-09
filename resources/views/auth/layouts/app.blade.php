<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('admin.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }} {{ !(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR) ? 'custom-color' : '' }}">
<!-- Main Content -->
@yield('content')

<!-- js file  -->
@include('admin.layouts.script')
</body>
</html>
