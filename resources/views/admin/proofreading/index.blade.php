@extends('admin.layouts.app')
@push('title')
    {{ __('Proofreading Management') }}
@endpush

@push('style')
    <style>
        .proofreading-container {
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

        .proof-status {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-manage {
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

        .btn-manage:hover {
            background: #0056b3;
            color: white;
        }

        .badge-proof {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
        }
    </style>
@endpush

@section('content')
    <div class="proofreading-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('Proofreading Management') }}</h2>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.proofreading.index') }}">
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
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>
                                {{ __('Pending Review') }}
                            </option>
                            <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>
                                {{ __('Approved') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.proofreading.index') }}"
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
                                @if($submission->proofFiles && $submission->proofFiles->count() > 0)
                                    <div class="meta-item">
                                        <i class="fa fa-file-pdf"></i>
                                        <span>{{ $submission->proofFiles->count() }} {{ __('Proof Version(s)') }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($submission->authors && $submission->authors->count() > 0)
                                <div class="mt-2" style="color: #495057; font-size: 0.9rem;">
                                    <strong>{{ __('Authors') }}:</strong>
                                    {{ $submission->authors->map(function($author) { return trim($author->first_name . ' ' . $author->last_name); })->implode(', ') }}
                                </div>
                            @endif
                        </div>
                        <div class="proof-status">
                            @if($submission->proofFiles && $submission->proofFiles->count() > 0)
                                @php
                                    $hasApproved = $submission->proofFiles->contains('status', 'approved');
                                    $latestProof = $submission->proofFiles->sortByDesc('created_at')->first();
                                @endphp
                                @if($hasApproved)
                                    <span class="badge-proof badge-approved">
                                        <i class="fa fa-check-circle"></i> {{ __('Proof Approved') }}
                                    </span>
                                @else
                                    <span class="badge-proof badge-pending">
                                        <i class="fa fa-clock"></i> {{ __('Pending Review') }}
                                    </span>
                                @endif
                            @else
                                <span class="badge-proof badge-pending">
                                    <i class="fa fa-exclamation-circle"></i> {{ __('No Proof Uploaded') }}
                                </span>
                            @endif
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('admin.proofreading.list', encrypt($submission->id)) }}"
                                   class="btn-manage justify-content-center">
                                    <i class="fa fa-cog"></i> {{ __('Manage') }}
                                </a>
                                <a href="{{ route('admin.client-orders.fullview', $submission->client_order->id) }}"
                                   target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center gap-1">
                                    <i class="fa fa-external-link-alt"></i> {{ __('View Details') }}
                                </a>
                            </div>
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
                <i class="fa fa-info-circle"></i> {{ __('No submissions in proofreading stage found.') }}
            </div>
        @endif
    </div>
@endsection

