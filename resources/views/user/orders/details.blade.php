@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
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
                <div class="d-flex flex-wrap g-10">
                    <a href="{{ route('user.orders.dashboard', $orderDetails->order_id) }}"
                       class="btn btn-primary btn-sm px-15">
                        {{ __('View Submission Dashboard') }}
                    </a>
                    @if($hasCompletedReviews)
                        <a href="{{ route('user.orders.reviews.summary', $orderDetails->order_id) }}"
                           class="btn btn-outline-primary btn-sm px-15">
                            {{ __('Review Feedback') }}
                        </a>
                    @endif
                </div>
            </div>
            <!-- Order info - Note/Message -->
            <div class="row rg-20">
                <!-- Order Info -->
                <div class="col-md-4">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white pt-12 pb-18 max-w-282 m-auto">

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
                    <!-- Message -->

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-clientMessage" role="tabpanel"
                             aria-labelledby="nav-clientMessage-tab" tabindex="0">
                            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-sm-20 p-10">
                                <div class="bd-ra-10 bg-body-bg overflow-hidden"
                                     data-background="{{asset("user")}}/images/chat_bg.png">
                                    <div class="content-chat-message-user-wrap">
                                        <div class="content-chat-message-user">
                                            <!-- Body -->
                                            <div class="body-chat-message-user client-chat">
                                                @if($conversationData != null && count($conversationData) >0 )
                                                    @foreach($conversationData as $data)
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
                                                                                            <div class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img src="{{ getFileUrl($file)  }}" alt="" />
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}" target="_blank" class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img src="{{ asset('assets/images/icon/files-1.svg')}}" alt="" /></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}} {{__('B')}}</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
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
                                                                                            <div class="sf-popup-gallery">
                                                                                                <a href="{{ getFileUrl($file) }}">
                                                                                                    <img src="{{ getFileUrl($file)  }}" alt="" />
                                                                                                </a>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li>
                                                                                            <a href="{{ getFileUrl($file)  }}" target="_blank" class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                                                                <div class="d-flex"><img src="{{ asset('assets/images/icon/files-1.svg')}}" alt="" /></div>
                                                                                                <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <!-- File size -->
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}} {{__('B')}}</h4>
                                                                                                    <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                        <div class="time-read">
                                                                            <span class="time">{{date('d M Y, H:i', strtotime($data->created_at))}}</span>
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
                                                    <form method="POST" class="ajax reset" action="{{ route('user.orders.conversation.store') }}"
                                                          data-handler="chatResponse">
                                                        @csrf
                                                        <!-- Attachment preview -->
                                                        <div id="files-area">
                                                            <span id="filesList">
                                                              <span id="files-names"></span>
                                                            </span>
                                                        </div>
                                                        <input type="hidden" value="{{encrypt($orderDetails->id)}}" name="order_id">
                                                        <input type="hidden" value="{{CONVERSATION_TYPE_CLIENT}}" name="type">
                                                        <!-- input - buttons -->
                                                        <div class="footer-inputs d-flex justify-content-between">
                                                                <div class="message-user-send">
                                                                    <input type="text" name="conversation_text" class="conversation-text" placeholder="{{__("Type your message here")}}..."/>
                                                                </div>
                                                                <button type="button" class="atta-btn">
                                                                    <label for="mAttachment"><img
                                                                            src="{{asset("assets/images/icon/file.svg")}}"
                                                                            alt=""/></label>
                                                                    <input type="file" name="file[]" id="mAttachment"
                                                                           style="visibility: hidden; position: absolute" multiple/>
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
                    <div class="pb-25">
                        <label for="noteDetails" class="zForm-label">{{__('Note Details')}}</label>
                        <textarea id="noteDetails" class="form-control zForm-control min-h-175"
                                  placeholder="Write note details here...."></textarea>
                    </div>
                    <!-- Button -->
                    <div class="d-flex g-12">
                        <button
                            class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-white bg-main-color d-flex justify-content-center">
                            {{__('Save Note')}}
                        </button>
                        <button
                            class="bd-one bd-c-para-text bd-ra-8 py-10 px-26 fs-15 fw-600 lh-25 text-para-text bg-white d-flex justify-content-center">
                            {{__('Cancel')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('user/custom/js/client-orders.js') }}"></script>
@endpush
