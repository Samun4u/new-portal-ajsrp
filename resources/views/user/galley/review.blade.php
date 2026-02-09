@extends('user.layouts.app')
@push('title')
    {{ __('Review Galley Version') }}
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
            flex-wrap: wrap;
        }

        .btn-approve {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-approve:hover:not(:disabled) {
            background: #218838;
        }

        .btn-approve:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-request-corrections {
            background: #ffc107;
            color: #212529;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-request-corrections:hover:not(:disabled) {
            background: #e0a800;
        }

        .btn-request-corrections:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .corrections-note {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .corrections-note textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            resize: vertical;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        #galleyReviewError {
            margin-top: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="proof-review-container">
        <h2 class="mb-4">{{ __('Review Galley Version') }} {{ $galley->version }}</h2>

        <div class="proof-info-card">
            <h5>{{ __('Article Information') }}</h5>
            <p><strong>{{ __('Title') }}:</strong> {{ $submission->article_title ?? __('N/A') }}</p>
            <p><strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}</p>
        </div>

        <div class="proof-info-card">
            <h5>{{ __('Galley File') }}</h5>
            @if ($galley->file)
                <div class="proof-viewer">
                    <iframe src="{{ getFileUrl($galley->file_id) }}" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <div class="text-center">
                    <a href="{{ getFileUrl($galley->file_id) }}" target="_blank"
                        class="btn btn-primary">{{ __('Download Galley') }}</a>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ __('Galley file not found. Please contact the editor.') }}
                </div>
            @endif
        </div>

        @if ($galley->status === 'pending')
            <div class="proof-info-card">
                <form id="galleyReviewForm" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="reviewAction" value="">

                    <div class="corrections-note" id="correctionsNote" style="display: none;">
                        <label class="form-label">{{ __('Corrections Needed') }} <span class="text-danger">*</span></label>
                        <textarea name="corrections_note" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="button" id="approveGalleyBtn" class="btn-approve" onclick="approveGalley()">
                            <span class="btn-text">{{ __('Approve Galley') }}</span>
                            <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                        <button type="button" id="requestCorrectionsBtn" class="btn-request-corrections" onclick="requestCorrections()">
                            <span class="btn-text">{{ __('Request Corrections') }}</span>
                            <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div id="galleyReviewError" class="alert alert-danger mt-3 d-none" role="alert"></div>
                </form>
            </div>
        @else
            <div class="alert alert-info">
                @if ($galley->status === 'approved')
                    {{ __('This galley has been approved.') }}
                @elseif($galley->status === 'corrections_requested')
                    <strong>{{ __('Corrections Requested') }}:</strong><br>
                    {{ $galley->corrections_requested }}
                @endif
            </div>
        @endif
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var isSubmitting = false;
                const approveUrl = '{{ route('user.submission.galley.approve', encrypt($galley->id)) }}';
                const correctionsUrl = '{{ route('user.submission.galley.request-corrections', encrypt($galley->id)) }}';

                window.approveGalley = function() {
                    if (isSubmitting) return;

                    if (confirm('{{ __('Are you sure you want to approve this galley?') }}')) {
                        var approveBtn = $('#approveGalleyBtn');
                        var requestBtn = $('#requestCorrectionsBtn');
                        var errorDiv = $('#galleyReviewError');

                        isSubmitting = true;
                        approveBtn.prop('disabled', true);
                        approveBtn.find('.btn-text').addClass('d-none');
                        approveBtn.find('.btn-spinner').removeClass('d-none');
                        requestBtn.prop('disabled', true);
                        errorDiv.addClass('d-none').text('');

                        $('#galleyReviewForm').attr('action', approveUrl);
                        $('#galleyReviewForm').attr('method', 'POST');
                        $('#galleyReviewForm').submit();
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
                        var approveBtn = $('#approveGalleyBtn');
                        var requestBtn = $('#requestCorrectionsBtn');
                        var errorDiv = $('#galleyReviewError');

                        isSubmitting = true;
                        approveBtn.prop('disabled', true);
                        requestBtn.prop('disabled', true);
                        requestBtn.find('.btn-text').addClass('d-none');
                        requestBtn.find('.btn-spinner').removeClass('d-none');
                        errorDiv.addClass('d-none').text('');

                        $('#galleyReviewForm').attr('action', correctionsUrl);
                        $('#galleyReviewForm').attr('method', 'POST');
                        $('#galleyReviewForm').submit();
                    }
                };
            });
        </script>
    @endpush
@endsection



