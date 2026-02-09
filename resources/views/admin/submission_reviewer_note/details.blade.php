@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15 overflow-x-hidden">
        <!--  -->
        <div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-20">
            <!--  -->
            <h4 class="fs-18 fw-600 lh-22 text-title-black"></h4>
            <!--  -->
            <div class="d-flex flex-wrap g-10">
                <a href="{{route('admin.submission-reviewer-notes.edit', encrypt($ticketDetails->id))}}" class="py-10 px-26 d-flex align-items-center g-12 fs-15 fw-600 lh-25 text-white bg-main-color bd-one bd-c-main-color bd-ra-8">
                    <div class="d-flex">
                        <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z" fill="white" />
                        </svg>
                    </div>
                    {{__("Edit")}}
                </a>
                <button onclick="deleteItem('{{route('admin.submission-reviewer-notes.delete', encrypt($ticketDetails->id))}}', 'no','{{route('admin.client-orders.task-board.index', $clientOrder->id)}}')" class="py-10 px-26 d-flex align-items-center g-12 fs-15 fw-600 lh-25 text-red bg-transparent bd-one bd-c-red bd-ra-8">
                    <div class="d-flex">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z"
                                fill="#FF3B30"
                            />
                        </svg>
                    </div>
                    {{__("Delete")}}
                </button>
            </div>
        </div>
        <!--  -->
        <div class="row justify-content-center rg-20">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15 bg-white">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-22 text-title-black bd-b-one bd-c-stroke pb-17 mb-17">{{__("Reviewer Note Info")}}</h4>
                    <!--  -->
                    <ul class="zList-pb-18 bd-b-one bd-c-stroke pb-18 mb-18">
                        <li>
                            <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Note ID")}}</h5>
                            <p class="fs-12 fw-500 lh-15 text-para-text">{{$ticketDetails->submission_reviewer_note_id}}</p>
                        </li>
                        <li>
                            <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Client Email")}}</h5>
                            <p class="fs-12 fw-500 lh-15 text-para-text text-break">{{$clientOrder->client->email}}</p>
                        </li>
                        <li>
                            <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Submitted")}}</h5>
                            <p class="fs-12 fw-500 lh-15 text-para-text text-break">{{ $ticketDetails->created_at->format('l, F jS, Y (H:i)') }}</p>
                        </li>
                        <li>
                            <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Last Updated")}}</h5>
                            <p class="fs-12 fw-500 lh-15 text-para-text text-break">{{ $ticketDetails->updated_at->diffForHumans() }}</p>
                        </li>
                        {{-- <li>
                            <div class="d-flex align-items-center justify-content-between g-10">
                                <!--  -->
                                <div>
                                    <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Priority")}}</h5>
                                    @if($ticketDetails->priority == TICKET_PRIORITY_LOW)
                                        <p class="fs-12 fw-500 lh-15 text-para-text">{{__("Low")}}</p>
                                    @elseif($ticketDetails->priority == TICKET_PRIORITY_HIGH)
                                        <p class="fs-12 fw-500 lh-15 text-para-text">{{__("High")}}</p>
                                    @elseif($ticketDetails->priority == TICKET_PRIORITY_MEDIUM)
                                        <p class="fs-12 fw-500 lh-15 text-para-text">{{__("Medium")}}</p>
                                    @endif
                                </div>
                                <!--  -->
                                <div class="dropdown dropdown-one">
                                    <button class="dropdown-toggle p-0 bg-transparent w-30 h-30 bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center text-para-text fs-10" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-angle-down"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdownItem-two">
                                        <li>
                                            <a href="{{route('admin.ticket.priority-change',[ encrypt($ticketDetails->id), TICKET_PRIORITY_HIGH])}}"><p class="fs-14 fw-400 lh-17 text-para-text">{{__("High")}}</p></a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.ticket.priority-change',[ encrypt($ticketDetails->id), TICKET_PRIORITY_MEDIUM])}}"><p class="fs-14 fw-400 lh-17 text-para-text">{{__("Medium")}}</p></a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.ticket.priority-change', [ encrypt($ticketDetails->id), TICKET_PRIORITY_LOW])}}"><p class="fs-14 fw-400 lh-17 text-para-text">{{__("Low")}}</p></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li> --}}
                    </ul>
                    <!--  -->
                    {{-- <div class="">
                        <h5 class="fs-14 fw-500 lh-17 text-title-black pb-8">{{__("Assign More")}}</h5>
                        <div class="dropdown dropdown-two imageDropdown">
                            <button class="dropdown-toggle border-0 p-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="images">
                                    @if(count($ticketAssignee) > 0)
                                        @foreach($ticketAssignee as $assignee)
                                            <img src="{{getFileUrl(getUserData($assignee, 'image'))}}" title=" {{ getUserData($assignee, 'name') }} "/>
                                        @endforeach
                                        @if(count($ticketAssignee) > 2)
                                            <div class='iconPlus'><i class='fa-solid fa-plus'></i></div>
                                        @endif
                                    @else
                                       <p class="fs-12">{{__(" N/A")}}</p>
                                    @endif
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdownItem-three">
                                @forelse($teamMemberList as $key=>$member)
                                    <li>
                                        <div class="zForm-wrap-checkbox-2">
                                            <input type="checkbox" class="form-check-input assign-member" {{in_array($member->id, $ticketAssignee) ? 'checked' : ''}} id="userOne{{$key}}" value="{{$member->id}}" data-ticket="{{$ticketDetails->id}}"/>
                                            <label for="userOne">
                                                    <div class="d-flex align-items-center g-8">
                                                    <div class="flex-shrink-0 w-25 h-25 rounded-circle overflow-hidden"><img src="{{getFileUrl($member->image)}}" alt="{{$member->name}}" /></div>
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
                    </div> --}}
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="d-flex flex-column rg-15">
                    <!--  -->
                    <div class="bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15 bg-white">
                        <!--  -->
                        <div class="bd-b-one bd-c-stroke pb-14 mb-20">
                            <div class="d-flex justify-content-between align-items-center g-8">
                                <!-- Left Side: Client Info -->
                                <div class="d-flex align-items-center g-8">
                                    <div class="flex-shrink-0 w-30 h-30 rounded-circle overflow-hidden">
                                        <img src="{{ getFileUrl($ticketDetails->client_image) }}" alt="" />
                                    </div>
                                    <h4 class="fs-15 fw-500 lh-20 text-title-black">
                                        {{ $ticketDetails->client_name }} 
                                        {{-- <span class="text-para-text">({{ __("Client") }})</span> --}}
                                    </h4>
                                </div>

                                <!-- Right Side: Submitted Date -->
                                <div class="text-end">
                                    <p class="fs-14 text-title-black mb-0">
                                        {{ $ticketDetails->created_at->format('l, F jS, Y (H:i)') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div>
                            <h4 class="fs-14 fw-500 lh-20 text-title-black pb-8">{{__("Details")}}</h4>
                            <p class="fs-14 fw-400 lh-24 text-para-text text-justify">
                                {{$ticketDetails->description}}
                            </p>
                            @if($ticketDetails->file_id !=null && count(json_decode($ticketDetails->file_id)) > 0)
                                <ul class="d-flex flex-wrap g-10">
                                    @foreach(json_decode($ticketDetails->file_id) as $file)
                                        @if(in_array(getFileData($file, 'extension'), ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP']))
                                            <li>
                                                <div class="sf-popup-gallery">
                                                    <a href="{{ getFileUrl($file) }}">
                                                        <img src="{{ getFileUrl($file)  }}" alt=""/>
                                                    </a>
                                                </div>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ getFileUrl($file)  }}" target="_blank"
                                                   class="p-10 bd-one bd-c-stroke bd-ra-10 bg-body-bg d-inline-flex flex-column g-13">
                                                    <div class="d-flex"><img
                                                            src="{{ asset('assets/images/icon/files-1.svg')}}" alt=""/>
                                                    </div>
                                                    <p class="fs-14 fw-400 lh-17 text-title-black">{{getFileData($file, 'file_name')}}</p>
                                                    <div class="d-flex align-items-center">
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
                        </div>
                    </div>
                    @include('admin.submission_reviewer_note.conversation')
                </div>
            </div>
        </div>
    </div>
    {{-- <input type="hidden" id="assignMemberRoute" value="{{route('admin.ticket.assign-member')}}"> --}}
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/submission_reviewer_note.js') }}"></script>
    <script>
        $('#submissionReviewerReplyForm').on('submit', function() {
    
            const submitBtn = $('#submitBtn');
            const spinner = submitBtn.find('.spinner-border');
            const submitText = submitBtn.find('.submit-text');
            
            // Show spinner and disable button
            spinner.removeClass('d-none');
            submitText.text("{{ __('Sending...') }}");
            submitBtn.prop('disabled', true);
        });
        
        // Handle AJAX completion using custom event
        $(document).on('commonResponseForModal', function(event, response) {
            const submitBtn = $('#submitBtn');
            const spinner = submitBtn.find('.spinner-border');
            const submitText = submitBtn.find('.submit-text');
            
            // Hide spinner and re-enable button
            spinner.addClass('d-none');
            submitText.text("{{ __('Send Message') }}");
            submitBtn.prop('disabled', false);
        });
    </script>
@endpush

