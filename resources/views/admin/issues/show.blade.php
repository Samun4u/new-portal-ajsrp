@extends('admin.layouts.app')
@push('title')
    {{ __('Issue Details') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Issue Details') }}</h5>
            <a href="{{ route('admin.issues.edit', $issue->id) }}" class="btn btn-warning">{{ __('Edit Issue') }}</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6>{{ __('Issue Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('Journal') }}:</strong> {{ $issue->journal->title ?? __('N/A') }}</p>
                        <p><strong>{{ __('Volume') }}:</strong> {{ $issue->volume ?? '-' }}</p>
                        <p><strong>{{ __('Number') }}:</strong> {{ $issue->number ?? '-' }}</p>
                        <p><strong>{{ __('Year') }}:</strong> {{ $issue->year ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Title') }}:</strong> {{ $issue->title ?? '-' }}</p>
                        <p><strong>{{ __('Status') }}:</strong>
                            @if ($issue->status === 'published')
                                <span class="badge bg-success">{{ __('Published') }}</span>
                            @elseif($issue->status === 'scheduled')
                                <span class="badge bg-primary">{{ __('Scheduled') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('Planned') }}</span>
                            @endif
                        </p>
                        <p><strong>{{ __('Planned Publication Date') }}:</strong>
                            {{ $issue->planned_publication_date ? $issue->planned_publication_date->format('Y-m-d') : '-' }}
                        </p>
                        <p><strong>{{ __('Publication Date') }}:</strong>
                            {{ $issue->publication_date ? $issue->publication_date->format('Y-m-d') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>{{ __('Assigned Submissions') }} ({{ $submissions->count() }})</h6>
            </div>
            <div class="card-body">
                @if ($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Authors') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr>
                                        <td>{{ $submission->article_title ?? __('N/A') }}</td>
                                        <td>
                                            @if ($submission->authors)
                                                {{ $submission->authors->take(2)->pluck('first_name')->implode(', ') }}
                                                @if ($submission->authors->count() > 2)
                                                    +{{ $submission->authors->count() - 2 }}
                                                @endif
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $status = $submission->approval_status ?? 'unknown';
                                                $statusClass = 'bg-secondary';
                                                $statusText = $status;
                                                
                                                if ($status === 'accepted' || $status === 'proof_approved' || $status === 'published') {
                                                    $statusClass = 'bg-success';
                                                    if ($status === 'published') {
                                                        $statusText = __('Published');
                                                    } elseif ($status === 'proof_approved') {
                                                        $statusText = __('Accepted');
                                                    } else {
                                                        $statusText = __('Accepted');
                                                    }
                                                } elseif (in_array($status, ['in_proofreading', 'in_galley'])) {
                                                    $statusClass = 'bg-warning';
                                                    $statusText = __('In Progress');
                                                } elseif (in_array($status, ['rejected', 'withdrawn'])) {
                                                    $statusClass = 'bg-danger';
                                                    $statusText = ucfirst($status);
                                                } else {
                                                    $statusText = ucfirst(str_replace('_', ' ', $status));
                                                }
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.client-orders.task-board.index', $submission->client_order_id) }}"
                                                class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">{{ __('No submissions assigned to this issue yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection



