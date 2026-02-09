@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
<!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15 overflow-x-hidden">
    {{-- <h4>{{ $page->title }}</h4> --}}
    <div class="mt-3">{!! $page->content !!}</div>
</div>
<!-- Content -->
 @endsection