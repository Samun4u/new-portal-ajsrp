@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<!-- Page content area start -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <h4 class="fs-18 fw-600 lh-20 text-title-black pb-16">{{ $title }}</h4>
    <form method="POST" enctype="multipart/form-data" action="{{ route('super-admin.setting.user.store') }}">
        @csrf

        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15 mb-20">
            <div class="row rg-20">
                <div class="col-12">
                    <div class="upload-img-box profileImage-upload">
                        <div class="icon"><img src="{{ asset('assets/images/icon/camera.svg') }}" alt="" /></div>
                        <img src="{{ getFileUrl() }}" />
                        <input type="file" name="image" id="zImageUpload" accept="image/*"
                            onchange="previewFile(this)" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <label for="epFullName" class="zForm-label">{{ __('Full Name') }}
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="epFullName" name="name"
                            placeholder="{{ __('Your Name') }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <label for="epFullName" class="zForm-label">{{ __('Email') }} <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control zForm-control" id="epFullName" name="email"
                            placeholder="{{ __('Email') }}" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label for="epEmail" class="zForm-label">{{ __('Password') }} <span
                                class="text-danger">*</span></label>
                        <input type="password" value="" name="password" class="form-control zForm-control" id="epEmail"
                            placeholder="{{ __('Password') }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <label for="epFullName" class="zForm-label">{{ __('Mobile Number') }}
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="mobile" name="mobile"
                            placeholder="{{ __('Your Phone Number') }}" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label for="epFullName" class="zForm-label">{{ __('Country') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="country" name="country"
                            placeholder="{{ __('Your Country') }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <label for="epFullName" class="zForm-label">{{ __('Address') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="address" name="address"
                            placeholder="{{ __('Your Address') }}" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <label for="app_timezone" class="zForm-label">{{ __('Mobile No
                            Verification') }} <span class="text-danger">*</span></label>
                        <select name="phone_verification_status" class="sf-select-without-search">
                            <option>{{__('Select')}}</option>
                            <option value="{{ ACTIVE }}">{{__('Yes')}}</option>
                            <option value="{{ DEACTIVATE }}">{{__('No')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <label for="app_timezone" class="zForm-label">{{ __('Email Verification')
                            }} <span class="text-danger">*</span></label>
                        <select name="email_verification_status" class="sf-select-without-search">
                            <option>{{__('Select')}}</option>
                            <option value="{{ ACTIVE }}">{{__('Yes')}}</option>
                            <option value="{{ DEACTIVATE }}">{{__('No')}}</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <button type="submit"
            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
            __('Save User') }}</button>
    </form>
    <!-- Page content area end -->
    @endsection