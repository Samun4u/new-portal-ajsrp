@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{ __($pageTitle) }}</h4>
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                <div class="row">
                    <input type="hidden" value="{{ route('super-admin.setting.email-template') }}"
                           id="email-temp-route">
                    <div class="col-lg-12">
                        <div class="customers__area bg-style mb-30">
                            <div class="customers__table">
                                <div class="table-responsive zTable-responsive">
                                    <table class="table zTable zTable-last-item-right" id="">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div>{{ __('Title') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Subject') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Action') }}</div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($emailTemplates as $key => $template)
                                            <tr>
                                                <input type="hidden" name="category" value="{{ $template->category }}">
                                                <td>{{ $template->title }}</td>
                                                <td> {{ $template->subject }}</td>
                                                <td>
                                                    <ul class="d-flex align-items-center cg-5 justify-content-end">
                                                        <li>
                                                            <button
                                                                class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white edit"
                                                                data-id="{{ $template->id }}">
                                                                <img
                                                                    src="{{ asset('assets/images/icon/edit-black.svg') }}"
                                                                    alt="edit"/>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <div class="modal fade" id="emailConfigureModal" tabindex="-1" aria-labelledby="emailConfigureModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Edit Email Template') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('admin.setting.email.template.config.update') }}" method="POST"
                      data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="row rg-20">
                        <!-- Available Placeholders Info -->
                        <div class="col-12">
                            <p class="alert-success p-20 templateFields"></p>
                        </div>

                        <div class="col-12">
                            <label for="title" class="zForm-label">{{ __('Title') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="title" name="title"
                                   placeholder="{{ __('Title') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="zForm-label">{{ __('Subject (English)') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="subject" name="subject"
                                   placeholder="{{ __('Subject') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject_ar" class="zForm-label">{{ __('Subject (Arabic)') }}</label>
                            <input type="text" class="form-control zForm-control" id="subject_ar" name="subject_ar"
                                   placeholder="{{ __('Arabic Subject') }}" dir="rtl">
                        </div>
                        <div class="col-12 mb-25">
                            <label for="body" class="zForm-label">{{ __('Body (English)') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control summernoteOne" name="body" id="body"
                                      placeholder="Body"></textarea>
                        </div>
                        <div class="col-12 mb-25">
                            <label for="body_ar" class="zForm-label">{{ __('Body (Arabic)') }}</label>
                            <textarea class="form-control zForm-control summernoteOne" name="body_ar" id="body_ar"
                                      placeholder="Arabic Body" dir="rtl"></textarea>
                        </div>
                    </div>

                    <button type="submit"
                            class="m-0 fs-15 fw-500 lh-25 py-10 px-26 bg-cdef84 hover-bg-one bd-ra-12 border-0">{{ __('Update') }}</button>

                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="emailTemplateConfigRoute" value="{{ route('admin.setting.email.template.config') }}">
@endsection
@push('script')
    <script src="{{ asset('sadmin/js/email-temp.js') }}"></script>
@endpush
