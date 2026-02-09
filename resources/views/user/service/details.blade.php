@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="">
        <!--  -->
        <div
            class="py-19 px-sm-30 px-15 bd-b-one bd-c-stroke d-flex justify-content-center justify-content-md-between align-items-center flex-wrap g-10">
            <!-- Right -->
            <h4 class="fs-18 fw-600 lh-22 text-title-black text-center">{{$serviceDetails->service_name}}</h4>
            <!-- Left -->
            {{-- 
            By oubtou 
            <form class="ajax" action="{{ route('user.gateway.list') }}" method="post"
                  enctype="multipart/form-data" data-handler="setPaymentModal">
                @csrf
                <input type="hidden" name="id" value="{{ $serviceDetails->id }}">
                <input type="hidden" name="type" value="service">
                <button
                    class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 bg-main-color d-flex align-items-center cg-12 fs-15 fw-600 lh-25 text-white">{{__("Buy Now")}}</button>
            </form>
            
            --}}
            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#byService" class="d-inline-flex border-0 bd-ra-8 bg-main-color py-8 px-26 fs-15 fw-600 lh-25 text-white">{{__("Buy Now")}}</button> --}}
            
            <form action="{{ route('user.checkout.order.withoutpaiement') }}" method="post"
                    enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $serviceDetails->id }}">
                <input type="hidden" name="type" value="service">
                <button class="btn btn-primary">{{__("Buy Now")}}</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </form>
            
        </div>
        <!--  -->
        @include('admin.service.details-partial')
    </div>
    
    <div class="modal fade" id="byService" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                
                <!--div class="modal-header">
                    <h5 class="modal-title">{{__('Buy Service')}}</h5>
                    <button type="button" class="w-32 h-32 d-flex justify-content-center align-items-center border-0 bg-transparent fs-20 text-para-text " data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div-->
                
                
                <div class="modal-body text-center">
                    <br>
                    <h3>{{ __('Are you sure ?') }}</h3>
                    <br>
                    <br>
                    <br>
                    <form action="{{ route('user.checkout.order.withoutpaiement') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $serviceDetails->id }}">
                        <input type="hidden" name="type" value="service">
                        <button class="btn btn-primary">{{__("Buy Now")}}</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Buy Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
                    <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__("Select Payment Method")}}</h4>
                    <button type="button"
                            class="w-30 h-30 bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center p-0 bg-white"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <!--  -->
                <form class="ajax" action="{{route('user.checkout.order.place')}}" method="POST" data-handler="checkoutOrderResponse">
                    @csrf
                    <input type="hidden" id="checkoutType" name="checkout_type" value="{{ CHECKOUT_TYPE_USER_SERVICE }}">
                    <input type="hidden" id="selectGateway" name="gateway">
                    <input type="hidden" id="selectedGatewayId" value="0" name="gateway_id">
                    <input type="hidden" id="currencyId" value="0" name="currency">
                    <input type="hidden" id="coupon" name="coupon">
                    <input type="hidden" id="itemId" name="item_id">
                    <span id="gatewayListBlock"></span>
                    <!--  -->
                    <button type="submit"
                            class="w-75 m-auto p-12 d-flex justify-content-center align-items-center border-0 bd-ra-8 bg-main-color fs-15 fw-600 lh-20 text-white">
                        {{__("Pay Now")}} <span id="orderPlaceSubmitBtnAmountBlock" class="d-none"> {{" ("}}{{currentCurrency('symbol')}}<span id="orderPlaceSubmitBtnAmount"></span>{{")"}}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="gotoRoute" value="{{ route('user.services.list') }}">
@endsection

@push('script')
    <script src="{{ asset('user/custom/js/checkout.js') }}"></script>
@endpush

