@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')

    @if($ticketCount > 0)
        <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
            <!-- Tab - Create -->
            <div class="d-flex flex-column-reverse flex-xl-row justify-content-center justify-content-xl-between align-items-center align-items-xl-start flex-wrap g-10 ticketList-table-search">
                <!-- Left -->
                <ul class="nav nav-tabs zTab-reset zTab-four flex-wrap pl-sm-20" id="myTab" role="tablist">
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 active bg-transparent ticketStatusTab" id="allTicket-tab" data-bs-toggle="tab" data-bs-target="#allTicket-tab-pane" type="button" role="tab" aria-controls="allTicket-tab-pane" aria-selected="true" data-status="all">{{__("All")}}</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 bg-transparent ticketStatusTab" id="openTicket-tab" data-bs-toggle="tab" data-bs-target="#openTicket-tab-pane" type="button" role="tab" aria-controls="openTicket-tab-pane" aria-selected="false" data-status="{{TICKET_STATUS_OPEN}}">{{__("Open")}}</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 bg-transparent ticketStatusTab" id="onHoldTicket-tab" data-bs-toggle="tab" data-bs-target="#onHoldTicket-tab-pane" type="button" role="tab" aria-controls="onHoldTicket-tab-pane" aria-selected="false" data-status="{{TICKET_STATUS_IN_PROGRESS}}">{{__("Processing")}}</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 bg-transparent ticketStatusTab" id="resolvedTicket-tab" data-bs-toggle="tab" data-bs-target="#resolvedTicket-tab-pane" type="button" role="tab" aria-controls="resolvedTicket-tab-pane" aria-selected="false" data-status="{{TICKET_STATUS_RESOLVED}}">{{__("Resolved")}}</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 bg-transparent ticketStatusTab" id="closedTicket-tab" data-bs-toggle="tab" data-bs-target="#closedTicket-tab-pane" type="button" role="tab" aria-controls="closedTicket-tab-pane" aria-selected="false" data-status="{{TICKET_STATUS_CLOSED}}">{{__("Closed")}}</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link px-sm-15 px-13 bg-transparent ticketStatusTab" id="trashedTicket-tab" data-bs-toggle="tab" data-bs-target="#trashedTicket-tab-pane" type="button" role="tab" aria-controls="trashedTicket-tab-pane" aria-selected="false" data-status="{{TICKET_STATUS_TRASHED}}">{{__("Trashed")}}</button>
                    </li>
                </ul>
                <div class="flex-grow-1 d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap g-12">
                    <div class="search-one flex-grow-1 max-w-282">
                        <input type="text" placeholder="{{__("Search here")}}..."  id="datatableSearch"/>
                        <button class="icon"><img src="{{asset('assets/images/icon/search.svg')}}" alt="" /></button>
                    </div>
                    <!--  -->
                    <a href="{{route('admin.ticket.add-new')}}" class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">+ {{__("Create Ticket")}}</a>
                </div>
            </div>
            <!--  -->
            <div class="tab-content" id="myTabContent">
                <!-- All Ticket -->
                <div class="tab-pane fade show active ticketStatusTab" id="allTicket-tab-pane" role="tabpanel" aria-labelledby="allTicket-tab" tabindex="0">
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right " id="ticketTable-all">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Last Updated")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Open Ticket -->
                <div class="tab-pane fade " id="openTicket-tab-pane" role="tabpanel" aria-labelledby="openTicket-tab" tabindex="0" >
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right" id="ticketTable-{{TICKET_STATUS_OPEN}}">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Processing Ticket -->
                <div class="tab-pane fade " id="onHoldTicket-tab-pane" role="tabpanel" aria-labelledby="onHoldTicket-tab" tabindex="0" >
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right" id="ticketTable-{{TICKET_STATUS_IN_PROGRESS}}">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- resolved Ticket -->
                <div class="tab-pane fade " id="resolvedTicket-tab-pane" role="tabpanel" aria-labelledby="resolvedTicket-tab" tabindex="0" >
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right" id="ticketTable-{{TICKET_STATUS_RESOLVED}}">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- closed Ticket -->
                <div class="tab-pane fade " id="closedTicket-tab-pane" role="tabpanel" aria-labelledby="closedTicket-tab" tabindex="0" >
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right" id="ticketTable-{{TICKET_STATUS_CLOSED}}">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Trashed Ticket -->
                <div class="tab-pane fade " id="trashedTicket-tab-pane" role="tabpanel" aria-labelledby="trashedTicket-tab" tabindex="0" >
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                        <table class="table zTable zTable-last-item-right" id="ticketTable-{{TICKET_STATUS_TRASHED}}">
                            <thead>
                            <tr>
                                <th><div class="text-nowrap">{{__("Ticket ID")}}</div></th>
                                <th><div class="text-nowrap">{{__('Order ID')}} / {{ __("General Inquiry") }}</div></th>
                                <th><div>{{__("Client")}}</div></th>
                                <th><div>{{__("Priority")}}</div></th>
                                <th><div>{{__("Assignee")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
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
                    <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt="" /></div>
                    <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">{{__("There is no tickets available here!")}}</h4>
                    <a href="{{route('admin.ticket.add-new')}}" class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">+{{__("Add Ticket")}}</a>
                </div>
            </div>
        </div>
    @endif
    <input type="hidden" id="ticketListRoute" value="{{route('admin.ticket.list')}}">
    <input type="hidden" id="assignMemberRoute" value="{{route('admin.ticket.assign-member')}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/ticket.js') }}"></script>
@endpush

