@extends('admin.layouts.app')
@push('title')
{{$pageTitle}}
@endpush
@section('content')
<!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <!-- Tab - Create -->
    <div
        class="d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center align-items-md-start flex-wrap g-10 table-pl">
        <!-- Left -->
        <ul class="nav nav-tabs zTab-reset zTab-four flex-wrap pl-sm-20" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active bg-transparent orderStatusTab" id="allOrder-tab" data-bs-toggle="tab"
                    data-bs-target="#allOrder-tab-pane" type="button" role="tab" aria-controls="allOrder-tab-pane"
                    aria-selected="true" data-status="all">{{__("Template List")}}</button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent orderStatusTab" id="pendingOrder-tab" data-bs-toggle="tab"
                    data-bs-target="#pendingOrder-tab-pane" type="button" role="tab"
                    aria-controls="pendingOrder-tab-pane" aria-selected="false"
                    data-status="sent_email">{{__("Send Email")}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent orderStatusTab" id="workingOrder-tab" data-bs-toggle="tab"
                    data-bs-target="#workingOrder-tab-pane" type="button" role="tab"
                    aria-controls="workingOrder-tab-pane" aria-selected="false"
                    data-status="sent_email_history">{{__("Email History")}}</button>
            </li>
        </ul>
       {{--  <a href="{{route('admin.client-orders.add')}}"
            class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">+ {{__("Add Orders")}}</a> --}}
    </div>
    <!--  -->
    <div class="tab-content" id="myTabContent">
        <!-- All Order -->
        <div class="tab-pane fade show active" id="allOrder-tab-pane" role="tabpanel" aria-labelledby="allOrder-tab"
            tabindex="0">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                <div class="d-flex justify-content-between align-items-center mb-30 flex-wrap g-15">
                    <h2 class="mb-0"></h2>

                    <!-- Add template button here when click it then open a modal -->
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                        {{ __("Add New Template") }}
                    </button>
                </div>

                <table class="table zTable zTable-last-item-right" id="orderTable-all">
                    <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Name')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Subject')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Created At')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- Pending Order -->
        <div class="tab-pane fade" id="pendingOrder-tab-pane" role="tabpanel" aria-labelledby="pendingOrder-tab"
             tabindex="0">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                <!-- Send email form -->
                <form id="sendEmailForm" class="ajax reset" method="POST" enctype="multipart/form-data" data-handler="commonResponse" action="{{ route('admin.send-email.send') }}">
                    @csrf
                    
                    <div class="row rg-20">
                        <div class="col-12">
                            <label for="to" class="zForm-label">{{ __('To (comma-separated):') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="to" name="to" placeholder="{{ __('To') }}" required>
                        </div>

                        <div class="col-12">
                            <label for="bcc" class="zForm-label">{{ __('BCC (comma-separated):') }} </label>
                            <input type="text" class="form-control zForm-control" id="bcc" name="bcc" placeholder="{{ __('BCC') }}">
                        </div>

                        <div class="col-12">
                            <label class="zForm-label">{{ __('Template:') }}</label>
                            <select class="sf-select-without-search" id="templateSelect" name="template_id">
                                <option value="">{{ __('Select a template') }}</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="sentEmailSubject" class="zForm-label">{{ __('Subject:') }}<span class="text-danger">*</span></label>
                            <input type="text" id="sentEmailSubject" name="subject" class="form-control zForm-control" placeholder="{{ __('Subject') }}" required>
                        </div>

                        <div class="col-12">
                            <label for="sentEmailBody" class="zForm-label">{{ __('Body:') }}<span class="text-danger">*</span> </label>
                            <textarea name="body" id="sentEmailBody" class="form-control zForm-control" placeholder="{{ __('Body') }}" ></textarea>
                        </div>
                    </div>

                    <button id="submitBtn" type="submit" class="m-0 fs-15 fw-500 lh-25 py-10 mt-25 px-26 text-white bg-primary bd-ra-12 border-0">
                        <span class="submit-text">{{__("Send")}}</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
        <!-- Done Pending Order -->
        <!-- Working Order -->
        <div class="tab-pane fade" id="workingOrder-tab-pane" role="tabpanel" aria-labelledby="workingOrder-tab"
            tabindex="0">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                <table class="table zTable zTable-last-item-right" id="orderTable-sent_email_history">
                    <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('To')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Subject')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Sent By')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Date')}}</div>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- Completed Order -->
    </div>
</div>
<input type="hidden" id="bulk-email-template-list-route" value="{{ route('admin.send-email.template.list') }}">
<input type="hidden" id="bulk-email-template-sent-history-route" value="{{ route('admin.send-email.history') }}">
<input type="hidden" id="bulk-email-template-edit-route" value="{{ route('admin.send-email.template.edit') }}">
<input type="hidden" id="bulk-email-template-details-route" value="{{ route('admin.send-email.template.details') }}">
<input type="hidden" id="bulk-email-template-get-route" value="{{ route('admin.send-email.get.email.template') }}">
<input type="hidden" id="bulk-email-send-history-route" value="{{ route('admin.send-email.history') }}">

<!-- add template modal -->
<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Add Email Template') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('admin.send-email.template.store') }}" method="POST"
                      data-handler="commonResponseForModal">
                    @csrf


                    <div class="row rg-20">
                        <div class="col-12">
                            <label for="name" class="zForm-label">{{ __('Name') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="name" name="name"
                                   placeholder="{{ __('Name') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="zForm-label">{{ __('Subject') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="subject" name="subject"
                                   placeholder="{{ __('Subject') }}" required>
                        </div>
                        <div class="col-12 mb-25">
                            <label for="emailBody" class="zForm-label">{{ __('Body') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="body" id="emailBody"
                                      placeholder="Body"></textarea>
                        </div>
                    </div>
                
                    <button type="submit"
                            class="m-0 fs-15 fw-500 lh-25 py-10 px-26 text-white bg-primary bd-ra-12 border-0">{{ __('Save') }}</button>

            </form>
        </div>
    </div>
</div>

<!-- edit template modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Edit Email Template') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('admin.send-email.template.store') }}" method="POST"
                      data-handler="commonResponseForModal">
                    @csrf

                    <input type="hidden" name="id">

                    <div class="row rg-20">
                        <div class="col-12">
                            <label for="name" class="zForm-label">{{ __('Name') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="name" name="name"
                                   placeholder="{{ __('Name') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="zForm-label">{{ __('Subject') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="subject" name="subject"
                                   placeholder="{{ __('Subject') }}" required>
                        </div>
                        <div class="col-12 mb-25">
                            <label for="emailBodyEdit" class="zForm-label">{{ __('Body') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control" name="body" id="emailBodyEdit"
                                      placeholder="Body"></textarea>
                        </div>
                    </div>
                
                    <button type="submit"
                            class="m-0 fs-15 fw-500 lh-25 py-10 px-26 text-white bg-primary bd-ra-12 border-0">{{ __('Update') }}</button>

            </form>
        </div>
    </div>
</div>

<!-- details template modal -->
<div class="modal fade" id="detailsTemplateModal" tabindex="-1" aria-labelledby="detailsTemplateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <div class="d-flex justify-content-between align-items-center pb-20  bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-24 text-title-black">{{ __('Details Email Template') }}</h5>
                    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p class="alert-success p-20 templateFields"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/bulk-email-sent.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/xji72nbks88sevgjk86fzuvy24rzgfv22qfpe5h65tw4tj70/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
     tinymce.init({
        selector: '#emailBody',
        plugins: 'link image code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
    });

    tinymce.init({
        selector: '#emailBodyEdit',
        plugins: 'link image code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
    });

    tinymce.init({
        selector: '#sentEmailBody',
        plugins: 'link image code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
    });

    function editTemplate(id) {
        $.ajax({
            url: $('#bulk-email-template-edit-route').val(),
            type: "GET",
            data: { id: id },
            success: function(response) {
                // Populate the form fields with the response data
                $('#editTemplateModal input[name="id"]').val(response.data.id);
                $('#editTemplateModal input[name="name"]').val(response.data.name);
                $('#editTemplateModal input[name="subject"]').val(response.data.subject);
                tinymce.get('emailBodyEdit').setContent(response.data.body); // Set content in TinyMCE editor

                // Show the modal
                $('#editTemplateModal').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("An error occurred while fetching the template data.");
            }
        });
    }

    function viewTemplate(id) {
        $.ajax({
            url: $('#bulk-email-template-details-route').val(),
            type: "GET",
            data: { id: id },
            success: function(response) {
                // Populate the details modal with the response data
                let detailsHtml = `
                    <strong>Name:</strong> ${response.data.name}<br>
                    <strong>Subject:</strong> ${response.data.subject}<br>
                    <strong>Body:</strong><br> ${response.data.body}
                `;
                $('#detailsTemplateModal .templateFields').html(detailsHtml);

                // Show the modal
                $('#detailsTemplateModal').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("An error occurred while fetching the template details.");
            }
        });
    }

    // Template select change event
    $('#templateSelect').on('change', function () {
        let templateId = $(this).val();

        if (templateId) {
            $.ajax({
                url: $('#bulk-email-template-get-route').val(),
                type: 'GET',
                data: {id: templateId},
                success: function (template) {
                    $('#sentEmailSubject').val(template.data.subject);
                    tinymce.get('sentEmailBody').setContent(template.data.body);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert("Failed to load template.");
                }
            });
        }
    });

    // Handle form submission
    $('#sendEmailForm').on('submit', function() {
        const submitBtn = $('#submitBtn');
        const spinner = submitBtn.find('.spinner-border');
        const submitText = submitBtn.find('.submit-text');
        
        // Show spinner and disable button
        spinner.removeClass('d-none');
        submitText.text("{{ __('Sending...') }}");
        submitBtn.prop('disabled', true);
    });

    //Handle AJAX completion using custom event
    const originalCommonResponse = window.commonResponse;
    window.commonResponse = function(response) {
        // work as before
        originalCommonResponse(response);

        const submitBtn = $('#submitBtn');
        const spinner = submitBtn.find('.spinner-border');
        const submitText = submitBtn.find('.submit-text');
        
        // Hide spinner and re-enable button
        spinner.addClass('d-none');
        submitText.text("{{ __('Send') }}");
        submitBtn.prop('disabled', false);
    }

</script>
@endpush
