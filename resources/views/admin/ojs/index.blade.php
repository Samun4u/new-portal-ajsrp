@extends('admin.layouts.app')
@push('title')
    {{ __('OJS QuickSubmit Integration') }}
@endpush

@push('style')
    <style>
        .ojs-container {
            padding: 2rem;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .submission-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }

        .submission-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .submission-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .submission-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-item i {
            color: #007bff;
        }

        .ojs-status {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-quicksubmit {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .btn-quicksubmit:hover {
            background: #0056b3;
            color: white;
        }

        .badge-ojs {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-ready {
            background: #d4edda;
            color: #155724;
        }

        .badge-submitted {
            background: #cce5ff;
            color: #004085;
        }

        .badge-published {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
@endpush

@section('content')
    <div class="ojs-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('OJS QuickSubmit Integration') }}</h2>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.ojs.index') }}">
                <div class="filter-row">
                    <div>
                        <label class="form-label">{{ __('Search') }}</label>
                        <input type="text" name="search" class="form-control"
                               value="{{ $filters['search'] ?? '' }}"
                               placeholder="{{ __('Title, Author name, or Email') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('Journal') }}</label>
                        <select name="journal_id" class="form-control">
                            <option value="">{{ __('All Journals') }}</option>
                            @foreach($journals as $journal)
                                <option value="{{ $journal->id }}"
                                        {{ ($filters['journal_id'] ?? '') == $journal->id ? 'selected' : '' }}>
                                    {{ $journal->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="">{{ __('All') }}</option>
                            <option value="ready" {{ ($filters['status'] ?? '') == 'ready' ? 'selected' : '' }}>
                                {{ __('Ready for OJS') }}
                            </option>
                            <option value="submitted" {{ ($filters['status'] ?? '') == 'submitted' ? 'selected' : '' }}>
                                {{ __('Submitted to OJS') }}
                            </option>
                            <option value="published" {{ ($filters['status'] ?? '') == 'published' ? 'selected' : '' }}>
                                {{ __('Published') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.ojs.index') }}"
                       class="btn btn-secondary">{{ __('Clear') }}</a>
                </div>
            </form>
        </div>

        <!-- Submissions List -->
        @if($submissions->count() > 0)
            @foreach($submissions as $submission)
                <div class="submission-card">
                    <div class="submission-header">
                        <div style="flex: 1;">
                            <div class="submission-title">
                                {{ $submission->article_title ?? __('Untitled') }}
                            </div>
                            <div class="submission-meta">
                                <div class="meta-item">
                                    <i class="fa fa-book"></i>
                                    <span>{{ $submission->journal->title ?? __('N/A') }}</span>
                                </div>
                                @if($submission->authors && $submission->authors->count() > 0)
                                    <div class="meta-item">
                                        <i class="fa fa-users"></i>
                                        <span>{{ $submission->authors->count() }} {{ __('Author(s)') }}</span>
                                    </div>
                                @endif
                                @if($submission->galleyFiles && $submission->galleyFiles->where('status', 'approved')->count() > 0)
                                    <div class="meta-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>{{ __('Galley Approved') }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($submission->authors && $submission->authors->count() > 0)
                                <div class="mt-2" style="color: #495057; font-size: 0.9rem;">
                                    <strong>{{ __('Authors') }}:</strong>
                                    {{ $submission->authors->map(function($author) { return trim($author->first_name . ' ' . $author->last_name); })->implode(', ') }}
                                </div>
                            @endif
                            @if($submission->ojs_article_url)
                                <div class="mt-2">
                                    <a href="{{ $submission->ojs_article_url }}" target="_blank" class="text-primary">
                                        <i class="fa fa-external-link"></i> {{ __('View in OJS') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="ojs-status">
                            @if($submission->approval_status === 'published')
                                <span class="badge-ojs badge-published">
                                    <i class="fa fa-check-circle"></i> {{ __('Published') }}
                                </span>
                            @elseif($submission->ojs_article_id)
                                <span class="badge-ojs badge-submitted">
                                    <i class="fa fa-paper-plane"></i> {{ __('Submitted to OJS') }}
                                </span>
                            @else
                                <span class="badge-ojs badge-ready">
                                    <i class="fa fa-clock"></i> {{ __('Ready for OJS') }}
                                </span>
                            @endif
                            <a href="{{ route('admin.ojs.quicksubmit-data', encrypt($submission->id)) }}"
                               class="btn-quicksubmit">
                                <i class="fa fa-file-export"></i> {{ __('QuickSubmit Data') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> {{ __('No submissions ready for OJS QuickSubmit found.') }}
            </div>
        @endif
    </div>
@endsection

