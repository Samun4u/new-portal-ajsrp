@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="row rg-20">
                <div class="col-xl-4 col-md-5">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-sm-25 p-15">
                        <div class="w-105 h-105 rounded-circle overflow-hidden"><img
                                src="{{asset(getFileUrl($clientDetails->image))}}" alt=""/></div>
                        <div class="bd-t-one bd-c-stroke pt-22 mt-30">
                            <ul class="zList-pb-16">
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4 class="fs-12 fw-500 lh-19 text-title-black">{{__('Name')}}
                                            :</h4></div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->name ?? 'N/A'}}</p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Email Address')}} :</h4>
                                    </div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->email ?? 'N/A'}}</p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Phone Number')}} :</h4>
                                    </div>
                                    <div class="col-6">
                                        @if($clientDetails && $clientDetails->mobile)
                                            <p class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->mobile}}</p>
                                        @else
                                            <p class="fs-12 fw-500 lh-19 text-para-text">{{__('No Data Found')}}</p>
                                        @endif
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Status')}} :</h4>
                                    </div>
                                    <div class="col-6">
                                        @if($clientDetails && $clientDetails->status == STATUS_ACTIVE)
                                            <p class="zBadge zBadge-active">{{__('Active')}}</p>
                                        @else
                                            <p class="zBadge zBadge-pending">{{__('Deactivate')}}</p>
                                        @endif
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Account Status')}} :</h4>
                                    </div>
                                    <div class="col-6 d-flex fs-12 fw-500 lh-19 gap-2">{{ accountStatus($clientDetails->status) }}
                                        <a class='d-flex align-items-center cg-8 ' href='#' id="changeStatusButton">
                                            Change
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-7">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white py-sm-25 pt-25">
                        <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-sm-15 px-13 active bg-transparent orderStatusTab" id="orderHistory-tab"
                                        data-bs-toggle="tab" data-bs-target="#orderHistory-tab-pane" type="button"
                                        role="tab" aria-controls="orderHistory-tab-pane"
                                        aria-selected="true">{{__('Order History')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-sm-15 px-13 bg-transparent orderStatusTab" id="invoice-tab"
                                        data-bs-toggle="tab" data-bs-target="#invoice-tab-pane" type="button" role="tab"
                                        aria-controls="invoice-tab-pane"
                                        aria-selected="false">{{__('Invoice')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-sm-15 px-13 bg-transparent orderStatusTab" id="activity-tab"
                                        data-bs-toggle="tab" data-bs-target="#activity-tab-pane" type="button" role="tab"
                                        aria-controls="activity-tab-pane"
                                        aria-selected="false">{{__('Activity Log')}}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <!-- Order History -->
                            <div class="tab-pane fade show active" id="orderHistory-tab-pane" role="tabpanel"
                                 aria-labelledby="orderHistory-tab" tabindex="0">
                                <div class="bd-t-one bd-c-stroke p-sm-30 p-15 pt-25">
                                    <table class="table zTable zTable-last-item-right" id="clientOrderHistoryTable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-nowrap">{{__('Order ID')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Amount')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Paid Amount')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Working Status')}}</div>
                                                </th>
                                                <th>
                                                    <div>{{__('Status')}}</div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- Invoice -->
                            <div class="tab-pane fade" id="invoice-tab-pane" role="tabpanel"
                                 aria-labelledby="invoice-tab" tabindex="0">
                                <div class="bd-t-one bd-c-stroke p-sm-30 p-15">
                                    <table class="table zTable zTable-last-item-right" id="clientInvoiceHistoryDatatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-nowrap">{{__('Invoice Id')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Order Id')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Gateway')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Amount')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Date')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Status')}}</div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- Activity log -->
                            <div class="tab-pane fade" id="activity-tab-pane" role="tabpanel"
                                 aria-labelledby="activity-tab" tabindex="0">
                                <div class="bd-t-one bd-c-stroke p-sm-30 p-15">
                                    <table class="table zTable zTable-last-item-right" id="clientActivityLogHistoryDatatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-nowrap">{{__('Action')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('Source')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('IP Address')}}</div>
                                                </th>

                                                <th>
                                                    <div class="text-nowrap">{{__('Location')}}</div>
                                                </th>
                                                <th>
                                                    <div class="text-nowrap">{{__('When')}}</div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div
                        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{__('Change Account Status')}}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" class="ajax reset"
                          action="{{route('admin.client.update-status',['id' => encrypt($clientDetails->id)])}}"
                          data-handler="commonResponseWithPageLoad">
                        @csrf
                        <div class="pb-25">
                            <label for="status">{{__('Select Status')}}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ $clientDetails->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="3" {{ $clientDetails->status == 3 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                <option value="5" {{ $clientDetails->status == 5 ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                            </select>
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit"
                                    class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-white bg-main-color d-flex justify-content-center">{{__("Submit")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal HTML -->
    <input type="hidden" id="client-order-history-route" value="{{route('admin.client.details',['id' => $clientDetails->id])}}">
    <input type="hidden" id="client-invoice-history-route" value="{{route('admin.client.invoice',['id' => $clientDetails->id])}}">
    <input type="hidden" id="client-activity-log-history-route" value="{{route('admin.client.activity-log-history',['id' => $clientDetails->id])}}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/client.js') }}"></script>
    <script>
        $('#changeStatusButton').on('click', function(e) {
            e.preventDefault();
            $('#statusModal').modal('show');
        });
    </script>
@endpush

