@extends('admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15 aos-init aos-animate">
    @if($quatationCount > 0)
    <div class="d-flex justify-content-between align-items-center flex-wrap pb-18">
        <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $pageTitle }}</h4>
        <a href="{{ route('admin.quotation.add') }}"
            class="d-inline-flex bd-ra-8 bg-main-color py-8 px-26 fs-15 fw-600 lh-25 text-white">{{ __('+ Add
            quotation') }}</a>
    </div>
    <div class="p-sm-30 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
        <table class="table zTable zTable-last-item-right" id="quotationDataList">
            <thead>
                <tr>
                    <th>
                        <div class="text-nowrap">{{ __('Quotation ID') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Price') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Customer') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Url') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Date') }}</div>
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
    @else
    <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
        <div class="create-wrap">
            <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt="" /></div>
            <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">{{__('There is no quotation available
                here!')}}</h4>
            <a href="{{route('admin.quotation.add')}}"
                class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">+ {{__('Add
                quotation')}}</a>
        </div>
    </div>
    @endif
</div>

{{-- quotation details model --}}
<div class="modal fade" id="quotationPreviewModal" tabindex="-1" aria-labelledby="quotationPreviewModal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-25 invoice-content-wrap">
            <div class="invoice-content">

            </div>
        </div>
    </div>
</div>

<input type="hidden" id="quotationListRoute" value="{{ route('admin.quotation.list') }}">
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/quotation.js') }}"></script>
@endpush
