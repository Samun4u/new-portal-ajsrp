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

                <input type="hidden" name="id" value="{{$clientInvoice->id}}">

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
                                    @if($clientInvoice->payable_amount == 0)
                                    <select class="sf-select-without-search py-4 clientSelectOption" name="client_id">
                                        @foreach ($allClient as $clients)
                                        <option value="{{ $clients->id }}" {{$clientInvoice->client_id == $clients->id
                                            ? 'selected' : ''}}>
                                            {{ $clients?->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @elseif($clientInvoice->payable_amount > 0)
                                    <select class="sf-select-without-search py-4 clientSelectOption" name="client_id">
                                        @foreach ($allClient as $clients)
                                        <option value="{{ $clients->id }}" {{$clientInvoice->client_id == $clients->id
                                            ? 'selected' : 'disabled'}}>
                                            {{ $clients->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif

                                </div>

                                @if($clientInvoice->payable_amount > 0)
                                <div class="orderPayableAmountContainerEdit row pe-0">
                                    <div class="col-12 pe-0">
                                        <div
                                            class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                                            <div
                                                class="d-flex align-items-center flex-wrap g-8  justify-content-between w-100">
                                                <label class="zForm-label mb-0">{{__('Order')}}</label><label
                                                    class="zForm-label mb-0"> {{__('Optional')}}</label>
                                            </div>
                                        </div>
                                        <select class="sf-select-without-search py-4 selectOrderList" name="order_id">
                                            <option value="all order">{{__("All Order")}}</option>
                                            @foreach ($orders as $order)
                                            <option value="{{ $order->id }}" {{$clientInvoice->order_id == $order->id
                                                ? 'selected' : 'disabled'}}>
                                                {{ $order->order_id }} - {{__('Total
                                                Amount')}}({{showPrice($order->total)}}) -
                                                {{__('Due')}}({{showPrice($order->total-$order->tranction_amount)}})
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-12 pe-0 pt-25">
                                        <label for="addInvoicePrice" class="zForm-label">{{__('Payable
                                            Amount')}}</label>
                                        <input type="text" class="form-control zForm-control" step="0.01"
                                            name="payable_amount" value="{{$clientInvoice->payable_amount}}">
                                    </div>
                                </div>
                                @endif

                                <div class="col-12">
                                    <label for="addInvoiceDueDate" class="zForm-label">{{__('Due Date')}}</label>
                                    <input type="date" class="form-control zForm-control" name="due_date"
                                        placeholder="{{(__(" Service Date"))}}" value="{{$clientInvoice->due_date}}" />
                                </div>

                                <div class="col-12 ">
                                    <div class="zForm-wrap ">
                                        <label class="zForm-label">{{ __('Payment Status') }}</label>
                                        <select class="sf-select-without-search cs-select-form" name="payment_status">
                                            <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_PAID ?
                                                'selected' : ''}}
                                                value="{{ PAYMENT_STATUS_PAID}}">{{__('Paid')}}</option>
                                            <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_PENDING ?
                                                'selected' :
                                                ''}} value="{{ PAYMENT_STATUS_PENDING }}">{{__('Pending')}}</option>
                                            <option {{ $clientInvoice->payment_status == PAYMENT_STATUS_CANCELLED?
                                                'selected' :
                                                ''}} value="{{ PAYMENT_STATUS_CANCELLED }}">{{__('Cancel')}}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>


                        @if($clientInvoice->payable_amount == 0)
                        <div class="col-12 invoiceCreateForm table-responsive quotation-table-wrap">
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
                                    @foreach ($invoiceItem as $key => $items)
                                    <tr class="select-price-wrap">
                                        <input type="hidden" name="types[]" value="1">
                                        <td>
                                            <select class="form-select py-4 singleService"
                                                name="service_id[]" id="singleService">
                                                @foreach ($service as $item)
                                                <option value="{{ $item->id }}" {{$items->service_id == $item->id ?
                                                    'selected' :
                                                    ''}} data-price="{{ $item->price }}">
                                                    {{ $item->service_name }}, ({{ showPrice($item->price) }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input value="{{$items->price}}" type="number"
                                                    class="form-control selectedValueContainer zForm-control service-price price"
                                                    step="0.01" id="addInvoicePrice" name="price[]"
                                                    placeholder="{{__('Enter Price')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input type="number" value="{{$items->discount}}"
                                                    class="form-control zForm-control" step="0.01"
                                                    id="addInvoiceDiscount" placeholder="{{__('Enter Discount')}}"
                                                    name="discount[]">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="">
                                                <input type="number" class="form-control zForm-control quantity"
                                                    value="{{$items->quantity}}" id="addInvoiceQuantity"
                                                    placeholder="{{__('Enter Quantity')}}" name="quantity[]">
                                            </div>
                                        </td>
                                        <td>
                                            @if(!$key+1 == 1)
                                            <button
                                                class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30
                                                    d-flex justify-content-center align-items-center text-red removeOtherField"
                                                type="button"><i class="fa-solid fa-trash"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr class="my-4  border-primary">

                            <div id="otherFields">

                            </div>
                            <button type="button"
                                class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addmore">
                                + {{__('Add More Service')}}</button>
                        </div>


                        @endif

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

<input type="hidden" id="service-route" value="{{ route('admin.client-invoice.all-service') }}">
<input type="hidden" id="client-invoice-list-route" value="{{route('admin.client-invoice.list')}}">
<input type="hidden" id="service-route" value="{{ route('admin.client-invoice.all-service') }}">

@endsection

@push('script')
<script src="{{ asset('admin/custom/js/client-invoice.js') }}"></script>
@endpush
