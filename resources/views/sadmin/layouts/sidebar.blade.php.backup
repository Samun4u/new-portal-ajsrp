<div data-aos="fade-right" data-aos-duration="1000" class="zSidebar">
    <div class="zSidebar-overlay"></div>
    <div class="zSidebar-wrap h-100">
        <div class="zSidebar-leftBar"></div>
        <!-- Logo -->
        @if (auth()->user()->role == USER_ROLE_ADMIN)
        <a href="{{ route('super-admin.dashboard') }}" class="zSidebar-logo">
            <img class="max-h-35" src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
        </a>
        @else
        <a href="{{ route('super-admin.dashboard') }}" class="zSidebar-logo">
            <img class="max-h-35" src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
        </a>
        @endif
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('MAIN') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets/images/icon/double-angle-right.svg') }}" alt="" />
                    </div>
                </li>
                <li>
                    <a href="{{ route('super-admin.dashboard') }}"
                        class="{{ $activeDashboard ?? '' }} d-flex align-items-center cg-10">
                        <div class="d-flex">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.88979 10.3929C6.14657 10.3929 5.62851 10.3924 5.22349 10.3635C4.82565 10.3351 4.59466 10.2819 4.4186 10.2051C3.89833 9.97813 3.48308 9.56288 3.25609 9.04261C3.17928 8.86655 3.12616 8.63556 3.09774 8.23773C3.0688 7.83271 3.06836 7.31465 3.06836 6.57143C3.06836 5.82821 3.0688 5.31015 3.09774 4.90513C3.12616 4.50729 3.17928 4.2763 3.25609 4.10024C3.48308 3.57997 3.89833 3.16473 4.4186 2.93773C4.59466 2.86092 4.82565 2.80781 5.22349 2.77938C5.6285 2.75045 6.14657 2.75 6.88979 2.75C7.63301 2.75 8.15107 2.75045 8.55609 2.77938C8.95392 2.80781 9.18491 2.86092 9.36097 2.93773C9.88124 3.16473 10.2965 3.57997 10.5235 4.10024C10.6003 4.2763 10.6534 4.50729 10.6818 4.90513C10.7108 5.31015 10.7112 5.82821 10.7112 6.57143C10.7112 7.31465 10.7108 7.83271 10.6818 8.23773C10.6534 8.63556 10.6003 8.86655 10.5235 9.04261C10.2965 9.56288 9.88124 9.97813 9.36097 10.2051C9.18491 10.2819 8.95392 10.3351 8.55609 10.3635C8.15107 10.3924 7.63301 10.3929 6.88979 10.3929Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                                <path
                                    d="M6.88979 21.25C6.14657 21.25 5.62851 21.2496 5.22349 21.2207C4.82565 21.1922 4.59466 21.1391 4.4186 21.0623C3.89833 20.8353 3.48308 20.4201 3.25609 19.8998C3.17928 19.7237 3.12616 19.4927 3.09774 19.0949C3.0688 18.6899 3.06836 18.1718 3.06836 17.4286C3.06836 16.6854 3.0688 16.1673 3.09774 15.7623C3.12616 15.3645 3.17928 15.1335 3.25609 14.9574C3.48308 14.4372 3.89833 14.0219 4.4186 13.7949C4.59466 13.7181 4.82565 13.665 5.22349 13.6366C5.6285 13.6076 6.14657 13.6072 6.88979 13.6072C7.63301 13.6072 8.15107 13.6076 8.55609 13.6366C8.95392 13.665 9.18491 13.7181 9.36097 13.7949C9.88124 14.0219 10.2965 14.4372 10.5235 14.9574C10.6003 15.1335 10.6534 15.3645 10.6818 15.7623C10.7108 16.1673 10.7112 16.6854 10.7112 17.4286C10.7112 18.1718 10.7108 18.6899 10.6818 19.0949C10.6534 19.4927 10.6003 19.7237 10.5235 19.8998C10.2965 20.4201 9.88124 20.8353 9.36097 21.0623C9.18491 21.1391 8.95392 21.1922 8.55609 21.2207C8.15107 21.2496 7.63301 21.25 6.88979 21.25Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                                <path
                                    d="M17.7472 10.3929C17.004 10.3929 16.4859 10.3924 16.0809 10.3635C15.6831 10.3351 15.4521 10.2819 15.276 10.2051C14.7558 9.97813 14.3405 9.56288 14.1135 9.04261C14.0367 8.86655 13.9836 8.63556 13.9552 8.23773C13.9262 7.83271 13.9258 7.31465 13.9258 6.57143C13.9258 5.82821 13.9262 5.31015 13.9552 4.90513C13.9836 4.50729 14.0367 4.2763 14.1135 4.10024C14.3405 3.57997 14.7558 3.16473 15.276 2.93773C15.4521 2.86092 15.6831 2.80781 16.0809 2.77938C16.4859 2.75045 17.004 2.75 17.7472 2.75C18.4904 2.75 19.0085 2.75045 19.4135 2.77938C19.8113 2.80781 20.0423 2.86092 20.2184 2.93773C20.7387 3.16473 21.1539 3.57997 21.3809 4.10024C21.4577 4.2763 21.5108 4.50729 21.5393 4.90513C21.5682 5.31015 21.5686 5.82821 21.5686 6.57143C21.5686 7.31465 21.5682 7.83271 21.5393 8.23773C21.5108 8.63556 21.4577 8.86655 21.3809 9.04261C21.1539 9.56288 20.7387 9.97813 20.2184 10.2051C20.0423 10.2819 19.8113 10.3351 19.4135 10.3635C19.0085 10.3924 18.4904 10.3929 17.7472 10.3929Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                                <path
                                    d="M17.7472 21.25C17.004 21.25 16.4859 21.2496 16.0809 21.2207C15.6831 21.1922 15.4521 21.1391 15.276 21.0623C14.7558 20.8353 14.3405 20.4201 14.1135 19.8998C14.0367 19.7237 13.9836 19.4927 13.9552 19.0949C13.9262 18.6899 13.9258 18.1718 13.9258 17.4286C13.9258 16.6854 13.9262 16.1673 13.9552 15.7623C13.9836 15.3645 14.0367 15.1335 14.1135 14.9574C14.3405 14.4372 14.7558 14.0219 15.276 13.7949C15.4521 13.7181 15.6831 13.665 16.0809 13.6366C16.4859 13.6076 17.004 13.6072 17.7472 13.6072C18.4904 13.6072 19.0085 13.6076 19.4135 13.6366C19.8113 13.665 20.0423 13.7181 20.2184 13.7949C20.7387 14.0219 21.1539 14.4372 21.3809 14.9574C21.4577 15.1335 21.5108 15.3645 21.5393 15.7623C21.5682 16.1673 21.5686 16.6854 21.5686 17.4286C21.5686 18.1718 21.5682 18.6899 21.5393 19.0949C21.5108 19.4927 21.4577 19.7237 21.3809 19.8998C21.1539 20.4201 20.7387 20.8353 20.2184 21.0623C20.0423 21.1391 19.8113 21.1922 19.4135 21.2207C19.0085 21.2496 18.4904 21.25 17.7472 21.25Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                 @if (isAddonInstalled('ENCYSAAS') > 0)
                <li>
                    <a href="{{ route('super-admin.packages.index') }}"
                        class="d-flex align-items-center cg-10 {{ $activePackageIndex ?? '' }}">
                        <div class="d-flex {{ isset($activePackageIndex) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="19" cy="5" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('Packages') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.packages.user') }}"
                        class="d-flex align-items-center cg-10 {{ $navSubscriptionActiveClass ?? '' }}">
                        <div class="d-flex {{ isset($navSubscriptionActiveClass) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="19" cy="5" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('User Packages') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.subscriptions.orders') }}"
                        class="d-flex align-items-center cg-10 {{ $activeSubscriptionIndex ?? '' }}">
                        <div class="d-flex {{ isset($activeSubscriptionIndex) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="19" cy="5" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('All Orders') }}</span>
                    </a>
                </li>
                 @endif
                <li>
                    <a href="{{ route('super-admin.setting.profile.index') }}"
                        class="{{ $activeProfile ?? '' }} d-flex align-items-center cg-10">
                        <div class="d-flex">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.50016 0.833313C9.51683 0.833313 10.4918 1.23718 11.2107 1.95607C11.9296 2.67496 12.3335 3.64998 12.3335 4.66665C12.3335 5.68331 11.9296 6.65833 11.2107 7.37722C10.4918 8.09611 9.51683 8.49998 8.50016 8.49998C7.4835 8.49998 6.50848 8.09611 5.78959 7.37722C5.0707 6.65833 4.66683 5.68331 4.66683 4.66665C4.66683 3.64998 5.0707 2.67496 5.78959 1.95607C6.50848 1.23718 7.4835 0.833313 8.50016 0.833313ZM8.50016 10.4166C12.736 10.4166 16.1668 12.1321 16.1668 14.25V16.1666H0.833496V14.25C0.833496 12.1321 4.26433 10.4166 8.50016 10.4166Z" fill="#6E6F81"></path>
                            </svg>
                        </div>
                        <span class="">{{ __('Profile') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.user.list') }}"
                        class="d-flex align-items-center cg-10 {{ $activeUserList ?? '' }}">
                        <div class="d-flex">
                            <svg width="19" height="15" viewBox="0 0 19 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.85 6.375C3.89797 6.375 4.75 5.42207 4.75 4.25C4.75 3.07793 3.89797 2.125 2.85 2.125C1.80203 2.125 0.95 3.07793 0.95 4.25C0.95 5.42207 1.80203 6.375 2.85 6.375ZM16.15 6.375C17.198 6.375 18.05 5.42207 18.05 4.25C18.05 3.07793 17.198 2.125 16.15 2.125C15.102 2.125 14.25 3.07793 14.25 4.25C14.25 5.42207 15.102 6.375 16.15 6.375ZM17.1 7.4375H15.2C14.6775 7.4375 14.2055 7.67324 13.8611 8.05508C15.0575 8.78887 15.9066 10.1137 16.0906 11.6875H18.05C18.5755 11.6875 19 11.2127 19 10.625V9.5625C19 8.39043 18.148 7.4375 17.1 7.4375ZM9.5 7.4375C11.3377 7.4375 12.825 5.77402 12.825 3.71875C12.825 1.66348 11.3377 0 9.5 0C7.66234 0 6.175 1.66348 6.175 3.71875C6.175 5.77402 7.66234 7.4375 9.5 7.4375ZM11.78 8.5H11.5336C10.9161 8.83203 10.2303 9.03125 9.5 9.03125C8.76969 9.03125 8.08687 8.83203 7.46641 8.5H7.22C5.33187 8.5 3.8 10.2133 3.8 12.325V13.2812C3.8 14.1611 4.43828 14.875 5.225 14.875H13.775C14.5617 14.875 15.2 14.1611 15.2 13.2812V12.325C15.2 10.2133 13.6681 8.5 11.78 8.5ZM5.13891 8.05508C4.79453 7.67324 4.3225 7.4375 3.8 7.4375H1.9C0.852031 7.4375 0 8.39043 0 9.5625V10.625C0 11.2127 0.424531 11.6875 0.95 11.6875H2.90641C3.09344 10.1137 3.9425 8.78887 5.13891 8.05508Z" fill="#5D697A"></path>
                            </svg>
                        </div>
                        <span class="">{{ __('Users') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.frontend-setting.index') }}"
                        class="d-flex align-items-center cg-10 {{ $activeFrontendList ?? '' }}">
                        <div class="d-flex {{ isset($activeFrontendList) ? 'active' : 'collapsed' }}">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.84308 3.80211C9.8718 2.6007 10.8862 2 12 2C13.1138 2 14.1282 2.6007 16.1569 3.80211L16.8431 4.20846C18.8718 5.40987 19.8862 6.01057 20.4431 7C21 7.98943 21 9.19084 21 11.5937V12.4063C21 14.8092 21 16.0106 20.4431 17C19.8862 17.9894 18.8718 18.5901 16.8431 19.7915L16.1569 20.1979C14.1282 21.3993 13.1138 22 12 22C10.8862 22 9.8718 21.3993 7.84308 20.1979L7.15692 19.7915C5.1282 18.5901 4.11384 17.9894 3.55692 17C3 16.0106 3 14.8092 3 12.4063V11.5937C3 9.19084 3 7.98943 3.55692 7C4.11384 6.01057 5.1282 5.40987 7.15692 4.20846L7.84308 3.80211Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.4" />
                                <circle cx="12" cy="12" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('Frontend Setting') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.application-settings') }}"
                        class="d-flex align-items-center cg-10 {{ $activeApplicationSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeApplicationSetting) ? 'active' : 'collapsed' }}">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.84308 3.80211C9.8718 2.6007 10.8862 2 12 2C13.1138 2 14.1282 2.6007 16.1569 3.80211L16.8431 4.20846C18.8718 5.40987 19.8862 6.01057 20.4431 7C21 7.98943 21 9.19084 21 11.5937V12.4063C21 14.8092 21 16.0106 20.4431 17C19.8862 17.9894 18.8718 18.5901 16.8431 19.7915L16.1569 20.1979C14.1282 21.3993 13.1138 22 12 22C10.8862 22 9.8718 21.3993 7.84308 20.1979L7.15692 19.7915C5.1282 18.5901 4.11384 17.9894 3.55692 17C3 16.0106 3 14.8092 3 12.4063V11.5937C3 9.19084 3 7.98943 3.55692 7C4.11384 6.01057 5.1282 5.40987 7.15692 4.20846L7.84308 3.80211Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.4" />
                                <circle cx="12" cy="12" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>
                        </div>
                        <span class="">{{ __('General Settings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.configuration-settings') }}"
                        class="d-flex align-items-center cg-10 {{ $activeConfigurationSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeConfigurationSetting) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.84308 3.80211C9.8718 2.6007 10.8862 2 12 2C13.1138 2 14.1282 2.6007 16.1569 3.80211L16.8431 4.20846C18.8718 5.40987 19.8862 6.01057 20.4431 7C21 7.98943 21 9.19084 21 11.5937V12.4063C21 14.8092 21 16.0106 20.4431 17C19.8862 17.9894 18.8718 18.5901 16.8431 19.7915L16.1569 20.1979C14.1282 21.3993 13.1138 22 12 22C10.8862 22 9.8718 21.3993 7.84308 20.1979L7.15692 19.7915C5.1282 18.5901 4.11384 17.9894 3.55692 17C3 16.0106 3 14.8092 3 12.4063V11.5937C3 9.19084 3 7.98943 3.55692 7C4.11384 6.01057 5.1282 5.40987 7.15692 4.20846L7.84308 3.80211Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="12" cy="12" r="3" stroke="white" stroke-width="1.5" stroke-opacity="0.7" />
                            </svg>
                        </div>
                        <span class="">{{ __('App Configuration') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.currencies.index') }}"
                        class="d-flex align-items-center cg-10 {{ $activeCurrenciesSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeCurrenciesSetting) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.78182 3.89076C10.3457 3.41023 10.6276 3.16997 10.9224 3.02907C11.6042 2.7032 12.3968 2.7032 13.0786 3.02907C13.3734 3.16997 13.6553 3.41023 14.2192 3.89076C14.4436 4.08201 14.5558 4.17764 14.6757 4.25796C14.9504 4.44209 15.2589 4.56988 15.5833 4.63393C15.7249 4.66188 15.8718 4.6736 16.1658 4.69706C16.9043 4.75599 17.2735 4.78546 17.5816 4.89427C18.2941 5.14594 18.8546 5.7064 19.1062 6.41893C19.2151 6.72699 19.2445 7.09625 19.3035 7.83475C19.3269 8.12868 19.3386 8.27564 19.3666 8.41718C19.4306 8.74163 19.5584 9.05014 19.7426 9.32485C19.8229 9.44469 19.9185 9.55691 20.1098 9.78133C20.5903 10.3452 20.8305 10.6271 20.9714 10.9219C21.2973 11.6037 21.2973 12.3963 20.9714 13.0781C20.8305 13.3729 20.5903 13.6548 20.1098 14.2187C19.9185 14.4431 19.8229 14.5553 19.7426 14.6752C19.5584 14.9499 19.4306 15.2584 19.3666 15.5828C19.3386 15.7244 19.3269 15.8713 19.3035 16.1653C19.2445 16.9038 19.2151 17.273 19.1062 17.5811C18.8546 18.2936 18.2941 18.8541 17.5816 19.1058C17.2735 19.2146 16.9043 19.244 16.1658 19.303C15.8718 19.3264 15.7249 19.3381 15.5833 19.3661C15.2589 19.4301 14.9504 19.5579 14.6757 19.7421C14.5558 19.8224 14.4436 19.918 14.2192 20.1093C13.6553 20.5898 13.3734 20.8301 13.0786 20.971C12.3968 21.2968 11.6042 21.2968 10.9224 20.971C10.6276 20.8301 10.3457 20.5898 9.78182 20.1093C9.55739 19.918 9.44518 19.8224 9.32534 19.7421C9.05063 19.5579 8.74211 19.4301 8.41767 19.3661C8.27613 19.3381 8.12917 19.3264 7.83524 19.303C7.09673 19.244 6.72748 19.2146 6.41942 19.1058C5.70689 18.8541 5.14643 18.2936 4.89476 17.5811C4.78595 17.273 4.75648 16.9038 4.69755 16.1653C4.67409 15.8713 4.66236 15.7244 4.63442 15.5828C4.57037 15.2584 4.44257 14.9499 4.25845 14.6752C4.17813 14.5553 4.0825 14.4431 3.89125 14.2187C3.41072 13.6548 3.17045 13.3729 3.02956 13.0781C2.70369 12.3963 2.70369 11.6037 3.02956 10.9219C3.17045 10.6271 3.41072 10.3452 3.89125 9.78133C4.0825 9.55691 4.17813 9.4447 4.25845 9.32485C4.44257 9.05014 4.57037 8.74163 4.63442 8.41718C4.66236 8.27564 4.67409 8.12868 4.69755 7.83475C4.75648 7.09625 4.78595 6.72699 4.89476 6.41893C5.14643 5.7064 5.70689 5.14594 6.41942 4.89427C6.72748 4.78546 7.09674 4.75599 7.83524 4.69706C8.12917 4.6736 8.27613 4.66188 8.41767 4.63393C8.74211 4.56988 9.05063 4.44209 9.32534 4.25796C9.44518 4.17764 9.5574 4.08201 9.78182 3.89076Z"
                                    stroke="white" stroke-width="1.5" stroke-opacity="0.7" />
                                <path d="M9 15L15 9" stroke="white" stroke-width="1.5" stroke-opacity="0.7"
                                    stroke-linecap="round" />
                                <path
                                    d="M15.5 14.5C15.5 15.0523 15.0523 15.5 14.5 15.5C13.9477 15.5 13.5 15.0523 13.5 14.5C13.5 13.9477 13.9477 13.5 14.5 13.5C15.0523 13.5 15.5 13.9477 15.5 14.5Z"
                                    fill="white" />
                                <path
                                    d="M10.5 9.5C10.5 10.0523 10.0523 10.5 9.5 10.5C8.94772 10.5 8.5 10.0523 8.5 9.5C8.5 8.94772 8.94772 8.5 9.5 8.5C10.0523 8.5 10.5 8.94772 10.5 9.5Z"
                                    fill="white" />
                            </svg>
                        </div>
                        <span class="">{{ __('Currency Settings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.gateway.index') }}"
                        class="d-flex align-items-center cg-10 {{ $activeGatewaySetting ?? '' }}">
                        <div class="d-flex {{ isset($activeGatewaySetting) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 8H10" stroke="white" stroke-width="1.5" stroke-opacity="0.7"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M20.8333 9H18.2308C16.4465 9 15 10.3431 15 12C15 13.6569 16.4465 15 18.2308 15H20.8333C20.9167 15 20.9583 15 20.9935 14.9979C21.5328 14.965 21.9623 14.5662 21.9977 14.0654C22 14.0327 22 13.994 22 13.9167V10.0833C22 10.006 22 9.96726 21.9977 9.9346C21.9623 9.43384 21.5328 9.03496 20.9935 9.00214C20.9583 9 20.9167 9 20.8333 9Z"
                                    stroke="white" stroke-width="1.5" stroke-opacity="0.7" />
                                <path
                                    d="M20.965 9C20.8873 7.1277 20.6366 5.97975 19.8284 5.17157C18.6569 4 16.7712 4 13 4L10 4C6.22876 4 4.34315 4 3.17157 5.17157C2 6.34315 2 8.22876 2 12C2 15.7712 2 17.6569 3.17157 18.8284C4.34315 20 6.22876 20 10 20H13C16.7712 20 18.6569 20 19.8284 18.8284C20.6366 18.0203 20.8873 16.8723 20.965 15"
                                    stroke="white" stroke-width="1.5" stroke-opacity="0.7" />
                                <path d="M17.9922 12H18.0012" stroke="white" stroke-opacity="0.7" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="">{{ __('Payment Getaway') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.languages.index') }}"
                        class="d-flex align-items-center cg-10 {{ $activeLanguagesSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeLanguagesSetting) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3559 18.0028L16.4299 14.1599L14.5039 18.0028" stroke="white"
                                    stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M14.8516 17.3188H18.0195" stroke="white" stroke-opacity="0.7"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M16.4274 21C13.9075 21 11.8555 18.9569 11.8555 16.4279C11.8555 13.9079 13.8985 11.856 16.4274 11.856C18.9474 11.856 20.9994 13.8989 20.9994 16.4279C20.9994 18.9569 18.9564 21 16.4274 21Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M5.71799 3H9.24597C11.109 3 12.009 3.90001 11.964 5.718V9.24597C12.009 11.109 11.109 12.009 9.24597 11.964H5.71799C3.9 12 3 11.1 3 9.23696V5.709C3 3.9 3.9 3 5.71799 3Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9.31023 6.46484H5.65625" stroke="white" stroke-opacity="0.7"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7.47266 5.85291V6.46489" stroke="white" stroke-opacity="0.7"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.38952 6.45587C8.38952 8.03086 7.15652 9.30885 5.64453 9.30885"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9.30951 9.30897C8.65252 9.30897 8.05853 8.95796 7.64453 8.39996"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 14.7C3 18.1829 5.81699 20.9999 9.29997 20.9999L8.35497 19.4249"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M20.9992 9.29997C20.9992 5.81699 18.1822 3 14.6992 3L15.6442 4.57499"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="">{{ __('Multi Language') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.email-template') }}"
                        class="d-flex align-items-center cg-10 {{ $activeEmailSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeEmailSetting) ? 'active' : 'collapsed' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" />
                                <circle cx="19" cy="5" r="3" stroke="white" stroke-opacity="0.7" stroke-width="1.5" />
                            </svg>

                        </div>
                        <span class="">{{ __('Email Template') }}</span>
                    </a>
                </li>
                @if (isAddonInstalled('ENCYSAAS') > 0)
                    <li>
                        <a href="{{ route('super-admin.file-version-update') }}"
                           class="{{ $activeVersionUpdate ?? '' }} d-flex align-items-center cg-10">
                            <div class="d-flex {{ $activeVersionUpdate ?? '' }}">
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_953_924)">
                                        <path d="M1.88647 4.98682V10.9868H7.88647" stroke="white" stroke-opacity="0.7"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.8865 20.9868V14.9868H17.8865" stroke="white" stroke-opacity="0.7"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M21.3765 9.98689C20.8693 8.55368 20.0073 7.27229 18.871 6.26231C17.7347 5.25233 16.361 4.54666 14.8782 4.21115C13.3954 3.87564 11.8517 3.92123 10.3913 4.34367C8.93085 4.7661 7.60122 5.55161 6.52647 6.62689L1.88647 10.9869M23.8865 14.9869L19.2465 19.3469C18.1717 20.4222 16.8421 21.2077 15.3817 21.6301C13.9212 22.0526 12.3776 22.0981 10.8948 21.7626C9.41194 21.4271 8.03827 20.7215 6.90194 19.7115C5.76561 18.7015 4.90364 17.4201 4.39647 15.9869"
                                            stroke="white" stroke-opacity="0.7" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_953_924">
                                            <rect width="24" height="24" fill="white"
                                                  transform="translate(0.886475 0.986816)" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </div>
                            <span class="">{{ __('Version Update') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-25">
                <img src="{{ asset('assets/images/icon/logout.svg') }}" alt="" />
                <p class="fs-15 fw-500 lh-18 text-para-text">{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
