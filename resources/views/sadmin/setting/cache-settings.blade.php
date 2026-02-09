@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($title) }}</h4>
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('sadmin.setting.partials.general-sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        <h4 class="fs-18 fw-600 lh-22 text-title-black pb-16 text-center text-sm-start">{{ $title }}</h4>
                        <div class="item-top mb-30">
                            <h2>{{ __(@$pageTitle) }}</h2>
                        </div>
                        <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                            <p class="fs-16 fw-400 lh-22 text-title-black">{{ __('Clear View Cache') }}</p>
                            <a href="{{ route('super-admin.setting.cache-update', 1) }}"
                               class="d-inline-block fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color hover-bg-one bd-ra-12">{{
                                    __('Click Here') }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                            <p class="fs-16 fw-400 lh-22 text-title-black">{{ __('Clear Route Cache') }}</p>
                            <a href="{{ route('super-admin.setting.cache-update', 2) }}"
                               class="d-inline-block fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color hover-bg-one bd-ra-12">{{
                                    __('Click Here') }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                            <p class="fs-16 fw-400 lh-22 text-title-black">{{ __('Clear Config Cache') }}</p>
                            <a href="{{ route('super-admin.setting.cache-update', 3) }}"
                               class="d-inline-block fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color hover-bg-one bd-ra-12">{{
                                    __('Click Here') }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                            <p class="fs-16 fw-400 lh-22 text-title-black">{{ __('Application Clear Cache') }}</p>
                            <a href="{{ route('super-admin.setting.cache-update', 4) }}"
                               class="d-inline-block fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color hover-bg-one bd-ra-12">{{
                                    __('Click Here') }}</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                            <p class="fs-16 fw-400 lh-22 text-title-black">{{ __('Storage Link') }}</p>
                            <a href="{{ route('super-admin.setting.cache-update', 5) }}"
                               class="d-inline-block fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color hover-bg-one bd-ra-12">{{
                                    __('Click Here') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
