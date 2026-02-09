@extends('admin.layouts.app')
@push('title')
    {{ __('Journal Issues Overview') }}
@endpush

@push('style')
    <style>
        .journal-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .journal-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .journal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
            margin: 0;
        }

        .journal-meta {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .issue-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .issue-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .issue-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
        }

        .issue-title {
            font-weight: 600;
            color: #212529;
            font-size: 1.1rem;
            margin: 0;
        }

        .issue-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-published {
            background: #28a745;
            color: white;
        }

        .badge-scheduled {
            background: #007bff;
            color: white;
        }

        .badge-planned {
            background: #6c757d;
            color: white;
        }

        .issue-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .issue-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .issue-info-item strong {
            color: #212529;
        }

        .papers-count {
            background: #e7f3ff;
            color: #0066cc;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .no-issues {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .filter-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Journal Issues Overview') }}</h5>
            <a href="{{ route('admin.issues.create') }}" class="btn btn-primary">{{ __('Create New Issue') }}</a>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.issues.journal-issues') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Filter by Journal') }}</label>
                    <select name="journal_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Journals') }}</option>
                        @foreach ($allJournals as $journal)
                            <option value="{{ $journal->id }}"
                                {{ request('journal_id') == $journal->id ? 'selected' : '' }}>
                                {{ $journal->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <a href="{{ route('admin.issues.journal-issues') }}" class="btn btn-secondary">{{ __('Clear Filter') }}</a>
                </div>
            </form>
        </div>

        <!-- Journals with Issues -->
        @forelse($journals as $journal)
            <div class="journal-card">
                    <div class="journal-header">
                        <h3 class="journal-title">{{ $journal->title }}</h3>
                        <div class="journal-meta">
                            {{ __('Total Issues') }}: <strong>{{ $journal->issues_count }}</strong>
                            @if($journal->issn_print)
                                | ISSN Print: {{ $journal->issn_print }}
                            @endif
                            @if($journal->issn_online)
                                | ISSN Online: {{ $journal->issn_online }}
                            @endif
                        </div>
                    </div>

                    <div class="issues-list">
                        @forelse($journal->issues as $issue)
                            <div class="issue-card">
                                <div class="issue-header">
                                    <div>
                                        <h5 class="issue-title">
                                            @if($issue->title)
                                                {{ $issue->title }}
                                            @else
                                                {{ __('Volume') }} {{ $issue->volume ?? '-' }}, 
                                                {{ __('Number') }} {{ $issue->number ?? '-' }}, 
                                                {{ $issue->year ?? '-' }}
                                            @endif
                                        </h5>
                                        @if($issue->title && ($issue->volume || $issue->number || $issue->year))
                                            <div style="font-size: 0.85rem; color: #6c757d; margin-top: 0.25rem;">
                                                Vol. {{ $issue->volume ?? '-' }}, No. {{ $issue->number ?? '-' }}, {{ $issue->year ?? '-' }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="issue-badge badge-{{ $issue->status }}">
                                        @if($issue->status === 'published')
                                            {{ __('Published') }}
                                        @elseif($issue->status === 'scheduled')
                                            {{ __('Scheduled') }}
                                        @else
                                            {{ __('Planned') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="issue-info">
                                    <div class="issue-info-item">
                                        <strong>{{ __('Date') }}:</strong>
                                        @if($issue->publication_date)
                                            {{ $issue->publication_date->format('Y-m-d') }} ({{ __('Published') }})
                                        @elseif($issue->planned_publication_date)
                                            {{ $issue->planned_publication_date->format('Y-m-d') }} ({{ __('Planned') }})
                                        @else
                                            {{ __('Not set') }}
                                        @endif
                                    </div>
                                    <div class="issue-info-item">
                                        <strong>{{ __('Papers') }}:</strong>
                                        <span class="papers-count">{{ $issue->submissions_count ?? 0 }}</span>
                                    </div>
                                    <div class="issue-info-item">
                                        <a href="{{ route('admin.issues.show', $issue->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            {{ __('View Papers') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="no-issues">
                                <p>{{ __('No issues found for this journal.') }}</p>
                                <a href="{{ route('admin.issues.create') }}?journal_id={{ $journal->id }}" 
                                   class="btn btn-sm btn-primary">
                                    {{ __('Create First Issue') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">{{ __('No journals found.') }}</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

