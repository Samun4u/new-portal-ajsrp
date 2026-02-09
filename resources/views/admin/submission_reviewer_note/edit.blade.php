@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!-- Order Info Section Added - Left: Order ID, Right: Paper Title -->
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <div class="d-flex align-items-center gap-8">
                        <span class="fs-16 fw-600 text-para-text">{{ __('Paper Title') }}:&nbsp; </span>
                        <span class="fs-16 fw-700 text-title-black"> {{ $clientOrder->client_order_submission->article_title }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-8">
                        <span class="fs-16 fw-600 text-para-text">{{ __('Order ID') }}:&nbsp; </span>
                        <span class="fs-16 fw-700 text-title-black text-end"> {{ $clientOrder->order_id }}</span>
                    </div>
                </div>
                <!-- End Order Info -->
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Edit Reviewer Note")}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <form id="reviewerNoteForm" class="ajax reset" action="{{route('admin.submission-reviewer-notes.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseWithPageLoad">
                    @csrf
                    <input type="hidden" name="id" value="{{$ticketDetails->id}}">
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                    <div class="max-w-713 m-auto py-sm-52 py-15">
                        <!--  -->
                        <div class="row rg-20">
                            <input type="hidden" name="order_id" value="{{$clientOrder->order_id}}">
                            {{-- <div class="col-12">
                                <label for="addTicketFieldSelectOrder" class="zForm-label">{{__("Select
                                    Order")}}</label>
                                <select class="sf-select-two" name="order_id">
                                    <option value="">{{__("Select")}}</option>
                                    @foreach($clientOrderList as $order)
                                    <option value="{{$order->order_id}}">{{$order->order_id.'
                                        ('.getEmailByUserId($order->client_id).')'}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <input type="hidden" name="ticket_title" value="ticket title">
                            {{-- <div class="col-12">
                                <label for="title" class="zForm-label">{{__("Title")}}</label>
                                <input type="text" name="ticket_title" id="title" class="form-control zForm-control"
                                    placeholder="{{__(" Title")}}" />
                            </div> --}}
                            <div class="col-12">
                                <label for="addTicketFieldDescription" class="zForm-label">{{__("Description")}}</label>
                                <textarea id="addTicketFieldDescription" name="description"
                                    class="form-control zForm-control min-h-175" placeholder="{{__(" Write description here")}}....">{{ old('description', $ticketDetails->description ?? '') }}</textarea>
                            </div>
                            {{-- <div class="col-12">
                                <label for="addTicketFieldAssignMember" class="zForm-label">{{__("Assign to a team
                                    member")}}</label>
                                <select class="sf-select-two" name="assign_member[]" multiple>
                                    @foreach($teamMemberList as $member)
                                    <option value="{{$member->id}}">{{$member->email}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {{-- <div class="col-12">
                                <label for="addTicketFieldPriority" class="zForm-label">{{__("Priority")}}</label>
                                <select class="sf-select-two" name="priority">
                                    <option value="{{TICKET_PRIORITY_LOW}}">{{__("Low")}}</option>
                                    <option value="{{TICKET_PRIORITY_MEDIUM}}">{{__("Medium")}}</option>
                                    <option value="{{TICKET_PRIORITY_HIGH}}">{{__("High")}}</option>
                                </select>
                            </div> --}} 
                            <div class="col-12">
                                <label for="addReviewerNoteFieldReviewer" class="zForm-label">{{__("Reviewer")}}</label>
                                <select class="sf-select-two" name="reviewer_id">
                                    <option value="">{{__("Select")}}</option>
                                    @foreach($reviewerList as $reviewer)
                                    <option value="{{$reviewer->id}}" @if($reviewer->id == $ticketDetails->client_id) selected @endif>{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="pb-25">
                                    <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload Image")}}
                                        {{__('(PDF, DOCX, PNG)')}}</p>
                                    <div class="file-upload-one">
                                        <label for="mAttachment">
                                            <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to
                                                upload")}}</p>
                                            <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                        </label>
                                        <input type="file" name="file[]" id="mAttachment"
                                            class="invisible position-absolute" multiple="" accept=".pdf,.docx,.png" />
                                    </div>
                                    <div id="files-area" class="">
                                        <span id="filesList">
                                            <span id="files-names"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button id="submitBtn" type="submit"
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white d-flex align-items-center justify-content-center">
                            <span class="submit-text">{{__("Save")}}</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                        <a href="{{ route('admin.client-orders.task-board.index', $clientOrder->id) }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/submission_reviewer_note.js') }}"></script>
    <script>
    (function ($) {
    "use strict";

        $(document).ready(function() {
            // Handle form submission
            $('#reviewerNoteForm').on('submit', function() {
                const submitBtn = $('#submitBtn');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');
                
                // Show spinner and disable button
                spinner.removeClass('d-none');
                submitText.text("{{ __('Saving...') }}");
                submitBtn.prop('disabled', true);
            });
            
            // Handle AJAX completion using custom event
            $(document).on('commonResponseWithPageLoad', function(event, response) {
                const submitBtn = $('#submitBtn');
                const spinner = submitBtn.find('.spinner-border');
                const submitText = submitBtn.find('.submit-text');
                
                // Hide spinner and re-enable button
                spinner.addClass('d-none');
                submitText.text("{{ __('Save') }}");
                submitBtn.prop('disabled', false);
            });
        });
        
    })(jQuery)
    </script>
@endpush

