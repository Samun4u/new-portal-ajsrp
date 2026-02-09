@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <form class="ajax" action="{{ route('admin.team-member.store') }}" method="POST" enctype="multipart/form-data"
                data-handler="commonResponseWithPageLoad">
                @csrf
                <input type="hidden" name="id" value="{{ $teamMember->id }}">
                <div class="max-w-894 m-auto">
                    <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                        <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Edit Team Member') }}</h4>
                    </div>
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <div class="row rg-20">
                                <div class="col-12">
                                    <label for="createTeamName" class="zForm-label">{{ __('Name') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createTeamName"
                                        name="name" value="{{ $teamMember->name }}"
                                        placeholder="{{ __('Enter Name') }}" />
                                </div>
                                <div class="col-12">
                                    <label for="createTeamEmailAddress"
                                        class="zForm-label">{{ __('Email Address') }}</label>
                                    <input type="email" class="form-control zForm-control" id="createTeamEmailAddress"
                                        name="email" placeholder="{{ __('Enter Email Address') }}" />
                                </div>
                                <div class="col-12">
                                    <label for="createTeamPassword" class="zForm-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control zForm-control" id="createTeamPassword"
                                        name="password" placeholder="{{ __('Enter Password') }}" />
                                </div>
                                <div class="col-12">
                                    <label for="createTeamDesignation" class="zForm-label">{{ __('Designation') }}</label>
                                    <select class="sf-select-two" name="designation_id">
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="createTeamRole" class="zForm-label">{{ __('Role') }}</label>
                                    <select class="sf-select-two" name="role">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <p class="fs-15 fw-600 lh-24 text-para-text pb-12">
                                        {{ __('Upload Image (JPG, JPEG, PNG)') }}</p>
                                    <div class="d-flex align-items-center g-10">
                                        <div class="servicePhotoUpload d-flex flex-column g-10 w-100">
                                            <label for="zImageUpload">
                                                <p class="fs-12 fw-500 lh-16 text-para-text">
                                                    {{ __('Choose Image to upload') }}
                                                </p>
                                                <p class="fs-12 fw-500 lh-16 text-white">{{ __('Browse File') }}</p>
                                            </label>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{ __('Recommended: 800/400') }}</span>
                                            <div class="max-w-150 flex-shrink-0">
                                                <img src="" class="p-10" />
                                                <input type="file" name="image" id="zImageUpload" accept="image/*"
                                                    class="position-absolute invisible" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-12 mt-25">
                        <button
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('Save') }}</button>
                        <a href="{{ route('admin.team-member.index') }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
