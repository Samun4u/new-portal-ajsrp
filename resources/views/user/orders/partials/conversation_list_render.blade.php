@if($type == CONVERSATION_TYPE_CLIENT)
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
@endif
