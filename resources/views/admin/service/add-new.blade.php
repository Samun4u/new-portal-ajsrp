@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Add Services")}}</h4>
                    <!--  -->
                </div>
                <form class="ajax reset" action="{{route('admin.services.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseRedirect" data-redirect-url="{{route('admin.services.list')}}">
                    @csrf

                    <!--  -->
                    <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 px-sm-25">
                            <!--  -->
                            <div class="row rg-20 pb-20">
                                <div class="col-12">
                                    <label for="createServiceServiceName" class="zForm-label">{{__("Service Name")}}
                                        <span class="text-red">*</span></label>
                                    <input type="text" class="form-control zForm-control" id="createServiceServiceName"
                                           placeholder="{{__("Service Name")}}" name="service_name"/>
                                </div>
                                <div class="col-12">
                                    <label for="createServiceDescription" class="zForm-label">{{__("Description")}}
                                        <span class="text-red">*</span></label>
                                    <textarea id="createServiceDescription" class="form-control zForm-control min-h-175"
                                              placeholder="{{__("Write description here....")}}"
                                              name="service_description"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center g-10 pb-8">
                                        <label for="createServiceAssignMember"
                                               class="zForm-label mb-0">{{__("Assign to a team member")}}</label>
                                        <span class="fs-12 fw-400 lh-20 text-para-text">{{__("Optional")}}</span>
                                    </div>
                                    <select class="sf-select-two" name="assign_member[]" multiple>
                                        @foreach($teamMember as $data)
                                            <option value="{{$data->id}}">{{$data->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center g-10 pb-8">
                                        <label for="createServiceSetDeadline"
                                               class="zForm-label mb-0">{{__("Duration (Day)")}}</label>
                                        <span class="fs-12 fw-400 lh-20 text-para-text">{{__("Optional")}}</span>
                                    </div>
                                    <input type="number" class="form-control zForm-control"
                                           name="duration" min="1" placeholder="{{(__("Service Day"))}}"/>
                                </div>
                                <div class="col-12 table-responsive">
                                    <label for="createServiceSetDeadline"
                                           class="zForm-label_">{{__("Extra Feature Fields")}}</label>
                                    <table class="table zTable zTable-last-item-right" id="inputTable">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="text-nowrap">Feature</div>
                                            </th>
                                            <th>
                                                <div>Value</div>
                                            </th>
                                            <th>
                                                <div>Action</div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="featureItems">
                                        <tr>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="text" name="feature[name][]"
                                                           class="feature-name form-control zForm-control zForm-control-table"
                                                           placeholder="Feature Name">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="text" name="feature[value][]"
                                                           class="feature-value form-control zForm-control zForm-control-table"
                                                           placeholder="Feature Value">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red removeFeature">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" id="addMoreFeatureBtn"
                                            class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline">
                                        +Add More
                                    </button>
                                </div>
                                <div class="col-12">
                                    <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload Image (JPG, JPEG, PNG)")}}</p>
                                    <div class="d-flex align-items-center g-10">
                                        <!--  -->
                                        <div class="servicePhotoUpload d-flex flex-column g-10 w-100">
                                            <label for="zImageUpload">
                                                <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                            </label>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 800 PX/400 PX")}}</span>
                                            <div class="max-w-150 flex-shrink-0">
                                                <img src="" class="p-10" id="serviceImage"/>
                                                <input type="file" name="image" id="zImageUpload" accept="image/*"
                                                       class="position-absolute invisible"
                                                       onchange="previewFile(this)"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-40">
                        <!--  -->
                        <div class="d-flex justify-content-between align-items-start flex-wrap g-10">
                            <!--  -->
                            <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Pricing")}}</h4>
                            <!--  -->
                            <ul class="nav nav-tabs justify-content-center flex-wrap zTab-reset zTab-one pr-9 rg-20"
                                id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active payment-type" id="OnTimePricing-tab"
                                            data-payment_type="{{PAYMENT_TYPE_ONETIME}}" data-bs-toggle="tab"
                                            data-bs-target="#OnTimePricing-tab-pane" type="button" role="tab"
                                            aria-controls="OnTimePricing-tab-pane" aria-selected="true">
                                        <span>{{__("On time pricing")}}</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link payment-type" id="recurringServices-tab"
                                            data-payment_type="{{PAYMENT_TYPE_RECURRING}}" data-bs-toggle="tab"
                                            data-bs-target="#recurringServices-tab-pane" type="button" role="tab"
                                            aria-controls="recurringServices-tab-pane" aria-selected="false">
                                        <span>{{__("Recurring Services")}}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <!--  -->
                        <div class="tab-content" id="myTabContent">
                            <input type="hidden" name="payment_type" id="payment_type" value="{{PAYMENT_TYPE_ONETIME}}">
                            <div class="tab-pane fade show active" id="OnTimePricing-tab-pane" role="tabpanel"
                                 aria-labelledby="OnTimePricing-tab" tabindex="0">
                                <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                                    <div class="max-w-713 m-auto py-sm-52 px-sm-25">
                                        <!--  -->
                                        <div class="row rg-20 pb-20">
                                            <div class="col-12">
                                                <label for="createServicePrice"
                                                       class="zForm-label">{{__("Price")}}</label>
                                                <div class="sf-input-wrap">
                                                    <div class="flex-grow-1">
                                                        <input type="text" class="form-control zForm-control"
                                                               id="createServicePrice" placeholder="0.00"
                                                               name="onetime_price"/>
                                                    </div>
                                                    <p class="currency">{{currentCurrencyType()}}</p>
                                                    {{--                                                <select class="sf-select-two" disabled>--}}
                                                    {{--                                                    <option value="0">{{currentCurrencyType()}}</option>--}}
                                                    {{--                                                </select>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="recurringServices-tab-pane" role="tabpanel"
                                 aria-labelledby="recurringServices-tab" tabindex="0">
                                <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                                    <div class="max-w-713 m-auto py-sm-52 px-sm-25">
                                        <!--  -->
                                        <div class="row rg-20 pb-20">
                                            <div class="col-12">
                                                <label for="createServicePrice"
                                                       class="zForm-label">{{__('Price')}}</label>
                                                <div class="sf-input-wrap">
                                                    <div class="flex-grow-1">
                                                        <input type="text" class="form-control zForm-control"
                                                               id="createServicePrice" placeholder="0.00"
                                                               name="recurring_price"/>
                                                    </div>
                                                    <p class="currency">{{currentCurrencyType()}}</p>
                                                    {{--                                                <select class="sf-select-two" disabled>--}}
                                                    {{--                                                    <option value="0">{{currentCurrencyType()}}</option>--}}
                                                    {{--                                                </select>--}}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="createServiceEvery"
                                                       class="zForm-label">{{__("Every")}}</label>
                                                <select class="sf-select-two" name="recurring_type">
                                                    <option value="{{RECURRING_EVERY_MONTH}}">{{__("Month")}}</option>
                                                    <option value="{{RECURRING_EVERY_DAY}}">{{__("Day")}}</option>
                                                    <option value="{{RECURRING_EVERY_YEAR}}">{{__("Year")}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->

                    <div class="mb-40">

                        <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                            <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Faqs")}}</h4>
                        </div>
                        <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                            <!--  -->
                            <div class="m-auto py-sm-52 px-sm-25">
                                <!--  -->
                                <div class="col-12 table-responsive">
                                    <table class="table zTable zTable-last-item-right" id="inputTable">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="text-nowrap">Question</div>
                                            </th>
                                            <th>
                                                <div>Answer</div>
                                            </th>
                                            <th>
                                                <div>Action</div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="faqItems">
                                        <tr>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="text" name="faqs[question][]"
                                                           class="faqs-question form-control zForm-control zForm-control-table" placeholder="Question">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="min-w-100">
                    <textarea name="faqs[answer][]" class="faqs-answer form-control zForm-control zForm-control-table" cols="30"
                              rows="2" placeholder="Answer here"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red removeFaq">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" id="addMoreFaqBtn"
                                            class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline">
                                        +Add More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex g-12 mt-25">
                        <button type="submit" id="saveBtn"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__("Save")}}</button>
                        <a href="{{ URL::previous() }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="d-none" id="clone-faq">
        <tr>
            <td>
                <div class="min-w-100">
                    <input type="text" name="faqs[question][]"
                           class="faqs-question form-control zForm-control zForm-control-table" placeholder="Question">
                </div>
            </td>
            <td>
                <div class="min-w-100">
                    <textarea name="faqs[answer][]" class="faqs-answer form-control zForm-control zForm-control-table" cols="30"
                              rows="2" placeholder="Answer here"></textarea>
                </div>
            </td>
            <td>
                <button type="button"
                        class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red removeFaq">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    </table>

    <table class="d-none" id="clone-custom-features">
        <tr>
            <td>
                <div class="min-w-100">
                    <input type="text" name="feature[name][]"
                           class="feature-name form-control zForm-control zForm-control-table"
                           placeholder="Feature Name">
                </div>
            </td>
            <td>
                <div class="min-w-100">
                    <input type="text" name="feature[value][]"
                           class="feature-value form-control zForm-control zForm-control-table"
                           placeholder="Feature Value">
                </div>
            </td>
            <td>
                <button type="button"
                        class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red removeFeature">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    </table>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/services.js') }}"></script>
@endpush

