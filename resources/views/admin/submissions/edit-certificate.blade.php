@extends('admin.layouts.app')
@push('title')
    {{ __('Edit Certificate') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Edit Certificate') }}</h5>
            <a href="{{ route('admin.submissions.final-acceptance-certificates.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>{{ __('Submission Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('Submission ID') }}:</strong> {{ $submission->id }}</p>
                        <p><strong>{{ __('Title') }}:</strong> {{ $submission->article_title ?? __('N/A') }}</p>
                        <p><strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}</p>
                        @if($submission->issue)
                            <p><strong>{{ __('Issue') }}:</strong> {{ $submission->issue->title ?? 'Vol. ' . ($submission->issue->volume ?? '-') . ', No. ' . ($submission->issue->number ?? '-') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Authors') }}:</strong>
                            @if($submission->authors && $submission->authors->count() > 0)
                                {{ $submission->authors->map(function($author) { return trim($author->first_name . ' ' . $author->last_name); })->implode(', ') }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </p>
                        <p><strong>{{ __('Acceptance Date') }}:</strong> {{ $submission->acceptance_date ? \Carbon\Carbon::parse($submission->acceptance_date)->format('M d, Y') : __('N/A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6>{{ __('Certificate Details') }}</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ __('The certificate will be regenerated using the current metadata. To update the certificate content, please update the final metadata first.') }}</p>
                
                <form method="POST" action="{{ route('admin.submissions.final-acceptance-certificate.update', encrypt($submission->id)) }}">
                    @csrf
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> {{ __('Clicking "Regenerate Certificate" will create a new certificate and archive the old one.') }}
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('{{ __('Are you sure you want to regenerate the certificate? The old certificate will be archived.') }}');">
                            <i class="fa fa-sync"></i> {{ __('Regenerate Certificate') }}
                        </button>
                        <a href="{{ route('admin.submissions.final-acceptance-certificates.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


