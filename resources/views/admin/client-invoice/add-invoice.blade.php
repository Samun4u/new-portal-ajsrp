@extends('admin.layouts.app')
@push('title')
{{$pageTitle}}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden aos-init aos-animate">
    <div class="p-sm-30 p-15">
        <div class="max-w-894 m-auto">

            <form method="POST" class="ajax reset" action="{{ route('admin.client-invoice.store') }}"
                data-handler="invoiceResponse">
                @csrf
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{$pageTitle}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                    <div class="max-w-713 m-auto py-sm-52 py-15" data-select2-id="select2-data-6-1nc9">
                        <!--  -->
                        <div class="bd-b-one bd-c-stroke pb-40 mb-36">
                            <div class="row rg-20">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                        <label for="addInvoiceClientName" class="zForm-label mb-0">{{__('Client
                                            Name')}}</label>
                                    </div>
                                    <select class="sf-select-two clientSelectOption" name="client_id">
                                        <option value="">{{__('Select client')}}</option>
                                        @foreach ($allClient as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client?->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <span class="orderPayableAmountContainer">
                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                            <div
                                                class="d-flex align-items-center flex-wrap g-8  justify-content-between w-100">
                                                <label class="zForm-label mb-0">{{__('Order')}}</label><label
                                                    class="zForm-label mb-0"> {{__('Optional')}}</label>
                                            </div>
                                        </div>
                                        <select class="sf-select-two cs-select-form selectOrderList" name="order_id">
                                            <option value="order-list">{{__("All Order")}}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 payableAmount pt-15">
                                        <label for="addInvoicePrice" class="zForm-label">{{__('Payable
                                            Amount')}}</label>
                                        <input type="number" class="form-control zForm-control" step="0.01"
                                            name="payable_amount" placeholder="{{__('Payable Amount')}}" min="1">

                                    </div>
                                </span>

                                <div class=" col-12">
                                    <label for="addInvoiceDueDate" class="zForm-label">{{__('Due Date')}}</label>
                                    <input type="date" name="due_date" class="form-control zForm-control"
                                        id="addInvoiceDueDate" placeholder="{{__('Enter Due Date')}}">
                                </div>
                            </div>
                        </div>

                        <div class=" col-12 invoiceCreateForm table-responsive quotation-table-wrap">
                            <table class="table zTable zTable-last-item-right" id="clientInvoiceTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="text-nowrap">{{__('Service Name')}}</div>
                                        </th>
                                        <th>
                                            <div>{{__('Price')}}</div>
                                        </th>
                                        <th>
                                            <div>{{__('Discount')}}</div>
                                        </th>
                                        <th>
                                            <div>{{__('Quantity')}}</div>
                                        </th>
                                        <th class="all">
                                            <div>{{__('Action')}}</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="otherFields">
                                    <tr class="select-price-wrap">
                                        <td>
                                            <input type="hidden" name="types[]" value="1">
                                            <select class="form-select singleService" name="service_id[]">
                                                <option value="">{{__('Select service')}}</option>
                                                @foreach ($service as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                    {{ $item->service_name }}, ({{ showPrice($item->price) }})
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="service_id"></div>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input type="number" name="price[]"
                                                    class="form-control zForm-control zForm-control-table selectedValueContainer service-price price"
                                                    id="" placeholder="{{__('Enter Price')}}" min="1" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input type="number" name="discount[]"
                                                    class="form-control zForm-control zForm-control-table discount"
                                                    id="" placeholder="Enter Discount" value="0" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="">
                                                <input type="number" name="quantity[]"
                                                    class="form-control zForm-control zForm-control-table quantity"
                                                    id="" placeholder="Enter Quantity" value="1" />
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr class="my-4  border-primary">
                            <button type="button"
                                class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addmore">
                                + {{__('Add More Service')}}</button>
                        </div>



                    </div>
                </div>
                <!--  -->
                <div class="d-flex g-12 mt-25">
                    <button
                        class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Save')}}</button>
                    <a href="{{route('admin.client-invoice.list')}}"
                        class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</a>
                </div>
            </form>
        </div>
    </div>
</div>


<input type="hidden" id="client-invoice-list-route" value="{{route('admin.client-invoice.list')}}">

<input type="hidden" id="service-route" value="{{ route('admin.client-invoice.all-service') }}">
<input type="hidden" id="client-order-route" value="{{ route('admin.client-invoice.order') }}">

@endsection

@push('script')
<script src="{{ asset('admin/custom/js/client-invoice.js') }}"></script>
@endpush