@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="max-w-894 m-auto">
            <!-- Order title -->
            <h4 class="fs-18 fw-600 lh-20 text-title-black pb-11 text-md-start text-center">{{__("Order ID")}}
                : {{$orderDetails->order_id}}</h4>
            <!-- Order info - Note + Assign + Status -->
            <div
                class="d-flex justify-content-center justify-content-md-between align-items-start flex-wrap g-10 pb-33">
                <!-- Left -->
                <ul class="bd-ra-5 py-5 px-sm-15 px-6 bg-main-color-10 order-info justify-content-around">
                    <li>
                        <h4 class="fs-10 fw-500 lh-20 text-main-color">{{__("Created")}}</h4>
                        <p class="fs-12 fw-400 lh-20 text-title-black">{{date('d/m/Y', strtotime($orderDetails->created_at))}}</p>
                    </li>
                    <li>
                        <h4 class="fs-10 fw-500 lh-20 text-main-color">{{__("Status")}}</h4>
                        <p class="fs-12 fw-400 lh-20 text-title-black">
                            @if($orderDetails->working_status == WORKING_STATUS_WORKING)
                                {{__("Working")}}
                            @elseif($orderDetails->working_status == WORKING_STATUS_COMPLETED)
                                {{__("Completed")}}
                            @elseif($orderDetails->working_status == WORKING_STATUS_CANCELED)
                                {{__("Canceled")}}
                            @elseif($orderDetails->working_status == WORKING_STATUS_PENDING)
                                {{__("Pending")}}
                            @endif
                        </p>
                    </li>
                </ul>
                <!-- Right -->
                <div class="d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap g-10">
                    <!-- Note button -->
                    <button class="border-0 bd-ra-8 bg-main-color py-5 px-15 fs-15 fw-600 lh-25 text-white"
                            id="noteAddModal"
                            data-bs-toggle="modal" data-bs-target="#addNoteModal"
                            data-order_id="{{encrypt($orderDetails->id)}}">+ {{__("Note")}}
                    </button>
                    <!-- Assign - Status -->
                    <div
                        class="d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap g-22">
                        <div class="">
                            <div class="dropdown dropdown-two imageDropdown">
                                <button class="dropdown-toggle border-0 p-0 bg-transparent g-10" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="images">
                                        @if(count($orderAssignee) > 0)
                                            @foreach($orderAssignee as $assignee)
                                                <img
                                                    src="{{getFileUrl(getUserData($assignee, 'image'))}}"
                                                    title=" {{ getUserData($assignee, 'name') }} "/>
                                            @endforeach
                                            @if(count($orderAssignee) > 2)
                                                <div class='iconPlus'><i class='fa-solid fa-plus'></i>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <p class="">Assign:</p>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-three">
                                    @forelse($teamMemberList as $key=>$member)
                                        <li>
                                            <div class="zForm-wrap-checkbox-2">
                                                <input type="checkbox" class="form-check-input assign-member"
                                                       {{in_array($member->id, $orderAssignee) ? 'checked' : ''}} id="userOne{{$key}}"
                                                       value="{{$member->id}}" data-order="{{$orderDetails->id}}"/>
                                                <label for="userOne">
                                                    <div class="d-flex align-items-center g-8">
                                                        <div
                                                            class="flex-shrink-0 w-25 h-25 rounded-circle overflow-hidden">
                                                            <img src="{{getFileUrl($member->image)}}"
                                                                 alt="{{$member->name}}"/></div>
                                                        <h4 class="fs-12 fw-500 lh-15 text-para-text text-nowrap">{{$member->name}}</h4>
                                                    </div>
                                                </label>
                                            </div>
                                        </li>
                                    @empty
                                        <p class="">{{__("No Member Available")}}</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="">
                            <div class="dropdown dropdown-two imageDropdown">
                                <button class="dropdown-toggle border-0 p-0 bg-transparent g-10 min-w-auto"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <p class="">{{getOrderStatusName($orderDetails->working_status)}}:</p>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-four">
                                    <li>
                                        <a href="{{route('admin.client-orders.status.change',[ encrypt($orderDetails->id), WORKING_STATUS_PENDING])}}">
                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{__("Pending")}}</p></a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.client-orders.status.change',[ encrypt($orderDetails->id), WORKING_STATUS_WORKING])}}">
                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{__("Working")}}</p></a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.client-orders.status.change',[ encrypt($orderDetails->id), WORKING_STATUS_COMPLETED])}}">
                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{__("Completed")}}</p></a>
                                    </li>
                                    <li>
                                        <a href="{{route('admin.client-orders.status.change',[ encrypt($orderDetails->id), WORKING_STATUS_CANCELED])}}">
                                            <p class="fs-14 fw-400 lh-17 text-para-text">{{__("Canceled")}}</p></a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order info - Note/Message -->
            <div class="row rg-20">
                <!-- Order Info -->
                <div class="col-md-4">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white pt-12 pb-18 max-w-282 m-auto">
                        <!-- Client name -->
                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                            <div class="row g-10">
                                <div class="col-auto">
                                    <h4 class="fs-14 fw-500 lh-17 text-title-black">{{__("Client")}} :</h4>
                                </div>
                                <div class="col-auto">
                                    <h4 class="fs-14 fw-500 lh-17 text-para-text">{{getUserData($orderDetails->client_id,'name')}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Service -->
                        <div class="bd-b-one bd-c-stroke pb-15 mb-15 px-15">
                            <h4 class="fs-14 fw-500 lh-17 text-title-black pb-3">{{__("Service")}} :</h4>
                            @foreach($orderDetails->client_order_items as $service)
                                <h4 class="fs-12 fw-500 lh-15 text-para-text">{{getServiceById($service->service_id,'service_name')}}</h4>
                            @endforeach
                        </div>
                        <!--  -->
                        <ul class="zList-pb-12 px-15">
                            <li class="row">
                                <div class="col-6"><h4 class="fs-14 fw-500 lh-17 text-title-black">{{__("Amount")}}
                                        :</h4></div>
                                <div class="col-6"><h4
                                        class="text-end fs-14 fw-500 lh-17 text-para-text">{{currentCurrency('symbol')}}{{$orderDetails->total}}</h4>
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-6"><h4
                                        class="fs-14 fw-500 lh-17 text-title-black">{{__("Payment Status")}} :</h4>
                                </div>
                                <div class="col-6">
                                    @if($orderDetails->payment_status == PAYMENT_STATUS_PENDING)
                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Unpaid")}}</h4>
                                    @elseif($orderDetails->payment_status == PAYMENT_STATUS_PAID)
                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Paid")}}</h4>
                                    @elseif($orderDetails->payment_status == PAYMENT_STATUS_PARTIAL)
                                        <h4 class="text-end fs-14 fw-500 lh-17 text-para-text">{{__("Partial")}}</h4>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Note/Message -->
                <div class="col-md-8 pe-md-0">
                    <!-- Note -->
                    @if($orderDetails->notes !=null && count($orderDetails->notes) > 0)
                        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-20 p-10 mb-25">
                            <!--  -->
                            <h4 class="fs-14 fw-500 lh-17 text-title-black pb-14">{{__("All Notes")}}
                            </h4>
                            <!--  -->
                            <ul class="note-list">
                                @foreach($orderDetails->notes as $note)
                                    @if($note->user_id == auth()->id())
                                        <li class="d-flex justify-content-between g-10 bg-note-self">
                                            <!--  -->
                                            <div class="flex-grow-1">
                                                <!--  -->
                                                <h4 class="title pb-15">{{$note->details}}</h4>
                                                <!--  -->
                                                <div class="d-flex align-items-center g-7 flex-wrap">
                                                    <div class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
                                                        <img
                                                            src="{{getFileUrl(getUserData($note->user_id, 'image'))}}"
                                                            alt=""/></div>
                                                    <h4 class="fs-12 fw-500 lh-14 text-title-black">{{getUserData($note->user_id, 'name')}}
                                                        ({{__("You")}})</h4>
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="dropdown dropdown-one">
                                                <button
                                                    class="dropdown-toggle p-0 bg-transparent w-24 h-24 d-flex justify-content-center align-items-center"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                        class="fa-solid fa-ellipsis-vertical"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                    <li>
                                                        <button class="d-flex align-items-center cg-8"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#addNoteModal" id="noteEditModal" data-order_id="{{encrypt($orderDetails->id)}}" data-details="{{$note->details}}" data-id="{{encrypt($note->id)}}">
                                                            <div class="d-flex">
                                                                <svg width="12" height="13" viewBox="0 0 12 13"
                                                                     fill="none"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                        fill="#5D697A"/>
                                                                </svg>
                                                            </div>
                                                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Edit")}}</p></button
                                                        >
                                                    </li>
                                                    <li>
                                                        <button class="d-flex align-items-center cg-8"
                                                                onclick="deleteItem('{{route('admin.client-orders.note.delete', encrypt($note->id))}}' , 'no','{{route('admin.client-orders.details', encrypt($orderDetails->id))}}')">
                                                            <div class="d-flex">
                                                                <svg width="14" height="15" viewBox="0 0 14 15"
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
                                    @else
                                        <li class="d-flex justify-content-between g-10">
                                            <!--  -->
                                            <div class="flex-grow-1">
                                                <!--  -->
                                                <h4 class="title pb-15">{{$note->details}}</h4>
                                                <!--  -->
                                                <div class="d-flex align-items-center g-7 flex-wrap">
                                                    <div class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden">
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
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                        class="fa-solid fa-ellipsis-vertical"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                                    <li>
                                                        <button class="d-flex align-items-center cg-8"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addNoteModal" id="noteEditModal" data-order_id="{{encrypt($orderDetails->id)}}" data-details="{{$note->details}}" data-id="{{encrypt($note->id)}}">
                                                            <div class="d-flex">
                                                                <svg width="12" height="13" viewBox="0 0 12 13"
                                                                     fill="none"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z"
                                                                        fill="#5D697A"/>
                                                                </svg>
                                                            </div>
                                                            <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Edit")}}</p></button
                                                        >
                                                    </li>
                                                    <li>
                                                        <button class="d-flex align-items-center cg-8"
                                                                onclick="deleteItem('{{route('admin.client-orders.note.delete', encrypt($note->id))}}' , 'no','{{route('admin.client-orders.details', encrypt($orderDetails->id))}}')">
                                                            <div class="d-flex">
                                                                <svg width="14" height="15" viewBox="0 0 14 15"
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
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Message -->
                    <nav>
                        <div class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-clientMessage-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-clientMessage" type="button" role="tab"
                                    aria-controls="nav-clientMessage" aria-selected="true">{{__("Client")}}</button>
                            <button class="nav-link chat-team-tab" id="nav-teamMessage-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-teamMessage" type="button" role="tab"
                                    aria-controls="nav-teamMessage" aria-selected="false">{{__("Team")}}</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-clientMessage" role="tabpanel"
                             aria-labelledby="nav-clientMessage-tab" tabindex="0">
                            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-sm-20 p-10">
                                <div class="bd-ra-10 bg-body-bg overflow-hidden"
                                     data-background="{{asset("admin")}}/images/chat_bg.png">
                                    <div class="content-chat-message-user-wrap">
                                        <div class="content-chat-message-user">
                                            <!-- Body -->
                                            <div class="body-chat-message-user admin-client-chat">
                                                @if($conversationClientTypeData != null && count($conversationClientTypeData) >0 )
                                                    @foreach($conversationClientTypeData as $data)
                                                        @if($data->user_id == auth()->id())
                                                            <div class="message-user-right">
                                                                <div class="message-user-right-text">
                                                                    <div class="text">
                                                                        <p>{{$data->conversation_text}}</p>
                                                                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                                                                            <ul class="d-flex flex-wrap g-10">
                                                                                @foreach(json_decode($data->attachment) as $file)
                                                                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                                                                        <li>
                                                                                            <div
                                                                                                class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img
                                                                                                        src="{{ getFileUrl($file)  }}"
                                                                                                        alt=""/>
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}"
                                                                                               target="_blank"
                                                                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img
                                                                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                                                                        alt=""/></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div
                                                                                                    class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                                                                        {{__('B')}}</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span
                                                                                class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="message-user-left">
                                                                <div class="message-user-left-text">
                                                                    <div class="text">
                                                                        <p>{{$data->conversation_text}}</p>
                                                                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                                                                            <ul class="d-flex flex-wrap g-10">
                                                                                @foreach(json_decode($data->attachment) as $file)
                                                                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                                                                        <li>
                                                                                            <div
                                                                                                class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img
                                                                                                        src="{{ getFileUrl($file)  }}"
                                                                                                        alt=""/>
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}"
                                                                                               target="_blank"
                                                                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img
                                                                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                                                                        alt=""/></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div
                                                                                                    class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                                                                        {{__('B')}}</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span
                                                                                class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="no-chat">
                                                        <div class="img"><img
                                                                src="{{asset("assets/images/chat_no_image.png")}}" alt="">
                                                        </div>
                                                        <p>{{__("No Message, yet")}}</p>
                                                    </div>
                                                @endif

                                            </div>
                                            <!-- Footer -->
                                            <div class="footer-chat-message-user">
                                                <form method="POST" class="ajax reset"
                                                      action="{{ route('admin.client-orders.conversation.store') }}"
                                                      data-handler="chatResponse">
                                                    @csrf
                                                    <!-- Attachment preview -->
                                                    <div id="files-area">
                                                            <span id="filesList">
                                                              <span id="files-names"></span>
                                                            </span>
                                                    </div>
                                                    <input type="hidden" value="{{encrypt($orderDetails->id)}}"
                                                           name="order_id">
                                                    <input type="hidden" value="{{CONVERSATION_TYPE_CLIENT}}"
                                                           name="type">
                                                    <!-- input - buttons -->
                                                    <div class="footer-inputs d-flex justify-content-between">
                                                        <div class="message-user-send">
                                                            <input type="text" name="conversation_text"
                                                                   class="conversation-text"
                                                                   placeholder="{{__("Type your message here")}}..."/>
                                                        </div>
                                                        <button type="button" class="atta-btn">
                                                            <label for="mAttachment1"><img
                                                                    src="{{asset("assets/images/icon/file.svg")}}"
                                                                    alt=""/></label>
                                                            <input type="file" name="file[]" id="mAttachment1"
                                                                   style="visibility: hidden; position: absolute"
                                                                   multiple/>
                                                        </button>
                                                        <button class="send-btn" type="submit">
                                                            <img src="{{asset("assets/images/icon/send.svg")}}" alt=""/>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-teamMessage" role="tabpanel"
                             aria-labelledby="nav-teamMessage-tab" tabindex="0">
                            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-sm-20 p-10">
                                <div class="bd-ra-10 bg-body-bg overflow-hidden"
                                     data-background="{{asset("admin")}}/images/chat_bg.png">
                                    <div class="content-chat-message-user-wrap">
                                        <div class="content-chat-message-user">
                                            <!-- Body -->
                                            <div class="body-chat-message-user admin-team-chat">
                                                @if($conversationTeamTypeData != null && count($conversationTeamTypeData) >0 )
                                                    @foreach($conversationTeamTypeData as $data)
                                                        @if($data->user_id == auth()->id())
                                                            <div class="message-user-right">
                                                                <div class="message-user-right-text">
                                                                    <div class="text">
                                                                        <p>{{$data->conversation_text}}</p>
                                                                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                                                                            <ul class="d-flex flex-wrap g-10">
                                                                                @foreach(json_decode($data->attachment) as $file)
                                                                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                                                                        <li>
                                                                                            <div
                                                                                                class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img
                                                                                                        src="{{ getFileUrl($file)  }}"
                                                                                                        alt=""/>
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}"
                                                                                               target="_blank"
                                                                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img
                                                                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                                                                        alt=""/></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div
                                                                                                    class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                                                                        B</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span
                                                                                class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="message-user-left">
                                                                <div class="message-user-left-text">
                                                                    <div class="text">
                                                                        <p>{{$data->conversation_text}}</p>
                                                                        @if($data->attachment !=null && count(json_decode($data->attachment)) > 0)
                                                                            <ul class="d-flex flex-wrap g-10">
                                                                                @foreach(json_decode($data->attachment) as $file)
                                                                                    @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp']))
                                                                                        <li>
                                                                                            <div
                                                                                                class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img
                                                                                                        src="{{ getFileUrl($file)  }}"
                                                                                                        alt=""/>
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}"
                                                                                               target="_blank"
                                                                                               class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img
                                                                                                        src="{{ asset('assets/images/icon/files-1.svg')}}"
                                                                                                        alt=""/></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div
                                                                                                    class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}}
                                                                                                        B</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span
                                                                                class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="no-chat">
                                                        <div class="img"><img
                                                                src="{{asset("assets/images/chat_no_image.png")}}" alt="">
                                                        </div>
                                                        <p>{{__("No Message, yet")}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Footer -->
                                            <div class="footer-chat-message-user">
                                                <form method="POST" class="ajax reset"
                                                      action="{{ route('admin.client-orders.conversation.store') }}"
                                                      data-handler="chatResponse">
                                                    @csrf
                                                    <!-- Attachment preview -->
                                                    <div id="files-area2">
                                                            <span id="filesList2">
                                                              <span id="files-names2"></span>
                                                            </span>
                                                    </div>
                                                    <input type="hidden" value="{{encrypt($orderDetails->id)}}"
                                                           name="order_id">
                                                    <input type="hidden" value="{{CONVERSATION_TYPE_TEAM}}" name="type">
                                                    <!-- input - buttons -->
                                                    <div class="footer-inputs d-flex justify-content-between">
                                                        <div class="message-user-send">
                                                            <input type="text" name="conversation_text"
                                                                   class="conversation-text"
                                                                   placeholder="{{__("Type your message here")}}..."/>
                                                        </div>
                                                        <button type="button" class="atta-btn">
                                                            <label for="mAttachment3"><img
                                                                    src="{{asset("assets/images/icon/file.svg")}}"
                                                                    alt=""/></label>
                                                            <input type="file" name="file[]" id="mAttachment3"
                                                                   style="visibility: hidden; position: absolute"
                                                                   multiple/>
                                                        </button>
                                                        <button class="send-btn" type="submit">
                                                            <img src="{{asset("assets/images/icon/send.svg")}}" alt=""/>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div
                        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{__('Add note')}}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" class="ajax reset"
                          action="{{ route('admin.client-orders.note.store') }}"
                          data-handler="commonResponseWithPageLoad">
                        @csrf
                        <div class="pb-25">
                            <label for="noteDetails" class="zForm-label">{{__("Note Details")}}</label>
                            <textarea id="noteDetails" name="details" class="form-control zForm-control min-h-175"
                                      placeholder="{{__("Write note details here")}}...."></textarea>
                            <input type="hidden" name="order_id" id="orderIdField">
                            <input type="hidden" name="id" id="noteIdField">
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit"
                                    class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-white bg-main-color d-flex justify-content-center">{{__("Save Note")}}</button>
                            <a href="{{ URL::previous() }}" type="button"
                               class="bd-one bd-c-para-text bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-para-text bg-white d-flex justify-content-center">{{__("Cancel")}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="assignMemberRoute" value="{{route('admin.client-orders.assign.member')}}">
    <input type="hidden" id="assignMemberReloadRoute"
           value="{{route('admin.client-orders.details',encrypt($orderDetails->id))}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/client-orders.js') }}"></script>
@endpush
