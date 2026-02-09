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
                            <i class="fa fa-plus"></i> {{ __('Add Testimonial') }}
                        </button>
                    </div>
                </div>

                <table class="table zTable zTable-last-item-right" id="testimonialDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Image') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Designation') }}</div>
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


<!-- Add Modal section start -->
<div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25">

            <div
                class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-20 mb-20 bd-b-one bd-c-stroke">
                <h5 class="fs-18 fw-500 lh-24 text-title-black">{{ __('Add Testimonial') }}</h5>
                <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
            </div>

            <form class="ajax reset" action="{{route('super-admin.setting.testimonial.store')}}" method="post"
                data-handler="commonResponseForModal" enctype="multipart/form-data">
                @csrf

                <div class="row rg-20">
                    <div class="col-12">
                        <label for="name" class="zForm-label">{{ __('Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="{{ __('Type Name') }}"
                            class="form-control zForm-control">
                    </div>
                    <div class="col-12">
                        <label for="designation" class="zForm-label">{{ __('Designation') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control zForm-control" name="designation" rows="6"
                            placeholder="{{__('Type Designation')}}"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="designation" class="zForm-label">{{ __('Comment') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control zForm-control" name="comment" rows="6"
                            placeholder="{{__('Type Comment')}}"></textarea>
                    </div>

                    <div class="col-12">
                        <label for="name" class="zForm-label">{{ __('Company Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="company_name" id="company_name" placeholder="{{ __('Company Name') }}"
                            class="form-control zForm-control">
                    </div>

                    <div class="col-12">
                        <label for="name" class="zForm-label">{{ __('Rating') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" name="rating" id="name" placeholder="1" class="form-control zForm-control"
                            min="1" max="5">
                    </div>

                    <div class="col-12">
                        <label for="currency_placement" class="zForm-label">{{ __('Status') }}
                            <span class="text-danger">*</span></label>
                        <select class="primary-form-control sf-select-without-search" id="eventType" name="status">
                            <option value="">--{{ __('Select Status') }}--</option>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap mt-4 zImage-upload-details mw-100">
                                <div class="zImage-inside">
                                    <div class="d-flex pb-12"><img
                                            src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                    </div>
                                    <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                                </div>
                                <label for="zImageUpload" class="zForm-label">{{ __('Image') }} <span
                                        class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                                        class="text-danger">*</span></label>
                                <div class="upload-img-box">
                                    <img src="" />
                                    <input type="file" name="image" id="flag" accept="image/*"
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
<div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->

<input type="hidden" id="testimonial-area" value="{{ route('super-admin.setting.testimonial.index') }}">
@endsection
@push('script')
<script src="{{asset('sadmin/custom/js/testimonial.js')}}"></script>
@endpush