@extends('admin.layouts.app')
@push('title')
    {{ __('Galley Management') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Galley Management') }}</h5>

        <div class="alert alert-info">
            <strong>{{ __('Submission') }}:</strong> {{ $submission->article_title ?? __('N/A') }}<br>
            <strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6>{{ __('Upload Galley Version') }}</h6>
            </div>
            <div class="card-body">
                <form id="uploadGalleyForm" method="POST"
                    action="{{ route('admin.galley.upload', encrypt($submission->id)) }}" enctype="multipart/form-data"
                    class="ajax" data-handler="handleGalleyUploadResponse">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Galley PDF') }} <span class="text-danger">*</span></label>
                        <input type="file" name="galley_file" class="form-control" accept=".pdf" required>
                        <small class="text-muted">{{ __('PDF format only. Max size: 20MB') }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }} ({{ __('Optional') }})</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" id="uploadGalleyBtn" class="btn btn-primary">
                        <span class="btn-text">{{ __('Upload Galley') }}</span>
                        <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="btn-loading-text d-none">{{ __('Uploading...') }}</span>
                    </button>
                    <div id="galleyUploadError" class="alert alert-danger mt-3 d-none" role="alert"></div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>{{ __('Galley Versions') }}</h6>
            </div>
            <div class="card-body">
                @if ($submission->galleyFiles && $submission->galleyFiles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Version') }}</th>
                                    <th>{{ __('Uploaded By') }}</th>
                                    <th>{{ __('Uploaded At') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submission->galleyFiles as $galley)
                                    <tr>
                                        <td>{{ $galley->version }}</td>
                                        <td>{{ $galley->uploadedBy->name ?? __('N/A') }}</td>
                                        <td>{{ $galley->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if ($galley->status === 'approved')
                                                <span class="badge bg-success">{{ __('Approved') }}</span>
                                            @elseif($galley->status === 'corrections_requested')
                                                <span class="badge bg-warning">{{ __('Corrections Requested') }}</span>
                                            @else
                                                <span class="badge bg-info">{{ __('Pending Review') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($galley->file)
                                                <a href="{{ getFileUrl($galley->file_id) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">{{ __('Download') }}</a>
                                            @else
                                                <span class="text-muted">{{ __('File not found') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">{{ __('No galley versions uploaded yet.') }}</p>
                @endif
            </div>
        </div>
    </div>

    @push('style')
        <style>
            .spinner-border-sm {
                width: 1rem;
                height: 1rem;
                border-width: 0.15em;
            }
            #galleyUploadError {
                margin-top: 1rem;
            }
        </style>
    @endpush

    @push('script')
        <script>
            $(document).ready(function() {
                // Prevent duplicate submissions
                var isSubmitting = false;

                $('#uploadGalleyForm').on('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    // Disable button and show loading state
                    var uploadBtn = $('#uploadGalleyBtn');
                    var btnText = uploadBtn.find('.btn-text');
                    var btnSpinner = uploadBtn.find('.btn-spinner');
                    var btnLoadingText = uploadBtn.find('.btn-loading-text');
                    var errorDiv = $('#galleyUploadError');

                    isSubmitting = true;
                    uploadBtn.prop('disabled', true);
                    btnText.addClass('d-none');
                    btnSpinner.removeClass('d-none');
                    btnLoadingText.removeClass('d-none');
                    errorDiv.addClass('d-none').text('');
                });

                window.handleGalleyUploadResponse = function(response) {
                    var uploadBtn = $('#uploadGalleyBtn');
                    var btnText = uploadBtn.find('.btn-text');
                    var btnSpinner = uploadBtn.find('.btn-spinner');
                    var btnLoadingText = uploadBtn.find('.btn-loading-text');
                    var errorDiv = $('#galleyUploadError');

                    if (response.status === true || response.success === true) {
                        toastr.success(response.message || '{{ __('Galley uploaded successfully') }}');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Re-enable button on error
                        isSubmitting = false;
                        uploadBtn.prop('disabled', false);
                        btnText.removeClass('d-none');
                        btnSpinner.addClass('d-none');
                        btnLoadingText.addClass('d-none');

                        // Display error message
                        var errorMessage = response.message || '{{ __('An error occurred') }}';
                        if (response.errors) {
                            var errorList = [];
                            $.each(response.errors, function(key, value) {
                                if (Array.isArray(value)) {
                                    errorList = errorList.concat(value);
                                } else {
                                    errorList.push(value);
                                }
                            });
                            errorMessage = errorList.join('<br>');
                        }
                        errorDiv.removeClass('d-none').html(errorMessage);
                        toastr.error(errorMessage);

                        // Scroll to error
                        $('html, body').animate({
                            scrollTop: errorDiv.offset().top - 100
                        }, 500);
                    }
                };
            });
        </script>
    @endpush
@endsection

