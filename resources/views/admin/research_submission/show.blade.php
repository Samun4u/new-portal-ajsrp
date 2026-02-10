@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-20">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Research Submission Details') }}</h1>
        <div>
            <a href="{{ route('admin.research-submission.download-docx', $research->id) }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-word fa-sm text-white-50"></i> {{ __('Download Word') }}
            </a>
            <a href="{{ route('admin.research-submission.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ __('Back') }}
            </a>
        </div>
        </div>

        <div class="row">
            <!-- Research Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('Research Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('English Title') }}:</strong>
                                <p>{{ $research->english_title ?: 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('Arabic Title') }}:</strong>
                                <p class="text-right">{{ $research->arabic_title ?: 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Field') }}:</strong>
                                <p>{{ $research->field }}</p>
                            </div>
                            @if($research->other_field)
                            <div class="col-md-6">
                                <strong>{{ __('Other Field') }}:</strong>
                                <p>{{ $research->other_field }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Journal') }}:</strong>
                                <p>{{ $research->journal }}</p>
                            </div>
                        </div>

                        @if($research->keywords)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Keywords') }}:</strong>
                                <p>{{ $research->keywords }}</p>
                            </div>
                        </div>
                        @endif

                        @if($research->paper_id_ar || $research->paper_id_en)
                        <div class="row mb-3">
                            @if($research->paper_id_ar)
                            <div class="col-md-6">
                                <strong>{{ __('Paper ID (Arabic)') }}:</strong>
                                <p>{{ $research->paper_id_ar }}</p>
                            </div>
                            @endif
                            @if($research->paper_id_en)
                            <div class="col-md-6">
                                <strong>{{ __('Paper ID (English)') }}:</strong>
                                <p>{{ $research->paper_id_en }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($research->thesis_answer)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __("Is this research extracted from your Master's/Ph.D. thesis?") }}:</strong>
                                <p>
                                    @if($research->thesis_answer == 'yes')
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($research->manuscript_path)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Manuscript') }}:</strong><br>
                                <a href="{{ asset('storage/' . $research->manuscript_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download"></i> {{ __('Download Manuscript') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($research->feedback)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Feedback') }}:</strong>
                                <p>{{ $research->feedback }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Authors Information -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">{{ __('Authors') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($research->authors as $index => $author)
                        <div class="author-item mb-3 p-3 bg-light rounded">
                            <h6>{{ __('Author') }} #{{ $index + 1 }}
                                @if($author->is_corresponding)
                                <span class="badge bg-success">{{ __('Corresponding Author') }}</span>
                                @endif
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    @if($author->title_en)
                                    <strong>{{ __('Title (English)') }}:</strong> {{ $author->title_en }}<br>
                                    @endif
                                    <strong>{{ __('Name (English)') }}:</strong> {{ $author->name_en }}<br>
                                    @if($author->degree_en)
                                    <strong>{{ __('Degree (English)') }}:</strong> {{ $author->degree_en }}<br>
                                    @endif
                                    <strong>{{ __('Email') }}:</strong> {{ $author->email }}<br>
                                    @if($author->phone)
                                    <strong>{{ __('Phone') }}:</strong> {{ $author->phone }}<br>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($author->title_ar)
                                    <strong>{{ __('Title (Arabic)') }}:</strong> {{ $author->title_ar }}<br>
                                    @endif
                                    <strong>{{ __('Name (Arabic)') }}:</strong> {{ $author->name_ar }}<br>
                                    @if($author->degree_ar)
                                    <strong>{{ __('Degree (Arabic)') }}:</strong> {{ $author->degree_ar }}<br>
                                    @endif
                                    @if($author->orcid)
                                    <strong>{{ __('ORCID') }}:</strong> {{ $author->orcid }}<br>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>{{ __('Affiliation (English)') }}:</strong><br>
                                    {{ $author->affiliation_en }}
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>{{ __('Affiliation (Arabic)') }}:</strong><br>
                                    {{ $author->affiliation_ar }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Status & Actions -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Submission Status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('Status') }}:</strong><br>
                            @if($research->approval_status === 'pending')
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @elseif($research->approval_status === 'approved')
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Language') }}:</strong><br>
                            {{ $research->language === 'ar' ? 'Arabic' : 'English' }}
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Submitted By') }}:</strong><br>
                            {{ $research->user ? $research->user->name : 'Guest' }}
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Submitted At') }}:</strong><br>
                            {{ $research->created_at->format('Y-m-d H:i') }}
                        </div>

                        @if($research->clientOrder)
                        <div class="mb-3">
                            <strong>{{ __('Order ID') }}:</strong><br>
                            <a href="{{ route('admin.client-order.details', $research->clientOrder->id) }}">
                                {{ $research->client_order_id }}
                            </a>
                        </div>
                        @endif

                        @if($research->approved_at)
                        <div class="mb-3">
                            <strong>{{ __('Processed At') }}:</strong><br>
                            {{ $research->approved_at->format('Y-m-d H:i') }}
                        </div>
                        @endif

                        @if($research->approver)
                        <div class="mb-3">
                            <strong>{{ __('Processed By') }}:</strong><br>
                            {{ $research->approver->name }}
                        </div>
                        @endif

                        @if($research->admin_notes)
                        <div class="mb-3">
                            <strong>{{ __('Admin Notes') }}:</strong><br>
                            <p class="alert alert-info">{{ $research->admin_notes }}</p>
                        </div>
                        @endif

                        @if($research->primaryCertificate)
                        <div class="mb-3">
                            <strong>{{ __('Certificate Status') }}:</strong><br>
                            @if($research->primaryCertificate->certificate_sent)
                                <span class="badge bg-success">{{ __('Sent') }}</span><br>
                                <small>{{ $research->primaryCertificate->sent_at->format('Y-m-d H:i') }}</small>
                            @else
                                <span class="badge bg-secondary">{{ __('Not Sent') }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                @if($research->approval_status === 'pending')
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">{{ __('Actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block mb-2" onclick="approveSubmission({{ $research->id }})">
                            <i class="fas fa-check"></i> {{ __('Approve & Send Certificate') }}
                        </button>
                        <button type="button" class="btn btn-danger btn-block" onclick="rejectSubmission({{ $research->id }})">
                            <i class="fas fa-times"></i> {{ __('Reject Submission') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function approveSubmission(id) {
        Swal.fire({
            title: '{{ __("Approve Submission?") }}',
            text: '{{ __("This will send the primary certificate to the user.") }}',
            icon: 'question',
            input: 'textarea',
            inputLabel: '{{ __("Notes (Optional)") }}',
            inputPlaceholder: '{{ __("Enter any notes...") }}',
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, Approve") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            preConfirm: (notes) => {
                return $.ajax({
                    url: "{{ route('admin.research-submission.approve', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        notes: notes
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`{{ __("Request failed:") }} ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.success(result.value.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        });
    }

    function rejectSubmission(id) {
        Swal.fire({
            title: '{{ __("Reject Submission?") }}',
            text: '{{ __("Please provide a reason for rejection") }}',
            icon: 'warning',
            input: 'textarea',
            inputLabel: '{{ __("Rejection Reason") }}',
            inputPlaceholder: '{{ __("Enter reason for rejection...") }}',
            inputValidator: (value) => {
                if (!value) {
                    return '{{ __("Please provide a rejection reason") }}';
                }
            },
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, Reject") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#dc3545',
            showLoaderOnConfirm: true,
            preConfirm: (notes) => {
                return $.ajax({
                    url: "{{ route('admin.research-submission.reject', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        notes: notes
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`{{ __("Request failed:") }} ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.success(result.value.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        });
    }
</script>
@endpush

