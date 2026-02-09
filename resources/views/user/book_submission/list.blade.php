@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Content -->
    <span id="searchresult">
        @if ($booksCount > 0)
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
                        {{-- <li class="nav-item" role="presentation">
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
                        </li> --}}
                    </ul>
                    <div class="flex-grow-1 d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap g-12">
                        <div class="search-one flex-grow-1 max-w-282">
                            <input type="text" placeholder="{{__("Search here")}}..."  id="datatableSearch"/>
                            <button class="icon"><img src="{{asset('assets/images/icon/search.svg')}}" alt="" /></button>
                        </div>
                        <!--  -->
                        <a href="{{route('user.submit-your-book.index')}}" class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">+ {{__("Submit New Book")}}</a>
                    </div>
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
                                            <div>{{ __('Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Author') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('email') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Publication Year') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Allow Public') }}</div>
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
                                            <div>{{ __('Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Author') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('email') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Publication Year') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Allow Public') }}</div>
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
                                            <div>{{ __('Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Author') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('email') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Publication Year') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Allow Public') }}</div>
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
                                            <div>{{ __('Title') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('Author') }}</div>
                                        </th>
                                        <th>
                                            <div>{{ __('email') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Publication Year') }}</div>
                                        </th>
                                        <th>
                                            <div  class="text-nowrap">{{ __('Allow Public') }}</div>
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
                            {{ __("There is no book yet") }}
                        </h4>
                        <a href="{{route('user.submit-your-book.index')}}"
                    class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">+ {{__("Submit a Book")}}</a>
                    </div>
                </div>
            </div>
        @endif
    </span>


    {{-- book details model --}}
    <div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-25 invoice-content-wrap">
                <div class="invoice-content">

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="client-submitted-book-list-route" value="{{ route('user.submit-your-book.list') }}">
@endsection


@push('script')
    <script src="{{ asset('user/custom/js/submit-book.js') }}"></script>
@endpush
