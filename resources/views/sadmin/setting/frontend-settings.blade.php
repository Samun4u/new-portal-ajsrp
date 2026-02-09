@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <h4 class="fs-18 fw-600 lh-20 text-title-black pb-16">{{ $title }}</h4>

    <div class="row rg-20">
        <div class="col-xl-3">
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                @include('sadmin.setting.partials.frontend-sidebar')
            </div>
        </div>
        <div class="col-xl-9">
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ $title }}</h4>
                <form class="ajax" action="{{ route('super-admin.setting.application-settings.update') }}" method="POST"
                    enctype="multipart/form-data" data-handler="settingCommonHandler">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-lg-6">
                            <div class="">
                                <div class="primary-form-group-wrap">
                                    <label class="zForm-label">{{ __('Active Status') }}</label>
                                    <select name="frontend_status"
                                        class="sf-select-without-search primary-form-control">
                                        <option value="1" {{ getOption('frontend_status')==1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}</option>
                                        <option value="0" {{ getOption('frontend_status')==0 ? 'selected' : '' }}>
                                            {{ __('Disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <div class="primary-form-group-wrap">
                                    <label class="zForm-label">{{ __('Registration Active') }}</label>
                                    <select name="registration_status"
                                        class="sf-select-without-search primary-form-control">
                                        <option value="0">{{ __('Select') }}</option>
                                        <option value="1" {{ getOption('registration_status', 0)=='1' ? 'selected' : ''
                                            }}>
                                            {{ __('Enable') }}</option>
                                        <option value="0" {{ getOption('registration_status', 0)=='0' ? 'selected' : ''
                                            }}>
                                            {{ __('Disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Meta Keyword') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="meta_keyword" value="{{ getOption('meta_keyword') }}"
                                    class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Meta Author') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="meta_author" value="{{ getOption('meta_author') }}"
                                    class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Meta Description') }} <span
                                        class="text-danger">*</span> </label>
                                <input type="text" name="meta_description" value="{{ getOption('meta_description') }}"
                                    class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Facebook link') }} </label>
                                <input type="text" name="social_media_facebook"
                                    value="{{ getOption('social_media_facebook') }}" class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Twitter Link') }} </label>
                                <input type="text" name="social_media_twitter"
                                    value="{{ getOption('social_media_twitter') }}" class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Linkedin link') }} </label>
                                <input type="text" name="social_media_linkedin"
                                    value="{{ getOption('social_media_linkedin') }}" class="form-control zForm-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="zForm-label">{{ __('Skype Link') }} </label>
                                <input type="text" name="social_media_skype"
                                    value="{{ getOption('social_media_skype') }}" class="form-control zForm-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex g-12 flex-wrap mt-25">
                                <button type="submit"
                                    class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                    __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection