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
                <input type="hidden" name="id" value="{{ $quotation->id }}">

                <div class="max-w-894 m-auto">
                    <!--  -->
                    <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                        <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $pageTitle }}</h4>
                    </div>
                    <!--  -->
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <!--  -->
                            <div class="row rg-20">
                                <div class="col-lg-6">
                                    <label for="quotationCustomerName"
                                           class="zForm-label">{{__('Customer Name')}}</label>
                                    <input type="text" class="form-control zForm-control" id="quotationCustomerName"
                                           name="client_name" placeholder="Enter Customer Name"
                                           value="{{ $quotation->client_name }}">
                                </div>
                                <div class="col-lg-6">
                                    <label for="quotationEmailAddress"
                                           class="zForm-label">{{__('Email Address')}}</label>
                                    <input type="email" class="form-control zForm-control" name="email"
                                           id="quotationEmailAddress" placeholder="Enter Email Address"
                                           value="{{ $quotation->email }}">
                                </div>
                                <div class="col-lg-6">
                                    <label for="quotationAddress" class="zForm-label">{{__('Address')}}</label>
                                    <input type="text" name="address" class="form-control zForm-control"
                                           id="quotationAddress" placeholder="Enter Address"
                                           value="{{ $quotation->address }}">
                                </div>
                                <div class="col-lg-6">
                                    <label for="quotationExpireDate" class="zForm-label">{{__('Expire Date')}}</label>
                                    <input type="date" name="expire_date" class="form-control zForm-control"
                                           placeholder="Enter Expire Date" value="{{ $quotation->expire_date }}">
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
                                            @foreach ($quotation_items as $key => $items)
                                                <tr class="select-price-wrap">
                                                    <input type="hidden" id="current-currency"
                                                           value="{{currentCurrency('symbol')}}">
                                                    <td class="">

                                                        <div
                                                            class="services_input @if($items->service_id != null) d-none @endif">
                                                            {{--                                                            <input type="hidden" name="service_id[]" value="new_service">--}}
                                                            <div class="min-w-100">
                                                                <input type="text"
                                                                       class="form-control zForm-control zForm-control-table"
                                                                       name="service_name[]" placeholder="Service name"
                                                                       value="{{$items->service_name}}">
                                                            </div>
                                                        </div>
                                                        <div class="services_select">
                                                            <select class="form-select singleService service_id"
                                                                    name="service_id[]">
                                                                <option value="">{{__('Select service')}}</option>
                                                                <option value="new_service"
                                                                        @if($items->service_id == null) selected @endif>{{__('Create new service')}}
                                                                </option>
                                                                @foreach ($service as $item)
                                                                    <option value="{{ $item->id }}"
                                                                            data-price="{{ $item->price }}"
                                                                        {{$item->id == $items->service_id ? 'selected' : '' }}>
                                                                        {{ $item->service_name }}
                                                                        ({{ showPrice($item->price) }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="min-w-100">
                                                            <input type="number"
                                                                   class="form-control zForm-control zForm-control-table selectedValueContainer service-price price"
                                                                   name="price[]" placeholder="Enter Price"
                                                                   value="{{$items->price}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class=""><input type="number" name="quantity[]"
                                                                             class="form-control zForm-control zForm-control-table service-quantity quantity"
                                                                             id="" placeholder="Enter Quantity"
                                                                             value="{{$items->quantity}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="">
                                                            <input type="number" name="duration[]"
                                                                   class="form-control zForm-control zForm-control-table duration"
                                                                   id="" placeholder="Duration Dealy"
                                                                   value="{{$items->duration}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="fs-14 fw-400 lh-17 text-para-text">
                                                        <span class="service-total">
                                                            {{currentCurrency('symbol')}}{{$items->total}}
                                                        </span>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @if(!$key+1 == 1)
                                                            <button
                                                                class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center removeOtherField align-items-center text-red">
                                                                <i
                                                                    class="fa-solid fa-trash"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--  -->
                                    <button
                                        class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addmore text-start">
                                        +
                                        {{__('Add More Service')}}</button>
                                    <!--  -->
                                    <div class="mt-22 pt-20 bd-t-one bd-c-stroke">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-4">
                                                <ul class="zList-pb-15">
                                                    <li class="d-flex justify-content-between align-items-center">
                                                        <p class="fs-14 fw-400 lh-17 text-para-text">{{__('Subtotal')}}
                                                            :</p>
                                                        <p class="fs-14 fw-400 lh-17 text-para-text">
                                                        <span class="sub-total-old">{{currentCurrency('symbol')}}
                                                            {{$quotation->sub_total}}
                                                        </span>

                                                            <span class="sub-total"></span>
                                                        </p>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-center">
                                                        <label for="quotationDiscount"
                                                               class="fs-14 fw-400 lh-17 text-para-text">{{__('Discount')}}
                                                            :</label>
                                                        <input type="text"
                                                               class="form-control zForm-control max-w-100 max-h-35 text-end"
                                                               id="quotationDiscount" placeholder="$0.00"
                                                               value="{{$quotation->discount}}" name="discount">
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-center">
                                                        <p class="fs-14 fw-400 lh-17 text-para-text">{{__('Total')}}
                                                            :</p>
                                                        <p class="fs-14 fw-400 lh-17 text-main-color">
                                                        <span class="sub-total-old">{{currentCurrency('symbol')}}
                                                        </span>
                                                            <span class="total">
                                                            {{$quotation->total}}
                                                        </span>
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="addTicketFieldDescription"
                                           class="zForm-label">{{__('Description')}}</label>
                                    <textarea id="addTicketFieldDescription"
                                              class="form-control zForm-control min-h-175"
                                              name="description"
                                              placeholder="Write description here....">{{$quotation->description}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Update')}}</button>
                        <a href="{{ route('admin.quotation.list') }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</a>
                    </div>
                </div>
            </form>
        </div>

        <input type="hidden" id="service-route" value="{{ route('admin.quotation.all-service') }}">
        <input type="hidden" id="quotationListRoute" value="{{ route('admin.quotation.list') }}">
        @endsection

        @push('script')
            <script src="{{ asset('admin/custom/js/quotation.js') }}"></script>
    @endpush
