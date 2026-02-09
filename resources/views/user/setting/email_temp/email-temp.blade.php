@extends('layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div class="p-30">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($title) }}</h4>
            <div class="bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
                <div class="row">
                    <input type="hidden" value="{{ route('admin.setting.email-template') }}" id="email-temp-route">
                    <div class="col-lg-12">
                        <div class="customers__area bg-style mb-30">
                            <div class="customers__table">
                                <div class="table-responsive zTable-responsive">
                                    <table class="table zTable" id="">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div>{{ __('Title') }}</div>
                                                </th>
                                                <th>
                                                    <div>{{ __('Details') }}</div>
                                                </th>
                                                <th class="text-center">
                                                    <div>{{ __('Action') }}</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (emailAdminTemplates() as $key => $template)
                                                <tr>
                                                    <input type="hidden" name="category" value="{{ $key }}">
                                                    <td>{{ __($template['title']) }}</td>
                                                    <td> {{ __($template['details']) }}</td>
                                                    <td>
                                                        <ul class="d-flex align-items-center cg-5 justify-content-center">
                                                            <li>
                                                                <button
                                                                    class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white templateConfigure">
                                                                    <img src="{{ asset('assets/images/icon/edit.svg') }}"
                                                                        alt="edit" />
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Email Template') }}</h5>
                    <button type="button" class="border-0 btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="ajax" action="{{ route('admin.setting.email.template.config.update') }}" method="POST"
                    data-handler="commonResponse">
                    @csrf
                    <input type="hidden" name="category">
                    <div class="modal-body model-lg">
                        <div class="row">
                            <div class="col-md-12 mb-25">
                                <p class="alert-success p-20 templateFields"></p>
                            </div>
                            <div class="col-md-12">
                                <div class="primary-form-group mt-2">
                                    <div class="primary-form-group-wrap">
                                        <label for="subject" class="form-label">{{ __('Subject') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="primary-form-control" id="subject" name="subject"
                                            placeholder="{{ __('Subject') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-25  m-t-2">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap mt-3">
                                        <label for="body" class="form-label">{{ __('Body') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="summernoteOne" name="body" id="body" placeholder="Body"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"
                            class="fs-15 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one border-0">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="emailTemplateConfigRoute" value="{{ route('admin.setting.email.template.config') }}">
@endsection
@push('script')
    <script src="{{ asset('assets/js/email-temp.js') }}"></script>
@endpush
