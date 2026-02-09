@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<!-- Page content area start -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <!-- Info & Add product button -->
    <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
        <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $title }}</h4>
        <a href="{{route('super-admin.user.add-new')}}"
            class="m-0 fs-15 border-0 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12">{{ __('+ Add User')
            }}</a>
    </div>
    <!-- Table -->
    <div class="customers__table bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
        <table class="table zTable zTable-last-item-right" id="userTable" aria-describedby="customersTable_info">
            <thead>
                <tr>
                    <th>
                        <div class="text-nowrap">{{ __('User Name') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Emails') }}</div>
                    </th>
                    <th class="text-nowrap">
                        <div>{{ __('Packages Name') }}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{ __('Created Date') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Country') }}</div>
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

    <input type="hidden" id="userTable-route" value="{{ route('super-admin.user.list') }}">
    <!-- Page content area end -->
    @endsection
    @push('script')
    <script src="{{asset('sadmin/custom/js/user.js')}}"></script>
    @endpush
