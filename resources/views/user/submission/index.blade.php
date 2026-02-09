@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/submission.css') }}" />
@endpush

@section('content')
<!-- Content -->

<a href="{{ route('user.submission.select-a-journal',['by' => 'by-subject']) }}" class="btn btn-primary">{{ __("Submision form") }}</a>

@endsection

@push('script')
<script src="{{ asset('user/custom/js/submission.js') }}"></script>
@endpush