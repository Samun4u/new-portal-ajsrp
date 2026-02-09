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
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-30">
                    <h4 class="fs-18 fw-600 lh-22 text-title-black ">{{ $title }}</h4>
                    <div>
                        <button
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white"
                            type="button" data-bs-toggle="modal" data-bs-target="#add-modal">
                            <i class="fa fa-plus"></i> {{ __('Add Service') }}
                        </button>
                    </div>
                </div>

                <table class="table zTable zTable-last-item-right" id="serviceDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('Image') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Title') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Status') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Action') }}</div>
                            </th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>
</div>

<!-- Add Modal section start -->
<div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25">
            <div class="modal-header p-20 border-0 pb-0">
                <h5 class="modal-title fs-18 fw-600 lh-24 text-1b1c17">{{ __($title) }}</h5>
                <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
            </div>
            <form class="ajax reset" action="{{ route('super-admin.setting.service.store') }}" method="post"
                data-handler="commonResponseForModal" enctype="multipart/form-data">
                @csrf

                <div class="row rg-20">
                    <div class="col-12">
                        <label for="currentPassword" class="zForm-label">{{ __('Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" name="name"
                            placeholder="{{ __('name') }}">
                    </div>
                    <div class="col-12">
                        <label for="currentPassword" class="zForm-label">{{ __('Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" name="title"
                            placeholder="{{ __('title') }}">
                    </div>
                    <div class="col-12">
                        <label for="currentPassword" class="zForm-label">{{ __('Sub Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" name="sub_title"
                            placeholder="{{ __('sub title') }}">
                    </div>
                    <div class="col-12">
                        <label for="currentPassword" class="zForm-label">{{ __('Description') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control zForm-control" name="description" id="" cols="30" rows="6"
                            placeholder="{{ __('description') }}"></textarea>
                    </div>
                    <div class="col-12">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                <div class="zImage-inside">
                                    <div class="d-flex pb-12"><img
                                            src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                    </div>
                                    <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                    </p>
                                </div>
                                <label for="zImageUpload" class="zForm-label">{{ __('Image') }} <span
                                        class="text-danger">*</span></label>
                                <div class="upload-img-box">
                                    <img src="" />
                                    <input type="file" name="image" id="image" accept="image/*"
                                        onchange="previewFile(this)" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white mt-25">{{
                    __('Save')
                    }}</button>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<!-- Edit Modal section start -->
<div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->

<input type="hidden" id="service_setting" value="{{ route('super-admin.setting.service.index') }}">
@endsection
@push('script')
<script src="{{ asset('sadmin/custom/js/service_setting.js') }}"></script>
@endpush