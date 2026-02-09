@extends('user.layouts.app')
@push('title')
    {{ __('Final Metadata Form') }}
@endpush

@push('style')
    <style>
        .final-metadata-form {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .form-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .author-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .author-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .author-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .btn-remove-author {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-add-author {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .submit-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }

        .btn-submit {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover:not(:disabled) {
            background: #0056b3;
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: #6c757d;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="final-metadata-form">
        <div class="alert-info">
            <strong>{{ __('Important') }}:</strong>
            {{ __('Please review and confirm all information below. This will be used for the final acceptance certificate and publication.') }}
        </div>

        <form id="finalMetadataForm" action="{{ route('user.submission.final-metadata.store') }}" method="POST" class="ajax"
            data-handler="handleFinalMetadataResponse">
            @csrf
            <input type="hidden" name="submission_id" value="{{ encrypt($submission->id) }}">

            <!-- Article Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Article Information') }}</h3>

                <div class="form-group">
                    <label class="form-label">{{ __('Final Article Title') }} <span class="required">*</span></label>
                    <input type="text" name="final_title" class="form-control"
                        value="{{ $submission->article_title ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Short Title') }} ({{ __('Optional') }})</label>
                    <input type="text" name="short_title" class="form-control" maxlength="100"
                        placeholder="{{ __('Abbreviated title for headers') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Final Abstract') }} <span class="required">*</span></label>
                    <textarea name="final_abstract" class="form-control" rows="6" required>{{ $submission->article_abstract ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Keywords') }} <span class="required">*</span></label>
                    <input type="text" name="final_keywords" class="form-control"
                        value="{{ $submission->article_keywords ?? '' }}" required
                        placeholder="{{ __('Separate keywords with commas') }}">
                    <small
                        class="text-muted">{{ __('Example: machine learning, neural networks, artificial intelligence') }}</small>
                </div>
            </div>

            <!-- Authors -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Authors') }}</h3>
                <div id="authorsContainer">
                    @if ($submission->authors && $submission->authors->count() > 0)
                        @foreach ($submission->authors as $index => $author)
                            <div class="author-item" data-author-index="{{ $index }}">
                                <div class="author-header">
                                    <span class="author-title">{{ __('Author') }} {{ $index + 1 }}</span>
                                    @if ($index > 0)
                                        <button type="button" class="btn-remove-author"
                                            onclick="removeAuthor({{ $index }})">{{ __('Remove') }}</button>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('First Name') }} <span
                                                class="required">*</span></label>
                                        <input type="text" name="authors[{{ $index }}][first_name]"
                                            class="form-control" value="{{ $author->first_name }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('Last Name') }} <span
                                                class="required">*</span></label>
                                        <input type="text" name="authors[{{ $index }}][last_name]"
                                            class="form-control" value="{{ $author->last_name }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('Email') }} <span
                                                class="required">*</span></label>
                                        <input type="email" name="authors[{{ $index }}][email]"
                                            class="form-control" value="{{ $author->email }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('ORCID') }}</label>
                                        <input type="text" name="authors[{{ $index }}][orcid]"
                                            class="form-control" value="{{ $author->orcid }}"
                                            placeholder="0000-0000-0000-0000">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="form-label">{{ __('Affiliation') }} <span
                                                class="required">*</span></label>
                                        <textarea name="authors[{{ $index }}][affiliation]" class="form-control" rows="2" required>@php
                                            $affiliationData = $author->affiliation ?? null;
                                            $affiliationText = '';
                                            if ($affiliationData) {
                                                if (is_array($affiliationData)) {
                                                    // Handle nested array structure (array of affiliation objects)
                                                    $parts = [];
                                                    foreach ($affiliationData as $aff) {
                                                        if (is_array($aff)) {
                                                            $affParts = [];
                                                            if (!empty($aff['university']) && trim($aff['university'])) $affParts[] = trim($aff['university']);
                                                            if (!empty($aff['faculty']) && trim($aff['faculty'])) $affParts[] = trim($aff['faculty']);
                                                            if (!empty($aff['department']) && trim($aff['department'])) $affParts[] = trim($aff['department']);
                                                            if (!empty($aff['city']) && trim($aff['city'])) $affParts[] = trim($aff['city']);
                                                            if (!empty($aff['country']) && trim($aff['country'])) $affParts[] = trim($aff['country']);
                                                            if (!empty($affParts)) {
                                                                $parts[] = implode(', ', $affParts);
                                                            }
                                                        } elseif (is_string($aff) && trim($aff)) {
                                                            $parts[] = trim($aff);
                                                        }
                                                    }
                                                    $affiliationText = !empty($parts) ? implode('; ', $parts) : '';
                                                } elseif (is_string($affiliationData)) {
                                                    $affiliationText = $affiliationData;
                                                }
                                            }
                                            echo e($affiliationText);
                                        @endphp</textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('Country') }}</label>
                                        <input type="text" name="authors[{{ $index }}][country]"
                                            class="form-control" value="{{ $author->nationality ?? '' }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">{{ __('Author Order') }}</label>
                                        <input type="number" name="authors[{{ $index }}][order]"
                                            class="form-control" value="{{ $index + 1 }}" min="1">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="author-item" data-author-index="0">
                            <div class="author-header">
                                <span class="author-title">{{ __('Author') }} 1</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('First Name') }} <span
                                            class="required">*</span></label>
                                    <input type="text" name="authors[0][first_name]" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('Last Name') }} <span
                                            class="required">*</span></label>
                                    <input type="text" name="authors[0][last_name]" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('Email') }} <span class="required">*</span></label>
                                    <input type="email" name="authors[0][email]" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('ORCID') }}</label>
                                    <input type="text" name="authors[0][orcid]" class="form-control"
                                        placeholder="0000-0000-0000-0000">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="form-label">{{ __('Affiliation') }} <span
                                            class="required">*</span></label>
                                    <textarea name="authors[0][affiliation]" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('Country') }}</label>
                                    <input type="text" name="authors[0][country]" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">{{ __('Author Order') }}</label>
                                    <input type="number" name="authors[0][order]" class="form-control" value="1"
                                        min="1">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add-author" onclick="addAuthor()">+ {{ __('Add Author') }}</button>
            </div>

            <!-- Additional Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Additional Information') }}</h3>

                <div class="form-group">
                    <label class="form-label">{{ __('Funding Statement') }}</label>
                    <textarea name="funding_statement" class="form-control" rows="4"
                        placeholder="{{ __('If applicable, describe funding sources') }}">{{ $submission->funders->first()->funding_statement ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Conflict of Interest Statement') }}</label>
                    <textarea name="conflict_statement" class="form-control" rows="4"
                        placeholder="{{ __('Declare any conflicts of interest') }}">{{ $submission->conflict_details ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Acknowledgements') }}</label>
                    <textarea name="acknowledgements" class="form-control" rows="4"
                        placeholder="{{ __('Thank individuals or organizations') }}"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Notes for Layout') }} ({{ __('Optional') }})</label>
                    <textarea name="notes_for_layout" class="form-control" rows="3"
                        placeholder="{{ __('Any special formatting or layout requests') }}"></textarea>
                </div>
            </div>

            <!-- Confirmation -->
            <div class="form-section">
                <div class="form-check">
                    <input type="checkbox" name="author_confirmed" id="author_confirmed" class="form-check-input"
                        required>
                    <label class="form-check-label" for="author_confirmed">
                        <strong>{{ __('I confirm this information is final and correct') }}</strong>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="submit-section">
                <button type="submit" id="submitFinalMetadataBtn" class="btn-submit">
                    <span id="submitBtnText">{{ __('Submit Final Metadata') }}</span>
                    <span id="submitBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        let authorIndex = {{ $submission->authors ? $submission->authors->count() : 1 }};

        function addAuthor() {
            const container = document.getElementById('authorsContainer');
            const authorHtml = `
        <div class="author-item" data-author-index="${authorIndex}">
            <div class="author-header">
                <span class="author-title">{{ __('Author') }} ${authorIndex + 1}</span>
                <button type="button" class="btn-remove-author" onclick="removeAuthor(${authorIndex})">{{ __('Remove') }}</button>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('First Name') }} <span class="required">*</span></label>
                    <input type="text" name="authors[${authorIndex}][first_name]" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('Last Name') }} <span class="required">*</span></label>
                    <input type="text" name="authors[${authorIndex}][last_name]" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('Email') }} <span class="required">*</span></label>
                    <input type="email" name="authors[${authorIndex}][email]" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('ORCID') }}</label>
                    <input type="text" name="authors[${authorIndex}][orcid]" class="form-control" placeholder="0000-0000-0000-0000">
                </div>
                <div class="col-md-12 form-group">
                    <label class="form-label">{{ __('Affiliation') }} <span class="required">*</span></label>
                    <textarea name="authors[${authorIndex}][affiliation]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('Country') }}</label>
                    <input type="text" name="authors[${authorIndex}][country]" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">{{ __('Author Order') }}</label>
                    <input type="number" name="authors[${authorIndex}][order]" class="form-control" value="${authorIndex + 1}" min="1">
                </div>
            </div>
        </div>
    `;
            container.insertAdjacentHTML('beforeend', authorHtml);
            authorIndex++;
        }

        function removeAuthor(index) {
            const authorItem = document.querySelector(`[data-author-index="${index}"]`);
            if (authorItem) {
                authorItem.remove();
            }
        }

        // Handle form submission - disable button and show spinner
        document.getElementById('finalMetadataForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitFinalMetadataBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnSpinner = document.getElementById('submitBtnSpinner');

            // Disable button and show spinner
            submitBtn.disabled = true;
            submitBtnText.classList.add('d-none');
            submitBtnSpinner.classList.remove('d-none');
        });

        function handleFinalMetadataResponse(response) {
            const submitBtn = document.getElementById('submitFinalMetadataBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnSpinner = document.getElementById('submitBtnSpinner');

            if (response.status === true || response.success === true) {
                // Keep button disabled and spinner showing during redirect
                alert(response.message || '{{ __('Final metadata submitted successfully') }}');
                window.location.href = response.redirect ||
                    '{{ route('user.orders.dashboard', $submission->client_order_id) }}';
            } else {
                // Re-enable button and hide spinner on error
                submitBtn.disabled = false;
                submitBtnText.classList.remove('d-none');
                submitBtnSpinner.classList.add('d-none');
                alert(response.message || '{{ __('An error occurred. Please try again.') }}');
            }
        }
    </script>
@endpush


