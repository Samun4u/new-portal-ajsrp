@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Content -->
    <span id="searchresult">
        @if ($invoiceCount > 0)
            <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
                <!-- Search - Create -->
                <div
                    class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
                    <div class="flex-grow-1">
                        <div class="search-one flex-grow-1 max-w-282">
                            <input type="text" placeholder="{{ __('Search here') }}..." id="datatableSearch" />
                            <button class="icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z"
                                        stroke="#707070" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!--  -->
                </div>

                <div
                    class="d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center align-items-md-start flex-wrap g-10 table-pl">
                    <!-- Left -->
                    <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active bg-transparent invoiceStatusTab" id="allOrder-tab"
                                data-bs-toggle="tab" data-bs-target="#allOrder-tab-pane" type="button" role="tab"
                                aria-controls="allOrder-tab-pane" aria-selected="true"
                                data-status="all">{{ __('All') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link bg-transparent invoiceStatusTab" id="completedOrder-tab"
                                data-bs-toggle="tab" data-bs-target="#completedOrder-tab-pane" type="button" role="tab"
                                aria-controls="completedOrder-tab-pane" aria-selected="false"
                                data-status="{{ PAYMENT_STATUS_PAID }}">{{ __('Paid') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link bg-transparent invoiceStatusTab" id="pendingOrder-tab"
                                data-bs-toggle="tab" data-bs-target="#pendingOrder-tab-pane" type="button" role="tab"
                                aria-controls="pendingOrder-tab-pane" aria-selected="false"
                                data-status="{{ PAYMENT_STATUS_PENDING }}">{{ __('Pending') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link bg-transparent invoiceStatusTab" id="cancelledOrder-tab"
                                data-bs-toggle="tab" data-bs-target="#cancelledOrder-tab-pane" type="button" role="tab"
                                aria-controls="cancelledOrder-tab-pane" aria-selected="false"
                                data-status="{{ PAYMENT_STATUS_CANCELLED }}">{{ __('Cancelled') }}</button>
                        </li>
                    </ul>
                </div>
                <!--  -->
                <div class="tab-content" id="myTabContent">
                    <!-- All Order -->
                    <div class="tab-pane fade show active invoiceStatusTab" id="allOrder-tab-pane" role="tabpanel"
                        aria-labelledby="allOrder-tab" tabindex="0">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                            <table class="table zTable zTable-last-item-right" id="invoiceTable-all">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>{{ __('Sl') }}</div>
                                        </th>
                                        <th>
                                            <div class="text-nowrap">{{ __('Invoice ID') }}</div>
                                        </th>
                                        <th>
                                            <div class="text-nowrap">{{ __('Paper Title') }}</div>
                                        </th>
                                        <th>
                                            <div class="text-nowrap">{{ __('Client Name') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Due Date') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Total Price') }}</div>
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
                    </div>

                    <!-- Working Order -->
                    <div class="tab-pane fade" id="pendingOrder-tab-pane" role="tabpanel" aria-labelledby="pendingOrder-tab"
                        tabindex="0">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                            <table class="table zTable zTable-last-item-right"
                                id="invoiceTable-{{ PAYMENT_STATUS_PENDING }}">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>{{ __('Sl') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Invoice ID') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Paper Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Client Name') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Due Date') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Total Price') }}</div>
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
                    </div>
                    <!-- Completed Order -->
                    <div class="tab-pane fade" id="completedOrder-tab-pane" role="tabpanel"
                        aria-labelledby="completedOrder-tab" tabindex="0">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                            <table class="table zTable zTable-last-item-right" id="invoiceTable-{{ PAYMENT_STATUS_PAID }}">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>{{ __('Sl') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Invoice ID') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Paper Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Client Name') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Invoice ID') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Due Date') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Total Price') }}</div>
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
                    </div>

                    <!-- Cancelled Order -->
                    <div class="tab-pane fade" id="cancelledOrder-tab-pane" role="tabpanel"
                        aria-labelledby="cancelledOrder-tab" tabindex="0">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                            <table class="table zTable zTable-last-item-right"
                                id="invoiceTable-{{ PAYMENT_STATUS_CANCELLED }}">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>{{ __('Sl') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Invoice ID') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Paper Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Client Name') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Due Date') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Total Price') }}</div>
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
                    </div>
                </div>

            </div>
        @else
            <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
                <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
                    <div class="create-wrap">
                        <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt="" />
                        </div>
                        <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">
                            {{ __("There is no Invoice
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                available here!") }}
                        </h4>
                        {{-- <a href="{{route('admin.client.add-list')}}"
                    class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">+{{__("Add
                    Client")}}</a> --}}
                    </div>
                </div>
            </div>
        @endif
    </span>


    {{-- invoice details model --}}
    <div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-25 invoice-content-wrap">
                <div class="invoice-content">

                </div>
            </div>
        </div>
    </div>
    {{-- checkout modal  --}}
    <div class="modal fade" id="gatewayModal" tabindex="-1" aria-labelledby="gatewayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
                    <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Select Payment Method') }}</h4>
                    <button type="button"
                        class="w-30 h-30 bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center p-0 bg-white"
                        data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('user.checkout.order.place') }}" method="POST"
                    data-handler="checkoutOrderResponse">
                    @csrf
                    <input type="hidden" id="selectGateway" name="gateway">
                    <input type="hidden" id="selectCurrency" name="currency">
                    <input type="hidden" id="invoiceId" name="invoice_id">
                    <input type="hidden" name="checkout_type" value="{{ CHECKOUT_TYPE_USER_INVOICE }}">
                    <div class="row rg-20 pb-20">
                        <div class="col-lg-5 col-md-6">
                            <div class="bd-one bd-c-stroke bd-ra-8 p-sm-25 p-15 bg-white">
                                <h4 class="fs-18 fw-500 lh-22 text-title-black pb-12">{{ __('Invoice Details') }}</h4>
                                <ul class="zList-pb-12">
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Invoice ID') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-black" id="showInvoiceId"></p>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <p class="fs-14 fw-400 lh-16 text-para-text">{{ __('Amount') }}</p>
                                        <p class="fs-14 fw-400 lh-16 text-title-black" id="showInvoiceAmount"></p>
                                    </li>
                                </ul>
                                <ul class="zList-pb-12 pt-20 mt-20 bd-t-one bd-c-stroke" id="gatewayCurrencyAppend">
                                </ul>
                            </div>
                            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mt-20 d-none"
                                 id="bankSection">
                                <h4 class="fs-16 fw-600 lh-19 text-title-black pb-15 mb-18 bd-b-one bd-c-stroke">
                                    {{ __('Bank Deposit') }}</h4>
                                <div class="zForm-wrap pb-20">
                                    <label for="bandDepositBankName" class="zForm-label">{{ __('Bank Name') }}</label>
                                    <select class="sf-select-two cs-select-form" id="bank_id" name="bank_id">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}" data-details="{{ nl2br($bank->details) }}">
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="fs-14 fw-500 lh-17 text-para-text p-10 mb-20 bd-one bd-c-stroke bd-ra-8"
                                   id="bankDetails"></p>
                                <div class="zForm-wrap">
                                    <label for="bank_slip" class="zForm-label">{{ __('Upload Deposit Slip') }}</label>
                                    <input type="file" class="form-control zForm-control" id="bank_slip"
                                           name="bank_slip" />
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-7 col-md-6">
                            <div class="row rg-20">
                                @foreach ($gateways as $key => $gateway)
                                    @if($gateway->status != 1) @continue @endif
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 payment-item">
                                            <h6
                                                class="py-8 p-sm-20 p-10 bd-ra-20 mb-20 bg-body-bg text-center fs-14 fw-400 lh-16 text-para-text">
                                                {{ $gateway->name }}</h6>
                                            <div class="text-center mb-20">
                                                <img src="{{ asset($gateway->image) }}" alt="" />
                                            </div>
                                            <button type="button" data-gateway="{{ $gateway->slug }}"
                                                data-id="{{ $gateway->id }}"
                                                class="bd-one bd-c-stroke bd-ra-5 py-8 p-sm-20 p-10 w-100 bg-white fs-14 fw-400 lh-16 text-para-text paymentGateway">{{ __('Select') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        class="w-75 m-auto p-12 d-flex justify-content-center align-items-center border-0 bd-ra-8 bg-main-color fs-15 fw-600 lh-20 text-white"
                        id="paymentNowBtn">{{ __('Pay Now') }}<span id="gatewayCurrencyAmount"></span></button>

                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="checkoutType" name="checkout_type" value="{{ CHECKOUT_TYPE_USER_INVOICE }}">
    <input type="hidden" id="totalAmount" value="0.00">
    <input type="hidden" id="discountAmount" value="0.00">
    <input type="hidden" id="gotoRoute" value="{{ route('user.client-invoice.list') }}">
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('gateway.currency') }}">
    <input type="hidden" id="client-invoice-list-route" value="{{ route('user.client-invoice.list') }}">
@endsection


@push('script')
    <script src="{{ asset('admin/custom/js/client-invoice.js') }}"></script>
    <script src="{{ asset('user/custom/js/checkout.js') }}"></script>
@endpush
