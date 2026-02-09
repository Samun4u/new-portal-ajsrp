<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('user.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }} {{ !(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR) ? 'custom-color' : '' }}">
@if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
        </div>
    </div>
@endif
<div class="zMain-wrap">
    <!-- Sidebar -->
    @include('user.layouts.sidebar')
    <!-- Main Content -->
    <div class="zMainContent">
        <!-- Header -->
        @include('user.layouts.nav')
        <!-- Content -->
       <main class="flex-grow-1"> <!-- Added flex-grow-1 here -->
          @yield('content')
        </main>
    </div>
</div>
<!-- resources/views/components/whatsapp-button.blade.php -->
<div class="container mt-4">
    <a href="https://wa.me/970569831045?text=أهلا بكم، سيقوم فريق التحرير بالتواصل معكم - المؤسسة العربية للعلوم ونشر الأبحاث" target="_blank" 
       class="whatsapp-btn btn btn-light rounded-pill d-flex align-items-center justify-content-center" 
       style="max-width: 200px; position: fixed; bottom: 20px; right: 20px; z-index: 1000; opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease;">
        <div class="whatsapp-icon me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#25D366" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.87.098-.177.208-.076.395.101.187.449.8.964 1.297.662.64 1.221.838 1.394.929.173.091.273.08.376-.043s.43-.511.545-.683c.114-.17.229-.142.385-.085s1.006.472 1.179.56c.173.087.288.131.33.202.042.072.042.419-.101.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/>
            </svg>
        </div>
        <span class="text-end" style="font-family: 'Arial', sans-serif; direction: rtl;">تحدث معنا - واتساب</span>
    </a>
</div>
@include('user.layouts.footer')

@if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
@endif
@include('user.layouts.script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var whatsappBtn = document.querySelector('.whatsapp-btn');
        if (whatsappBtn) {
            whatsappBtn.style.opacity = '1';
            whatsappBtn.style.transform = 'translateY(0)';
        }
    }, 500); // 500ms delay before animation starts
});
</script>
</body>

</html>