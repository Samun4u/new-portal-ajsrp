@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
        <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{$title}}</h4>
        <form method="POST" class="" enctype="multipart/form-data"
            action="{{ route('super-admin.setting.profile.update') }}">
            <div class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15 bg-white mb-25">
                @csrf

                <!-- image upload -->
                <div class="pb-40">
                    <div class="upload-img-box profileImage-upload">
                        <div class="icon"><img src="{{ asset('assets/images/icon/camera.svg') }}" alt="" /></div>
                        <img src="{{ getFileUrl($user->image) }}" />
                        <input type="file" name="image" id="zImageUpload" accept="image/*"
                            onchange="previewFile(this)" />
                    </div>
                </div>
                <!--  -->
                <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ __('Personal Information :') }}</h4>
                <!--  -->
                <div class="row rg-20 pb-40">
                    <div class="col-md-6">
                        <label for="editProfileFirstName" class="zForm-label">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control zForm-control" id="editProfileFirstName"
                            value="{{ $user->name }}" name="name" placeholder="{{ __('Enter First Name') }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="editProfileEmail" class="zForm-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control zForm-control" id="editProfileEmail"
                            value="{{ $user->email }}" name="email" placeholder="{{ __('Enter Email') }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="editProfilePassword" class="zForm-label">{{ __('Password') }}</label>
                        <input type="password" value="" name="pass1" class="form-control zForm-control"
                            id="editProfilePassword" placeholder="{{ __('Password') }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="editProfileRePassword" class="zForm-label">{{ __('Re Password') }}</label>
                        <input type="password" value="" name="pass2" class="form-control zForm-control"
                            id="editProfileRePassword" placeholder="{{ __('Re Enter Password') }}" />
                    </div>
                </div>
            </div>
            <div class="d-flex g-12 flex-wrap">
                <button type="submit"
                    class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                    __('Save Changes') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection