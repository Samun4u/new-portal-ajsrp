@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Page content area start -->
    <div class="px-24 pb-24 position-relative">
        <!-- Info & Add product button -->
        <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
            <div class="">
                <h4 class="fs-24 fw-500 lh-24 text-white"></h4>
            </div>
        </div>
        <!-- Table -->
        <div class="col-lg-12">
            <div class="col-md-12 bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <div class="customers__table">
                    <div class="table-responsive zTable-responsive">
                        <table class="able zTable" id="customersTable"
                               aria-describedby="customersTable_info">
                            <thead>
                            <tr>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-w-150">{{ __('Customer Name') }}</div>
                                </th>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-sm-w-100">{{ __('Emails') }}</div>
                                </th>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-w-120">{{ __('Created Date') }}</div>
                                </th>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-sm-w-100">{{ __('Country') }}</div>
                                </th>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-sm-w-100">{{ __('Payments') }}</div>
                                </th>
                                <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                    <div class="min-sm-w-100">{{ __('Revenue') }}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content area end -->
        @endsection
        @push('script')
            <script>
                (function ($) {
                    "use strict";

                    $("#customersTable").DataTable({
                        pageLength: 10,
                        ordering: false,
                        serverSide: true,
                        processing: true,
                        searching: false,
                        responsive: {
                            breakpoints: [
                                { name: "desktop", width: Infinity },
                                { name: "tablet", width: 1400 },
                                { name: "fablet", width: 768 },
                                { name: "phone", width: 480 },
                            ],
                        },
                        ajax: '{{route('admin.customer.list')}}',
                        language: {
                            paginate: {
                                previous: "<i class='fa-solid fa-angles-left'></i>",
                                next: "<i class='fa-solid fa-angles-right'></i>",
                            },
                            searchPlaceholder: "Search pending event",
                            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
                        },
                        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
                        columns: [
                            {"data": "name", "name": "name",responsivePriority:1},
                            {"data": "email", "name": "email"},
                            {"data": "created_at", "name": "userDetail.created_at"},
                            {"data": "country", "name": "userDetail.country"},
                            {"data": "payment", "name": "userDetail.payment"},
                            {"data": "revenue", "name": "userDetail.revenue"},
                        ],
                    });

                })(jQuery)

            </script>
    @endpush
