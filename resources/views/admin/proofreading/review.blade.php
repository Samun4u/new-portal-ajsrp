@extends('admin.layouts.app')
@push('title')
    {{ __('Review Proof Version') }}
@endpush

@push('style')
    <style>
        .proof-review-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .proof-info-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .proof-viewer {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            min-height: 400px;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .btn-approve, .btn-request-corrections {
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease, opacity 0.3s ease;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover:not(:disabled) {
            background: #218838;
        }

        .btn-request-corrections {
            background: #ffc107;
            color: #212529;
        }

        .btn-request-corrections:hover:not(:disabled) {
            background: #e0a800;
        }

        .btn-approve:disabled, .btn-request-corrections:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .corrections-note {
            margin-top: 1rem;
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="proof-review-container">
        <h2 class="mb-4">{{ __('Review Proof Version') }} {{ $proof->version }}</h2>

        <div class="proof-info-card">
            <h5>{{ __('Article Information') }}</h5>
            <p><strong>{{ __('Title') }}:</strong> {{ $submission->article_title ?? __('N/A') }}</p>
            <p><strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}</p>
            @if($proof->notes)
                <p><strong>{{ __('Editor Notes') }}:</strong> {{ $proof->notes }}</p>
            @endif
        </div>

        <div class="proof-info-card">
            <h5>{{ __('Proof File') }}</h5>
            @if ($proof->file_id && $proof->file)
                <div class="proof-viewer">
                    <iframe src="{{ getFileUrl($proof->file_id) }}" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <div class="text-center">
                    <a href="{{ getFileUrl($proof->file_id) }}" target="_blank"
                        class="btn btn-primary">{{ __('Download Proof') }}</a>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ __('Proof file not found. Please contact the editor.') }}
                </div>
            @endif
        </div>

        @if ($proof->status === 'pending')
            <div class="proof-info-card">
                <form id="proofReviewForm" method="POST" action="{{ route('admin.proofreading.review.submit', encrypt($proof->id)) }}">
                    @csrf
                    <input type="hidden" name="action" id="reviewAction" value="">

                    <div class="mb-3">
                        <label class="form-label">{{ __('Review Notes') }} ({{ __('Optional') }})</label>
                        <textarea name="review_notes" class="form-control" rows="4" placeholder="{{ __('Add your review notes...') }}"></textarea>
                    </div>

                    <div class="corrections-note" id="correctionsNote" style="display: none;">
                        <label class="form-label">{{ __('Corrections Needed') }} <span class="text-danger">*</span></label>
                        <textarea name="corrections_requested" class="form-control" rows="5" placeholder="{{ __('Please specify what corrections are needed...') }}"></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="button" id="approveProofBtn" class="btn-approve" onclick="window.approveProof()">
                            <span class="btn-text">{{ __('Approve Proof') }}</span>
                            <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                        <button type="button" id="requestCorrectionsBtn" class="btn-request-corrections" onclick="window.requestCorrections()">
                            <span class="btn-text">{{ __('Request Corrections') }}</span>
                            <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div id="proofReviewError" class="alert alert-danger mt-3 d-none" role="alert"></div>
                </form>
            </div>
        @else
            <div class="alert alert-info">
                @if ($proof->status === 'approved')
                    {{ __('This proof has been approved.') }}
                    @if($proof->review_notes)
                        <div class="mt-2">
                            <strong>{{ __('Review Notes') }}:</strong>
                            <p>{{ $proof->review_notes }}</p>
                        </div>
                    @endif
                @elseif($proof->status === 'corrections_requested')
                    <strong>{{ __('Corrections Requested') }}:</strong><br>
                    {{ $proof->corrections_requested }}
                    @if($proof->review_notes)
                        <div class="mt-2">
                            <strong>{{ __('Review Notes') }}:</strong>
                            <p>{{ $proof->review_notes }}</p>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var isSubmitting = false;

                window.approveProof = function() {
                    if (isSubmitting) return;

                    if (confirm('{{ __('Are you sure you want to approve this proof?') }}')) {
                        var approveBtn = $('#approveProofBtn');
                        var requestBtn = $('#requestCorrectionsBtn');
                        var errorDiv = $('#proofReviewError');

                        isSubmitting = true;
                        approveBtn.prop('disabled', true);
                        approveBtn.find('.btn-text').addClass('d-none');
                        approveBtn.find('.btn-spinner').removeClass('d-none');
                        requestBtn.prop('disabled', true);
                        errorDiv.addClass('d-none').text('');

                        $('#reviewAction').val('approve');
                        $('#proofReviewForm').submit();
                    }
                };

                window.requestCorrections = function() {
                    if (isSubmitting) return;

                    var correctionsNote = $('#correctionsNote');
                    if (correctionsNote.is(':hidden')) {
                        correctionsNote.show();
                        correctionsNote.find('textarea').focus();
                        return;
                    }

                    var note = correctionsNote.find('textarea').val().trim();
                    if (!note) {
                        alert('{{ __('Please provide corrections note.') }}');
                        return;
                    }

                    if (confirm('{{ __('Are you sure you want to request corrections?') }}')) {
                        var approveBtn = $('#approveProofBtn');
                        var requestBtn = $('#requestCorrectionsBtn');
                        var errorDiv = $('#proofReviewError');

                        isSubmitting = true;
                        approveBtn.prop('disabled', true);
                        requestBtn.prop('disabled', true);
                        requestBtn.find('.btn-text').addClass('d-none');
                        requestBtn.find('.btn-spinner').removeClass('d-none');
                        errorDiv.addClass('d-none').text('');

                        $('#reviewAction').val('request_corrections');
                        $('#proofReviewForm').submit();
                    }
                };
            });
        </script>
    @endpush
@endsection


