@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="main-content p-sm-30 p-15">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-content-wrapper bg-white p-30 radius-20">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="page-title-box d-flex justify-content-between align-items-center flex-wrap pb-20 mb-20 bd-b-one bd-c-stroke">
                            <h3 class="fs-18 fw-600 lh-20 text-title-black">{{ $title }}</h3>
                            <ol class="breadcrumb sf-breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                        title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="item-title d-flex flex-wrap justify-content-end pb-20">
                        <button class="m-0 fs-15 border-0 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12"
                            type="button" id="assignPackage">
                            <i class="fa fa-plus"></i> {{ __('Assign Package') }}
                        </button>
                    </div>
                    <table id="packageUserDataTable" class="table zTable zTable-last-item-right">
                        <thead>
                            <tr>

                                <th class="all">
                                    <div class="text-nowrap">{{ __('User Name') }}</div>
                                </th>
                                <th class="all">
                                    <div class="text-nowrap">{{ __('Package Name') }}</div>
                                </th>
                                <th class="desktop">
                                    <div class="text-nowrap">{{ __('Start Date') }}</div>
                                </th>
                                <th class="desktop">
                                    <div class="text-nowrap">{{ __('End Date') }}</div>
                                </th>
                                <th class="desktop">
                                    <div class="text-nowrap">{{ __('Payment Status') }}</div>
                                </th>
                                <th class="desktop">
                                    <div class="text-nowrap">{{ __('Status') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignPackageModal" tabindex="-1" aria-labelledby="assignPackageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bd-c-stroke bd-one bd-ra-10">
            <div class="modal-body p-sm-25 p-15">
                <div class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                    <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Assign Package') }}</h4>
                    <button type="button"
                        class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                        data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.packages.assign') }}" method="post"
                    enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="gateway" value="cash">
                    <input type="hidden" name="currency" value="{{ currentCurrencyType() }}">
                    <div class="row rg-20">
                        <div class="">
                            <label class="zForm-label">{{ __('User') }}
                                <span class="text-danger">*</span></label>
                            <select name="user_id" class="sf-select-without-search">
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <label class="zForm-label">{{ __('Package') }}
                                <span class="text-danger">*</span></label>
                            <select name="package_id" class="sf-select-without-search">
                                @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <label class="zForm-label">{{ __('Duration Type') }}
                                <span class="text-danger">*</span></label>
                            <select name="duration_type" class="sf-select-without-search">
                                <option value="1">{{ __('Monthly') }}</option>
                                <option value="2">{{ __('Yearly') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="m-0 fs-15 border-0 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12 mt-20">{{
                        __('Assign') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="packagesUserRoute" value="{{ route('super-admin.packages.user') }}">
@endsection

@push('script')
<script src="{{ asset('sadmin/custom/js/package.js') }}"></script>
@endpush
