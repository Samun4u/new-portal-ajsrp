@extends('sadmin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Page content area start -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <!-- Table -->
            <h4 class="fs-18 fw-600 lh-24 text-title-black pb-20">{{ $pageTitle }}</h4>

            <div id="customersTable_wrapper" class="dataTables_wrapper no-footer">
                <div class="row rg-20">
                    <div class="col-xl-4 col-md-5">
                        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-sm-25 p-15">
                            <div class="w-105 h-105 rounded-circle overflow-hidden">
                                <img src="{{ asset(getFileUrl($user->image)) }}" alt=""/>
                            </div>
                            <div class="bd-t-one bd-c-stroke pt-22 mt-30">
                                <ul class="zList-pb-16">
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Name')}} :</h4></div>
                                        <div class="col-6"><p
                                                class="fs-12 fw-500 lh-19 text-para-text">{{$user->name}}</p></div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Email')}} :</h4></div>
                                        <div class="col-6"><p
                                                class="fs-12 fw-500 lh-19 text-para-text">{{$user->email}}</p></div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Mobile')}} :</h4>
                                        </div>
                                        <div class="col-6"><p
                                                class="fs-12 fw-500 lh-19 text-para-text">{{$user->mobile}}</p></div>
                                    </li>
                                </ul>
                            </div>

                            <div class="bd-t-one bd-c-stroke pt-15 mt-20">
                                <ul class="zList-pb-16">
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Address')}} :</h4>
                                        </div>
                                        <div class="col-6"><p
                                                class="fs-12 fw-500 lh-19 text-para-text">{{$user->address?? __("No")}}</p>
                                        </div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Email Verify')}}
                                                :</h4></div>
                                        <div class="col-6">
                                            <p class="fs-12 fw-500 lh-19 text-para-text">
                                                @if ($user->email_verification_status == ACTIVE)
                                                    {{__('Yes')}}
                                                @else
                                                    {{__('NO')}}
                                                @endif
                                            </p>
                                        </div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Mobile Verify')}}
                                                :</h4></div>
                                        <div class="col-6">
                                            <p class="fs-12 fw-500 lh-19 text-para-text">
                                                @if ($user->phone_verification_status == ACTIVE)
                                                    {{__('Yes')}}
                                                @else
                                                    {{__('NO')}}
                                                @endif
                                            </p>
                                        </div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Join Date')}} :</h4>
                                        </div>
                                        <div class="col-6"><p class="fs-12 fw-500 lh-19 text-para-text">
                                                {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at ??
                                    now())->format('jS F, h:i:s A')}}
                                            </p></div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Last Update')}} :</h4>
                                        </div>
                                        <div class="col-6"><p class="fs-12 fw-500 lh-19 text-para-text">
                                                {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at ??
                                    now())->format('jS F, h:i:s A')}}
                                            </p></div>
                                    </li>
                                    <li class="row flex-wrap">
                                        <div class="col-6"><h4
                                                class="fs-12 fw-500 lh-19 text-title-black">{{__('Status')}} :</h4>
                                        </div>
                                        <div class="col-6 d-flex g-17">
                                            <p class="fs-12 fw-500 lh-19 text-para-text">
                                                @if ($user->status == ACTIVE)
                                                    <span
                                                        class="zBadge zBadge-active">{{__("Active")}}</span>
                                                @else
                                                    <span
                                                        class="zBadge zBadge-inactive">{{__("Deactivate")}}</span>
                                                @endif
                                            </p>
                                            <div class="dropdown dropdown-one">
                                                <button class="dropdown-toggle p-0 bg-transparent w-30 h-30 bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center text-para-text fs-10" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-angle-down"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                    @if ($user->status != ACTIVE)
                                                        <li>
                                                            <a href="{{route('super-admin.user.suspend', $user->id)}}"><p class="fs-14 fw-400 lh-17 text-para-text">{{__("Active")}}</p></a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{route('super-admin.user.suspend', $user->id)}}"><p class="fs-14 fw-400 lh-17 text-para-text">{{__("Deactivate")}}</p></a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-md-7">
                        <div class="bd-one bd-c-stroke bd-ra-8 bg-white py-sm-25 pt-25">
                            <!--  -->
                            <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-sm-15 px-13 active bg-transparent activeLogStatusTab" id="active-log-tab"
                                            data-bs-toggle="tab" data-bs-target="#active-log-tab-pane" type="button"
                                            role="tab" aria-controls="active-log-tab-pane"
                                            aria-selected="true">{{__('Activity Log')}}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-sm-15 px-13 bg-transparent packagesHistoryTab" id="packages-history-tab"
                                            data-bs-toggle="tab" data-bs-target="#packages-history-tab-pane" type="button" role="tab"
                                            aria-controls="packages-history-tab-pane"
                                            aria-selected="false">{{__('Packages History')}}</button>
                                </li>
                            </ul>
                            <!--  -->
                            <div class="tab-content" id="myTabContent">
                                <!-- Active log History -->
                                <div class="tab-pane fade show active" id="active-log-tab-pane" role="tabpanel"
                                     aria-labelledby="active-log-tab" tabindex="0">
                                    <div class="bd-t-one bd-c-stroke p-sm-30 p-15 pt-25">
                                        <table class="table zTable zTable-last-item-right" id="activityDataTable"
                                               aria-describedby="activityLogDataTable">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <div class="min-w-150">{{ __('Action') }}</div>
                                                </th>
                                                <th>
                                                    <div class="min-w-150">{{ __('Source') }}</div>
                                                </th>
                                                <th>
                                                    <div class="min-sm-w-100 text-nowrap">{{ __('IP Address') }}</div>
                                                </th>
                                                <th>
                                                    <div class="min-w-150">{{ __('Location') }}</div>
                                                </th>
                                                <th>
                                                    <div class="min-w-150">{{ __('When') }}</div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <!-- packages history   -->
                                <div class="tab-pane fade" id="packages-history-tab-pane" role="tabpanel"
                                     aria-labelledby="packages-history-tab" tabindex="0">
                                    <div class="bd-t-one bd-c-stroke p-sm-30 p-15">
                                        <table class="table zTable zTable-last-item-right" id="packagesHistoryDatatable">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-nowrap">{{__('Packages Name')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Start Date')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('End Date')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Payment Status')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Status')}}</div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{--            </div>--}}
        </div>
    </div>

    <input type="hidden" id="user-activity-route" value="{{ route('super-admin.user.activity', $user->id )}}">
    <input type="hidden" id="user-packages-history-route" value="{{ route('super-admin.user.packages-history', $user->id )}}">
    <!-- Page content area end -->
@endsection

@push('script')
    <script src="{{asset('sadmin/custom/js/user.js')}}"></script>
@endpush
