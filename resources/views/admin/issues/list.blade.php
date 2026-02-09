@extends('admin.layouts.app')
@push('title')
    {{ __('Issues Management') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Issues Management') }}</h5>
            <a href="{{ route('admin.issues.create') }}" class="btn btn-primary">{{ __('Create Issue') }}</a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.issues.index') }}" class="row g-3">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>
                                {{ __('Planned') }}</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                {{ __('Scheduled') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                {{ __('Published') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Year') }}</label>
                        <input type="number" name="year" class="form-control" value="{{ request('year') }}"
                            placeholder="{{ __('Year') }}" min="2000" max="2100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Issues Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Journal') }}</th>
                                <th>{{ __('Volume') }}</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Year') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Submissions') }}</th>
                                <th>{{ __('Planned Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issues as $issue)
                                <tr>
                                    <td>{{ $issue->journal->title ?? __('N/A') }}</td>
                                    <td>{{ $issue->volume ?? '-' }}</td>
                                    <td>{{ $issue->number ?? '-' }}</td>
                                    <td>{{ $issue->year ?? '-' }}</td>
                                    <td>{{ $issue->title ?? '-' }}</td>
                                    <td>
                                        @if ($issue->status === 'published')
                                            <span class="badge bg-success">{{ __('Published') }}</span>
                                        @elseif($issue->status === 'scheduled')
                                            <span class="badge bg-primary">{{ __('Scheduled') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Planned') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $issue->submissionsCount() }}</td>
                                    <td>{{ $issue->planned_publication_date ? $issue->planned_publication_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.issues.show', $issue->id) }}"
                                            class="btn btn-sm btn-info">{{ __('View') }}</a>
                                        <a href="{{ route('admin.issues.edit', $issue->id) }}"
                                            class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('No issues found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $issues->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection



