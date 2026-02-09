@extends('user.layouts.app')
@push('title')
    {{ __('Submission Details') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Submission Details') }}</h5>

        <div class="card mb-4">
            <div class="card-header">
                <h6>{{ __('Article Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('Title') }}:</strong> {{ $submission->article_title ?? __('N/A') }}</p>
                        <p><strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}</p>
                        <p><strong>{{ __('Status') }}:</strong>
                            <span class="badge bg-info">{{ $submission->approval_status ?? __('N/A') }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Acceptance Date') }}:</strong>
                            {{ $submission->acceptance_date ? $submission->acceptance_date->format('Y-m-d') : '-' }}</p>
                        <p><strong>{{ __('Scheduled Publication Date') }}:</strong>
                            {{ $submission->scheduled_publication_date ? $submission->scheduled_publication_date->format('Y-m-d') : '-' }}
                        </p>
                        <p><strong>{{ __('Publication Date') }}:</strong>
                            {{ $submission->publication_date ? $submission->publication_date->format('Y-m-d') : '-' }}</p>
                    </div>
                </div>
                @if ($submission->issue)
                    <div class="mt-3">
                        <p><strong>{{ __('Issue') }}:</strong>
                            Volume {{ $submission->issue->volume ?? '-' }},
                            Number {{ $submission->issue->number ?? '-' }},
                            {{ $submission->issue->year ?? '-' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6>{{ __('Actions') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    @if ($submission->acceptance_certificate_file_id)
                        <a href="{{ route('user.submission.final-acceptance-certificate.download', encrypt($submission->id)) }}"
                            class="btn btn-success">
                            {{ __('Download Acceptance Certificate') }}
                        </a>
                    @endif
                    @if ($submission->ojs_article_url)
                        <a href="{{ $submission->ojs_article_url }}" target="_blank" class="btn btn-info">
                            {{ __('View in OJS') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection



