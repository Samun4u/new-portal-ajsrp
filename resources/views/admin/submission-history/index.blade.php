@extends('admin.layouts.app')
@push('title')
    {{ __('Submission History') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Submission History') }}</h5>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.submission-history.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                {{ __('Accepted') }}</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                {{ __('Scheduled') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                {{ __('Published') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Journal') }}</label>
                        <select name="journal_id" class="form-select">
                            <option value="">{{ __('All Journals') }}</option>
                            @foreach ($journals as $journal)
                                <option value="{{ $journal->id }}"
                                    {{ request('journal_id') == $journal->id ? 'selected' : '' }}>
                                    {{ $journal->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Issue') }}</label>
                        <select name="issue_id" class="form-select">
                            <option value="">{{ __('All Issues') }}</option>
                            @foreach ($issues as $issue)
                                <option value="{{ $issue->id }}"
                                    {{ request('issue_id') == $issue->id ? 'selected' : '' }}>
                                    {{ $issue->journal->title ?? '' }} - Vol {{ $issue->volume ?? '' }}, No
                                    {{ $issue->number ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Date From') }}</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Date To') }}</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('Author Search') }}</label>
                        <input type="text" name="author_search" class="form-control"
                            value="{{ request('author_search') }}" placeholder="{{ __('Search author...') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                        <a href="{{ route('admin.submission-history.index') }}"
                            class="btn btn-secondary">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Journal') }}</th>
                                <th>{{ __('Issue') }}</th>
                                <th>{{ __('Author') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Acceptance Date') }}</th>
                                <th>{{ __('Publication Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->client_order_id }}</td>
                                    <td>{{ $submission->article_title ?? __('N/A') }}</td>
                                    <td>{{ $submission->journal->title ?? __('N/A') }}</td>
                                    <td>
                                        @if ($submission->issue)
                                            Vol {{ $submission->issue->volume ?? '-' }}, No
                                            {{ $submission->issue->number ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($submission->authors && $submission->authors->count() > 0)
                                            {{ $submission->authors->first()->first_name }}
                                            {{ $submission->authors->first()->last_name }}
                                        @else
                                            {{ $submission->client_order->client->name ?? __('N/A') }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $submission->approval_status ?? __('N/A') }}</span>
                                    </td>
                                    <td>{{ $submission->acceptance_date ? $submission->acceptance_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>{{ $submission->publication_date ? $submission->publication_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.client-orders.task-board.index', $submission->client_order_id) }}"
                                            class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('No submissions found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection



