@extends('admin.layouts.app')
@push('title')
    {{ __('Edit Issue') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Edit Issue') }}</h5>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.issues.update', $issue->id) }}" class="ajax"
                    data-handler="handleIssueResponse">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Journal') }} <span class="text-danger">*</span></label>
                            <select name="journal_id" class="form-select" required>
                                @foreach ($journals as $journal)
                                    <option value="{{ $journal->id }}"
                                        {{ $issue->journal_id == $journal->id ? 'selected' : '' }}>
                                        {{ $journal->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Volume') }}</label>
                            <input type="number" name="volume" class="form-control" value="{{ $issue->volume }}"
                                min="1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Number') }}</label>
                            <input type="number" name="number" class="form-control" value="{{ $issue->number }}"
                                min="1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Year') }}</label>
                            <input type="number" name="year" class="form-control" value="{{ $issue->year }}"
                                min="2000" max="2100">
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input type="text" name="title" class="form-control" value="{{ $issue->title }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="planned" {{ $issue->status == 'planned' ? 'selected' : '' }}>
                                    {{ __('Planned') }}</option>
                                <option value="scheduled" {{ $issue->status == 'scheduled' ? 'selected' : '' }}>
                                    {{ __('Scheduled') }}</option>
                                <option value="published" {{ $issue->status == 'published' ? 'selected' : '' }}>
                                    {{ __('Published') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Planned Publication Date') }}</label>
                            <input type="date" name="planned_publication_date" class="form-control"
                                value="{{ $issue->planned_publication_date ? $issue->planned_publication_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Publication Date') }}</label>
                            <input type="date" name="publication_date" class="form-control"
                                value="{{ $issue->publication_date ? $issue->publication_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">{{ __('Update Issue') }}</button>
                        <a href="{{ route('admin.issues.show', $issue->id) }}"
                            class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function handleIssueResponse(response) {
                if (response.status === true || response.success === true) {
                    alert(response.message || '{{ __('Issue updated successfully') }}');
                    if (response.data && response.data.redirect) {
                        window.location.href = response.data.redirect;
                    } else {
                        window.location.href = '{{ route('admin.issues.show', $issue->id) }}';
                    }
                } else {
                    alert(response.message || '{{ __('An error occurred') }}');
                }
            }
        </script>
    @endpush
@endsection



