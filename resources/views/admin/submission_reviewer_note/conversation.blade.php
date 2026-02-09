<!--  -->
@if(count($ticketConversations) > 0)
<div class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15 bg-white">
    <h4 class="fs-15 fw-600 lh-20 text-title-black pb-15">{{__("Reviewer Note Replies")}}</h4>
        <ul class="d-flex flex-column rg-15">
                @foreach($ticketConversations as $item)
                    <li class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-20 bg-white">
                    <!-- Top -->
                    <div class="d-flex justify-content-between pb-6">
                        <!-- Left -->
                        <div class="d-flex align-items-center g-7 flex-wrap">
                            <div class="flex-shrink-0 w-24 h-24 rounded-circle overflow-hidden"><img src="{{getFileUrl($item->client_image)}}" alt="" /></div>
                            @if($item->user_id == auth()->id())
                                <h4 class="fs-14 fw-600 lh-22 text-title-black">{{__("You")}}</h4>
                            @elseif($item->user_id != auth()->id() && $item->client_role != USER_ROLE_CLIENT)
                                <h4 class="fs-14 fw-600 lh-22 text-title-black">{{$item->client_name}} <span>({{__("Team Member")}})</span></h4>
                            @else
                                <h4 class="fs-14 fw-600 lh-22 text-title-black">{{$item->client_name}} <span>({{__("Client")}})</span></h4>
                            @endif
                        </div>
                        <!-- Middle: Submitted Date -->
                        <div class="text-center">
                            <p class="fs-14 text-title-black mb-0">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('l, F jS, Y (H:i)') }}
                            </p>
                        </div>
                        <!-- Right -->
                        <div class="dropdown dropdown-one">
                            <button class="dropdown-toggle p-0 bg-transparent w-24 h-24 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center fs-13 text-para-text" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                <li>
                                    <button class="d-flex align-items-center cg-8 border-0 bg-body" onclick="deleteItem('{{route('admin.submission-reviewer-notes.conversations.delete', encrypt($item->id))}}' , 'no','{{route('admin.submission-reviewer-notes.details', encrypt($ticketDetails->id))}}')">
                                        <div class="d-flex">
                                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                                    fill="#5D697A"
                                                />
                                            </svg>
                                        </div>
                                        <p class="fs-14 fw-500 lh-17 text-para-text">{{__("Delete")}}</p></button
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Middle -->
                    <div class="pb-12">
                        <p class="fs-14 fw-400 lh-24 text-para-text text-justify">{{$item->conversation_text}}</p>
                    </div>
                    <!-- Bottom -->
                        @if($item->attachment !=null && count(json_decode($item->attachment)) > 0)
                        <ul class="d-flex flex-wrap g-10">
                            @foreach(json_decode($item->attachment) as $file)
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
                                                <h4 class="fs-12 fw-400 lh-15 text-para-text pr-8 bd-r-one bd-c-stroke">{{getFileData($file, 'size')}} B</h4>
                                                <h4 class="fs-12 fw-400 lh-15 text-para-text pl-8">{{__("File")}}</h4>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                            @endforeach
                        </ul>
                        @endif
                </li>
                @endforeach

        </ul>

</div>
@endif

<!--  -->
<div class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15 bg-white">
    <form class="ajax reset" action="{{route('admin.submission-reviewer-notes.conversations.store')}}" method="POST"
          enctype="multipart/form-data" data-handler="commonResponseForModal"
          id="submissionReviewerReplyForm"
        >
        @csrf
        <div class="pb-30">
            <div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-8">
                <label for="ticketReply" class="fs-15 fw-600 lh-20 text-title-black mb-0">{{__("Write a Reply")}}</label>
                <!--  -->
                {{-- <div class="d-flex flex-wrap align-items-center g-10">
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_OPEN}}" {{$ticketDetails->status == TICKET_STATUS_OPEN?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="pending"  value="{{TICKET_STATUS_OPEN}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="pending">{{__("Pending")}}</label>
                    </div>
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_IN_PROGRESS}}" {{$ticketDetails->status == TICKET_STATUS_IN_PROGRESS?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="processing"  value="{{TICKET_STATUS_IN_PROGRESS}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="processing">{{__("Processing")}}</label>
                    </div>
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_RESOLVED}}" {{$ticketDetails->status == TICKET_STATUS_RESOLVED?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="solved" value="{{TICKET_STATUS_RESOLVED}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="solved">{{__("Solved")}}</label>
                    </div>
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_CLOSED}}" {{$ticketDetails->status == TICKET_STATUS_CLOSED?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="closed" value="{{TICKET_STATUS_CLOSED}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="closed">{{__("Closed")}}</label>
                    </div>
                </div> --}}
            </div>
            <input type="hidden" value="{{encrypt($ticketDetails->id)}}" name="ticket_id">
            <textarea id="ticketReply" class="form-control zForm-control zForm-control-textarea-2 min-h-175" placeholder="{{__("Write Reply here")}}...." name="conversation_text"></textarea>
        </div>
        <div class="pb-25">
            <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload Image")}} {{__('(PDF, DOCX, PNG)')}}</p>
            <div class="file-upload-one">
                <label for="mAttachment">
                    <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                    <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                </label>
                    <input type="file" name="file[]" id="mAttachment" class="invisible position-absolute" multiple=""  accept=".pdf,.docx,.png"/>
            </div>
            <div id="files-area" class="">
                      <span id="filesList">
                        <span id="files-names"></span>
                      </span>
            </div>
        </div>
        {{-- @if($ticketDetails->status == TICKET_STATUS_CLOSED)
            <p class="text-red">{{__("Note: Not possible to conversation for this ticket. Because, This ticket is closed")}}.</p>
        @else --}}
            <button type="submit" id="submitBtn" class="py-10 px-26 bg-main-color border-0 bd-ra-8 fs-15 fw-600 lh-25 text-white">
                <span class="submit-text">{{__("Send Message")}}</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        {{-- @endif --}}
    </form>
</div>
{{-- <input type="hidden" id="statusChangeRoute" value="{{route('admin.ticket.status.change')}}"> --}}

