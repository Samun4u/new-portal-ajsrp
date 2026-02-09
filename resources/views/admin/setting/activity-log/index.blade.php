@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <div class="pb-19 ">
                        <span class="px-2 fw-600 text-black">{{ __($pageTitle) }} </span>
                    </div>
                    <table class="table zTable zTable-last-item-right" id="activityLogDatatable">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Action')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Source')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('IP Address')}}</div>
                            </th>

                            <th>
                                <div class="text-nowrap">{{__('Location')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('When')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="activity-log-history-route" value="{{ route('admin.setting.activity-log.index') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/activity-log.js') }}"></script>
@endpush
