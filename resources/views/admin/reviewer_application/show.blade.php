@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-20">
            <h3>{{ __('Reviewer Application Details') }}</h3>
            <a href="{{ route('admin.reviewer-application.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>

        <div class="row">
            <!-- Application Information -->
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> {{ __('Personal Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Full Name') }}:</strong>
                                <p>{{ $application->full_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('Email') }}:</strong>
                                <p><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Institution') }}:</strong>
                                <p>{{ $application->institution }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('Country') }}:</strong>
                                <p>{{ $application->country }}</p>
                            </div>
                        </div>

                        @if($application->orcid)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('ORCID') }}:</strong>
                                <p><a href="https://orcid.org/{{ $application->orcid }}" target="_blank">{{ $application->orcid }}</a></p>
                            </div>
                        </div>
                        @endif

                        @if($application->photo_file_id && $application->photoFile)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Photo') }}:</strong><br>
                                <img src="{{ getFileUrl($application->photo_file_id) }}" alt="Photo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-briefcase"></i> {{ __('Professional Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Qualification') }}:</strong>
                                <p>{{ $application->qualification }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('Current Position') }}:</strong>
                                <p>{{ $application->position }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Field of Study') }}:</strong>
                                <p>{{ $application->field_of_study }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('Experience Years') }}:</strong>
                                <p>{{ $application->experience_years }} {{ __('years') }}</p>
                            </div>
                        </div>

                        @if($application->subject_areas)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Subject Areas') }}:</strong>
                                <p>
                                    @foreach($application->subject_areas as $area)
                                        <span class="badge bg-primary me-1">{{ $area }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($application->keywords)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Keywords') }}:</strong>
                                <p>
                                    @foreach($application->keywords as $keyword)
                                        <span class="badge bg-secondary me-1">{{ $keyword }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($application->review_experience)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>{{ __('Review Experience') }}:</strong>
                                <p>{{ $application->review_experience }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Links -->
                @if($application->profile_links)
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-link"></i> {{ __('Profile Links') }}</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($application->profile_links['google_scholar']))
                        <p>
                            <strong>{{ __('Google Scholar') }}:</strong><br>
                            <a href="{{ $application->profile_links['google_scholar'] }}" target="_blank">
                                {{ $application->profile_links['google_scholar'] }}
                            </a>
                        </p>
                        @endif

                        @if(!empty($application->profile_links['linkedin']))
                        <p>
                            <strong>{{ __('LinkedIn') }}:</strong><br>
                            <a href="{{ $application->profile_links['linkedin'] }}" target="_blank">
                                {{ $application->profile_links['linkedin'] }}
                            </a>
                        </p>
                        @endif

                        @if(!empty($application->profile_links['researchgate']))
                        <p>
                            <strong>{{ __('ResearchGate') }}:</strong><br>
                            <a href="{{ $application->profile_links['researchgate'] }}" target="_blank">
                                {{ $application->profile_links['researchgate'] }}
                            </a>
                        </p>
                        @endif

                        @if(!empty($application->profile_links['website']))
                        <p>
                            <strong>{{ __('Personal Website') }}:</strong><br>
                            <a href="{{ $application->profile_links['website'] }}" target="_blank">
                                {{ $application->profile_links['website'] }}
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Files -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-file"></i> {{ __('Uploaded Documents') }}</h5>
                    </div>
                    <div class="card-body">
                        @if($application->cv_file_id && $application->cvFile)
                        <p>
                            <strong>{{ __('CV / Resume') }}:</strong><br>
                            <a href="{{ getFileUrl($application->cv_file_id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download"></i> {{ __('Download CV') }}
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status & Actions -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Application Status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('Status') }}:</strong><br>
                            @if($application->status === 'pending')
                                <span class="badge bg-warning text-dark fs-6">{{ __('Pending Review') }}</span>
                            @elseif($application->status === 'approved')
                                <span class="badge bg-success fs-6">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-danger fs-6">{{ __('Rejected') }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Applied Date') }}:</strong><br>
                            {{ $application->created_at->format('Y-m-d H:i') }}
                        </div>

                        @if($application->approved_at)
                        <div class="mb-3">
                            <strong>{{ __('Processed Date') }}:</strong><br>
                            {{ $application->approved_at->format('Y-m-d H:i') }}
                        </div>
                        @endif

                        @if($application->approver)
                        <div class="mb-3">
                            <strong>{{ __('Processed By') }}:</strong><br>
                            {{ $application->approver->name }}
                        </div>
                        @endif

                        @if($application->approvedUser)
                        <div class="mb-3">
                            <strong>{{ __('Reviewer Account') }}:</strong><br>
                            <a href="{{ route('admin.reviewer.details', encrypt($application->approved_user_id)) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user"></i> {{ __('View Reviewer Profile') }}
                            </a>
                        </div>
                        @endif

                        @if($application->rejection_reason)
                        <div class="mb-3">
                            <strong>{{ __('Rejection Reason') }}:</strong><br>
                            <div class="alert alert-danger">{{ $application->rejection_reason }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                @if($application->status === 'pending')
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">{{ __('Actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block w-100 mb-2" onclick="approveApplication({{ $application->id }})">
                            <i class="fas fa-user-check"></i> {{ __('Approve & Create Reviewer') }}
                        </button>
                        <button type="button" class="btn btn-danger btn-block w-100" onclick="rejectApplication({{ $application->id }})">
                            <i class="fas fa-times"></i> {{ __('Reject Application') }}
                        </button>
                    </div>
                </div>
                @endif

                <!-- Agreement Card -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Agreements') }}</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <i class="fas fa-check-circle text-success"></i>
                            {{ __('Terms & Conditions Agreed') }}
                        </p>
                        <p>
                            <i class="fas fa-check-circle text-success"></i>
                            {{ __('Consent Acknowledgment') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function approveApplication(id) {
        Swal.fire({
            title: '{{ __("Approve Application?") }}',
            html: '<div class="alert alert-info">' +
                  '<i class="fas fa-info-circle"></i> ' +
                  '{{ __("This will create a reviewer account and send login credentials to the applicant.") }}' +
                  '</div>' +
                  '<p>{{ __("Are you sure you want to approve this application?") }}</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, Approve") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('admin.reviewer-application.approve', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
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
                    window.location.href = "{{ route('admin.reviewer-application.index') }}";
                }, 1500);
            }
        });
    }

    function rejectApplication(id) {
        Swal.fire({
            title: '{{ __("Reject Application?") }}',
            text: '{{ __("Please provide a reason for rejection") }}',
            icon: 'warning',
            input: 'textarea',
            inputLabel: '{{ __("Rejection Reason") }}',
            inputPlaceholder: '{{ __("Enter detailed reason for rejection...") }}',
            inputAttributes: {
                'minlength': '10',
                'rows': '4'
            },
            inputValidator: (value) => {
                if (!value || value.length < 10) {
                    return '{{ __("Please provide a detailed rejection reason (minimum 10 characters)") }}';
                }
            },
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, Reject") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#dc3545',
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                return $.ajax({
                    url: "{{ route('admin.reviewer-application.reject', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        reason: reason
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

