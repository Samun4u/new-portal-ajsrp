@extends('admin.layouts.app')
@push('title')
    {{ __('Proofreading Management') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Proofreading Management') }}
        </h5>

        <div class="alert alert-info">
            <strong>{{ __('Submission') }}:</strong> {{ $submission->article_title ?? __('N/A') }}
            <a href="{{ route('admin.client-orders.fullview', $submission->client_order->id) }}" target="_blank" class="ms-2 text-primary">
                {{ __('(View Submission Details)') }} <i class="fa fa-external-link-alt"></i>
            </a><br>
            <strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6>{{ __('Upload Proof Version') }}</h6>
            </div>
            <div class="card-body">
                <form id="uploadProofForm" method="POST"
                    action="{{ route('admin.proofreading.upload', encrypt($submission->id)) }}" enctype="multipart/form-data"
                    class="ajax" data-handler="handleProofUploadResponse" data-error-handler="handleProofUploadResponse">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Proof File') }} <span class="text-danger">*</span></label>
                        <input type="file" name="proof_file" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">{{ __('Accepted formats: PDF, DOC, DOCX. Max size: 10MB') }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Review Type') }} <span class="text-danger">*</span></label>
                        <select name="review_type" id="reviewType" class="form-control" required>
                            <option value="author">{{ __('Author Review') }}</option>
                            <option value="editor">{{ __('Editor/Admin Review') }}</option>
                            <option value="reviewer">{{ __('Assign Reviewer') }}</option>
                        </select>
                        <small class="text-muted">{{ __('Select who should review this proof') }}</small>
                    </div>
                    <div class="mb-3" id="reviewerSelection" style="display: none;">
                        <label class="form-label">{{ __('Select Reviewer') }} <span class="text-danger">*</span></label>
                        <select name="assigned_reviewer_id" class="form-control">
                            <option value="">{{ __('Select Reviewer') }}</option>
                            @if(isset($reviewers))
                                @foreach($reviewers as $reviewer)
                                    <option value="{{ $reviewer->id }}">{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }} ({{ __('Optional') }})</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Add any notes for the reviewer...') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="uploadProofBtn">
                        <span class="btn-text">{{ __('Upload Proof') }}</span>
                        <span class="btn-spinner d-none">
                            <i class="fa fa-spinner fa-spin me-2"></i>{{ __('Uploading...') }}
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>{{ __('Proof Versions') }}</h6>
            </div>
            <div class="card-body">
                @if ($submission->proofFiles && $submission->proofFiles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Version') }}</th>
                                    <th>{{ __('Uploaded By') }}</th>
                                    <th>{{ __('Uploaded At') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Review By') }}</th>
                                    <th>{{ __('Reviewed At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submission->proofFiles as $proof)
                                    <tr>
                                        <td>{{ $proof->version }}</td>
                                        <td>{{ $proof->uploadedBy->name ?? __('N/A') }}</td>
                                        <td>{{ $proof->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if ($proof->status === 'approved')
                                                <span class="badge bg-success">{{ __('Approved') }}</span>
                                            @elseif($proof->status === 'corrections_requested')
                                                <span class="badge bg-warning">{{ __('Corrections Requested') }}</span>
                                            @else
                                                <span class="badge bg-info">
                                                    {{ __('Pending Review') }}
                                                    @if($proof->review_type === 'editor')
                                                        <small class="d-block">({{ __('By Editor/Admin') }})</small>
                                                    @elseif($proof->review_type === 'reviewer' && $proof->assignedReviewer)
                                                        <small class="d-block">({{ __('By') }}: {{ $proof->assignedReviewer->name }})</small>
                                                    @else
                                                        <small class="d-block">({{ __('By Author') }})</small>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($proof->review_type === 'editor')
                                                <span class="badge bg-primary">{{ __('Editor/Admin') }}</span>
                                            @elseif($proof->review_type === 'reviewer' && $proof->assignedReviewer)
                                                <span class="badge bg-secondary">{{ $proof->assignedReviewer->name }}</span>
                                            @else
                                                <span class="badge bg-info">{{ __('Author') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $proof->reviewed_at ? $proof->reviewed_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($proof->file_id && $proof->file)
                                                    <a href="{{ getFileUrl($proof->file_id) }}" target="_blank"
                                                        class="btn btn-sm btn-primary">{{ __('Download') }}</a>
                                                @else
                                                    <span class="text-muted">{{ __('File not found') }}</span>
                                                @endif

                                                @if($proof->status === 'pending')
                                                    @if($proof->review_type === 'editor' || (in_array(auth()->user()->role, ['admin', 'editor'])))
                                                        <a href="{{ route('admin.proofreading.review', encrypt($proof->id)) }}"
                                                            class="btn btn-sm btn-success">{{ __('Review') }}</a>
                                                    @elseif($proof->review_type === 'reviewer' && !$proof->assigned_reviewer_id)
                                                        <button class="btn btn-sm btn-warning"
                                                            onclick="assignReviewer('{{ encrypt($proof->id) }}')">{{ __('Assign Reviewer') }}</button>
                                                    @elseif($proof->review_type === 'reviewer' && $proof->assigned_reviewer_id && $proof->assigned_reviewer_id === auth()->id())
                                                        <a href="{{ route('admin.proofreading.review', encrypt($proof->id)) }}"
                                                            class="btn btn-sm btn-success">{{ __('Review') }}</a>
                                                    @endif
                                                @endif

                                                @if ($proof->corrections_requested)
                                                    <button class="btn btn-sm btn-info"
                                                        onclick="showCorrections('{{ $proof->corrections_requested }}')">{{ __('View Corrections') }}</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">{{ __('No proof versions uploaded yet.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="correctionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Requested Corrections') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="correctionsText"></p>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                const uploadForm = $('#uploadProofForm');
                const uploadBtn = $('#uploadProofBtn');
                const btnText = uploadBtn.find('.btn-text');
                const btnSpinner = uploadBtn.find('.btn-spinner');

                // Disable button and show spinner on form submit (before AJAX handler)
                // Use 'submit' event with higher priority to run before common.js handler
                uploadForm.on('submit', function(e) {
                    // Check if form is valid
                    if (uploadForm[0].checkValidity()) {
                        uploadBtn.prop('disabled', true);
                        btnText.addClass('d-none');
                        btnSpinner.removeClass('d-none');
                    }
                });

                // Also handle button click to disable immediately
                uploadBtn.on('click', function() {
                    if (uploadForm[0].checkValidity()) {
                        // Small delay to ensure form validation completes
                        setTimeout(function() {
                            uploadBtn.prop('disabled', true);
                            btnText.addClass('d-none');
                            btnSpinner.removeClass('d-none');
                        }, 100);
                    }
                });
            });

            // Handle both success and error responses
            window.handleProofUploadResponse = function(response, textStatus, xhr) {
                const uploadBtn = $('#uploadProofBtn');
                const btnText = uploadBtn.find('.btn-text');
                const btnSpinner = uploadBtn.find('.btn-spinner');

                // Check if this is an HTTP error (from jQuery error callback)
                // When jQuery calls error handler, response is the jqXHR object
                if (textStatus === 'error' || (xhr && xhr.status >= 400) || (response && response.status >= 400)) {
                    let errorMessage = '{{ __('An error occurred while uploading proof.') }}';

                    // Try to extract error message from various response formats
                    if (response && response.responseJSON && response.responseJSON.message) {
                        errorMessage = response.responseJSON.message;
                    } else if (response && response.message) {
                        errorMessage = response.message;
                    } else if (typeof response === 'string') {
                        errorMessage = response;
                    }

                    alert(errorMessage);

                    // Re-enable button on error
                    uploadBtn.prop('disabled', false);
                    btnText.removeClass('d-none');
                    btnSpinner.addClass('d-none');
                    return;
                }

                // Handle success response (status: true)
                if (response && (response.status === true || response.success === true)) {
                    alert(response.message || '{{ __('Proof uploaded successfully') }}');
                    window.location.reload();
                } else if (response && response.status === false) {
                    // Handle error response (status: false from Laravel ResponseTrait)
                    let errorMessage = response.message || '{{ __('An error occurred while uploading proof.') }}';

                    alert(errorMessage);

                    // Re-enable button on error
                    uploadBtn.prop('disabled', false);
                    btnText.removeClass('d-none');
                    btnSpinner.addClass('d-none');
                } else {
                    // Fallback for unexpected response format
                    alert('{{ __('An error occurred while uploading proof.') }}');
                    uploadBtn.prop('disabled', false);
                    btnText.removeClass('d-none');
                    btnSpinner.addClass('d-none');
                }
            };

            function showCorrections(text) {
                document.getElementById('correctionsText').textContent = text;
                new bootstrap.Modal(document.getElementById('correctionsModal')).show();
            }

            function assignReviewer(proofId) {
                $('#assignProofId').val(proofId);
                // The form action will be set dynamically via AJAX, but we need the encrypted ID
                // We'll handle this in the form submission
                new bootstrap.Modal(document.getElementById('assignReviewerModal')).show();
            }

            // Handle assign reviewer form submission
            $('#assignReviewerForm').on('submit', function(e) {
                e.preventDefault();
                const proofId = $('#assignProofId').val();
                const reviewerId = $(this).find('select[name="assigned_reviewer_id"]').val();

                if (!reviewerId) {
                    alert('{{ __('Please select a reviewer') }}');
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.proofreading.assign-reviewer', '') }}/' + proofId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        assigned_reviewer_id: reviewerId
                    },
                    success: function(response) {
                        if (response.status === true || response.success === true) {
                            alert(response.message || '{{ __('Reviewer assigned successfully') }}');
                            $('#assignReviewerModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message || '{{ __('An error occurred') }}');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = '{{ __('An error occurred') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            window.handleReviewerAssignment = function(response) {
                if (response.status === true || response.success === true) {
                    alert(response.message || '{{ __('Reviewer assigned successfully') }}');
                    $('#assignReviewerModal').modal('hide');
                    window.location.reload();
                } else {
                    alert(response.message || '{{ __('An error occurred') }}');
                }
            };
        </script>
    @endpush
@endsection



