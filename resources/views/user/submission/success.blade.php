@extends('user.submission.main')
@section('submission-content')
<div class="step-seven-submit">

<div class="success-message">
    <!-- left -->
    <div class="">
        <i class="fas fa-check"></i>
    </div>
    <!-- right -->
    <div class="text">
        <h6>{{ __('Submit Successfully') }}</h6>
        <h5>{{ __('Order Number') }} : {{ $clientOrderId }}</h5>
    </div>
</div>

<a class="view-status">
    {{ __('View the status of your submission') }} <i class="fas fa-external-link-alt"></i>
</a>

<h3>{{ __('Peer Review Process') }}</h3>

<p>
 {{ __('Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolor, nostrum! Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolor, nostrum!') }}
</p>

<p>
{{ __('Lorem ipsum dolor sit amet consectetur adipisicing elit.')}} <a href="">{{ __('the Infographic') }}</a> {{ __('Facere, dolores.  Lorem ipsum dolor sit amet consectetur adipisicing elit.') }} 
</p>

</div>
@endsection
@push('script')
    <script>
        // 5 seconds = 5000 milliseconds
        setTimeout(function () {
            window.location.href = "{{ route('user.orders.list') }}";
        }, 5000);
    </script>
@endpush