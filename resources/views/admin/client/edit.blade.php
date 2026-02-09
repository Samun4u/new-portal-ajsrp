@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__($pageTitle)}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <form class="ajax reset" action="{{route('admin.client.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <input type="hidden" name="id" value="{{$clientDetails->id}}">
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <!--  -->
                            <div class="bd-b-one bd-c-stroke pb-40 mb-36">
                                <div class="row rg-20">
                                    <div class="col-12">
                                        <label for="addClientName" class="zForm-label">{{__('Name')}}</label>
                                        <input name="client_name" type="text" class="form-control zForm-control"
                                               id="addClientName" placeholder="{{__('Enter Name')}}" value="{{$clientDetails->name}}"/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addClientEmail" class="zForm-label">{{__('Email')}}</label>
                                        <input name="client_email" type="email" class="form-control zForm-control"
                                               id="addClientEmail" placeholder="{{__('Enter Email')}}" value="{{$clientDetails->email}}"/>
                                    </div>
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                            <label for="addClientPassword"
                                                   class="zForm-label mb-0">{{__('Password')}}</label>
                                        </div>
                                        <input name="client_password" type="password" class="form-control zForm-control"
                                               id="addClientPassword" placeholder="{{__('Enter Password')}}" />
                                    </div>
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                            <label for="addClientPhoneNumber"
                                                   class="zForm-label mb-0">{{__('Phone Number')}}</label>
                                            <p class="fs-14 fw-400 lh-22 text-para-text">{{__('Optional')}}</p>
                                        </div>
                                        <input name="client_phone_number" type="number"
                                               class="form-control zForm-control" id="addClientPhoneNumber"
                                               placeholder="{{__('Enter Phone Number')}}" value="{{$clientDetails->mobile}}"/>
                                    </div>
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                            <label for="addClientCompany"
                                                   class="zForm-label mb-0">{{__('Company')}}</label>
                                            <p class="fs-14 fw-400 lh-22 text-para-text">{{__('Optional')}} </p>
                                        </div>
                                        <input name="client_company_name" type="text" class="form-control zForm-control"
                                               id="addClientCompany" placeholder="{{__('Enter Company')}}" value="{{$clientDetails->company_name}}"/>
                                    </div>

                                    <div class="col-12">
                                        <label for="rtl" class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                        <select class="sf-select-without-search" name="status">
                                            <option {{ $clientDetails->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                            <option {{ $clientDetails->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__('Profile Picture (JPG, JPEG, PNG)')}}</p>
                                        <div class="d-flex align-items-center g-10">
                                            <!--  -->
                                            <div class="servicePhotoUpload d-flex flex-column g-10 w-100">
                                                <label for="zImageUpload">
                                                    <p class="fs-12 fw-500 lh-16 text-para-text">{{__('Choose Image to upload')}}</p>
                                                    <p class="fs-12 fw-500 lh-16 text-white">{{__('Browse File')}}</p>
                                                </label>
                                                <span
                                                    class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__('Recommended: 800 PX/400 PX')}}</span>
                                                <div class="max-w-150 flex-shrink-0">
                                                    <img src="{{getFileUrl($clientDetails->image)}}" class="p-10"/>
                                                    <input name="image" type="file" id="zImageUpload"
                                                           accept="image/*" class="position-absolute invisible"
                                                           onchange="previewFile(this)"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Save')}}</button>
                        <a href="{{ URL::previous() }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
