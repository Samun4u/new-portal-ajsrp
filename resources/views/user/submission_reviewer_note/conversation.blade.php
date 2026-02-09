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
                            @else
                                <h4 class="fs-14 fw-600 lh-22 text-title-black">{{$item->client_name}} </h4>
                            @endif
                        </div>
                        <!-- Right -->

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
    <form class="ajax reset" action="{{route('user.submission-reviewer-notes.conversations.store')}}" method="POST"
          enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="pb-30">
            {{-- <div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-8">
                <label for="ticketReply" class="fs-15 fw-600 lh-20 text-title-black mb-0">{{__("Write a Reply")}}</label>
                <!--  -->
                <div class="d-flex flex-wrap align-items-center g-10">
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_RESOLVED}}" {{$ticketDetails->status == TICKET_STATUS_RESOLVED?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="solved" value="{{TICKET_STATUS_RESOLVED}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="solved">{{__("Solved")}}</label>
                    </div>
                    <div class="zForm-wrap-checkbox-2">
                        <input type="radio" name="ticketReply" class="form-check-input ticket-status-change status{{TICKET_STATUS_CLOSED}}" {{$ticketDetails->status == TICKET_STATUS_CLOSED?'checked':''}} data-ticket="{{encrypt($ticketDetails->id)}}" id="closed" value="{{TICKET_STATUS_CLOSED}}" data-status="{{$ticketDetails->status}}"/>
                        <label for="closed">{{__("Closed")}}</label>
                    </div>
                </div>
            </div> --}}
            <input type="hidden" value="{{encrypt($ticketDetails->id)}}" name="ticket_id">
            <textarea id="ticketReply" class="form-control zForm-control zForm-control-textarea-2 min-h-175" placeholder="{{__("Write Reply here")}}...." name="conversation_text"></textarea>
        </div>
        <div class="pb-25">
            <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload Image  (PDF, DOCX, PNG)")}} </p>
            <div class="file-upload-one">
                <label for="mAttachment">
                    <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                    <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                </label>
                    <input type="file" name="file[]" id="mAttachment" class="invisible position-absolute" multiple="" accept=".pdf,.docx,.png"/>
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
            <button type="submit" class="py-10 px-26 bg-main-color border-0 bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__("Send Message")}}</button>
        {{-- @endif --}}
    </form>
</div>
{{-- <input type="hidden" id="statusChangeRoute" value="{{route('user.ticket.status.change')}}"> --}}
