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
                <form class="ajax" action="{{ route('admin.setting.coupon.store') }}" method="POST"
                      data-handler="commonResponse">
                    @csrf
                    <input type="hidden" name="id" value="{{ encrypt($coupon->id) }}">
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-25">
                        <div class="row rg-20">
                            <div class="col-12">
                                <label for="addCouponTitle" class="zForm-label">{{ __('Title') }}</label>
                                <input type="text" name="title" value="{{ $coupon->title }}"
                                       class="form-control zForm-control" id="addCouponTitle"
                                       placeholder="{{ __('Enter Title') }}"/>
                            </div>
                            <div class="col-12">
                                <label for="addCouponCouponCode" class="zForm-label">{{ __('Coupon Code') }}</label>
                                <input type="text" name="code" value="{{ $coupon->code }}"
                                       class="form-control zForm-control" id="addCouponCouponCode"
                                       placeholder="{{ __('Enter Coupon Code') }}"/>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="zForm-wrap w-100">
                                        <label class="zForm-label">{{ __('All Service') }}</label>
                                        <select
                                            class="sf-select-two cs-select-form product_id select2-hidden-accessible"
                                            name="service_ids[]" multiple="" data-select2-id="select2-data-1-62wt"
                                            tabindex="-1"
                                            aria-hidden="true">
                                            <option>{{ __('Select service') }}</option>
                                            @foreach ($service as $item)
                                                @php
                                                    $selected = in_array($item->id, json_decode($coupon->service_ids, true));
                                                @endphp
                                                <option value="{{ $item->id }}" {{ $selected ? 'selected' : '' }}>
                                                    ({{ $item->service_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="addCouponDiscountAmount"
                                       class="zForm-label">{{ __('Discount Amount') }}</label>
                                <input type="number" name="discount_amount" value="{{ $coupon->discount_amount }}"
                                       step="any" min="0" class="form-control zForm-control"
                                       id="addCouponDiscountAmount" placeholder="{{ __('Enter Discount Amount') }}"/>
                            </div>
                            <div class="col-md-12">
                                <label for="addCouponDiscountType" class="zForm-label">{{ __('Discount Type') }}</label>
                                <select class="sf-select-two" name="discount_type" value="{{ $coupon->discount_type }}"
                                        id="addCouponDiscountType">
                                    <option value="1" {{ $coupon->discount_type == 1 ? 'selected' : '' }}>
                                        {{ __('Flat') }}</option>
                                    <option value="2" {{ $coupon->discount_type != 1 ? 'selected' : '' }}>
                                        {{ __('Percentage') }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="addCouponDate" class="zForm-label">{{ __('Valid Date') }}</label>
                                <input type="date" name="valid_date" value="{{ $coupon->valid_date }}"
                                       class="form-control zForm-control" id="addCouponDate"
                                       placeholder="{{ __('Enter Discount Amount') }}"/>
                            </div>
                            <div class="col-md-12">
                                <label for="addCouponStatus" class="zForm-label">{{ __('Status') }}</label>
                                <select class="sf-select-two" name="status" id="addCouponStatus">
                                    <option value="1" {{ $coupon->status == 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="0" {{ $coupon->status != 1 ? 'selected' : '' }}>
                                        {{ __('Deactivate') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-12 flex-wrap">
                        <button
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('Update') }}</button>
                        <a href="{{ route('admin.setting.coupon.index') }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
