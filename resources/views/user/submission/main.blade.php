@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/submission.css') }}" />
@endpush

@section('content')
<!-- Content -->
<section class="submit-section">

    <!-- submit-tabs -->
    <div class="submit-tabs">
        <div class="submit-tab-content d-flex align-items-start">
            <!-- left menu start-->
            @include('user.submission.left-menu')
            <!-- left menu end-->

            <!-- right section -->
            <div class="tab-content" id="v-pills-tabContent">
                @yield('submission-content')
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')
<script src="{{ asset('user/custom/js/submission.js') }}"></script>
@endpush