@extends('user.layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <style>
        .revision-wrapper {
            display: grid;
            gap: 20px;
        }
        .revision-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .revision-header {
            border-left: 4px solid #4f46e5;
            padding: 16px 20px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(59, 130, 246, 0.12));
        }
        .revision-header h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #1e293b;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 999px;
            background: #eef2ff;
            color: #312e81;
            font-weight: 600;
        }
        .form-section-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 12px;
            color: #0f172a;
        }
        .file-upload-card {
            border: 1px dashed #cbd5f5;
            border-radius: 12px;
            padding: 18px;
            background: #f8fafc;
            margin-bottom: 16px;
        }
        .file-upload-card:hover {
            border-color: #94a3b8;
        }
        .file-upload-card label {
            cursor: pointer;
        }
        .review-feedback-card {
            border-left: 4px solid #f59e0b;
            padding-left: 16px;
            margin-bottom: 18px;
        }
        .review-feedback-card h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .review-feedback-card p {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }
        .revision-history-item + .revision-history-item {
            border-top: 1px solid #e2e8f0;
            padding-top: 14px;
            margin-top: 14px;
        }
        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }
        .attachment-link:hover {
            text-decoration: underline;
        }
        .rating-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 12px;
            background: #e2e8f0;
            font-size: 12px;
            margin: 4px 4px 0 0;
        }
        .alert-custom {
            border-radius: 12px;
            padding: 12px 16px;
        }
    </style>
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="revision-header mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h2>{{ __('Submit Revised Manuscript') }}</h2>
                    <p class="mb-0 text-para-text">
                        {{ __('Provide your updated manuscript and address reviewer feedback to move forward in the publication process.') }}
                    </p>
                </div>
                <span class="status-pill">
                    <i class="fa-solid fa-diagram-project"></i>
                    {{ __('Current status:') }} {{ $statusLabel }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-custom">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-custom">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ __('Please review the highlighted issues below.') }}
                <ul class="mt-2 mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="revision-wrapper">
            <div class="row rg-20">
                <div class="col-xl-8">
                    <div class="revision-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="form-section-title mb-0">{{ __('Revision package') }}</h3>
                            <span class="badge bg-light text-dark">{{ __('Version') }} {{ $nextVersion }}</span>
                        </div>

                        <form action="{{ route('user.orders.revision.submit', $clientOrder->order_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="response_summary" class="form-label fw-semibold">{{ __('Response to reviewers') }}</label>
                                <textarea id="response_summary" name="response_summary" rows="5" class="form-control"
                                    placeholder="{{ __('Provide a structured summary of the revisions you have made. You can also upload a detailed response document below.') }}">{{ old('response_summary') }}</textarea>
                            </div>

                            <div class="file-upload-card">
                                <label for="manuscript_file" class="form-label fw-semibold d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ __('Revised manuscript') }}</span>
                                    <span class="badge bg-danger">{{ __('Required') }}</span>
                                </label>
                                <input class="form-control" type="file" name="manuscript_file" id="manuscript_file" accept=".doc,.docx,.pdf" required>
                                <small class="text-para-text d-block mt-2">
                                    {{ __('Upload the clean version of your manuscript (.doc, .docx, .pdf). Maximum size 50MB.') }}
                                </small>
                            </div>

                            <div class="file-upload-card">
                                <label for="response_file" class="form-label fw-semibold">{{ __('Response letter (optional)') }}</label>
                                <input class="form-control" type="file" name="response_file" id="response_file" accept=".doc,.docx,.pdf">
                                <small class="text-para-text d-block mt-2">
                                    {{ __('Attach a point-by-point response document if available.') }}
                                </small>
                            </div>

                            <div class="file-upload-card">
                                <label for="attachments" class="form-label fw-semibold">{{ __('Additional attachments') }}</label>
                                <input class="form-control" type="file" name="attachments[]" id="attachments" multiple>
                                <small class="text-para-text d-block mt-2">
                                    {{ __('Optional figures, datasets, or supporting files. You can select multiple files (Max 50MB each).') }}
                                </small>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('user.orders.details', encrypt($clientOrder->id)) }}" class="btn btn-light">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-regular fa-paper-plane me-1"></i>{{ __('Submit revision') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="revision-card mb-20">
                        <h3 class="form-section-title">{{ __('Reviewer feedback') }}</h3>
                        @forelse($reviews as $review)
                            <div class="review-feedback-card">
                                <h4>
                                    {{ $review->reviewer->name ?? __('Anonymous reviewer') }}
                                    <span class="badge {{ $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED ? 'bg-success' : 'bg-secondary' }} ms-2">
                                        {{ ucwords(str_replace('_', ' ', $review->status)) }}
                                    </span>
                                </h4>
                                @if(!empty($review->overall_recommendation))
                                    <div class="text-para-text fs-12 mb-2">
                                        {{ __('Recommendation:') }} <strong>{{ ucwords(str_replace('_', ' ', $review->overall_recommendation)) }}</strong>
                                    </div>
                                @endif
                                <div class="mb-2">
                                    @foreach([
                                        'rating_originality' => __('Originality'),
                                        'rating_methodology' => __('Methodology'),
                                        'rating_results' => __('Results'),
                                        'rating_clarity' => __('Clarity'),
                                        'rating_significance' => __('Impact'),
                                    ] as $field => $label)
                                        @if(!is_null($review->{$field}))
                                            <span class="rating-chip">
                                                {{ $label }}: {{ $review->{$field} }}/5
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                                @if(!empty($review->comment_for_authors))
                                    <p class="mt-3"><strong>{{ __('Comments for authors') }}:</strong><br>{{ $review->comment_for_authors }}</p>
                                @endif
                                @if(!empty($review->comment_strengths))
                                    <p class="mb-2"><strong>{{ __('Strengths') }}:</strong><br>{{ $review->comment_strengths }}</p>
                                @endif
                                @if(!empty($review->comment_weaknesses))
                                    <p class="mb-0"><strong>{{ __('Areas to improve') }}:</strong><br>{{ $review->comment_weaknesses }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-para-text mb-0">{{ __('Reviewer feedback will appear here once available.') }}</p>
                        @endforelse
                    </div>

                    <div class="revision-card">
                        <h3 class="form-section-title">{{ __('Revision history') }}</h3>
                        @forelse($revisions as $revision)
                            <div class="revision-history-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ __('Version') }} {{ $revision->version }}</strong>
                                    <span class="text-para-text fs-12">{{ optional($revision->created_at)->format('M d, Y H:i') }}</span>
                                </div>
                                @if(!empty($revision->response_summary))
                                    <p class="text-para-text mt-2">{{ $revision->response_summary }}</p>
                                @endif
                                <div class="d-flex flex-column gap-1 mt-2">
                                    <a href="{{ getFileUrl($revision->manuscript_file_id) }}" target="_blank" class="attachment-link">
                                        <i class="fa-regular fa-file-lines"></i>{{ __('Revised manuscript') }}
                                    </a>
                                    @if($revision->response_file_id)
                                        <a href="{{ getFileUrl($revision->response_file_id) }}" target="_blank" class="attachment-link">
                                            <i class="fa-regular fa-file-lines"></i>{{ __('Response letter') }}
                                        </a>
                                    @endif
                                    @foreach($revision->attachments as $attachment)
                                        <a href="{{ getFileUrl($attachment->file_id) }}" target="_blank" class="attachment-link">
                                            <i class="fa-regular fa-paperclip"></i>{{ $attachment->label ?? __('Attachment') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-para-text mb-0">{{ __('You have not submitted any revisions yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        window.revisionTranslations = {
            filesSelected: "{{ __('files selected') }}"
        };
    </script>
    <script src="{{ asset('user/custom/js/revision-submission.js') }}"></script>
@endpush

