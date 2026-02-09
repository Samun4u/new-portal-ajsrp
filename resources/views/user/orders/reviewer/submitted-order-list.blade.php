@extends('user.layouts.app')
@push('title')
{{$pageTitle}}
@endpush
@push('style')
    <style>
        .order-take-part-content {
            box-shadow: 0 2px 10px rgb(0 0 0 / 10%);
            padding: 25px 20px;
            box-sizing: border-box;
            border-radius: 10px;
            color: #333;
        }

        .order-take-part-content h3 {
            font-size: 20px;
            padding-bottom: 40px;
        }

        .row .col-lg-3:last-child .order-take-part-item {
            border-right: none;
        }

        .order-take-part-item {
            text-align: center;
            border-right: 1px solid #dadada;
            padding: 0 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* .order-take-part-item a {
            width: 100%;
        } */

        .order-take-part-item p {
            font-size: 17px;
            line-height: 28px;
            padding: 15px 0;
            font-weight: 500;
        }

        .order-take-part-item h4 {
            font-size: 19px;
            font-weight: 700;
            color: #0073ac;

        }

        .order-take-part-item h4:hover {
            text-decoration: underline;
        }


        /* media query */
        @media(max-width: 1199px) {

            .order-take-part-item p {
                font-size: 14px;
                line-height: 20px;
                padding: 10px 0;
                font-weight: 500;
            }

            .order-take-part-item h4 {
                font-size: 16px;
                font-weight: 700;
                color: #0073ac;
            }

            .order-take-part-content .col-lg-3.col-sm-6{
                padding-top:50px;
            }

        }

         /* media query */
         @media(max-width: 666.99px) {
            .order-take-part-item{
                border-right: none;
            }

            .order-take-part-content h3 {
                font-size: 20px;
                padding-bottom: 0px;
            }

         }
    </style>
@endpush
@section('content')
<!-- Content -->
 @if(auth()->user()->role == USER_ROLE_CLIENT)
{{-- <section class="order-take-part">
    <div class="p-sm-30 p-15">
            <div class="order-take-part-content">

                <h3>Take Part in AJSRP</h3>

                <div class="row">

                    <!-- order-take-part-item -->
                    <div class="col-lg-3 col-sm-6">

                        <div class="order-take-part-item">
                            <div class="img">
                                <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                            </div>

                            <div class="text">

                                <p>
                                    {{ __('Rigorous, constructive, transparent and fast peer review') }}
                                </p>

                                <a href="{{ route('user.submission.select-a-journal', ['by' => 'by-subject']) }}" target="_blank" class="text-decoration-none">
                                    <h4 class="m-0">
                                        {{ __('New Submission') }}
                                        <i class="fas fa-external-link-alt"></i>
                                    </h4>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- order-take-part-item -->
                    {{-- <div class="col-lg-3 col-sm-6">

                        <div class="order-take-part-item">
                            <div class="img">
                                <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                            </div>

                            <div class="text">

                                <p>
                                    {{ __('Rigorous, constructive, transparent and fast peer review') }}
                                </p>

                                <h4>
                                    {{ __('Submit My Manuscript') }}
                                    <i class="fas fa-external-link-alt"></i>
                                </h4>

                            </div>
                        </div>

                    </div>

                    <!-- order-take-part-item -->
                    <div class="col-lg-3 col-sm-6">

                        <div class="order-take-part-item">
                            <div class="img">
                                <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                            </div>

                            <div class="text">

                                <p>
                                    {{ __('Rigorous, constructive, transparent and fast peer review') }}
                                </p>

                                <h4>
                                    {{ __('Submit My Manuscript') }}
                                    <i class="fas fa-external-link-alt"></i>
                                </h4>

                            </div>
                        </div>

                    </div>

                    <!-- order-take-part-item -->
                    <div class="col-lg-3 col-sm-6">

                        <div class="order-take-part-item">
                            <div class="img">
                                <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                            </div>

                            <div class="text">

                                <p>
                                    {{ __('Rigorous, constructive, transparent and fast peer review') }}
                                </p>

                                <h4>
                                    {{ __('Submit My Manuscript') }}
                                    <i class="fas fa-external-link-alt"></i>
                                </h4>

                            </div>
                        </div>

                    </div> --}}

                </div>

            </div>
    </div>
</section> --}}
@endif
@if($orderCount > 0)
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Tab - Create -->
        {{-- <div
            class="d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center align-items-md-start flex-wrap g-10 table-pl">
            <!-- Left -->
            <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active bg-transparent orderStatusTab" id="allOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#allOrder-tab-pane" type="button" role="tab" aria-controls="allOrder-tab-pane"
                            aria-selected="true" data-status="all">{{__("All")}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent orderStatusTab" id="pendingOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#workingOrder-tab-pane" type="button" role="tab"
                            aria-controls="workingOrder-tab-pane" aria-selected="false" data-status="{{ORDER_PAYMENT_STATUS_PENDING}}">{{__("In Progress")}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent orderStatusTab" id="completedOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#completedOrder-tab-pane" type="button" role="tab"
                            aria-controls="completedOrder-tab-pane" aria-selected="false" data-status="{{ORDER_PAYMENT_STATUS_PAID}}">{{__("Completed")}}</button>
                </li>
            </ul>

        </div> --}}
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!-- All Order -->
            <div class="tab-pane fade show active" id="allOrder-tab-pane" role="tabpanel" aria-labelledby="allOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-all">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order Number')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Title of the paper')}}</div>
                            </th>
                            <th>
                                <div>{{__('Submission date')}}</div>
                            </th>
                            <th>
                                <div>{{__('Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Working Order -->
            {{-- <div class="tab-pane fade" id="workingOrder-tab-pane" role="tabpanel" aria-labelledby="workingOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_PENDING}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div> --}}
            <div class="tab-pane fade" id="workingOrder-tab-pane" role="tabpanel" aria-labelledby="workingOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_PENDING}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Paper Title')}}</div>
                            </th>
                            <th>
                                <div>{{__('Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Completed Order -->
            <!-- Cancelled Order -->
            <div class="tab-pane fade" id="completedOrder-tab-pane" role="tabpanel" aria-labelledby="completedOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_PAID}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Paper Title')}}</div>
                            </th>
                            <th>
                                <div>{{__('Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Cancelled Order -->
            {{-- <div class="tab-pane fade" id="cancelledOrder-tab-pane" role="tabpanel" aria-labelledby="cancelledOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_CANCELLED}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div> --}}
        </div>
    </div>
    <input type="hidden" id="client-order-list-route" value="{{ route('user.orders.reviewer.submission.list') }}">
@else
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
            <div class="create-wrap">
                <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt=""/></div>
                <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">{{__("There is no order available here!")}}</h4>
            </div>
        </div>
    </div>
@endif

@endsection

@push('script')
<script src="{{ asset('user/custom/js/reviewer-submitted-orders.js') }}"></script>
@endpush
