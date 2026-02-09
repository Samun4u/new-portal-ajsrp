@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="d-flex justify-content-between align-items-center flex-wrap pb-18">
            <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $pageTitle }}</h4>
            <a href="{{ route('admin.order-form.add') }}"
                class="d-inline-flex bd-ra-8 bg-main-color py-8 px-26 fs-15 fw-600 lh-25 text-white">{{ __('+ Add Order Forms') }}</a>
        </div>
        <div class="p-sm-30 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <table class="table zTable zTable-last-item-right" id="orderFormsList">
                <thead>
                    <tr>
                        <th>
                            <div class="text-nowrap">{{ __('Form Name') }}</div>
                        </th>
                        <th>
                            <div class="t-xl-min-w-150">{{ __('ID') }}</div>
                        </th>
                        <th>
                            <div class="t-xl-min-w-200">{{ __('Link') }}</div>
                        </th>
                        <th>
                            <div class="t-xl-min-w-200">{{ __('Total') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Action') }}</div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="orderFormIndexRoute" value="{{ route('admin.order-form.index') }}">
@endsection
@push('script')
    <script>
        var services = '';
    </script>
    <script src="{{ asset('admin/custom/js/order-form.js') }}"></script>
@endpush
