<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.responsive.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}" />

    <style>
        @page {
            margin: 0px;
            padding: 0px;
        }

        body {
            margin: 0px;
            padding: 0px;
        }

        img {
            width: 120px;
        }

        * {
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="modal-xl mx-auto m-5">
        <div class="row">
            <div class="col-md-12">
                <div class="p-3 bg-white rounded">
                    <div class="px-20" id="printableArea">
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
                            <li class="d-flex justify-content-between align-items-center">
                                <p class="fs-15 fw-400 lh-20 text-para-text">{{__('Address')}} :</p>
                                <p class="fs-15 fw-400 lh-20 text-title-black">{{$quotation->address}}</p>
                            </li>
                        </ul>
                        <!-- Table -->
                        <div class="pb-30">
                            <table class="table zTable zTable-last-item-right zTable-last-item-border">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>{{__('Service Name')}}</div>
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
                                            <p class="fs-14 fw-400 lh-17 text-title-black">
                                                {{showPrice($quotation->sub_total)}}</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Discount')}}:</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="fs-14 fw-400 lh-17 text-title-black">
                                                {{showPrice($quotation->discount)}}</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__('Total')}}:</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="fs-14 fw-600 lh-17 text-main-color">
                                                {{showPrice($quotation->total)}}</p>
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
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/js/jquery-3.7.0.min.js') }}"></script>
        <script src="{{ asset('admin/custom/js/print-invoice.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{asset('assets/js/dataTables.responsive.min.js')}}"></script>

</body>

</html>
