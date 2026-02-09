@extends('frontend.layouts.app')
@push('title')
    {{ __(@$pageTitle) }}
@endpush
@section('content')
    <section class="checkout-section">
        <div class="checkout-wrap">
            <form class="ajax" action="{{ route('checkout.order') }}" method="POST" data-handler="checkoutOrderResponse">
                @csrf
                <input type="hidden" name="custom_fields">
                <input type="hidden" id="selectGateway" name="gateway">
                <input type="hidden" id="selectCurrency" name="currency">
                <input type="hidden" value="{{ request()->route()->parameters('hash')['hash'] }}" name="checkout_details">
                <div class="row rg-20">
                    <div class="col-xxl-9 col-lg-8">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-35 p-15">
                            <div class="pb-41">
                                <h4 class="fs-18 fw-600 lh-24 text-title-black pb-25">{{ __('Basic Information') }}</h4>
                                <div class="row rg-20">
                                    <div class="col-12">
                                        <label for="checkoutFullName" class="zForm-label">{{ __('Full Name') }}</label>
                                        <input type="text" name="name" class="form-control zForm-control"
                                            id="checkoutFullName" placeholder="{{ __('Full Name') }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="checkoutEmailAddress"
                                            class="zForm-label">{{ __('Email Address') }}</label>
                                        <input type="email" name="email" class="form-control zForm-control"
                                            id="checkoutEmailAddress" placeholder="{{ __('Email Address') }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="checkoutPhoneNumber"
                                            class="zForm-label">{{ __('Phone Number') }}</label>
                                        <input type="number" name="phone" class="form-control zForm-control"
                                            id="checkoutPhoneNumber" placeholder="{{ __('Phone Number') }}" />
                                    </div>
                                </div>
                            </div>
                            @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                <div class="pb-33">
                                    <h4 class="fs-18 fw-600 lh-24 text-title-black pb-25">{{ __('Other Information') }}
                                    </h4>
                                    <div id="orderFormFiledsRender"></div>
                                </div>
                            @endif
                            <div class="pb-41">
                                <h4 class="fs-18 fw-600 lh-24 text-title-black pb-25">{{ __('Your Services') }}</h4>
                                <div class="table-responsive pb-15">
                                    <table class="table zTable zTable-last-item-right" id="orderFormsList">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-nowrap">{{ __('Service Name') }}</div>
                                                </th>
                                                <th>
                                                    <div class="t-xl-min-w-200">{{ __('Quantity') }}</div>
                                                </th>
    {{--                                            @if ($type == CHECKOUT_TYPE_ORDER_FORM)--}}
    {{--                                                <th>--}}
    {{--                                                    <div class="t-xl-min-w-200">{{ __('Discount') }}</div>--}}
    {{--                                                </th>--}}
    {{--                                                <th>--}}
    {{--                                                    <div>{{ __('Price') }}</div>--}}
    {{--                                                </th>--}}
    {{--                                            @else--}}
                                                    <th>
                                                        <div class="t-xl-min-w-200">{{ __('Price') }}</div>
                                                    </th>
                                                    <th>
                                                        <div>{{ __('Total') }}</div>
                                                    </th>
    {{--                                            @endif--}}
                                            </tr>
                                        </thead>
                                        <tbody id="orderFormServiceItems">
                                            @foreach ($orderFormServices as $service)
                                                <tr>
                                                    <td>{{ $service->service_name }}</td>
                                                    <td>{{ $service->quantity }}</td>
    {{--                                                @if ($type == CHECKOUT_TYPE_ORDER_FORM)--}}
    {{--                                                    <td>0</td>--}}
    {{--                                                    <td>{{ showPrice($service->price) }}</td>--}}
    {{--                                                @else--}}
                                                        <td>{{ showPrice($service->price) }}</td>
                                                        <td>{{ showPrice($service->price * $service->quantity) }}</td>
    {{--                                                @endif--}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                @if ($orderForm->policy_link)
                                    <div class="pb-41">
                                        <h4 class="fs-18 fw-600 lh-24 text-title-black pb-25">
                                            {{ __('Terms & Conditions') }}
                                        </h4>
                                        <div class="bd-one bd-c-stroke bd-ra-8 bg-body-bg p-sm-25 p-15">
                                            <ul class="zList-pb-12">
                                                <li class="zForm-wrap-checkbox align-items-start">
                                                    <input type="checkbox" name="terms_conditions"
                                                        class="form-check-input mt-4" id="item1" required />
                                                    <label for="item1">{{ __('You agree with our ') }}
                                                        <a href="{{ $orderForm->policy_link }}" class="text-main-color"
                                                            target="_blank">{{ __('Terms of Service.') }}</a></label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="pb-41">
                                <h4 class="fs-18 fw-600 lh-24 text-title-black pb-20">{{ __('Payment Method') }}</h4>
                                <ul class="zTab-three d-flex checkoutPaymentItem">
                                    @foreach ($gateways as $key => $gateway)
                                        <li class="nav-item">
                                            <button class="nav-link" type="button">
                                                <label for="gateway{{ $key }}"><img
                                                        src="{{ asset($gateway->image) }}" /></label>
                                                <input type="radio" value="{{ $gateway->id }}"
                                                    data-gateway="{{ $gateway->slug }}" data-id="{{ $gateway->id }}"
                                                    class="paymentGateway" id="gateway{{ $key }}" />
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <button type="button"
                                    class="border-0 bd-ra-8 py-10 px-26 bg-main-color fs-15 fw-600 lh-25 text-white"
                                    id="paymentNowBtn">{{ __('Complete Your Purchase') }}<span
                                        id="gatewayCurrencyAmount"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4">
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-20 max-w-374 m-auto">
                            <h4 class="fs-16 fw-600 lh-19 text-title-black bd-b-one bd-c-stroke pb-15 mb-17">
                                {{ __('Purchase Details') }}</h4>
                            @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                <div class="pb-20">
                                    <label for="couponCode"
                                        class="fs-14 fw-400 lh-17 text-para-text pb-10">{{ __('Have a coupon?') }}</label>
                                    <div class="d-flex g-10 flex-lg-wrap flex-xxl-nowrap">
                                        <input type="text" name="coupon_code" class="form-control zForm-control max-h-35"
                                            id="couponCode" placeholder="{{ __('Coupon') }}" />
                                        <button type="button"
                                            class="border-0 bd-ra-6 py-5 px-25 bg-main-color fs-15 fw-600 lh-25 text-white"
                                            id="couponCodeApplyBtn">{{ __('Apply') }}</button>
                                    </div>
                                </div>
                            @endif
                            <ul class="zList-pb-19 bd-b-one bd-c-stroke pb-15 mb-18">
                                <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{ __('Quantity') }}:</p>
                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{ $orderFormServices->sum('quantity') }}
                                    </p>
                                </li>
                                <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{ __('Subtotal') }}:</p>
                                    <p class="fs-14 fw-400 lh-17 text-para-text">
                                        @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                            {{ showPrice($orderForm->total) }}
                                        @else
                                            {{ showPrice($orderFormServices->sum('total')) }}
                                        @endif
                                    </p>
                                </li>
                                <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                    <p class="fs-14 fw-400 lh-17 text-para-text">{{ __('Discount') }}:</p>
                                    <p class="fs-14 fw-400 lh-17 text-para-text" id="discountShowAmount">
                                        @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                            {{ showPrice(0) }}
                                        @else
                                            {{ showPrice($quotation->discount) }}
                                        @endif
                                    </p>
                                </li>
                            </ul>
                            <div
                                class="d-flex bd-b-one bd-c-stroke pb-15 pb-15 mb-18 justify-content-between align-items-center flex-wrap g-10">
                                <p class="fs-14 fw-600 lh-17 text-para-text">{{ __('Total') }}:</p>
                                <p class="fs-14 fw-600 lh-20 text-para-text" id="totalShowAmount">
                                    @if ($type == CHECKOUT_TYPE_ORDER_FORM)
                                        {{ showPrice($orderForm->total) }}
                                    @else
                                        {{ showPrice($quotation->total) }}
                                    @endif
                                </p>
                            </div>
                            <ul class="zList-pb-17 bd-c-stroke" id="gatewayCurrencyAppend">
                            </ul>
                        </div>
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 max-w-374 m-auto d-none"
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
                </div>
            </form>
        </div>
    </section>
    <input type="hidden" id="getCouponInfoRoute" value="{{ route('get.coupon.info') }}">
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('gateway.currency') }}">
    <input type="hidden" id="waitingRoute" value="{{ route('waiting') }}">
    @if ($type == CHECKOUT_TYPE_ORDER_FORM)
        <input type="hidden" id="checkoutType" value="{{ CHECKOUT_TYPE_ORDER_FORM }}">
        <input type="hidden" id="discountAmount" value="0.00">
        <input type="hidden" id="totalAmount" value="{{ $orderForm->total }}">
        <input type="hidden" id="formOrderId" value="{{ $orderForm->id }}">
        <input type="hidden" id="userId" value="{{ $orderForm->user_id }}">
    @else
        <input type="hidden" id="gotoRoute" value="{{ route('waiting') }}">
        <input type="hidden" id="checkoutType" value="{{ CHECKOUT_TYPE_QUOTATION }}">
        <input type="hidden" id="discountAmount" value="{{ $quotation->discount }}">
        <input type="hidden" id="totalAmount" value="{{ $quotation->total }}">
    @endif
@endsection

@push('script')
    <script src="{{ asset('user/custom/js/checkout.js') }}"></script>
    @if ($type == CHECKOUT_TYPE_ORDER_FORM)
        <script>
            var orderFormServices = {!! $orderFormServices !!};
            var formRenderOptions = {
                disableInjectedStyle: 'bootstrap',
                formData: {!! $orderForm->fields !!}
            }
            var formRenderInstance = $('#orderFormFiledsRender').formRender(formRenderOptions);
            $(document).on('click', '#paymentNowBtn', function() {
                $(this).closest('form').find('input[name=service_fields]').val(JSON.stringify(formRenderInstance
                    .userData))
            });
        </script>
    @endif
@endpush
