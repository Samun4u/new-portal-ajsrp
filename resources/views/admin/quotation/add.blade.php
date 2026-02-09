@extends('admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush
@section('content')

<div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden aos-init aos-animate">
    <div class="p-sm-30 p-15">
        <form method="POST" class="ajax reset" action="{{ route('admin.quotation.store') }}"
            data-handler="quotationResponse">
            @csrf
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $pageTitle }}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                    <div class="max-w-713 m-auto py-sm-52 py-15">
                        <!--  -->
                        <div class="row rg-20">
                            <div class="col-lg-6">
                                <label for="quotationCustomerName" class="zForm-label">{{__('Customer Name')}}</label>
                                <input type="text" class="form-control zForm-control" id="quotationCustomerName"
                                    name="client_name" placeholder="Enter Customer Name">
                            </div>
                            <div class="col-lg-6">
                                <label for="quotationEmailAddress" class="zForm-label">{{__('Email Address')}}</label>
                                <input type="email" class="form-control zForm-control" name="email"
                                    id="quotationEmailAddress" placeholder="Enter Email Address">
                            </div>
                            <div class="col-lg-6">
                                <label for="quotationAddress" class="zForm-label">{{__('Address')}}</label>
                                <input type="text" name="address" class="form-control zForm-control"
                                    id="quotationAddress" placeholder="Enter Address">
                            </div>
                            <div class="col-lg-6">
                                <label for="quotationExpireDate" class="zForm-label">{{__('Expire Date')}}</label>
                                <input type="date" name="expire_date" class="form-control zForm-control"
                                    placeholder="Enter Expire Date">
                            </div>

                            <div class="col-12">
                                <div class="table-responsive quotation-table-wrap">
                                    <table class="table zTable zTable-last-item-right" id="inputTable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div>{{__('Service')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Price')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Quantity')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Duration')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Total')}}</div>
                                                </th>
                                                <th class="all">
                                                    <div>{{__('Action')}}</div>
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody id="otherFields">
                                            <tr class="select-price-wrap">
                                                <td>
                                                    <div class="services_select">
                                                        <input type="hidden" id="current-currency"
                                                            value="{{currentCurrency('symbol')}}">
                                                        <select class="form-select singleService" name="service_id[]">
                                                            <option value="">{{__('Select service')}}</option>
                                                            <option value="new_service">{{__('Create new service')}}
                                                            </option>
                                                            @foreach ($service as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-price="{{ $item->price }}">
                                                                {{ $item->service_name }}, ({{ showPrice($item->price)
                                                                }})
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="service_id"></div>
                                                    </div>
                                                    <div class="d-none services_input">
                                                        <div class="min-w-100">
                                                            <input type="text"
                                                                class="form-control zForm-control zForm-control-table"
                                                                name="service_name[]" placeholder="Service name">
                                                        </div>
                                                    </div>

                                                </td>

                                                <td>
                                                    <div class="min-w-100">
                                                        <input type="number"
                                                            class="form-control zForm-control zForm-control-table selectedValueContainer service-price price"
                                                            name="price[]" placeholder="Enter Price">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="min-w-100"><input type="number" name="quantity[]"
                                                            class="form-control zForm-control zForm-control-table service-quantity quantity"
                                                            id="" placeholder="Enter Quantity" value="1"></div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <input type="number" name="duration[]"
                                                            class="form-control zForm-control zForm-control-table duration"
                                                            id="" placeholder="Duration Dealy" value="1">
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="fs-14 fw-400 lh-17 text-para-text ">
                                                        {{currentCurrency('symbol')}}<span
                                                            class="service-total">0.00</span></p>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--  -->
                                <button
                                    class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addmore">+
                                    {{__('Add More Service')}}</button>
                                <!--  -->
                                <div class="mt-22 pt-20 bd-t-one bd-c-stroke">
                                    <div class="row justify-content-end">
                                        <div class="col-lg-4">
                                            <ul class="zList-pb-15">
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{__('Subtotal')}}:</p>
                                                    <p class="fs-14 fw-400 lh-17 text-para-text">
                                                        {{currentCurrency('symbol')}}<span class="sub-total">0.00</span>
                                                    </p>
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <label for="quotationDiscount"
                                                        class="fs-14 fw-400 lh-17 text-para-text">{{__('Discount')}}:</label>
                                                    <input type="number"
                                                        class="form-control zForm-control max-w-100 max-h-35 text-end"
                                                        id="quotationDiscount" placeholder="$0.00" name="discount"
                                                        value="0">
                                                </li>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{__('Total')}}:</p>
                                                    <p class="fs-14 fw-400 lh-17 text-main-color">
                                                        {{currentCurrency('symbol')}}<span class="total">0.00</span></p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="addTicketFieldDescription" class="zForm-label">{{__('Description')}}</label>
                                <textarea id="addTicketFieldDescription" class="form-control zForm-control min-h-175"
                                    name="description" placeholder="Write description here...."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="d-flex g-12 mt-25">
                    <button type="submit"
                        class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Save')}}</button>
                    <button
                        class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<input type="hidden" id="service-route" value="{{ route('admin.quotation.all-service') }}">
<input type="hidden" id="quotationListRoute" value="{{ route('admin.quotation.list') }}">
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/quotation.js') }}"></script>
@endpush