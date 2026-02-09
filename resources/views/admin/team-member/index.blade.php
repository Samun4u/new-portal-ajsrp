@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    @if ($allTeamMemberCount > 0)
        <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
            <div class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
                <div class="flex-grow-1">
                    <div class="search-one flex-grow-1 max-w-282">
                        <input type="text" placeholder="{{ __('Search here...') }}" id="teamMemberSearch" />
                        <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" /></button>
                    </div>
                </div>
                <a href="{{ route('admin.team-member.add') }}" class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('+ Add User') }}</a>
            </div>
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                <table class="table zTable zTable-last-item-right" id="allTeamTable">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('Name') }}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{ __('Email Address') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Role') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Designation') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Action') }}</div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @else
        <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
            <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
                <div class="create-wrap">
                    <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" /></div>
                    <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">
                        {{ __('There is no user available here!') }}</h4>
                    <a href="{{ route('admin.team-member.add') }}"
                        class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">{{ __('+ Add User') }}</a>
                </div>
            </div>
        </div>
    @endif
    <input type="hidden" id="teamMemberIndexRoute" value="{{ route('admin.team-member.index') }}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/team-member.js') }}"></script>
@endpush
