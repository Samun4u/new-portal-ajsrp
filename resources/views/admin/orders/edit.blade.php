@extends('admin.layouts.app')
@push('title')
{{$pageTitle}}
@endpush
@section('content')
<!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="max-w-894 m-auto">
        <!--  -->
        <h4 class="fs-18 fw-600 lh-20 text-title-black pb-17">{{$pageTitle}}</h4>
        <!--  -->
        <form method="POST" class="ajax reset" action="{{ route('admin.client-orders.store') }}"
            data-handler="commonResponse">
            @csrf

            <input type="hidden" name="id" value="{{$order->id}}">

            <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-28">
                <div class="max-w-713 m-auto py-sm-52 py-15">
                    <!--  -->
                    <div class="row rg-20">
                        <div class="col-12">
                            <div class="col-12">
                                <label for="createOrderSelectClient" class="zForm-label">{{__('Select
                                    Client')}}</label>
                                <select class="sf-select-two" name="client_id">
                                    @foreach($allClient as $client)
                                    <option value="{{$client->id}}" {{$client->id == $order->client_id ? 'selected' :
                                        ''}}>{{$client->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 table-responsive quotation-table-wrap">
                            <table class="table zTable zTable-last-item-right" id="inputTable">
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
                                <tbody>

                                    @foreach ($orderItem as $key => $items)
                                    <tr>
                                        <input type="hidden" name="types[]" value="1">
                                        <td>
                                            <select class="form-select singleService service_id"
                                                name="service_id[]">
                                                @foreach($allService as $serviceItem)
                                                <option value="{{ $serviceItem->id }}"
                                                    data-price="{{ $serviceItem->price }}" {{ $serviceItem->id ==
                                                    $items->service_id ? 'selected' : ''}}>
                                                    {{ $serviceItem->service_name }}, ({{ showPrice($serviceItem->price)
                                                    }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input type="text" name="price[]"
                                                    class="form-control zForm-control zForm-control-table selectedValueContainer service-price price"
                                                    id="" placeholder="Enter Price" value="{{$items->price}}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="min-w-100">
                                                <input type="text" name="discount[]"
                                                    class="form-control zForm-control zForm-control-table discount"
                                                    id="" placeholder="Enter Discount" value="{{$items->discount}}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="">
                                                <input type="number" name="quantity[]"
                                                    class="form-control zForm-control zForm-control-table quantity"
                                                    id="" placeholder="Enter Quantity" value="{{$items->quantity}}" />
                                            </div>
                                        </td>
                                        <td>
                                            @if(!$key+1 == 1)
                                            <button type="button"
                                                class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red"><i
                                                    class="fa-solid fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <hr class="my-4  border-primary">

                            <div id="otherFields">

                            </div>

                            <button
                                class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addmoreservice">
                                + {{__('Add More Service')}}</button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start flex-wrap g-14 pb-25">
                    <button
                        class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 bg-main-color fs-15 fw-600 lh-25 text-white">
                        {{__('Create Order')}}
                    </button>
                    <a href="{{ URL::previous() }}"
                        class="bd-one bd-c-para-text bd-ra-8 py-10 px-26 bg-white fs-15 fw-600 lh-25 text-para-text">
                        {{__('Cancel')}}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<input type="hidden" id="service-data-route" value="{{ route('admin.client-orders.all-service') }}">
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/client-orders.js') }}"></script>
@endpush
