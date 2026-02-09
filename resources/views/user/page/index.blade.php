@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
    <div class="p-sm-30 p-15">
        <div class="max-w-1000 m-auto">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection