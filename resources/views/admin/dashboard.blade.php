@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
@php
    $spacificRoles = [USER_ROLE_INITIAL_EVALUATOR, USER_ROLE_FINANCIAL_MANAGER, USER_ROLE_PEER_REVIEWER_MANAGER, USER_ROLE_PUBLISHER, USER_ROLE_MARKETER];
    
    $allAccess = true;
    if(in_array(auth()->user()->role, $spacificRoles)){
        $allAccess = false;
    }
    
@endphp
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="home-section">
            <!--  -->
            <div class="d-flex align-items-center cg-5 pb-26">
                <h4 class="fs-24 fw-600 lh-29 text-title-black">{{ __('Hey') }}, {{ auth()->user()->name }}</h4>
                <span class="d-flex"><img src="{{ asset('assets') }}/images/icon/hand-wave.svg" alt="" /></span>
            </div>

            <!--total count start  -->
            <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">
                <div class="count-item-one">
                    <div class="row justify-content-xl-between rg-13">
                        @if($allAccess)
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-purple-10">
                                    <img src="{{ asset('assets/images/icon/bag-dollar.svg') }}" alt="" />
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Revenue') }}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">
                                        {{ currentCurrency('symbol') }}{{ $totalRevenue }}</p>
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
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Total Clients') }}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalClientCount }}</p>
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
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Complete Orders') }}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalCompletedOrder }}</p>
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
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Open Orders') }}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalOpenOrder }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color2-10">
                                    <img src="{{ asset('assets/images/icon/user-multiple-2.svg') }}" alt="" />
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{ __('Team Members') }}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{ $totalTeamMemberCount }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- total count end -->

             @if($allAccess)
            <!-- Overview start  -->
            <div class="pb-30">
                <div class="row rg-20">
                    <div class="col-lg-7">
                        <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                            <!--  -->
                            <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                                <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Revenue Overview') }}</h4>
                            </div>
                            <!--  -->
                            <div id="revenueOverviewChart"></div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                            <!--  -->
                            <div class="d-flex justify-content-between align-items-center g-10 pb-13">
                                <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Clients Overview') }}</h4>
                                <!--  -->
                                <select class="sf-select-without-search dashboard-select client-overview-year d-none">
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <!--  -->
                            <canvas id="myChart" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Overview end -->
             @endif
            <!-- Recent History Start -->
             @if($allAccess)
            <div class="row rg-20">
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Recent Order History') }}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="recentOrderHistoryDashboard">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="text-nowrap">{{ __('Order ID') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Client Name') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Payment Status') }}</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Recent Open Ticket History') }}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="recentOpenTicketHistoryList">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="text-nowrap">{{ __('Client Name') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Ticket Id') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Order ID') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Priority') }}</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
             @endif
            <!-- Recent History End -->
        </div>
    </div>
    <input type="hidden" id="recent-open-history-route" value="{{ route('admin.dashboard') }}">
    <input type="hidden" id="recent-open-order-route" value="{{ route('admin.recent-open-order') }}">
    <input type="hidden" id="revenue-overview-chart-data-route" value="{{ route('admin.revenue-overview-chart-data') }}">
    <input type="hidden" id="client-overview-chart-data-route" value="{{ route('admin.client-overview-chart-data') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/admin-dashboard.js') }}"></script>
@endpush
