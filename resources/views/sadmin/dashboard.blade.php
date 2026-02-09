@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
        <div class="d-flex align-items-center cg-5 pb-26">
            <h4 class="fs-24 fw-600 lh-29 text-title-black">{{ __($title) }}</h4>
            <span class="d-flex"><img src="{{ asset('assets/images/icon/hand-wave.svg') }}" alt="" /></span>
        </div>
        <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">
            <div class="count-item-one">
                <div class="row justify-content-xl-between rg-13">
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                        <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                            <div
                                class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-purple-10">
                                <img src="{{ asset('assets/images/icon/bag-dollar.svg') }}" alt="" />
                            </div>
                            <div class="content">
                                <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Total User') }}</h4>
                                <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalUser }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                        <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                            <div
                                class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-main-color-10">
                                <img src="{{ asset('assets/images/icon/user-multiple.svg') }}" alt="" />
                            </div>
                            <div class="content">
                                <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Total Client') }}</h4>
                                <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalClient }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                        <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                            <div
                                class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color1-10">
                                <img src="{{ asset('assets/images/icon/receipt-check.svg') }}" alt="" />
                            </div>
                            <div class="content">
                                <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Total Package') }}</h4>
                                <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalPackage }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                        <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                            <div
                                class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-green-10">
                                <img src="{{ asset('assets/images/icon/orders.svg') }}" alt="" />
                            </div>
                            <div class="content">
                                <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">
                                    {{ __('Total Revenue') }}</h4>
                                <p class="fs-18 fw-500 lh-21 text-title-black">
                                    {{ showPrice($totalRevenue) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-30">
            <div class="row rg-20">
                <div class="col-lg-12">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center g-10 pb-13">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Users Overview') }}</h4>
                        </div>
                        <canvas id="myChart" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="user-overview-chart-data-route" value="{{ route('super-admin.user-overview-chart-data') }}">
@endsection

@push('script')
<script src="{{ asset('sadmin/js/admin-dashboard.js') }}"></script>
@endpush