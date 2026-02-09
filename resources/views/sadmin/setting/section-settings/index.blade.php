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

                <table class="table zTable zTable-last-item-right" id="frontendSectionDataTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('SL') }}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{ __('Section Name') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Title') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Image') }}</div>
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
<div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->

<input type="hidden" id="frontend-section" value="{{ route('super-admin.setting.frontend-setting.section.index') }}">
@endsection
@push('script')
<script src="{{ asset('sadmin/custom/js/frontend_section.js') }}"></script>
@endpush