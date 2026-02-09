@extends('user.layouts.app')
@push('title')
    {{ __('Submission History') }}
@endpush

@section('content')
    <div class="p-sm-30 p-15">
        <h5 class="fs-18 fw-600 lh-20 text-title-black pb-18 mb-18 bd-b-one bd-c-stroke">{{ __('Submission History') }}</h5>

        <div class="card">
            <div class="card-body">
                @if ($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Journal') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Acceptance Date') }}</th>
                                    <th>{{ __('Publication Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $order)
                                    @php
                                        $submission = $order->submissions->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $submission->article_title ?? __('N/A') }}</td>
                                        <td>{{ $submission->journal->title ?? __('N/A') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $submission->approval_status ?? __('N/A') }}</span>
                                        </td>
                                        <td>{{ $submission->acceptance_date ? $submission->acceptance_date->format('Y-m-d') : '-' }}
                                        </td>
                                        <td>{{ $submission->publication_date ? $submission->publication_date->format('Y-m-d') : '-' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('user.submission-history.show', $order->order_id) }}"
                                                class="btn btn-sm btn-primary">{{ __('View Details') }}</a>
                                            @if ($submission->acceptance_certificate_file_id)
                                                <a href="{{ route('user.submission.final-acceptance-certificate.download', encrypt($submission->id)) }}"
                                                    class="btn btn-sm btn-success">{{ __('Certificate') }}</a>
                                            @endif
                                            @if ($submission->ojs_article_url)
                                                <a href="{{ $submission->ojs_article_url }}" target="_blank"
                                                    class="btn btn-sm btn-info">{{ __('View in OJS') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $submissions->links() }}
                    </div>
                @else
                    <p class="text-muted text-center">{{ __('No submission history found.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection



