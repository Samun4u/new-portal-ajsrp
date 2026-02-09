@extends(auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER ? 'user.layouts.app' : 'admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@push('style')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
}

.badge {
    padding: 0.5em 1em;
    font-size: 0.9em;
    border-radius: 0.5rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: background-color 0.3s ease;
}

/* .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
} */

.form-select {
    border-radius: 0.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-radius: 0.5rem 0 0 0.5rem;
}

        /* Styles for reviewer assign modal (from reviwer-dropdown-selection.html) */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1055;
        }

        .overlay.active {
            display: flex;
        }

        .reviewer-assign-modal {
            background: #ffffff;
            width: 100%;
            max-width: 800px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.35);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .reviewer-assign-modal .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reviewer-assign-modal .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .reviewer-assign-modal .modal-subtitle {
            font-size: 12px;
            color: #9aa5b1;
            margin-top: 4px;
        }

        .reviewer-assign-modal .modal-close {
            border: none;
            background: transparent;
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
            color: #9aa5b1;
            transition: color 0.08s ease, transform 0.08s ease;
        }

        .reviewer-assign-modal .modal-close:hover {
            color: #111827;
            transform: scale(1.1);
        }

        .reviewer-assign-modal .modal-body {
            padding: 16px 22px 18px;
            overflow-y: auto;
        }

        .reviewer-search-row {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .reviewer-search-input-wrap {
            flex: 1;
            position: relative;
        }

        .reviewer-search-input-wrap input {
            width: 100%;
            padding: 9px 12px 9px 30px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            outline: none;
            transition: border 0.08s ease, box-shadow 0.08s ease;
        }

        .reviewer-search-input-wrap input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .reviewer-search-icon {
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #9ca3af;
        }

        .reviewer-pill-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .reviewer-pill {
            border-radius: 999px;
            font-size: 11px;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
        }

        .reviewer-pill.active {
            background: #e0edff;
            border-color: #3b82f6;
            color: #1d4ed8;
            font-weight: 500;
        }

        .reviewer-list {
            margin-top: 6px;
            display: grid;
            gap: 10px;
        }

        .reviewer-card {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            transition: box-shadow 0.08s ease, transform 0.08s ease, border-color 0.08s ease;
        }

        .reviewer-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            border-color: #d1d5db;
        }

        .reviewer-main {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: linear-gradient(135deg, #2563eb, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .reviewer-info {
            font-size: 13px;
        }

        .reviewer-name {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .reviewer-meta {
            font-size: 11px;
            color: #6b7280;
        }

        .reviewer-meta span+span::before {
            content: "•";
            margin: 0 6px;
            color: #d1d5db;
        }

        .reviewer-expertise {
            margin-top: 2px;
            font-size: 11px;
            color: #4b5563;
        }

        .reviewer-tags {
            margin-top: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .tag {
            font-size: 10px;
            background: #f3f4f6;
            border-radius: 999px;
            padding: 3px 7px;
            color: #4b5563;
        }

        .reviewer-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            font-size: 11px;
            color: #9ca3af;
        }

        .btn-assign {
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            background: #10b981;
            color: #ffffff;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-assign:hover {
            background: #059669;
        }

        .btn-outline {
            border: 1px dashed #9ca3af;
            background: transparent;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 11px;
            cursor: pointer;
            color: #6b7280;
        }

        .reviewer-modal-footer {
            padding: 12px 22px 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #9ca3af;
        }

        .reviewer-modal-footer-right {
            display: flex;
            gap: 8px;
        }
</style>
@endpush
@section('content')
@php
        $submissionRoles = [
            USER_ROLE_TEAM_MEMBER,
            USER_ROLE_INITIAL_EVALUATOR,
            USER_ROLE_FINANCIAL_MANAGER,
            USER_ROLE_PUBLISHER,
            USER_ROLE_MARKETER,
        ];
@endphp
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="page-content-wrapper bg-white bd-ra-15 p-20">
            <!-- Page Title -->
            <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ $pageTitle }}</h5>

            <div class="row flex-column-reverse flex-xl-row">
                <div class="col-xl-4">
                    <div class="p-lg-30">
                        <div class="max-w-894 m-auto">
                            <!-- Order title -->
                            <h4 class="fs-18 fw-600 lh-20 text-title-black pb-11 text-md-start text-center">
                                {{ __('Order ID') }}
                                : {{ $order->order_id }}</h4>
                            <!-- Order info - Note + Assign + Status -->
                            <div
                                    class="d-flex justify-content-center justify-content-md-between align-items-start flex-wrap g-10 pb-33">
                                <!-- Left -->
                                <ul
                                    class="bd-ra-5 py-5 px-sm-15 px-6 bg-main-color-10 d-flex justify-content-between w-100">
                                    @if (auth()->user()->role != USER_ROLE_CLIENT &&
                                            auth()->user()->role != USER_ROLE_REVIEWER &&
                                            !in_array(auth()->user()->role, $submissionRoles))
                                        <li>
                                            <h4 class="fs-10 fw-500 lh-20 text-main-color">{{ __('Assign') }}</h4>
                                            <div class="imageDropdown">
                                                    <div class="taskProgressImage">
                                                    @if (count($orderAssignee) > 0)
                                                        @foreach ($orderAssignee as $assignee)
                                                            <img src="{{ getFileUrl(getUserData($assignee, 'image')) }}"
                                                                title="{{ getUserData($assignee, 'name') }}" />
                                                            @endforeach
                                                        @endif
                                                     <button type="button"
                                                        class="border-0 p-0 bg-transparent justify-content-start" id="openAssignModal">
                                                        <div class='iconPlus {{ count($orderAssignee) ? '' : 'ml-0' }}'>
                                                            <i class='fa-solid fa-plus'></i>
                                                    </div>
                                                </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    @if (auth()->user()->role != USER_ROLE_REVIEWER && !in_array(auth()->user()->role, $submissionRoles))
                                    <li>
                                            <h4 class="fs-10 fw-500 lh-20 text-main-color">{{ __('Status') }}</h4>
                                        <div class="dropdown dropdown-two imageDropdown">
                                                <button class="dropdown-toggle border-0 p-0 bg-transparent g-10 min-w-auto"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <p class="fs-12 fw-400 lh-20 text-title-black">
                                                        {{ getOrderStatusName($order->working_status) }}</p>
                                            </button>
                                                @if (auth()->user()->role != USER_ROLE_CLIENT &&
                                                        auth()->user()->role != USER_ROLE_REVIEWER &&
                                                        auth()->user()->role != USER_ROLE_PEER_REVIEWER_MANAGER)
                                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-four">
                                                    <li>
                                                            <a
                                                                href="{{ route('admin.client-orders.status.change', [encrypt($order->id), WORKING_STATUS_WORKING]) }}">
                                                                <p class="fs-14 fw-400 lh-17 text-para-text">
                                                                    {{ __('Working') }}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                            <a
                                                                href="{{ route('admin.client-orders.status.change', [encrypt($order->id), WORKING_STATUS_COMPLETED]) }}">
                                                                <p class="fs-14 fw-400 lh-17 text-para-text">
                                                                    {{ __('Completed') }}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                            <a
                                                                href="{{ route('admin.client-orders.status.change', [encrypt($order->id), WORKING_STATUS_CANCELED]) }}">
                                                                <p class="fs-14 fw-400 lh-17 text-para-text">
                                                                    {{ __('Canceled') }}</p>
                                                        </a>

                                                    </li>
                                                </ul>
                                            @endif
                                        </div>
                                    </li>
                                    @endif
                                    @if (auth()->user()->role === USER_ROLE_REVIEWER)
                                    <li>
                                            <h4 class="fs-10 fw-500 lh-20 text-main-color">{{ __('Status') }}</h4>
                                        <div class="dropdown dropdown-two imageDropdown">
                                                <button class="dropdown-toggle border-0 p-0 bg-transparent g-10 min-w-auto"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <p class="fs-12 fw-400 lh-20 text-title-black">
                                                        {{ getOrderStatusName($order->working_status) }}</p>
                                                {{-- <p class="fs-12 fw-400 lh-20 text-title-black">{{ $orderSubmission && $review ? ucwords(str_replace("_"," ",$review->status)) : ucwords(str_replace("_"," ",SUBMISSION_REVIEWER_ORDER_STATUS_PENDING_REVIEW)) }}</p> --}}
                                            </button>

                                                {{-- <ul class="dropdown-menu dropdown-menu-end dropdownItem-four">
                                                    <li>
                                                        <a href="{{route('user.orders.reviewer.assigned.order.status.change',[ encrypt($orderSubmission->id), SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS])}}">
                                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{ ucwords(str_replace("_"," ",SUBMISSION_REVIEWER_ORDER_STATUS_IN_PROGRESS)) }}</p>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('user.orders.reviewer.assigned.order.status.change',[ encrypt($orderSubmission->id), SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED])}}">
                                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{ ucwords(str_replace("_"," ",SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)) }}</p>
                                                        </a>

                                                    </li>
                                                </ul> --}}
                                        </div>
                                    </li>
                                    @endif

                                </ul>
                            </div>


                            @if (auth()->user()->role == USER_ROLE_CLIENT &&
                                    auth()->user()->role == USER_ROLE_REVIEWER &&
                                    $order->working_status == 3)
                             {{-- <form id="obFileUploadform" action="{{route('user.orders.task-board.uploadfile')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$order->id}}">
                                <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload File (PDF, DOC)")}}</p>
                                <div class="d-flex align-items-center g-10">
                                    <!--  -->
                                    <div class="servicePhotoUpload d-flex flex-column g-10 w-100">
                                        <label for="obFileUpload">
                                            <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose File to upload")}}</p>
                                            <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                        </label>
                                        <div class="max-w-150 flex-shrink-0">
                                            <input type="file" name="file" id="obFileUpload" class="position-absolute invisible" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form> --}}
                            @endif

                            <!-- Info - Note -->
                            <div class="">
                                <!-- Info -->
                                <div class="pb-20">
                                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white pt-12 pb-18">
                                        @if (auth()->user()->role !== USER_ROLE_REVIEWER)
                                            <!-- Client File -->
                                            @if ($order->file)
                                            <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15 bg-main-color-10 pt-12">
                                                <div class="row justify-content-center g-10">
                                                    <div class="col-auto">
                                                            <a href="{{ getFileUrl($order->file) }}" target="blank"
                                                                class="">{{ getFileData($order->file, 'original_name') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- Created Date -->
                                            <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">{{ __('Created') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-para-text">
                                                            {{ date('d/m/Y', strtotime($order->created_at)) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- @if (!in_array(auth()->user()->role, $submissionRoles))  --}}
                                            <!-- Client name -->
                                            <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">{{ __('Client') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-para-text">
                                                            {{ getUserData($order->client_id, 'name') }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- @endif --}}
                                            <!-- Service -->
                                            <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Service') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        @foreach ($order->client_order_items as $service)
                                                            <h4 class="fs-12 fw-500 lh-15 text-para-text">
                                                                {{ getServiceById($service->service_id, 'service_name') }}
                                                            </h4>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Amount -->
                                            <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Amount') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">
                                                            {{ currentCurrency('symbol') }}{{ $order->total }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Payment Status -->
                                            <div
                                                class="@if (auth()->user()->role == USER_ROLE_CLIENT && $primaryCertificate) bd-b-one bd-c-stroke pb-15 mb-15 @endif px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Payment Status') }}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                        @if ($order->payment_status == PAYMENT_STATUS_PENDING)
                                                            <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">
                                                                {{ __('Unpaid') }}</h4>
                                                    @elseif($order->payment_status == PAYMENT_STATUS_PAID)
                                                            <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">
                                                                {{ __('Paid') }}</h4>
                                                    @elseif($order->payment_status == PAYMENT_STATUS_PARTIAL)
                                                            <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">
                                                                {{ __('Partial') }}</h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if (auth()->user()->role == USER_ROLE_CLIENT && $primaryCertificate && $primaryCertificate->certificate_sent)
                                        <!-- Primary certificate - Only shown after admin approval -->
                                            <div
                                                class="@if (auth()->user()->role == USER_ROLE_CLIENT && $finalCertificate) bd-b-one bd-c-stroke pb-15 mb-15 @endif px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Primary Certificate') }}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text"><a
                                                                href="{{ route('user.orders.task-board.primary.certificate', encrypt($order->id)) }}"
                                                                target="_blank">View</a></h4>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if (auth()->user()->role == USER_ROLE_CLIENT && $finalCertificate)
                                        <!-- Final certificate -->
                                        <div class="px-15">
                                            <div class="row justify-content-between g-10">
                                                <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Final Certificate') }}
                                                        :</h4>
                                                </div>
                                                <div class="col-auto">
                                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text"><a
                                                                href="{{ route('user.orders.task-board.final.certificate', encrypt($order->id)) }}"
                                                                target="_blank">View</a></h4>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if (auth()->user()->role == USER_ROLE_REVIEWER)
                                            <!-- Created Date -->
                                            <div
                                                class="@if ($reviewerCertificate) bd-b-one bd-c-stroke pb-15 mb-15 @endif px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                            {{ __('Created') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h4 class="fs-14 fw-500 lh-17 text-para-text">
                                                            {{ date('d/m/Y', strtotime($order->created_at)) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($reviewerCertificate)
                                            <!-- Reviewer certificate -->
                                            <div class="px-15">
                                                <div class="row justify-content-between g-10">
                                                    <div class="col-auto">
                                                            <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                                {{ __('Reviewer Certificate') }}
                                                            :</h4>
                                                    </div>
                                                    <div class="col-auto">
                                                            <h4 class="text-end fs-14 fw-500 lh-17 text-para-text"><a
                                                                    href="{{ route('user.orders.task-board.reviewer.certificate', encrypt($order->id)) }}"
                                                                    target="_blank">View</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if (auth()->user()->role != USER_ROLE_CLIENT &&
                                        auth()->user()->role != USER_ROLE_REVIEWER &&
                                        !in_array(auth()->user()->role, $submissionRoles) &&
                                        auth()->user()->role != USER_ROLE_PEER_REVIEWER_MANAGER)
                                    <!-- Note -->
                                    <div class="">
                                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-25">
                                            <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-14">
                                                <h4 class="fs-14 fw-500 lh-17 text-title-black">{{ __('All Notes') }}</h4>
                                                <button
                                                        class="border-0 bd-ra-8 bg-main-color py-5 px-15 fs-15 fw-600 lh-25 text-white"
                                                    id="noteAddModal" data-bs-toggle="modal"
                                                    data-bs-target="#addNoteModal"
                                                    data-order_id="{{ encrypt($order->id) }}">+ {{ __('Note') }}
                                                </button>
                                            </div>
                                            @if ($order->notes != null && count($order->notes) > 0)
                                                <!--  -->
                                                <ul class="note-list">
                                                    @foreach ($order->notes as $note)
                                                        @if ($note->user_id == auth()->id())
                                                            <li class="d-flex justify-content-between g-10 bg-note-self">
                                                                <!--  -->
                                                                <div class="flex-grow-1">
                                                                    <!--  -->
                                                                    <h4 class="title pb-15">{{ $note->details }}</h4>
                                                                    <!--  -->
                                                                    <div class="d-flex align-items-center g-7 flex-wrap">
                                                                        <div
                                                                                class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                            <img src="{{ getFileUrl(getUserData($note->user_id, 'image')) }}"
                                                                                alt="" />
                                                                        </div>
                                                                        <h4 class="fs-12 fw-500 lh-14 text-title-black">
                                                                            {{ getUserData($note->user_id, 'name') }}
                                                                            ({{ __('You') }})
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                                <!--  -->
                                                                <div class="dropdown dropdown-one">
                                                                    <button
                                                                            class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                            type="button" data-bs-toggle="dropdown"
                                                                            aria-expanded="false"><i
                                                                                class="fa-solid fa-ellipsis-vertical"></i>
                                                                    </button>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                        <li>
                                                                            <button class="d-flex align-items-center cg-8"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#addNoteModal"
                                                                                    id="noteEditModal"
                                                                                data-order_id="{{ encrypt($order->id) }}"
                                                                                data-details="{{ $note->details }}"
                                                                                data-id="{{ encrypt($note->id) }}">
                                                                                <div class="d-flex">
                                                                                    <svg width="12" height="13"
                                                                                        viewBox="0 0 12 13" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                                d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                            fill="#5D697A" />
                                                                                    </svg>
                                                                                </div>
                                                                                <p
                                                                                    class="fs-14 fw-500 lh-17 text-para-text">
                                                                                    {{ __('Edit') }}</p>
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="d-flex align-items-center cg-8"
                                                                                onclick="deleteItem('{{ route('admin.client-orders.note.delete', encrypt($note->id)) }}' , 'no','{{ route('admin.client-orders.task-board.index', [$order->id]) }}')">
                                                                                <div class="d-flex">
                                                                                    <svg width="14" height="15"
                                                                                        viewBox="0 0 14 15" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                                clip-rule="evenodd"
                                                                                                d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                            fill="#5D697A" />
                                                                                    </svg>
                                                                                </div>
                                                                                <p
                                                                                    class="fs-14 fw-500 lh-17 text-para-text">
                                                                                    {{ __('Delete') }}</p>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        @else
                                                            <li class="d-flex justify-content-between g-10">
                                                                <!--  -->
                                                                <div class="flex-grow-1">
                                                                    <!--  -->
                                                                    <h4 class="title pb-15">{{ $note->details }}</h4>
                                                                    <!--  -->
                                                                    <div class="d-flex align-items-center g-7 flex-wrap">
                                                                        <div
                                                                                class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                            <img src="{{ getFileUrl(getUserData($note->user_id, 'image')) }}"
                                                                                alt="" />
                                                                        </div>
                                                                        <h4 class="fs-12 fw-500 lh-14 text-title-black">
                                                                            {{ getUserData($note->user_id, 'name') }}
                                                                            ({{ __('Team Member') }})</h4>
                                                                    </div>
                                                                </div>
                                                                <!--  -->
                                                                <div class="dropdown dropdown-one">
                                                                    <button
                                                                            class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                            type="button" data-bs-toggle="dropdown"
                                                                            aria-expanded="false"><i
                                                                                class="fa-solid fa-ellipsis-vertical"></i>
                                                                    </button>
                                                                    <ul
                                                                        class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                        <li>
                                                                            <button class="d-flex align-items-center cg-8"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#addNoteModal"
                                                                                    id="noteEditModal"
                                                                                data-order_id="{{ encrypt($order->id) }}"
                                                                                data-details="{{ $note->details }}"
                                                                                data-id="{{ encrypt($note->id) }}">
                                                                                <div class="d-flex">
                                                                                    <svg width="12" height="13"
                                                                                        viewBox="0 0 12 13" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                                d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                            fill="#5D697A" />
                                                                                    </svg>
                                                                                </div>
                                                                                <p
                                                                                    class="fs-14 fw-500 lh-17 text-para-text">
                                                                                    {{ __('Edit') }}</p>
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button class="d-flex align-items-center cg-8"
                                                                                onclick="deleteItem('{{ route('admin.client-orders.note.delete', encrypt($note->id)) }}' , 'no','{{ route('admin.client-orders.details', encrypt($order->id)) }}')">
                                                                                <div class="d-flex">
                                                                                    <svg width="14" height="15"
                                                                                        viewBox="0 0 14 15" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                                clip-rule="evenodd"
                                                                                                d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                            fill="#5D697A" />
                                                                                    </svg>
                                                                                </div>
                                                                                <p
                                                                                    class="fs-14 fw-500 lh-17 text-para-text">
                                                                                    {{ __('Delete') }}</p>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if ($orderSubmission)
                                    @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                                    {{-- <div class="pb-20">
                                        <div class="card border-1 border-gray-200">
                                            <div class="card-body position-relative">

                                            <div class="card-text fs-5 mb-4">
                                                <div class="row align-items-center justify-content-between mb-3">
                                                    <div class="col-auto">
                                                        <h6 class="mb-0 text-muted">{{ __('Approval Status') }}:</h6>
                                                    </div>
                                                    <?php

                                                        $approvalStatusColor = 'primary';
                                                    if ($orderSubmission->approval_status == 'accepted') {
                                                            $approvalStatusColor = 'success';
                                                    } elseif ($orderSubmission->approval_status == 'rejected') {
                                                            $approvalStatusColor = 'danger';
                                                        }

                                                    ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-{{ $approvalStatusColor }} text-uppercase px-3 py-2">
                                                            {{ $orderSubmission->approval_status }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="mb-2"> {{ __('Would you like to take action?')}} </p>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{route('admin.client-orders.submission.status.change',[ encrypt($order->id), 'accepted' ])}}" class="btn btn-success">
                                                            <i class="fa-solid fa-check me-1"></i> {{__('Accept')}}
                                                        </a>
                                                        <a href="{{route('admin.client-orders.submission.status.change',[ encrypt($order->id), 'rejected' ])}}" class="btn btn-danger">
                                                            <i class="fa-solid fa-xmark me-1"></i> {{__('Reject')}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="">
                                            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-25">
                                                <h5 class="fw-bold mb-4 text-dark">{{ __('Submission Label Status') }}
                                                </h5>

                                                <div class="d-flex justify-content-between align-items-center mb-10 mt-10">
                                                    <strong class="text-muted">{{ __('Current Label') }}:</strong>
                                                    <span
                                                        class="badge {{ $orderSubmission->approval_status == 'rejected' ? 'bg-danger' : ($orderSubmission->approval_status == 'published' ? 'bg-success' : 'bg-info') }} text-white fw-normal">
                                                        {{ ucwords(str_replace('_', ' ', $orderSubmission->approval_status)) }}
                                                    </span>
                                                </div>

                                                {{-- Workflow Complete Stage Button --}}
                                                @if (isset($currentStage) && $currentStage['button_text'])
                                                    @if ($canCompleteStage)
                                                        <form method="POST"
                                                            action="{{ route('admin.client-orders.task-board.complete-stage', encrypt($orderSubmission->id)) }}"
                                                            onsubmit="return confirm('{{ __('Are you sure you want to complete this stage?') }}')">
                                                            @csrf
                                                            <button class="btn btn-success px-4" type="submit">
                                                                {{ __($currentStage['button_text']) }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        {{-- Optional: Show why they can't complete it or just show pending status --}}
                                                        <div class="text-muted fs-12 mt-1">
                                                            {{ __('Current Stage') }}: {{ __($currentStage['label']) }}
                                                        </div>
                                                    @endif
                                                @endif

                                            </div>
                                    </div>
                                    @endif
                                    <!-- Note -->
                                    @if (!in_array(auth()->user()->role, $submissionRoles))
                                    <div class="">
                                            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-25">
                                                <div
                                                        class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-14">
                                                    <h4 class="fs-14 fw-500 lh-17 text-title-black">
                                                        {{ __('All Reviewer Notes') }}</h4>
                                                    @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                                                        <a href="{{ route('admin.submission-reviewer-notes.add-new', encrypt($order->id)) }}"
                                                            class="border-0 bd-ra-8 bg-main-color py-5 px-15 fs-15 fw-600 lh-25 text-white d-inline-block text-decoration-none">
                                                            + {{ __('Reviewer Note') }}
                                                    </a>
                                                    @endif
                                                </div>
                                                @if ($order->submission_reviewer_notes != null && count($order->submission_reviewer_notes) > 0)
                                                    <!--  -->
                                                    <ul class="note-list">
                                                        @foreach ($order->submission_reviewer_notes as $note)
                                                            @if (
                                                                $note->client_id == auth()->id() ||
                                                                    (auth()->user()->role !== USER_ROLE_CLIENT && auth()->user()->role !== USER_ROLE_REVIEWER))
                                                                <li
                                                                    class="d-flex justify-content-between g-10 bg-note-self">
                                                                    <!--  -->
                                                                    <div class="flex-grow-1">
                                                                        <!--  -->
                                                                        <h4 class="title pb-15">
                                                                        <?php
                                                                            $detailsRoute = route('admin.submission-reviewer-notes.details', encrypt($note->id));
                                                                            if (auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER) {
                                                                                $detailsRoute = route('user.submission-reviewer-notes.details', encrypt($note->id));
                                                                            }
                                                                        ?>
                                                                            <a href="{{ $detailsRoute }}"
                                                                        style="color: inherit !important;
                                                                        text-decoration: none !important;
                                                                        background: none !important;">
                                                                                {{ $note->description }}
                                                                        </a>
                                                                        </h4>
                                                                        <!--  -->
                                                                        <div
                                                                                class="d-flex align-items-center g-7 flex-wrap">
                                                                            <div
                                                                                    class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                                <img src="{{ getFileUrl(getUserData($note->created_by, 'image')) }}"
                                                                                    alt="" />
                                                                            </div>
                                                                            <h4
                                                                                class="fs-12 fw-500 lh-14 text-title-black">
                                                                                {{ getUserData($note->created_by, 'name') }}
                                                                                @if ($note->created_by == auth()->id())
                                                                                    ({{ __('You') }})
                                                                                @endif
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                    <!--  -->
                                                                    @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                                                                    <div class="dropdown dropdown-one">
                                                                        <button
                                                                                class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                                type="button" data-bs-toggle="dropdown"
                                                                                aria-expanded="false"><i
                                                                                    class="fa-solid fa-ellipsis-vertical"></i>
                                                                        </button>
                                                                            <ul
                                                                                class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                            <li>
                                                                                    <a href="{{ route('admin.submission-reviewer-notes.edit', encrypt($note->id)) }}"
                                                                                        class="d-flex align-items-center cg-8"
                                                                                        {{-- data-bs-toggle="modal"
                                                                                        data-bs-target="#addNoteModal"
                                                                                        id="noteEditModal"
                                                                                        data-order_id="{{encrypt($order->id)}}"
                                                                                        data-details="{{$note->details}}"
                                                                                        data-id="{{encrypt($note->id)}}" --}}>
                                                                                    <div class="d-flex">
                                                                                            <svg width="12"
                                                                                                height="13"
                                                                                            viewBox="0 0 12 13"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                    d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                                    fill="#5D697A" />
                                                                                        </svg>
                                                                                    </div>
                                                                                        <p
                                                                                            class="fs-14 fw-500 lh-17 text-para-text">
                                                                                            {{ __('Edit') }}</p>
                                                                                    </a>
                                                                            </li>
                                                                            <li>
                                                                                <button
                                                                                        class="d-flex align-items-center cg-8"
                                                                                        onclick="deleteItem('{{ route('admin.submission-reviewer-notes.delete', encrypt($note->id)) }}' , 'no','{{ route('admin.client-orders.task-board.index', [$order->id]) }}')">
                                                                                    <div class="d-flex">
                                                                                            <svg width="14"
                                                                                                height="15"
                                                                                            viewBox="0 0 14 15"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                                <path fill-rule="evenodd"
                                                                                                    clip-rule="evenodd"
                                                                                                    d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                                    fill="#5D697A" />
                                                                                        </svg>
                                                                                    </div>
                                                                                        <p
                                                                                            class="fs-14 fw-500 lh-17 text-para-text">
                                                                                            {{ __('Delete') }}</p>
                                                                                </button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                            {{-- @else
                                                                <li class="d-flex justify-content-between g-10">
                                                                    <!--  -->
                                                                    <div class="flex-grow-1">
                                                                        <!--  -->
                                                                        <h4 class="title pb-15">{{$note->details}}</h4>
                                                                        <!--  -->
                                                                        <div
                                                                                class="d-flex align-items-center g-7 flex-wrap">
                                                                            <div
                                                                                    class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                                                <img
                                                                                        src="{{getFileUrl(getUserData($note->user_id, 'image'))}}"
                                                                                        alt=""/></div>
                                                                            <h4 class="fs-12 fw-500 lh-14 text-title-black">{{getUserData($note->user_id, 'name')}}
                                                                                ({{__("Team Member")}})</h4>
                                                                        </div>
                                                                    </div>
                                                                    <!--  -->
                                                                    <div class="dropdown dropdown-one">
                                                                        <button
                                                                                class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                                                type="button" data-bs-toggle="dropdown"
                                                                                aria-expanded="false"><i
                                                                                    class="fa-solid fa-ellipsis-vertical"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                                            <li>
                                                                                <button
                                                                                        class="d-flex align-items-center cg-8"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#addNoteModal"
                                                                                        id="noteEditModal"
                                                                                        data-order_id="{{encrypt($order->id)}}"
                                                                                        data-details="{{$note->details}}"
                                                                                        data-id="{{encrypt($note->id)}}">
                                                                                    <div class="d-flex">
                                                                                        <svg width="12" height="13"
                                                                                            viewBox="0 0 12 13"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                    d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                                    fill="#5D697A"/>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Edit")}}</p>
                                                                                </button
                                                                                >
                                                                            </li>
                                                                            <li>
                                                                                <button
                                                                                        class="d-flex align-items-center cg-8"
                                                                                        onclick="deleteItem('{{route('admin.client-orders.note.delete', encrypt($note->id))}}' , 'no','{{route('admin.client-orders.details', encrypt($order->id))}}')">
                                                                                    <div class="d-flex">
                                                                                        <svg width="14" height="15"
                                                                                            viewBox="0 0 14 15"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                    fill-rule="evenodd"
                                                                                                    clip-rule="evenodd"
                                                                                                    d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                                                    fill="#5D697A"
                                                                                            />
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Delete")}}</p>
                                                                                </button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            @endif --}}
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="fs-14 fw-500 lh-17 text-para-text">
                                                        {{ __('No reviewer notes have been added yet') }}</p>
                                                @endif
                                            </div>
                                    </div>
                                    @endif
                                    @if (auth()->user()->role != USER_ROLE_REVIEWER)
                                    <div class="pb-20">
                                        <div class="card border-1 border-gray-200">
                                            <div class="card-body position-relative">

                                                <h5 class="card-title display-7 fw-bold mb-5">
                                                        {{ $orderSubmission->article_title }}
                                                </h5>

                                                <p class="card-text fs-5 mb-5">
                                                        {{ $orderSubmission->Journal->title }}
                                                </p>

                                                <p class="card-text fs-5 mb-5">
                                                    <a href="{{ getFileUrl($orderSubmission->full_article_file) }}">
                                                            {{ __('Full Article File') }}
                                                    </a>
                                                </p>

                                                @php
                                                    $fullViewRoute = route('user.orders.fullview', $order->id);
                                                        if (
                                                            auth()->user()->role != USER_ROLE_CLIENT &&
                                                            auth()->user()->role != USER_ROLE_REVIEWER
                                                        ) {
                                                            $fullViewRoute = route(
                                                                'admin.client-orders.fullview',
                                                                $order->id,
                                                            );
                                                    }
                                                @endphp

                                                    @if (auth()->user()->role === USER_ROLE_CLIENT || auth()->user()->role === USER_ROLE_REVIEWER)
                                                        @if (
                                                            !in_array($orderSubmission->approval_status, [
                                                                SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
                                                                SUBMISSION_ORDER_STATUS_PUBLISHED,
                                                            ]))
                                                            <a href="{{ route('user.submission.select-a-journal', ['by' => 'by-subject', 'action' => 'update', 'id' => $order->order_id]) }}"
                                                                class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white float-start">
                                                                {{ __('Edit') }}
                                                        <i class="fa-solid fa-edit ms-2"></i>
                                                    </a>
                                                    @endif
                                                @endif
                                                    <a href="{{ $fullViewRoute }}"
                                                        class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white float-end">
                                                        {{ __('More') }}
                                                    <i class="fa-solid fa-arrow-right ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif


                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                <div class="col-xl-8">

                    <!-- Page Top Bar Start -->
                    <div
                            class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-25">
                            @if (auth()->user()->role == USER_ROLE_ADMIN)
                            <button type="button"
                                    class="d-inline-flex border-0 bd-ra-8 bg-main-color py-8 px-26 fs-15 fw-600 lh-25 text-white"
                                    data-bs-toggle="modal" data-bs-target="#addTaskModal" title="Add Task">+ Add Task
                            </button>
                        @endif
                    </div>
                    <!-- Page Top Bar End -->

                    <!-- Project Board View Wrap Start -->
                    <div class="project-board-view-wrap">
                        <div class="row rg-25 project-board-view-wrap-row">
                            @php
                                $columns = [
                                        [
                                            'status' => ORDER_TASK_STATUS_PENDING,
                                            'title' => __('Pending'),
                                            'id' => 'pendingTask',
                                            'statusClass' => 'pending',
                                        ],
                                        [
                                            'status' => ORDER_TASK_STATUS_PROGRESS,
                                            'title' => __('Progress'),
                                            'id' => 'progressTask',
                                            'statusClass' => 'progress',
                                        ],
                                        [
                                            'status' => ORDER_TASK_STATUS_REVIEW,
                                            'title' => __('Review'),
                                            'id' => 'reviewTask',
                                            'statusClass' => 'review',
                                        ],
                                        [
                                            'status' => ORDER_TASK_STATUS_DONE,
                                            'title' => __('Done'),
                                            'id' => 'doneTask',
                                            'statusClass' => 'done',
                                        ],
                                ];
                            @endphp

                                @foreach ($columns as $column)
                                <div class="col-md-3">
                                    <div class="task-column">
                                            <div
                                                class="task-column-title task-column-title-{{ $column['statusClass'] }}">
                                            <h5 class="title">{{ $column['title'] }} (<span
                                                        class="itemCount">{{ $orderTasks->where('status', $column['status'])->count() }}</span>)
                                            </h5>
                                        </div>
                                        <div class="
                                        {{-- task-column-items  --}}
                                        d-flex flex-column rg-8"
                                                id="{{ $column['id'] }}"
                                                @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER) data-status="{{ $column['status'] }}"
                                             ondrop="dropIt(event)" ondragover="allowDrop(event)" @endif>
                                                @foreach ($orderTasks->where('status', $column['status']) as $task)
                                                <div class="task-column-item"
                                                     id="{{ $column['id'] }}-{{ $loop->iteration }}"
                                                     draggable="true" ondragstart="dragStart(event)">
                                                    <div class="taskModalContent"
                                                            @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER) onclick="getEditModal('{{ route('admin.client-orders.task-board.view', [$order->id, $task->id]) }}', '#viewTaskModal', 'taskViewResponse')"
                                                         @else
                                                             onclick="getEditModal('{{ route('user.orders.task-board.view', [$order->id, $task->id]) }}', '#viewTaskModal', 'taskViewResponse')" @endif
                                                         title="Add Task">
                                                            <input type="hidden" name="task_id"
                                                                value="{{ $task->id }}">
                                                        @php
                                                            $colorClasses = [
                                                                ['bg-hover-color', 'text-main-color'],
                                                                ['bg-yellow-10', 'text-yellow'],
                                                                    ['bg-green-10', 'text-green'],
                                                            ];
                                                            $colorCount = count($colorClasses);
                                                        @endphp

                                                            @if (count($task->labels))
                                                            <div class="d-flex flex-wrap rg-10 cg-5 pb-16">
                                                                    @foreach ($task->labels as $index => $label)
                                                                    @php
                                                                            $randomClass =
                                                                                $colorClasses[$index % $colorCount];
                                                                    @endphp
                                                                    <div
                                                                            class="py-6 px-10 bd-ra-2 {{ $randomClass[0] }}">
                                                                            <h4
                                                                                class="fs-13 fw-400 lh-13 {{ $randomClass[1] }}">
                                                                                {{ $label->name }}</h4>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                            <h4 class="fs-14 fw-500 lh-19 text-title-black pb-17">
                                                                {{ $task->task_name }}</h4>
                                                            @if ($task->end_date || $task->priority)
                                                            <div class="align-items-center d-flex flex-wrap g-7 pb-17">
                                                                    @if ($task->end_date)
                                                                    <div
                                                                            class="p-5 bd-one bd-ra-2 bd-c-stroke d-flex align-items-center g-4">
                                                                        <div class="d-flex">
                                                                                <img src="{{ asset('assets/images/icon/clock.svg') }}"
                                                                                    alt="">
                                                                        </div>
                                                                            <p class="fs-13 fw-400 lh-13 text-para-text">
                                                                                {{ formatDate($task->end_date) }}</p>
                                                                    </div>
                                                                @endif
                                                                    @if ($task->priority)
                                                                        <h4
                                                                            class="fs-13 fw-400 lh-13 {{ getPriorityClass($task->priority) }}">
                                                                            {{ getPriority($task->priority) }}</h4>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                            class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                                        <div class="d-flex gap-1">
                                                                @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                                                                <button
                                                                        onclick="getEditModal('{{ route('admin.client-orders.task-board.edit', [$order->id, $task->id]) }}', '#editTaskModal', 'editResponse')"
                                                                        class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-24 justify-content-center rounded w-24">
                                                                        <svg width="12" height="13"
                                                                            viewBox="0 0 12 13" fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                                d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                                fill="#63647B"></path>
                                                                    </svg>
                                                                </button>
                                                                <button
                                                                        onclick="deleteItem('{{ route('admin.client-orders.task-board.delete', [$order->id, $task->id]) }}', null, '{{ route('admin.client-orders.task-board.index', $order->id) }}')"
                                                                        class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-24 justify-content-center rounded w-24">
                                                                        <svg width="14" height="15"
                                                                            viewBox="0 0 14 15" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                              d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                                              fill="#5D697A"></path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <div class="dropdown-two">
                                                                <div
                                                                    class="dropdown-toggle justify-content-end min-w-auto">
                                                                    @if (count($task->assignees))
                                                                    <div class="taskProgressImage">
                                                                            @foreach ($task->assignees as $agent)
                                                                                <img src="{{ getFileUrl($agent->user->image) }}"
                                                                                    alt="{{ $agent->user->name }}" />
                                                                        @endforeach
                                                                            @if (auth()->user()->role != USER_ROLE_CLIENT && auth()->user()->role != USER_ROLE_REVIEWER)
                                                                            <button
                                                                                    onclick="getEditModal('{{ route('admin.client-orders.task-board.edit', [$order->id, $task->id]) }}', '#editTaskModal', 'editResponse')"
                                                                                    class="iconPlus"><i
                                                                                        class="fa-solid fa-plus"></i>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Project Board View Wrap End -->

                    {{-- Editor Decision Interface - Version History and Decisions --}}
                        @if (auth()->user()->role != USER_ROLE_CLIENT &&
                                auth()->user()->role != USER_ROLE_REVIEWER &&
                                isset($reviewVersionHistory) &&
                                $reviewVersionHistory)
                    <div class="pb-20">
                        <div class="card border-1 border-gray-200">
                            <div class="card-body">
                                <h5 class="card-title display-7 fw-bold mb-20">
                                            <i
                                                class="fa-solid fa-clipboard-check me-2"></i>{{ __('Editor Decision Interface') }}
                                </h5>

                                {{-- Complete Version History for All Reviewers --}}
                                @php
                                    $hasVersionHistory = false;
                                    if ($reviewVersionHistory !== null) {
                                        if (is_array($reviewVersionHistory)) {
                                            $hasVersionHistory = count($reviewVersionHistory) > 0;
                                                } elseif (
                                                    is_object($reviewVersionHistory) &&
                                                    method_exists($reviewVersionHistory, 'count')
                                                ) {
                                            $hasVersionHistory = $reviewVersionHistory->count() > 0;
                                        }
                                    }
                                @endphp
                                        @if ($hasVersionHistory)
                                <div class="mb-20">
                                                <h6 class="fw-600 mb-15">{{ __('Complete Review Version History') }}</h6>
                                                @foreach ($reviewVersionHistory as $reviewerId => $versions)
                                        @php
                                            // Ensure $versions is a collection
                                                        if (
                                                            !is_object($versions) ||
                                                            !method_exists($versions, 'first')
                                                        ) {
                                                $versions = collect($versions);
                                            }
                                            $reviewer = $versions->first()->reviewer ?? null;
                                                        $reviewerName = $reviewer
                                                            ? $reviewer->name
                                                            : __('Reviewer ID') . ' ' . $reviewerId;
                                        @endphp
                                        <div class="mb-15 p-15 bg-light rounded">
                                            <h6 class="fw-600 mb-10">
                                                            <i
                                                                class="fa-solid fa-user-check me-2"></i>{{ $reviewerName }}
                                            </h6>
                                            <div class="timeline">
                                                            @foreach ($versions as $version)
                                                    @php
                                                        $recommendationLabels = [
                                                            'accept' => __('Accept'),
                                                            'minor_revisions' => __('Minor Revisions'),
                                                            'major_revisions' => __('Major Revisions'),
                                                            'reject' => __('Reject'),
                                                        ];
                                                                    $recommendation =
                                                                        $version->overall_recommendation ??
                                                                        'minor_revisions';
                                                                    $recommendationLabel =
                                                                        $recommendationLabels[$recommendation] ??
                                                                        \Illuminate\Support\Str::title(
                                                                            str_replace('_', ' ', $recommendation),
                                                                        );
                                                                    $recommendationClass =
                                                                        $recommendation === 'accept'
                                                                            ? 'success'
                                                                            : ($recommendation === 'reject'
                                                                                ? 'danger'
                                                                                : ($recommendation === 'major_revisions'
                                                                                    ? 'warning'
                                                                                    : 'info'));
                                                                    $submittedDate = $version->submitted_at
                                                                        ? \Carbon\Carbon::parse(
                                                                            $version->submitted_at,
                                                                        )->format('M d, Y')
                                                                        : __('Draft');
                                                    @endphp
                                                                <div
                                                                    class="d-flex align-items-start mb-10 p-10 {{ $version->id === ($review->id ?? null) ? 'bg-primary bg-opacity-10 border-start border-primary border-3' : 'bg-white' }}">
                                                        <div class="flex-grow-1">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-5">
                                                                            <strong class="text-dark">Version
                                                                                {{ $version->version ?? 1 }} - Round
                                                                                {{ $version->round ?? 1 }}</strong>
                                                                            <span
                                                                                class="badge bg-{{ $recommendationClass }}">{{ $recommendationLabel }}</span>
                </div>
                                                            <div class="text-muted small mb-5">
                                                                            <i
                                                                                class="fa-solid fa-calendar me-1"></i>{{ $submittedDate }}
                                                                            @if ($version->submitted_at)
                                                                                <span class="ms-3"><i
                                                                                        class="fa-solid fa-check-circle me-1 text-success"></i>{{ __('Submitted') }}</span>
                                                                @else
                                                                                <span class="ms-3"><i
                                                                                        class="fa-solid fa-clock me-1 text-warning"></i>{{ __('Draft') }}</span>
            @endif
            </div>

                                                            {{-- Expandable Review Comments Section --}}
                                                                        @if ($version->submitted_at)
                                                            <div class="mt-3">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        onclick="toggleReviewDetails({{ $version->id }})"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#reviewDetails-{{ $version->id }}">
                                                                                    <i class="fa-solid fa-chevron-down"
                                                                                        id="icon-details-{{ $version->id }}"></i>
                                                                                    View Full Review Details
                                                                </button>
                                                                                <div class="collapse mt-3"
                                                                                    id="reviewDetails-{{ $version->id }}">
                                                                    <div class="card card-body bg-light">
                                                                                        @if ($version->comment_for_authors)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Comments for Authors') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->comment_for_authors)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->comment_strengths)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Strengths') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->comment_strengths)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->comment_weaknesses)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Weaknesses') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->comment_weaknesses)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->comment_for_editor)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Confidential Comments for Editor') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->comment_for_editor)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->questions_for_authors)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Questions for Authors') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->questions_for_authors)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->minor_issues)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Minor Issues') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->minor_issues)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                                        @if ($version->major_issues)
                                                                        <div class="mb-3">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Major Issues') }}:</strong>
                                                                                                <p class="mt-2 mb-0"
                                                                                                    style="color: #555; line-height: 1.6;">
                                                                                                    {!! nl2br(e($version->major_issues)) !!}
                                                                                                </p>
                                                                        </div>
                                                                        @endif
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-6">
                                                                                                <strong
                                                                                                    class="text-dark">{{ __('Quality Ratings') }}:</strong>
                                                                                                <ul
                                                                                                    class="list-unstyled mt-2">
                                                                                                    @if ($version->rating_originality)
                                                                                                        <li>{{ __('Originality') }}:
                                                                                                            {{ $version->rating_originality }}/5
                                                                                                        </li>
                                                                                    @endif
                                                                                                    @if ($version->rating_methodology)
                                                                                                        <li>{{ __('Methodology') }}:
                                                                                                            {{ $version->rating_methodology }}/5
                                                                                                        </li>
                                                                                    @endif
                                                                                                    @if ($version->rating_results)
                                                                                                        <li>{{ __('Results') }}:
                                                                                                            {{ $version->rating_results }}/5
                                                                                                        </li>
                                                                                    @endif
                                                                                                    @if ($version->rating_clarity)
                                                                                                        <li>{{ __('Clarity') }}:
                                                                                                            {{ $version->rating_clarity }}/5
                                                                                                        </li>
                                                                                    @endif
                                                                                                    @if ($version->rating_significance)
                                                                                                        <li>{{ __('Significance') }}:
                                                                                                            {{ $version->rating_significance }}/5
                                                                                                        </li>
                                                                                    @endif
                                                                                                    @if ($version->quality_rating)
                                                                                                        <li><strong>{{ __('Overall Quality') }}:
                                                                                                                {{ number_format($version->quality_rating, 2) }}/5</strong>
                                                                                                        </li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @php
                                                                $roundNumber = $version->round ?? 1;
                                                                $roundRevisions = isset($revisionsByRound)
                                                                                ? (is_array($revisionsByRound)
                                                                                    ? collect($revisionsByRound)
                                                                                    : $revisionsByRound)
                                                                    : collect();
                                                                            $roundSet =
                                                                                $roundRevisions instanceof
                                                                                \Illuminate\Support\Collection
                                                                                    ? $roundRevisions->get(
                                                                                        $roundNumber,
                                                                                        collect(),
                                                                                    )
                                                                    : collect();
                                                                            if (
                                                                                !(
                                                                                    $roundSet instanceof
                                                                                    \Illuminate\Support\Collection
                                                                                )
                                                                            ) {
                                                                    $roundSet = collect($roundSet);
                                                                }
                                                                            $latestRoundRevision = $roundSet
                                                                                ->sortByDesc('created_at')
                                                                                ->first();
                                                            @endphp

                                                                        @if ($latestRoundRevision)
                                                                <div class="mt-3 p-3 bg-white border rounded">
                                                                                <div
                                                                                    class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <strong
                                                                                        class="text-dark">{{ __('Authors\' Revisions') }}</strong>
                                                                                    <span
                                                                                        class="badge bg-info">{{ __('Round') }} {{ $roundNumber }}</span>
                                                                    </div>
                                                                    <div class="d-flex flex-column gap-2">
                                                                                    @if (!empty($latestRoundRevision->manuscript_url))
                                                                                        <a href="{{ $latestRoundRevision->manuscript_url }}"
                                                                                            target="_blank"
                                                                                            class="text-decoration-none">
                                                                                            📄
                                                                                            {{ __('Revised Manuscript (PDF)') }}
                                                                            </a>
                                                                        @endif
                                                                                    @if (!empty($latestRoundRevision->response_url))
                                                                                        <a href="{{ $latestRoundRevision->response_url }}"
                                                                                            target="_blank"
                                                                                            class="text-decoration-none">
                                                                                            📨
                                                                                            {{ __('Response to Reviewers') }}
                                                                            </a>
                                                                        @endif
                                                                        {{-- Author Comments - Always Visible --}}
                                                                        @php
                                                                                        $metadata =
                                                                                            $latestRoundRevision->metadata ??
                                                                                            [];
                                                                                        $hasAuthorComments =
                                                                                            !empty(
                                                                                                $metadata[
                                                                                                    'general_response'
                                                                                                ]
                                                                                            ) ||
                                                                                            !empty(
                                                                                                $metadata[
                                                                                                    'reviewer_responses'
                                                                                                ]
                                                                                            ) ||
                                                                                            !empty(
                                                                                                $latestRoundRevision->response_summary
                                                                                            );
                                                                        @endphp
                                                                                    @if ($hasAuthorComments)
                                                                                        <div
                                                                                            class="mt-3 p-3 bg-light border rounded">
                                                                                            <strong
                                                                                                style="color: #2c3e50; display: block; margin-bottom: 0.75rem; font-size: 0.95rem;">{{ __('Author Comments - Round') }} {{ $roundNumber }}:</strong>
                                                                                            @if (!empty($metadata['general_response']))
                                                                                                <div
                                                                                                    style="margin-bottom: 1rem;">
                                                                                                    <strong
                                                                                                        style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('General Response to All Reviewers') }}:</strong>
                                                                                                    <p
                                                                                                        style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">
                                                                                                        {!! nl2br(e($metadata['general_response'])) !!}
                                                                                                    </p>
                                                                                    </div>
                                                                                @endif
                                                                                            @if (!empty($metadata['reviewer_responses']) && is_array($metadata['reviewer_responses']))
                                                                                                @foreach ($metadata['reviewer_responses'] as $responseReviewerId => $responseText)
                                                                                        @php
                                                                                                        $responseReviewer = \App\Models\User::find(
                                                                                                            $responseReviewerId,
                                                                                                        );
                                                                                                        $reviewerName = $responseReviewer
                                                                                                            ? $responseReviewer->name
                                                                                                            : __(
                                                                                                                    'Reviewer ID',
                                                                                                                ) .
                                                                                                                ' ' .
                                                                                                                $responseReviewerId;
                                                                                        @endphp
                                                                                                    <div
                                                                                                        style="margin-bottom: 1rem;">
                                                                                                        <strong
                                                                                                            style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Response to :reviewer', ['reviewer' => $reviewerName]) }}:</strong>
                                                                                                        <p
                                                                                                            style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">
                                                                                                            {!! nl2br(e($responseText)) !!}
                                                                                                        </p>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                            @if (!empty($latestRoundRevision->response_summary))
                                                                                                <div
                                                                                                    style="margin-bottom: 1rem;">
                                                                                                    <strong
                                                                                                        style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Summary of Major Changes') }}:</strong>
                                                                                                    <p
                                                                                                        style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">
                                                                                                        {!! nl2br(e($latestRoundRevision->response_summary)) !!}
                                                                                                    </p>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                                    @if (!empty($latestRoundRevision->attachment_links) && $latestRoundRevision->attachment_links->count() > 0)
                                                                                        <div
                                                                                            class="d-flex flex-wrap gap-2">
                                                                                            @foreach ($latestRoundRevision->attachment_links as $attachment)
                                                                                                @if (!empty($attachment['url']))
                                                                                                    <a href="{{ $attachment['url'] }}"
                                                                                                        target="_blank"
                                                                                                        class="small text-decoration-none">
                                                                                                        📎
                                                                                                        {{ $attachment['label'] }}
                                                                                        </a>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="small text-muted mt-2">
                                                                        {{ __('Last updated: :date', ['date' => optional($latestRoundRevision->created_at)->format('M d, Y')]) }}
                                                                    </div>
                                                                                @if ($roundSet->count() > 1)
                                                                        <div class="small text-muted">
                                                                            {{ __('Includes :count uploaded revisions for this round.', ['count' => $roundSet->count()]) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                {{-- Authors' Revisions History --}}
                                        @if (isset($orderSubmission) && $orderSubmission)
                                @php
                                                $revisions = \App\Models\ClientOrderSubmissionRevision::where(
                                                    'client_order_submission_id',
                                                    $orderSubmission->id,
                                                )
                                        ->orderBy('version')
                                        ->get();
                                @endphp
                                            @if ($revisions && $revisions->count() > 0)
                                <div class="mb-20">
                                                    <h6 class="fw-600 mb-15">{{ __('Authors\' Revisions') }}</h6>
                                                    @foreach ($revisions as $revision)
                                        <div class="mb-10 p-15 bg-light rounded">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-10">
                                                <strong>Round {{ $revision->version }} Revision</strong>
                                                                <span
                                                                    class="badge bg-info">{{ \Carbon\Carbon::parse($revision->created_at)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="d-flex gap-10 flex-wrap">
                                                                @if ($revision->manuscript_file_id)
                                                                    <a href="{{ getFileUrl($revision->manuscript_file_id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i
                                                                            class="fa-solid fa-file-pdf me-1"></i>{{ __('Revised Manuscript') }}
                                                    </a>
                                                @endif
                                                                @if ($revision->response_file_id)
                                                                    <a href="{{ getFileUrl($revision->response_file_id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-info">
                                                                        <i
                                                                            class="fa-solid fa-file-text me-1"></i>{{ __('Author Response') }}
                                                    </a>
                                                @endif
                                                @php
                                                    $metadata = $revision->metadata ?? [];
                                                                    $hasAuthorComments =
                                                                        !empty($metadata['general_response']) ||
                                                                        !empty($metadata['reviewer_responses']) ||
                                                                        !empty($revision->response_summary);
                                                @endphp
                                                                @if ($hasAuthorComments)
                                                                    <button type="button"
                                                                        onclick="toggleAuthorCommentsAdmin({{ $revision->id }})"
                                                                        class="btn btn-sm btn-outline-success">
                                                                        <i
                                                                            class="fa-solid fa-comments me-1"></i>{{ __('View Author Comments') }}
                                                    </button>
                                                                    <div id="authorCommentsAdmin-{{ $revision->id }}"
                                                                        style="display: none; margin-top: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 6px; font-size: 0.9rem; width: 100%;">
                                                                        @if (!empty($metadata['general_response']))
                                                            <div style="margin-bottom: 1rem;">
                                                                                <strong
                                                                                    style="color: #2c3e50; display: block; margin-bottom: 0.5rem;">{{ __('General Response to All Reviewers') }}:</strong>
                                                                                <p
                                                                                    style="color: #555; line-height: 1.6; margin: 0;">
                                                                                    {!! nl2br(e($metadata['general_response'])) !!}</p>
                                                            </div>
                                                        @endif
                                                                        @if (!empty($metadata['reviewer_responses']) && is_array($metadata['reviewer_responses']))
                                                                            @foreach ($metadata['reviewer_responses'] as $responseReviewerId => $responseText)
                                                                @php
                                                                                    $responseReviewer = \App\Models\User::find(
                                                                                        $responseReviewerId,
                                                                                    );
                                                                                    $reviewerName = $responseReviewer
                                                                                        ? $responseReviewer->name
                                                                                        : __('Reviewer ID') .
                                                                                            ' ' .
                                                                                            $responseReviewerId;
                                                                @endphp
                                                                <div style="margin-bottom: 1rem;">
                                                                                    <strong
                                                                                        style="color: #2c3e50; display: block; margin-bottom: 0.5rem;">{{ __('Response to :reviewer', ['reviewer' => $reviewerName]) }}:</strong>
                                                                                    <p
                                                                                        style="color: #555; line-height: 1.6; margin: 0;">
                                                                                        {!! nl2br(e($responseText)) !!}</p>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                                        @if (!empty($revision->response_summary))
                                                            <div style="margin-bottom: 1rem;">
                                                                                <strong
                                                                                    style="color: #2c3e50; display: block; margin-bottom: 0.5rem;">{{ __('Summary of Major Changes') }}:</strong>
                                                                                <p
                                                                                    style="color: #555; line-height: 1.6; margin: 0;">
                                                                                    {!! nl2br(e($revision->response_summary)) !!}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                                @endif

                                        {{-- Generate Certificate Button (After Acceptance) --}}
                                        @if (isset($orderSubmission) &&
                                                $orderSubmission &&
                                                $orderSubmission->approval_status == SUBMISSION_ORDER_STATUS_ACCEPTED)
                                            <div class="border-top mt-20 pt-20">
                                                <h6 class="fw-600 mb-15">{{ __('Post-Acceptance Actions') }}</h6>
                                                <a href="{{ route('certificates.create', $orderSubmission->id) }}"
                                                   class="btn btn-primary btn-md">
                                                    <i class="fa-solid fa-certificate me-2"></i>
                                                    {{ __('Generate/View Final Acceptance Certificate') }}
                                                </a>
                                                <p class="text-muted mt-2 mb-0 small">
                                                    {{ __('Generate or view the acceptance certificate PDF using the new bilingual system.') }}
                                                </p>
                                            </div>
                                        @endif

                                {{-- Editor Decision Buttons --}}
                                        @if (isset($orderSubmission) &&
                                                $orderSubmission &&
                                                in_array($orderSubmission->approval_status, [
                                                    SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW,
                                                    SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
                                                    SUBMISSION_ORDER_STATUS_ACCEPTED, // Final Decision Stage
                                                ]))
                                <div class="border-top mt-20 pt-20">
                                                <h6 class="fw-600 mb-15">{{ __('Make Editorial Decision') }}</h6>
                                    <div class="d-flex flex-wrap gap-10">
                                                    <button type="button" class="btn btn-success btn-md ml-4"
                                                        data-bs-toggle="modal" data-bs-target="#acceptModal"
                                                data-submission-id="{{ encrypt($orderSubmission->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            style="vertical-align: middle; margin-right: 8px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                                        {{ __('Accept') }}
                                        </button>
                                                    <button type="button" class="btn btn-info btn-md ml-4"
                                                        data-bs-toggle="modal" data-bs-target="#minorRevisionsModal"
                                                data-submission-id="{{ encrypt($orderSubmission->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            style="vertical-align: middle; margin-right: 8px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                                        {{ __('Minor Revisions') }}
                                        </button>
                                                    <button type="button" class="btn btn-warning btn-md ml-4"
                                                        data-bs-toggle="modal" data-bs-target="#majorRevisionsModal"
                                                data-submission-id="{{ encrypt($orderSubmission->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            style="vertical-align: middle; margin-right: 8px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                                        {{ __('Major Revisions') }}
                                        </button>
                                                    <button type="button" class="btn btn-outline-danger btn-md ml-4"
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-submission-id="{{ encrypt($orderSubmission->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            style="vertical-align: middle; margin-right: 8px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                                        {{ __('Reject') }}
                                        </button>
                                                    <button type="button" class="btn btn-secondary btn-md ml-4"
                                                        data-bs-toggle="modal" data-bs-target="#requestRevisionModal"
                                                data-submission-id="{{ encrypt($orderSubmission->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            style="vertical-align: middle; margin-right: 8px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                                        {{ __('Request Another Revision') }}
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
            </div>
        </div>
    </div>
    <input type="hidden" id="client-order-list-route" value="{{ route('admin.client-orders.list') }}">
    <input type="hidden" id="order_status_change_route"
           value="{{ route('admin.client-orders.task-board.update_status', $order->id) }}">
    <input type="hidden" id="reviewer-matching-route"
        value="{{ route('admin.reviewer-matching.recommendations') }}">

    {{-- Reviewer Suggestions Overlay Modal (from reviwer-dropdown-selection.html) --}}
    @if (auth()->user()->role != USER_ROLE_CLIENT &&
            auth()->user()->role != USER_ROLE_REVIEWER &&
            !in_array(auth()->user()->role, $submissionRoles))
        <div id="assignModal" class="overlay" aria-hidden="true">
            <div class="reviewer-assign-modal" role="dialog" aria-modal="true">
                <div class="modal-header">
                    <div>
                        <div class="modal-title">{{ __('Suggested Reviewers') }}</div>
                        <div class="modal-subtitle">
                            {{ __('Order ID') }}: {{ $order->order_id }}
                        </div>
                    </div>
                    <button class="modal-close" id="closeAssignModal" aria-label="{{ __('Close') }}">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Search & filters -->
                    <div class="reviewer-search-row">
                        <div class="reviewer-search-input-wrap">
                            <span class="reviewer-search-icon">&#128269;</span>
                            <input type="text" id="reviewerSearchInput"
                                placeholder="{{ __('Search by name, email, or expertise…') }}" />
                        </div>
                        <div class="reviewer-pill-filters">
                            <button type="button" class="reviewer-pill active" data-filter="best">
                                {{ __('Best match') }}
                            </button>
                            <button type="button" class="reviewer-pill" data-filter="no_conflict">
                                {{ __('No conflicts') }}
                            </button>
                        </div>
                    </div>

                    <!-- Reviewer list (filled dynamically) -->
                    <div class="reviewer-list" id="reviewerRecommendationsList">
                        <p class="text-muted mb-0">{{ __('Loading suggestions...') }}</p>
                    </div>
                </div>

                <div class="reviewer-modal-footer">
                    <span>{{ __('Showing top suggestions based on expertise & history.') }}</span>
                    <div class="reviewer-modal-footer-right">
                        <button type="button" class="btn-secondary border-0 px-3 py-2">
                            {{ __('View all reviewers') }}
                        </button>
                        <button type="button" class="btn-primary border-0 px-3 py-2">
                            {{ __('Invite New Reviewer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form class="ajax" data-handler="commonResponseWithPageLoad"
                    action="{{ route('admin.client-orders.task-board.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Task') }}</h5>
                        <button type="button"
                            class="w-32 h-32 d-flex justify-content-center align-items-center border-0 bg-transparent fs-20 text-para-text "
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <div class="modal-body task-modalBody-height overflow-y-auto">
                        @include('admin.orders.task-board.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="taskViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 bd-ra-10">

            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add note') }}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" class="ajax reset" action="{{ route('admin.client-orders.note.store') }}"
                          data-handler="commonResponseWithPageLoad">
                        @csrf
                        <div class="pb-25">
                            <label for="noteDetails" class="zForm-label">{{ __('Note Details') }}</label>
                            <textarea id="noteDetails" name="details" class="form-control zForm-control min-h-175"
                                placeholder="{{ __('Write note details here') }}...."></textarea>
                            <input type="hidden" name="order_id" id="orderIdField">
                            <input type="hidden" name="id" id="noteIdField">
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit"
                                class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-white bg-main-color d-flex justify-content-center">{{ __('Save Note') }}</button>
                            <a href="{{ URL::previous() }}" type="button"
                                class="bd-one bd-c-para-text bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-para-text bg-white d-flex justify-content-center">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="progress_change_route"
           value="{{ route('admin.client-orders.task-board.change_progress', ['order_id' => $order->id, 'id' => '__ID__']) }}">
    <input type="hidden" id="assignMemberRoute" value="{{ route('admin.client-orders.assign.member') }}">

    {{-- Editor Decision Modals --}}

    {{-- Accept Modal --}}
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Accept Submission') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                        data-bs-dismiss="modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="acceptForm" class="ajax" data-handler="handleEditorDecisionResponse"
                    action="{{ route('admin.client-orders.task-board.editor-decision', ['submission_id' => '__SUBMISSION_ID__']) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="decision" value="accept">

                    <div class="row rg-20">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Template') }}</label>
                            <select class="sf-select-without-search" name="email_template_id" id="acceptTemplateSelect">
                                <option value="">{{ __('Select template (optional)') }}</option>
                                @if (isset($orderSubmission) && $orderSubmission)
                                    @php
                                        $templates = \App\Models\BulkEmailTemplate::where('status', 1)->get();
                                    @endphp
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Subject') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="email_subject"
                                value="{{ __('Submission Accepted') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Message to Author') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="email_message" rows="6" required>{{ __('Congratulations! Your submission has been accepted for publication.') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Publication Schedule (Optional)') }}</label>
                            <input type="text" class="form-control zForm-control" name="publication_schedule"
                                placeholder="{{ __('e.g., Issue 2025, Volume 10') }}">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Assign to Journal & Issue (Optional)') }}</label>
                            <select name="journal_id" id="acceptJournalSelect" class="form-control zForm-control mb-2"
                                onchange="loadIssuesForJournal(this.value, 'acceptIssueSelect')">
                                <option value="">{{ __('Select Journal') }}</option>
                                @foreach (\App\Models\Journal::where('status', 'active')->get() as $journal)
                                    <option value="{{ $journal->id }}">{{ $journal->title }}</option>
                                @endforeach
                            </select>
                            <select name="issue_id" id="acceptIssueSelect" class="form-control zForm-control" disabled>
                                <option value="">{{ __('Select Issue') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-10 mt-20 pt-20 bd-t-one bd-c-stroke">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Accept & Send Email') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Minor Revisions Modal --}}
    <div class="modal fade" id="minorRevisionsModal" tabindex="-1" aria-labelledby="minorRevisionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Request Minor Revisions') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                        data-bs-dismiss="modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="minorRevisionsForm" class="ajax" data-handler="handleEditorDecisionResponse"
                    action="{{ route('admin.client-orders.task-board.editor-decision', ['submission_id' => '__SUBMISSION_ID__']) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="decision" value="minor_revisions">

                    <div class="row rg-20">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Subject') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="email_subject"
                                value="{{ __('Minor Revisions Required') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Instructions') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="revision_instructions" rows="8" required
                                placeholder="{{ __('Provide detailed instructions for the minor revisions needed...') }}"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Include Reviewer Comments') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_reviewer_comments"
                                    id="minorIncludeComments" value="1" checked>
                                <label class="form-check-label"
                                    for="minorIncludeComments">{{ __('Include compiled reviewer comments with instructions') }}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Deadline') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control zForm-control" name="revision_deadline" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <small class="text-muted">{{ __('Recommended: 2-3 weeks for minor revisions') }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-10 mt-20 pt-20 bd-t-one bd-c-stroke">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-info">{{ __('Request Minor Revisions') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Major Revisions Modal --}}
    <div class="modal fade" id="majorRevisionsModal" tabindex="-1" aria-labelledby="majorRevisionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Request Major Revisions') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                        data-bs-dismiss="modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="majorRevisionsForm" class="ajax" data-handler="handleEditorDecisionResponse"
                    action="{{ route('admin.client-orders.task-board.editor-decision', ['submission_id' => '__SUBMISSION_ID__']) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="decision" value="major_revisions">

                    <div class="row rg-20">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Subject') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="email_subject"
                                value="{{ __('Major Revisions Required') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Instructions') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="revision_instructions" rows="8" required
                                placeholder="{{ __('Provide detailed instructions for the major revisions needed...') }}"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Include Reviewer Comments') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_reviewer_comments"
                                    id="majorIncludeComments" value="1" checked>
                                <label class="form-check-label"
                                    for="majorIncludeComments">{{ __('Include compiled reviewer comments with instructions') }}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Deadline') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control zForm-control" name="revision_deadline" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <small class="text-muted">{{ __('Recommended: 4-8 weeks for major revisions') }}</small>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i
                                    class="fa-solid fa-info-circle me-2"></i>{{ __('Note: After revision, the submission will require re-review by the reviewers.') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-10 mt-20 pt-20 bd-t-one bd-c-stroke">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ __('Request Major Revisions') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Reject Submission') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                        data-bs-dismiss="modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="rejectForm" class="ajax" data-handler="handleEditorDecisionResponse"
                    action="{{ route('admin.client-orders.task-board.editor-decision', ['submission_id' => '__SUBMISSION_ID__']) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="decision" value="reject">

                    <div class="row rg-20">
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Rejection Reason') }}</label>
                            <select class="sf-select-without-search" name="rejection_reason" id="rejectionReason">
                                <option value="">{{ __('Select reason (optional)') }}</option>
                                <option value="scope">{{ __('Out of scope') }}</option>
                                <option value="quality">{{ __('Quality standards not met') }}</option>
                                <option value="methodology">{{ __('Methodological issues') }}</option>
                                <option value="duplicate">{{ __('Duplicate or redundant') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Subject') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="email_subject"
                                value="{{ __('Submission Decision') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Rejection Message') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="rejection_message" rows="8" required
                                placeholder="{{ __('Provide constructive feedback for the author...') }}"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Include Reviewer Feedback') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_reviewer_feedback"
                                    id="includeReviewerFeedback" value="1">
                                <label class="form-check-label"
                                    for="includeReviewerFeedback">{{ __('Include sanitized reviewer feedback (anonymized)') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-10 mt-20 pt-20 bd-t-one bd-c-stroke">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Reject Submission') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Request Another Revision Modal --}}
    <div class="modal fade" id="requestRevisionModal" tabindex="-1" aria-labelledby="requestRevisionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Request Another Revision Round') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                        data-bs-dismiss="modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <form id="requestRevisionForm" class="ajax" data-handler="handleEditorDecisionResponse"
                    action="{{ route('admin.client-orders.task-board.request-revision', ['submission_id' => '__SUBMISSION_ID__']) }}"
                    method="POST">
                    @csrf

                    <div class="row rg-20">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i
                                    class="fa-solid fa-info-circle me-2"></i>{{ __('This will increment the revision round and create a new review cycle. Reviewers will be able to submit Version 3, 4, etc.') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Email Subject') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="email_subject"
                                value="{{ __('Additional Revisions Required') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Instructions') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="revision_instructions" rows="8" required
                                placeholder="{{ __('Provide instructions for the additional revisions needed...') }}"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Revision Deadline') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control zForm-control" name="revision_deadline" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-10 mt-20 pt-20 bd-t-one bd-c-stroke">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit"
                            class="btn btn-secondary">{{ __('Request Another Revision Round') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/client-order-task-boards.js') }}"></script>
    <script>
        $('#obFileUpload').change(function() {
            $('#obFileUploadform').submit();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#memberSearchBox').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#memberList .member-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Editor Decision Functions
        function makeEditorDecision(submissionId, decision) {
            const decisionLabels = {
                'accept': '{{ __('Accept') }}',
                'minor_revisions': '{{ __('Minor Revisions') }}',
                'major_revisions': '{{ __('Major Revisions') }}',
                'reject': '{{ __('Reject') }}'
            };

            const label = decisionLabels[decision] || decision;
            const confirmMessage = `{{ __('Are you sure you want to') }} ${label} {{ __('this submission?') }}`;

            if (!confirm(confirmMessage)) {
                return;
            }

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const url =
                '{{ route('admin.client-orders.task-board.editor-decision', ['submission_id' => ':submission_id']) }}'
                .replace(':submission_id', submissionId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    decision: decision
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                        alert(data.message || `{{ __('Decision saved successfully') }}`);
                    location.reload();
                } else {
                        alert(data.message || `{{ __('Error saving decision') }}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                    alert(`{{ __('An error occurred. Please try again.') }}`);
            });
        }

        function requestAnotherRevision(submissionId) {
            const confirmMessage =
                `{{ __('This will request another round of review from the reviewers. This will create a new review round and allow reviewers to submit Version 3, 4, etc. Continue?') }}`;

            if (!confirm(confirmMessage)) {
                return;
            }

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const url =
                '{{ route('admin.client-orders.task-board.request-revision', ['submission_id' => ':submission_id']) }}'
                .replace(':submission_id', submissionId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                        alert(data.message ||
                            `{{ __('Another revision round has been requested. Reviewers will be notified.') }}`);
                    location.reload();
                } else {
                        alert(data.message || `{{ __('Error requesting revision') }}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                    alert(`{{ __('An error occurred. Please try again.') }}`);
            });
        }

        // Toggle review details visibility
        function toggleReviewDetails(versionId) {
            const icon = document.getElementById('icon-details-' + versionId);
            if (icon) {
                const target = document.getElementById('reviewDetails-' + versionId);
                if (target && target.classList.contains('show')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        }

        // Toggle author comments visibility (Admin view)
        function toggleAuthorCommentsAdmin(revisionId) {
            const commentsDiv = document.getElementById('authorCommentsAdmin-' + revisionId);
            if (commentsDiv) {
                if (commentsDiv.style.display === 'none' || !commentsDiv.style.display) {
                    commentsDiv.style.display = 'block';
                } else {
                    commentsDiv.style.display = 'none';
                }
            }
        }

        // Handle modal opening and set submission ID and form action
        document.addEventListener('DOMContentLoaded', function() {
            // Accept Modal
            const acceptModal = document.getElementById('acceptModal');
            if (acceptModal) {
                acceptModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const submissionId = button.getAttribute('data-submission-id');
                    const form = document.getElementById('acceptForm');
                    if (form) {
                        form.action = form.action.replace('__SUBMISSION_ID__', submissionId);
                    }
                });
            }

            // Minor Revisions Modal
            const minorRevisionsModal = document.getElementById('minorRevisionsModal');
            if (minorRevisionsModal) {
                minorRevisionsModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const submissionId = button.getAttribute('data-submission-id');
                    const form = document.getElementById('minorRevisionsForm');
                    if (form) {
                        form.action = form.action.replace('__SUBMISSION_ID__', submissionId);
                    }
                });
            }

            // Major Revisions Modal
            const majorRevisionsModal = document.getElementById('majorRevisionsModal');
            if (majorRevisionsModal) {
                majorRevisionsModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const submissionId = button.getAttribute('data-submission-id');
                    const form = document.getElementById('majorRevisionsForm');
                    if (form) {
                        form.action = form.action.replace('__SUBMISSION_ID__', submissionId);
                    }
                });
            }

            // Reject Modal
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                rejectModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const submissionId = button.getAttribute('data-submission-id');
                    const form = document.getElementById('rejectForm');
                    if (form) {
                        form.action = form.action.replace('__SUBMISSION_ID__', submissionId);
                    }
                });
            }

            // Request Revision Modal
            const requestRevisionModal = document.getElementById('requestRevisionModal');
            if (requestRevisionModal) {
                requestRevisionModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const submissionId = button.getAttribute('data-submission-id');
                    const form = document.getElementById('requestRevisionForm');
                    if (form) {
                        form.action = form.action.replace('__SUBMISSION_ID__', submissionId);
                    }
                });
            }
        });

        // Handle editor decision form submissions
        function handleEditorDecisionResponse(response) {
            if (response.status === true || response.success === true) {
                // Close all modals
                const modals = ['acceptModal', 'minorRevisionsModal', 'majorRevisionsModal', 'rejectModal',
                    'requestRevisionModal'
                ];
                modals.forEach(modalId => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                    if (modal) {
                        modal.hide();
                    }
                });

                // Show success message and reload page
                alert(response.message || __('Decision saved successfully'));
                window.location.reload();
            } else {
                alert(response.message || __('An error occurred. Please try again.'));
            }
        }

        // Reviewer Matching Functionality
        let currentOrderId = null;
        let searchTimeout = null;

        function loadReviewerRecommendations(orderId, searchTerm = '') {
            const recommendationsList = document.getElementById('reviewerRecommendationsList');
            if (!recommendationsList) {
                return;
            }

            currentOrderId = orderId;
            recommendationsList.innerHTML =
                '<p class="text-muted mb-0">{{ __('Loading suggestions...') }}</p>';

            // Build query string
            let queryParams = `order_id=${orderId}`;
            if (searchTerm && searchTerm.trim()) {
                queryParams += `&search=${encodeURIComponent(searchTerm.trim())}`;
            }

            fetch(`{{ route('admin.reviewer-matching.recommendations') }}?${queryParams}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.reviewers && data.reviewers.length > 0) {
                        displayReviewerRecommendations(data.reviewers, orderId);
                    } else {
                        recommendationsList.innerHTML =
                            '<p class="text-muted mb-0">{{ __('No reviewers found. Try adjusting your search.') }}</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading reviewer recommendations:', error);
                    recommendationsList.innerHTML =
                        '<p class="text-danger mb-0">{{ __('Error loading recommendations') }}</p>';
                });
        }

        // Search input handler with debounce
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('reviewerSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value;

                    // Clear previous timeout
                    if (searchTimeout) {
                        clearTimeout(searchTimeout);
                    }

                    // Debounce search - wait 500ms after user stops typing
                    searchTimeout = setTimeout(function() {
                        if (currentOrderId) {
                            loadReviewerRecommendations(currentOrderId, searchTerm);
                        }
                    }, 500);
                });
            }
        });

        function displayReviewerRecommendations(reviewers, orderId) {
            const recommendationsList = document.getElementById('reviewerRecommendationsList');
            if (!recommendationsList) return;

            let html = '';

            reviewers.forEach(function(reviewer, index) {
                const isAssigned = reviewer.is_assigned || false;
                const isChecked = isAssigned ? 'checked' : '';
                const initials = (reviewer.name || '').split(' ').map(p => p[0]).join('').substring(0, 2).toUpperCase();
                const notes = (reviewer.match_notes || []).map(n => '<div class="tag">' + n + '</div>').join('');
                const checkboxId = 'assignReviewer' + reviewer.id + '-' + index;

                // Build action button HTML based on assignment status
                let actionButtonHtml = '';
                if (isAssigned) {
                    actionButtonHtml = '<span class="badge bg-success" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500;">' +
                        '<i class="fa-solid fa-check-circle"></i> {{ __('Assigned') }}' +
                        '</span>';
                } else {
                    actionButtonHtml = '<input type="checkbox" class="d-none assign-member" ' + isChecked +
                        '       id="' + checkboxId + '" value="' + reviewer.id + '" data-order="' + orderId + '"/>' +
                        '<button type="button" class="btn-assign" data-target="' + checkboxId + '">' +
                        '      <i class="fa-solid fa-user-plus"></i> {{ __('Assign') }}' +
                        '    </button>';
                }

                html += '' +
                    '<div class="reviewer-card">' +
                    '  <div class="reviewer-main">' +
                    '    <div class="avatar">' + initials + '</div>' +
                    '    <div class="reviewer-info">' +
                    '      <div class="reviewer-name">' + (reviewer.name || '') + '</div>' +
                    '      <div class="reviewer-meta">' +
                    '        <span>' + (reviewer.email || '') + '</span>' +
                    '        <span>' + (reviewer.institution || '') + '</span>' +
                    '      </div>' +
                    '      <div class="reviewer-expertise">' + (reviewer.field_of_study || '') + '</div>' +
                    '      <div class="reviewer-tags">' + notes + '</div>' +
                    '    </div>' +
                    '  </div>' +
                    '  <div class="reviewer-actions">' +
                    actionButtonHtml +
                    '    <span>' + (reviewer.match_score || 0) + '% {{ __('match') }}</span>' +
                    '  </div>' +
                    '</div>';
            });

            recommendationsList.innerHTML = html;
        }

        // Toggle hidden checkbox when clicking Assign button (reuses existing .assign-member logic)
        $(document).on('click', '.btn-assign', function() {
            const targetId = $(this).data('target');
            const checkbox = document.getElementById(targetId);
            if (!checkbox) return;
            // Let the checkbox toggle itself and fire the existing .assign-member handler
            checkbox.click();
        });

        // Open custom overlay modal and load recommendations
        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById("openAssignModal");
            const closeBtn = document.getElementById("closeAssignModal");
            const modal = document.getElementById("assignModal");

            if (openBtn && modal) {
                openBtn.addEventListener("click", () => {
                    const orderId = {{ $order->id }};
                    currentOrderId = orderId;
                    // Clear search input when opening modal
                    const searchInput = document.getElementById('reviewerSearchInput');
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    loadReviewerRecommendations(orderId);
                    modal.classList.add("active");
                    modal.setAttribute("aria-hidden", "false");
                });
            }

            // Initialize search input handler
            const searchInput = document.getElementById('reviewerSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value;

                    // Clear previous timeout
                    if (searchTimeout) {
                        clearTimeout(searchTimeout);
                    }

                    // Debounce search - wait 500ms after user stops typing
                    searchTimeout = setTimeout(function() {
                        if (currentOrderId) {
                            loadReviewerRecommendations(currentOrderId, searchTerm);
                        }
                    }, 500);
                });
            }

            if (closeBtn && modal) {
                closeBtn.addEventListener("click", () => {
                    modal.classList.remove("active");
                    modal.setAttribute("aria-hidden", "true");
                });
            }

            if (modal) {
                modal.addEventListener("click", (e) => {
                    if (e.target === modal) {
                        modal.classList.remove("active");
                        modal.setAttribute("aria-hidden", "true");
                    }
                });
            }
        });

        // Generate Certificate Button Handler
        $(document).on('click', '.generate-certificate-btn', function() {
            const btn = $(this);
            const submissionId = btn.data('submission-id');

            if (!submissionId) {
                alert('{{ __('Invalid submission ID') }}');
                return;
            }

            if (!confirm('{{ __('Generate Final Acceptance Certificate?') }}')) {
                return;
            }

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Generating...') }}');

            $.ajax({
                url: '{{ route("admin.submissions.final-acceptance-certificate.generate", ["submission_id" => "__ID__"]) }}'.replace('__ID__', submissionId),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === true || response.success === true) {
                        alert(response.message || '{{ __('Certificate generated successfully!') }}');
                        location.reload(); // Reload to show download button
                    } else {
                        alert(response.message || '{{ __('Failed to generate certificate') }}');
                        btn.prop('disabled', false).html('<i class="fa-solid fa-certificate me-2"></i>{{ __('Generate Final Acceptance Certificate') }}');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || '{{ __('An error occurred. Please try again.') }}';
                    alert(message);
                    btn.prop('disabled', false).html('<i class="fa-solid fa-certificate me-2"></i>{{ __('Generate Final Acceptance Certificate') }}');
                }
            });
        });

        // Task 23-24: Load issues for selected journal
        function loadIssuesForJournal(journalId, selectId) {
            const issueSelect = document.getElementById(selectId);
            if (!journalId) {
                issueSelect.innerHTML = '<option value="">{{ __('Select Issue') }}</option>';
                issueSelect.disabled = true;
                return;
            }

            issueSelect.disabled = true;
            issueSelect.innerHTML = '<option value="">{{ __('Loading...') }}</option>';

            fetch(`{{ route('admin.issues.get-by-journal') }}?journal_id=${journalId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    issueSelect.innerHTML = '<option value="">{{ __('Select Issue') }}</option>';
                    if (data.issues && data.issues.length > 0) {
                        data.issues.forEach(issue => {
                            const option = document.createElement('option');
                            option.value = issue.id;
                            option.textContent =
                                `Vol ${issue.volume || '-'}, No ${issue.number || '-'}, ${issue.year || '-'}${issue.title ? ' - ' + issue.title : ''}`;
                            issueSelect.appendChild(option);
                        });
                    }
                    issueSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading issues:', error);
                    issueSelect.innerHTML = '<option value="">{{ __('Error loading issues') }}</option>';
                });
        }

        function confirmSubmissionLabelChange(event) {
            const form = event.target;
            const statusSelect = form.querySelector('select[name="status"]');
            const selectedStatus = statusSelect.value;
            const paymentStatus = form.getAttribute('data-payment-status');
            const paidStatus = form.getAttribute('data-paid-status');
            const underPeerReviewStatus = "{{ SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW }}";

            if (selectedStatus === underPeerReviewStatus) {
                if (paymentStatus != paidStatus) {
                    const confirmed = confirm("Payment is not completed. Do you want to proceed regardless?");
                    if (!confirmed) {
                        event.preventDefault();
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
@endpush
