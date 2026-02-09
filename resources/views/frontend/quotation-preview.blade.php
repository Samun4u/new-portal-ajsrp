@extends('frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush
@section('content')
<section class="checkout-section">
    <div class="checkout-wrap">
        <!-- Back/Download -->
        <div class="bd-b-one bd-c-stroke pb-25 mb-25 d-flex justify-content-end align-items-center flex-wrap g-10">
            <!--  -->

            <!--  -->
            <a target="blank" href="{{route('quotation.print', encrypt($quotation->id))}}"
                class="d-inline-flex align-items-center cg-10 border-0 bd-ra-4 py-5 px-10 bg-green fs-14 fw-500 lh-20 text-white">
                {{__('Download')}}
                <img src="{{asset('assets/images/icon/download-inv.svg')}}" alt="" />
            </a>

            <a target="blank" href="{{$checkout_url}}"
                class="d-inline-flex align-items-center cg-10 border-0 bd-ra-4 py-5 px-10 bg-main-color fs-14 fw-500 lh-20 text-white">
                {{__('Pay Now')}}
            </a>

            <a target="blank" href="{{route('quotation.cancel',[ encrypt($quotation->id), QUOTATION_STATUS_CANCELED])}}"
                class="d-inline-flex align-items-center cg-10 border-0 bd-ra-4 py-5 px-10 bg-red fs-14 fw-500 lh-20 text-white">
                {{__('Cancel')}}
            </a>
        </div>
        <!-- Logo/No-Expire -->
        <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-stroke pb-25 mb-25">
            <!--  -->
            <div class="max-w-167">
                <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
            </div>
            <!--  -->
            <div class="">
                <p class="fs-15 fw-500 lh-20 text-para-text pb-6 text-end">{{__('Qut No')}} - <span
                        class="text-title-black">{{$quotation->quotation_id}}</span>
                </p>
                <p class="fs-15 fw-500 lh-20 text-para-text text-end">{{__('Expire Date')}} :
                    <span class="text-title-black">{{date('d/m/Y',
                        strtotime($quotation->expire_date))}}</span>
                </p>
            </div>
        </div>
        <!-- Info -->
        <ul class="zList-pb-15 pb-50">
            <li class="d-flex justify-content-between align-items-center">
                <p class="fs-15 fw-400 lh-20 text-para-text">{{__('Quotation to')}} :</p>
                <p class="fs-15 fw-400 lh-20 text-title-black">{{$quotation->client_name}}</p>
            </li>
            <li class="d-flex justify-content-between align-items-center">
                <p class="fs-15 fw-400 lh-20 text-para-text">{{__('Email Address')}} :</p>
                <p class="fs-15 fw-400 lh-20 mailto:text-title-black">{{$quotation->email}}</p>
            </li>
            <li class="d-flex justify-content-between g-10">
                <p class="fs-15 fw-400 lh-20 text-para-text flex-shrink-0">{{__('Address')}} :</p>
                <p class="fs-15 fw-400 lh-20 text-title-black">{{$quotation->address}}</p>
            </li>
        </ul>
        <!-- Table -->
        <div class="pb-15 mb-15 table-responsive">
            <table class="table zTable zTable-last-item-right zTable-last-item-border">
                <thead>
                    <tr>
                        <th>
                            <div class="text-nowrap">{{__('Service Name')}}</div>
                        </th>
                        <th>
                            <div>{{__('Price')}}</div>
                        </th>
                        <th>
                            <div>{{__('Duration')}}</div>
                        </th>
                        <th>
                            <div>{{__('Quantity')}}</div>
                        </th>
                        <th>
                            <div>{{__('Total')}}</div>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($quotation_items as $items)
                    <tr>
                        <td>{{$items->service_name}}</td>
                        <td>{{showPrice($items->price)}}</td>
                        <td>{{$items->duration}}-{{__('Day')}}</td>
                        <td>{{$items->quantity}}</td>
                        <td>{{showPrice($items->total)}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <!-- Subtotal/Discount/Total -->
        <div class="max-w-190 w-100 ms-auto mb-30 text-end">
            <ul class="zList-pb-15">
                <li>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Subtotal')}}:</p>
                        </div>
                        <div class="col-6">
                            <p class="fs-14 fw-400 lh-17 text-title-black">{{showPrice($quotation->sub_total)}}</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Discount')}}:</p>
                        </div>
                        <div class="col-6">
                            <p class="fs-14 fw-400 lh-17 text-title-black">{{showPrice($quotation->discount)}}</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Total')}}:</p>
                        </div>
                        <div class="col-6">
                            <p class="fs-14 fw-600 lh-17 text-main-color">{{showPrice($quotation->total)}}</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Terms and Conditions -->
        <h4 class="fs-14 fw-500 lh-28 text-title-black pb-3">{{__('Description')}}:</h4>
        <ul>
            <li>
                <p class="fs-14 fw-400 lh-28 text-para-text">{{$quotation->description}}</p>
            </li>
        </ul>
    </div>
</section>

@endsection

@push('script')

@endpush
