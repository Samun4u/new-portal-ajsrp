@extends('admin.layouts.app')
@push('title')
    {{ __('Review Final Metadata') }}
@endpush

@push('style')
    <style>
        .metadata-review-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .review-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .metadata-field {
            margin-bottom: 1.5rem;
        }

        .field-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .field-value {
            color: #212529;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            min-height: 40px;
        }

        .author-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .author-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }

        .btn-approve {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-request-corrections {
            background: #ffc107;
            color: #212529;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-request-corrections:hover {
            background: #e0a800;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .corrections-note {
            margin-top: 1rem;
        }

        .corrections-note textarea {
            width: 100%;
            min-height: 120px;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="metadata-review-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('Review Final Metadata') }}</h2>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.submissions.final-acceptance-certificates.index') }}"
                   class="btn btn-primary btn-sm"
                   style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 600;">
                    <i class="fa fa-list"></i> {{ __('Final Acceptance Certificates List') }}
                </a>
                <span class="status-badge status-{{ $submission->metadata_status === 'approved' ? 'approved' : 'pending' }}">
                    {{ $submission->metadata_status === 'approved' ? __('Approved') : __('Pending Review') }}
                </span>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>{{ __('Submission') }}:</strong> {{ $submission->article_title ?? __('N/A') }}<br>
            <strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}<br>
            <strong>{{ __('Submitted by Author') }}:</strong> {{ $submission->client_order->client->name ?? __('N/A') }}
        </div>

        @if ($finalMetadata)
            <!-- Article Information -->
            <div class="review-section">
                <h3 class="section-header">{{ __('Article Information') }}</h3>

                <div class="metadata-field">
                    <span class="field-label">{{ __('Final Article Title') }}</span>
                    <div class="field-value">{{ $finalMetadata->final_title ?? __('N/A') }}</div>
                </div>

                @if ($finalMetadata->short_title)
                    <div class="metadata-field">
                        <span class="field-label">{{ __('Short Title') }}</span>
                        <div class="field-value">{{ $finalMetadata->short_title }}</div>
                    </div>
                @endif

                <div class="metadata-field">
                    <span class="field-label">{{ __('Final Abstract') }}</span>
                    <div class="field-value" style="white-space: pre-wrap;">
                        {{ $finalMetadata->final_abstract ?? __('N/A') }}</div>
                </div>

                <div class="metadata-field">
                    <span class="field-label">{{ __('Keywords') }}</span>
                    <div class="field-value">{{ $finalMetadata->final_keywords ?? __('N/A') }}</div>
                </div>
            </div>

            <!-- Authors -->
            <div class="review-section">
                <h3 class="section-header">{{ __('Authors') }}</h3>
                @if ($submission->authors && $submission->authors->count() > 0)
                    @foreach ($submission->authors as $index => $author)
                        <div class="author-card">
                            <div class="author-name">{{ __('Author') }} {{ $index + 1 }}: {{ $author->first_name }}
                                {{ $author->last_name }}</div>
                            <div class="metadata-field">
                                <strong>{{ __('Email') }}:</strong> {{ $author->email }}
                            </div>
                            @if ($author->orcid)
                                <div class="metadata-field">
                                    <strong>{{ __('ORCID') }}:</strong> {{ $author->orcid }}
                                </div>
                            @endif
                            <div class="metadata-field">
                                <strong>{{ __('Affiliation') }}:</strong>
                                <div class="field-value" style="margin-top: 0.5rem;">
                                    {{ is_array($author->affiliation) ? implode(', ', $author->affiliation) : $author->affiliation }}
                                </div>
                            </div>
                            @if ($author->nationality)
                                <div class="metadata-field">
                                    <strong>{{ __('Country') }}:</strong> {{ $author->nationality }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('No authors found') }}</p>
                @endif
            </div>

            <!-- Additional Information -->
            <div class="review-section">
                <h3 class="section-header">{{ __('Additional Information') }}</h3>

                @if ($finalMetadata->funding_statement)
                    <div class="metadata-field">
                        <span class="field-label">{{ __('Funding Statement') }}</span>
                        <div class="field-value" style="white-space: pre-wrap;">{{ $finalMetadata->funding_statement }}
                        </div>
                    </div>
                @endif

                @if ($finalMetadata->conflict_statement)
                    <div class="metadata-field">
                        <span class="field-label">{{ __('Conflict of Interest Statement') }}</span>
                        <div class="field-value" style="white-space: pre-wrap;">{{ $finalMetadata->conflict_statement }}
                        </div>
                    </div>
                @endif

                @if ($finalMetadata->acknowledgements)
                    <div class="metadata-field">
                        <span class="field-label">{{ __('Acknowledgements') }}</span>
                        <div class="field-value" style="white-space: pre-wrap;">{{ $finalMetadata->acknowledgements }}
                        </div>
                    </div>
                @endif

                @if ($finalMetadata->notes_for_layout)
                    <div class="metadata-field">
                        <span class="field-label">{{ __('Notes for Layout') }}</span>
                        <div class="field-value" style="white-space: pre-wrap;">{{ $finalMetadata->notes_for_layout }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if ($submission->metadata_status === 'pending_editor_review')
                <div class="review-section">
                    <form id="metadataReviewForm" method="POST"
                        action="{{ route('admin.submissions.final-metadata.review-action') }}">
                        @csrf
                        <input type="hidden" name="submission_id" value="{{ encrypt($submission->id) }}">
                        <input type="hidden" name="action" id="reviewAction" value="">

                        <div class="corrections-note" id="correctionsNote" style="display: none;">
                            <label class="field-label">{{ __('Corrections Requested') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="corrections_note" id="correctionsNoteTextarea" class="form-control"
                                placeholder="{{ __('Please specify what corrections are needed...') }}"></textarea>
                        </div>

                        <div class="action-buttons">
                            <button type="button" id="approveBtn" class="btn-approve" onclick="approveMetadata()">
                                <span class="btn-text">{{ __('Approve Metadata') }}</span>
                                <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                            <button type="button" id="requestCorrectionsBtn" class="btn-request-corrections" onclick="requestCorrections()">
                                <span class="btn-text">{{ __('Request Corrections') }}</span>
                                <span class="btn-spinner d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div id="metadataReviewError" class="alert alert-danger mt-3 d-none" role="alert"></div>
                    </form>
                </div>
            @elseif($submission->metadata_status === 'approved')
                <div class="alert alert-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{ __('This metadata has been approved.') }}
                            @if($submission->acceptance_certificate_file_id)
                                <br><small>{{ __('Final acceptance certificate has been generated.') }}</small>
                            @endif
                        </div>
                        @if($submission->acceptance_certificate_file_id)
                            <a href="{{ route('admin.submissions.final-acceptance-certificate.download', encrypt($submission->id)) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-download"></i> {{ __('Download Certificate') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-warning">
                {{ __('Final metadata has not been submitted yet.') }}
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            var isSubmitting = false;

            // Make functions globally accessible
            window.approveMetadata = function() {
                if (isSubmitting) return;

                if (confirm('{{ __('Are you sure you want to approve this metadata?') }}')) {
                    var approveBtn = $('#approveBtn');
                    var requestBtn = $('#requestCorrectionsBtn');
                    var errorDiv = $('#metadataReviewError');

                    isSubmitting = true;
                    approveBtn.prop('disabled', true);
                    approveBtn.find('.btn-text').addClass('d-none');
                    approveBtn.find('.btn-spinner').removeClass('d-none');
                    requestBtn.prop('disabled', true);
                    errorDiv.addClass('d-none').text('');

                    $('#reviewAction').val('approve');
                    $('#metadataReviewForm').submit();
                }
            };

            window.requestCorrections = function() {
                if (isSubmitting) return;

                const correctionsNote = $('#correctionsNote');
                const correctionsTextarea = $('#correctionsNoteTextarea');

                if (correctionsNote.is(':hidden')) {
                    correctionsNote.show();
                    correctionsTextarea.focus();
                    return;
                }

                const note = correctionsTextarea.val().trim();
                if (!note) {
                    alert('{{ __('Please provide corrections note.') }}');
                    return;
                }

                if (confirm('{{ __('Are you sure you want to request corrections?') }}')) {
                    var approveBtn = $('#approveBtn');
                    var requestBtn = $('#requestCorrectionsBtn');
                    var errorDiv = $('#metadataReviewError');

                    isSubmitting = true;
                    approveBtn.prop('disabled', true);
                    requestBtn.prop('disabled', true);
                    requestBtn.find('.btn-text').addClass('d-none');
                    requestBtn.find('.btn-spinner').removeClass('d-none');
                    errorDiv.addClass('d-none').text('');

                    $('#reviewAction').val('request_corrections');
                    $('#metadataReviewForm').submit();
                }
            };
        });
    </script>
@endpush
