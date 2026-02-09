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
                <form class="ajax reset" action="{{route('admin.certificate.reviewer.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.certificate.reviewer.list')}}">
                    @csrf
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <!--  -->
                            <div class="bd-b-one bd-c-stroke pb-40 mb-36">
                                <div class="row rg-20">

                                    <div class="col-12">
                                        <label for="rtl" class="zForm-label">{{ __('Order') }}</label>
                                        <select class="sf-select-without-search" name="client_order_id" required>
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach ($orderList as $order)
                                                <option value="{{$order->order_id}}">{{$order->order_id.' ('.getEmailByUserId($order->client_id).')'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" value='{{ route("admin.certificate.reviewer.order-details") }}' id="orderDetailsUrl">
                                     <input type="hidden" id="reviewerCertificateType" value="add">

                                    <div class="col-12">
                                        <label for="rtl" class="zForm-label">{{ __('Reviewer') }}</label>
                                        <select id="reviewer_id" class="sf-select-without-search" name="reviewer_id" required>
                                            <option value="">{{ __('Select') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="addTitle" class="zForm-label">{{__('Title')}}</label>
                                        <input name="title" type="text" class="form-control zForm-control"
                                               id="addTitle" placeholder="{{__('Enter Title')}}" required/>
                                    </div>

                                    <div class="col-12">
                                        <label for="addAffiliations" class="zForm-label">{{__('Affiliations')}}</label>
                                        <input name="affiliations" type="text" class="form-control zForm-control"
                                               id="addAffiliations" placeholder="{{__('Enter Affiliations')}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addPaperTitle" class="zForm-label">{{__('Paper Title')}}</label>
                                        <input name="paper_title" type="text" class="form-control zForm-control"
                                               id="addPaperTitle" placeholder="{{__('Enter Paper Title')}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addJournalName" class="zForm-label">{{__('Journal Name')}}</label>
                                        <input name="journal_name" type="text" class="form-control zForm-control"
                                               id="addJournalName" placeholder="{{__('Enter Journal Name')}}" required/>
                                    </div>

                                    <div class="col-12">
                                        <label for="language" class="zForm-label">{{ __('Certificate Language') }}</label>
                                        <select class="sf-select-without-search" name="language" required>
                                            <option value="en" selected>{{ __('English') }}</option>
                                            <option value="ar">{{ __('Arabic') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="chief_editor_name" class="zForm-label">{{__('Chief Managing Editor (English)')}}</label>
                                        <input name="chief_editor_name" type="text" class="form-control zForm-control"
                                               id="chief_editor_name" placeholder="{{__('Enter Editor Name')}}" value="Dr. Jane Smith"/>
                                    </div>

                                    <div class="col-12">
                                        <label for="chief_editor_name_ar" class="zForm-label">{{__('Chief Managing Editor (Arabic)')}}</label>
                                        <input name="chief_editor_name_ar" type="text" class="form-control zForm-control"
                                               id="chief_editor_name_ar" placeholder="{{__('Enter Editor Name in Arabic')}}"/>
                                    </div>

                                    <div class="col-12">
                                        <label for="signature_image" class="zForm-label">{{__('Signature Image')}}</label>
                                        <input name="signature_image" type="file" class="form-control zForm-control" id="signature_image" accept="image/*"/>
                                    </div>

                                    <div class="col-12">
                                        <label for="logo_image" class="zForm-label">{{__('Logo Image')}}</label>
                                        <input name="logo_image" type="file" class="form-control zForm-control" id="logo_image" accept="image/*"/>
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
@push('script')
    <script>
         const selectText = "{{ __('Select') }}";
    </script>
    <script src="{{ asset('admin/custom/js/reviewer_certificate.js') }}"></script>
@endpush
