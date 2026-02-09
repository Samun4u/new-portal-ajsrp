@extends('admin.layouts.app')
@push('title')
    {{ __('OJS QuickSubmit Data') }}
@endpush

@push('style')
    <style>
        .ojs-data-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .data-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .data-field {
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .data-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .data-value {
            color: #212529;
        }

        .copy-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        .download-section {
            text-align: center;
            padding: 2rem;
            background: #e9ecef;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
    </style>
@endpush

@section('content')
    <div class="ojs-data-container">
        <h2 class="mb-4">{{ __('OJS QuickSubmit Data') }}</h2>

        <div class="alert alert-info">
            <strong>{{ __('Instructions') }}:</strong>
            {{ __('Copy the metadata below and paste it into OJS QuickSubmit form. Download the galley PDF and upload it to OJS.') }}
        </div>

        @if ($submission->finalMetadata && $submission->metadata_status === 'approved')
            <!-- Article Information -->
            <div class="data-section">
                <h4 class="mb-3">{{ __('Article Information') }}</h4>

                <div class="data-field">
                    <div class="data-label">{{ __('Title') }}</div>
                    <div class="data-value" id="title">{{ $submission->finalMetadata->final_title }}</div>
                    <button class="copy-btn" onclick="copyToClipboard('title')">{{ __('Copy') }}</button>
                </div>

                @if ($submission->finalMetadata->short_title)
                    <div class="data-field">
                        <div class="data-label">{{ __('Short Title') }}</div>
                        <div class="data-value" id="shortTitle">{{ $submission->finalMetadata->short_title }}</div>
                        <button class="copy-btn" onclick="copyToClipboard('shortTitle')">{{ __('Copy') }}</button>
                    </div>
                @endif

                <div class="data-field">
                    <div class="data-label">{{ __('Abstract') }}</div>
                    <div class="data-value" id="abstract" style="white-space: pre-wrap;">
                        {{ $submission->finalMetadata->final_abstract }}</div>
                    <button class="copy-btn" onclick="copyToClipboard('abstract')">{{ __('Copy') }}</button>
                </div>

                <div class="data-field">
                    <div class="data-label">{{ __('Keywords') }}</div>
                    <div class="data-value" id="keywords">{{ $submission->finalMetadata->final_keywords }}</div>
                    <button class="copy-btn" onclick="copyToClipboard('keywords')">{{ __('Copy') }}</button>
                </div>
            </div>

            <!-- Authors -->
            <div class="data-section">
                <h4 class="mb-3">{{ __('Authors') }}</h4>
                @if ($submission->authors && $submission->authors->count() > 0)
                    @foreach ($submission->authors as $index => $author)
                        <div class="data-field">
                            <div class="data-label">{{ __('Author') }} {{ $index + 1 }}</div>
                            <div class="data-value">
                                <strong>{{ __('Name') }}:</strong> {{ $author->first_name }}
                                {{ $author->last_name }}<br>
                                <strong>{{ __('Email') }}:</strong> {{ $author->email }}<br>
                                @if ($author->orcid)
                                    <strong>{{ __('ORCID') }}:</strong> {{ $author->orcid }}<br>
                                @endif
                                <strong>{{ __('Affiliation') }}:</strong>
                                {{ is_array($author->affiliation) ? implode(', ', $author->affiliation) : $author->affiliation }}<br>
                                @if ($author->nationality)
                                    <strong>{{ __('Country') }}:</strong> {{ $author->nationality }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Additional Information -->
            <div class="data-section">
                <h4 class="mb-3">{{ __('Additional Information') }}</h4>

                @if ($submission->finalMetadata->funding_statement)
                    <div class="data-field">
                        <div class="data-label">{{ __('Funding Statement') }}</div>
                        <div class="data-value" id="funding" style="white-space: pre-wrap;">
                            {{ $submission->finalMetadata->funding_statement }}</div>
                        <button class="copy-btn" onclick="copyToClipboard('funding')">{{ __('Copy') }}</button>
                    </div>
                @endif

                @if ($submission->finalMetadata->conflict_statement)
                    <div class="data-field">
                        <div class="data-label">{{ __('Conflict of Interest Statement') }}</div>
                        <div class="data-value" id="conflict" style="white-space: pre-wrap;">
                            {{ $submission->finalMetadata->conflict_statement }}</div>
                        <button class="copy-btn" onclick="copyToClipboard('conflict')">{{ __('Copy') }}</button>
                    </div>
                @endif

                @if ($submission->finalMetadata->acknowledgements)
                    <div class="data-field">
                        <div class="data-label">{{ __('Acknowledgements') }}</div>
                        <div class="data-value" id="acknowledgements" style="white-space: pre-wrap;">
                            {{ $submission->finalMetadata->acknowledgements }}</div>
                        <button class="copy-btn" onclick="copyToClipboard('acknowledgements')">{{ __('Copy') }}</button>
                    </div>
                @endif
            </div>

            <!-- Galley PDF Download -->
            @if ($submission->galleyFiles && $submission->galleyFiles->where('status', 'approved')->count() > 0)
                @php
                    $approvedGalley = $submission->galleyFiles->where('status', 'approved')->first();
                @endphp
                <div class="download-section">
                    <h4>{{ __('Galley PDF') }}</h4>
                    <p>{{ __('Download the final galley PDF to upload to OJS:') }}</p>
                    <a href="{{ getFileUrl($approvedGalley->file_id) }}" class="btn btn-primary btn-lg" download>
                        {{ __('Download Galley PDF') }}
                    </a>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ __('No approved galley version available. Please ensure galley is approved before submitting to OJS.') }}
                </div>
            @endif

            <!-- Journal & Issue Info -->
            <div class="data-section">
                <h4 class="mb-3">{{ __('Journal & Issue Information') }}</h4>
                <div class="data-field">
                    <div class="data-label">{{ __('Journal') }}</div>
                    <div class="data-value">
                        <strong>{{ __('Name') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}<br>
                        @if ($submission->journal->short_name)
                            <strong>{{ __('Short Name') }}:</strong> {{ $submission->journal->short_name }}<br>
                        @endif
                        @if ($submission->journal->issn_print)
                            <strong>{{ __('ISSN Print') }}:</strong> {{ $submission->journal->issn_print }}<br>
                        @endif
                        @if ($submission->journal->issn_online)
                            <strong>{{ __('ISSN Online') }}:</strong> {{ $submission->journal->issn_online }}<br>
                        @endif
                        @if ($submission->journal->ojs_context)
                            <strong>{{ __('OJS Context') }}:</strong> {{ $submission->journal->ojs_context }}
                        @endif
                    </div>
                </div>
                @if ($submission->issue)
                    <div class="data-field">
                        <div class="data-label">{{ __('Issue') }}</div>
                        <div class="data-value">
                            <strong>{{ __('Volume') }}:</strong> {{ $submission->issue->volume ?? __('N/A') }}<br>
                            <strong>{{ __('Number') }}:</strong> {{ $submission->issue->number ?? __('N/A') }}<br>
                            <strong>{{ __('Year') }}:</strong> {{ $submission->issue->year ?? __('N/A') }}<br>
                            @if ($submission->issue->title)
                                <strong>{{ __('Title') }}:</strong> {{ $submission->issue->title }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Auto-Submit to OJS (Task 18) -->
            @if ($submission->galleyFiles && $submission->galleyFiles->where('status', 'approved')->count() > 0)
                <div class="data-section">
                    <h4 class="mb-3">{{ __('Auto-Submit to OJS') }}</h4>
                    <div class="alert alert-info">
                        {{ __('This will automatically submit the article to OJS using the REST API. Ensure OJS API credentials are configured in .env file.') }}
                    </div>
                    <form id="autoSubmitForm" method="POST"
                        action="{{ route('admin.ojs.auto-submit', encrypt($submission->id)) }}" class="ajax"
                        data-handler="handleAutoSubmitResponse">
                        @csrf
                        <button type="submit" class="btn btn-primary">{{ __('Submit to OJS Automatically') }}</button>
                    </form>
                </div>
            @endif

            <!-- Update Publication Status -->
            <div class="data-section">
                <h4 class="mb-3">{{ __('After OJS Publication') }}</h4>
                <form id="updatePublicationForm" method="POST"
                    action="{{ route('admin.ojs.update-publication', encrypt($submission->id)) }}" class="ajax"
                    data-handler="handlePublicationUpdate">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('OJS Article URL') }}</label>
                        <input type="url" name="ojs_article_url" class="form-control"
                            value="{{ $submission->ojs_article_url }}"
                            placeholder="https://ojs.example.com/article/view/123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('OJS Article ID') }}</label>
                        <input type="text" name="ojs_article_id" class="form-control"
                            value="{{ $submission->ojs_article_id }}" placeholder="123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Publication Date') }}</label>
                        <input type="date" name="publication_date" class="form-control"
                            value="{{ $submission->publication_date ? $submission->publication_date->format('Y-m-d') : '' }}">
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('Update Publication Status') }}</button>
                </form>
            </div>
        @else
            <div class="alert alert-warning">
                {{ __('Final metadata must be approved before OJS QuickSubmit data is available.') }}
            </div>
        @endif
    </div>

    @push('script')
        <script>
            function copyToClipboard(elementId) {
                const element = document.getElementById(elementId);
                const text = element.textContent || element.innerText;

                navigator.clipboard.writeText(text).then(function() {
                    alert('{{ __('Copied to clipboard!') }}');
                }, function(err) {
                    console.error('Failed to copy: ', err);
                });
            }

            function handlePublicationUpdate(response) {
                if (response.status === true || response.success === true) {
                    alert(response.message || '{{ __('Publication status updated successfully') }}');
                    window.location.reload();
                } else {
                    alert(response.message || '{{ __('An error occurred') }}');
                }
            }

            function handleAutoSubmitResponse(response) {
                if (response.status === true || response.success === true) {
                    alert(response.message || '{{ __('Article submitted to OJS successfully') }}');
                    if (response.data && response.data.article_url) {
                        if (confirm('{{ __('Article submitted successfully. Open in new tab?') }}')) {
                            window.open(response.data.article_url, '_blank');
                        }
                    }
                    window.location.reload();
                } else {
                    alert(response.message || '{{ __('An error occurred during OJS submission') }}');
                }
            }
        </script>
    @endpush
@endsection
