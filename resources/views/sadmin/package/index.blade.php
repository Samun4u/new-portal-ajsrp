@extends('sadmin.layouts.app')
@section('content')
@push('title')
{{ $title }}
@endpush
<div class="p-30">
    <div class="">
        <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($title) }}</h4>
        <div class="row bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
            <div class="col-lg-12">
                <div class="customers__area bg-style mb-30">
                    <div class="item-title d-flex flex-wrap justify-content-end pb-20">
                        <div class="mb-3">
                            <button class="border-0 fs-15 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12"
                                type="button" id="add">
                                <i class="fa fa-plus"></i> {{ __('Add Package') }}
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive zTable-responsive">
                        <table class="table zTable zTable-last-item-right" id="packageDataTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div>{{ __('SL') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('Name') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Monthly Price') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Yearly Price') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('Status') }}</div>
                                    </th>
                                    <th>
                                        <div class="text-nowrap">{{ __('Is Trial') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('Action') }}</div>
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

{{-- modal --}}
<div class="modal fade" id="addModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bd-c-stroke bd-one bd-ra-10">
            <div class="modal-body p-sm-25 p-15">
                <div class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                    <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Package') }}</h4>
                    <button type="button"
                        class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                        data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>

                <form class="ajax reset" action="{{ route('super-admin.packages.store') }}" method="post"
                    data-handler="commonResponseForModal">
                    @csrf

                    <div class="row rg-20 pb-20">
                        <div class="">
                            <label for="name" class="zForm-label">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="{{ __('Name') }}"
                                class="form-control zForm-control">
                        </div>
                        <div class="">
                            <label for="customer_limit" class="zForm-label">{{ __('Order Limit') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="order_limit" id="order_limit"
                                placeholder="{{ __('Order Limit') }}" class="form-control zForm-control mb-8">
                            <select name="number_of_order" class="sf-select-without-search">
                                <option value="1">{{ __('Limited') }}</option>
                                <option value="2">{{ __('Unlimited') }}</option>
                            </select>
                        </div>
                        <div class="">
                            <label for="product_limit" class="zForm-label">{{ __('Client Limit') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="client_limit" id="client_limit"
                                placeholder="{{ __('Client Limit') }}" class="form-control zForm-control mb-8">
                            <select name="number_of_client" class="sf-select-without-search">
                                <option value="1">{{ __('Limited') }}</option>
                                <option value="2">{{ __('Unlimited') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row pb-20">
                        <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center g-10">
                            <label class="zForm-label">{{ __('Other Fields') }}</label>
                            <button type="button"
                                class="bg-main-color text-white border-0 bd-ra-8 h-30 p-0 w-30 addOtherField">
                                <i class="fa fa-plus"></i></button>
                        </div>
                        <div class="otherFields d-flex flex-column g-20">
                        </div>
                    </div>
                    <div class="row rg-20">
                        <div class="">
                            <label for="monthly_price" class="zForm-label">{{ __('Monthly Price') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="monthly_price" id="monthly_price"
                                placeholder="{{ __('Monthly Price') }}" class="form-control zForm-control">
                        </div>
                        <div class="">
                            <label for="yearly_price" class="zForm-label">{{ __('Yearly Price') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="yearly_price" id="yearly_price"
                                placeholder="{{ __('Yearly Price') }}" class="form-control zForm-control">
                        </div>
                        <div class="d-flex flex-wrap g-10">
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="status" type="checkbox"
                                            id="status">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="status">
                                        {{ __('Status') }}
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="is_default" type="checkbox"
                                            id="is_default">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="is_default">
                                        {{ __('Is Popular') }}
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="is_trail" type="checkbox"
                                            id="is_trail">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="is_trail">
                                        {{ __('Is Trail') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="m-0 fs-15 border-0 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12 mt-20">{{
                        __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- edit modal --}}
<div class="modal fade" id="editModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bd-c-stroke bd-one bd-ra-10">
            <div class="modal-body p-sm-25 p-15">
                <div class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                    <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Edit Package') }}</h4>
                    <button type="button"
                        class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                        data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>

                <form class="ajax reset" action="{{ route('super-admin.packages.store') }}" method="post"
                    data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="row rg-20 pb-20">
                        <div class="">
                            <label for="name" class="zForm-label">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="{{ __('Name') }}"
                                class="form-control zForm-control">
                        </div>
                        <div class="">
                            <label for="customer_limit" class="zForm-label">{{ __('Order Limit') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="order_limit" id="order_limit"
                                placeholder="{{ __('Order Limit') }}" class="form-control zForm-control mb-8">
                            <select name="number_of_order" class="sf-select-without-search">
                                <option value="1">{{ __('Limited') }}</option>
                                <option value="2">{{ __('Unlimited') }}</option>
                            </select>
                        </div>
                        <div class="">
                            <label for="product_limit" class="zForm-label">{{ __('Client Limit') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="client_limit" id="client_limit"
                                placeholder="{{ __('Client Limit') }}" class="form-control zForm-control mb-8">
                            <select name="number_of_client" class="sf-select-without-search">
                                <option value="1">{{ __('Limited') }}</option>
                                <option value="2">{{ __('Unlimited') }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="row pb-20">
                        <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center g-10">
                            <label>{{ __('Other Fields') }}</label>
                            <button type="button"
                                class="bg-main-color text-white border-0 bd-ra-8 h-30 p-0 w-30 addOtherField"><i
                                    class="fa fa-plus"></i></button>
                        </div>
                        <div class="otherFields d-flex flex-column g-20">
                        </div>
                    </div>
                    <div class="row rg-20">
                        <div class="">
                            <label for="monthly_price" class="zForm-label">{{ __('Monthly Price') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="monthly_price" id="monthly_price"
                                placeholder="{{ __('Monthly Price') }}" class="form-control zForm-control">
                        </div>
                        <div class="">
                            <label for="yearly_price" class="zForm-label">{{ __('Yearly Price') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="yearly_price" id="yearly_price"
                                placeholder="{{ __('Yearly Price') }}" class="form-control zForm-control">
                        </div>
                        <div class="d-flex flex-wrap g-10">
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0 status" value="1" name="status"
                                            type="checkbox" id="status">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="status">
                                        {{ __('Status') }}
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="is_default" type="checkbox"
                                            id="is_default">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="is_default">
                                        {{ __('Is Popular') }}
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="is_trail" type="checkbox"
                                            id="is_trail">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex" for="is_trail">
                                        {{ __('Is Trail') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="m-0 fs-15 border-0 fw-500 lh-25 text-white py-10 px-26 bg-main-color bd-ra-12 mt-20">{{
                        __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>


<input type="hidden" id="packageIndexRoute" value="{{ route('super-admin.packages.index') }}">
<input type="hidden" id="packageInfoRoute" value="{{ route('super-admin.packages.get.info') }}">

@endsection
@push('script')
<script src="{{ asset('sadmin/custom/js/package.js') }}"></script>
@endpush