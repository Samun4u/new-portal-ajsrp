@extends('admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush
@section('content')
<!-- Content -->
<span id="searchresult">
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Search - Create -->
        <div
            class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
            <div class="flex-grow-1">
                <div class="search-one flex-grow-1 max-w-282">
                    <input type="text" placeholder="{{ __('Search here') }}..." id="datatableSearch" />
                    <button class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z"
                                stroke="#707070" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center align-items-md-start flex-wrap g-10">
            <!-- Left -->
            <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active bg-transparent applicationStatusTab" id="allApplication-tab"
                        data-bs-toggle="tab" data-bs-target="#allApplication-tab-pane" type="button" role="tab"
                        aria-controls="allApplication-tab-pane" aria-selected="true"
                        data-status="all">{{ __('All') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent applicationStatusTab" id="pendingApplication-tab"
                        data-bs-toggle="tab" data-bs-target="#pendingApplication-tab-pane" type="button" role="tab"
                        aria-controls="pendingApplication-tab-pane" aria-selected="false"
                        data-status="pending">{{ __('Pending') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent applicationStatusTab" id="approvedApplication-tab"
                        data-bs-toggle="tab" data-bs-target="#approvedApplication-tab-pane" type="button" role="tab"
                        aria-controls="approvedApplication-tab-pane" aria-selected="false"
                        data-status="approved">{{ __('Approved') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent applicationStatusTab" id="rejectedApplication-tab"
                        data-bs-toggle="tab" data-bs-target="#rejectedApplication-tab-pane" type="button" role="tab"
                        aria-controls="rejectedApplication-tab-pane" aria-selected="false"
                        data-status="rejected">{{ __('Rejected') }}</button>
                </li>
            </ul>
        </div>
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!-- All Applications -->
            <div class="tab-pane fade show active applicationStatusTab" id="allApplication-tab-pane" role="tabpanel"
                aria-labelledby="allApplication-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="applicationTable-all">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Email') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Institution & Country') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Field of Study') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Experience') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Applied') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="tab-pane fade" id="pendingApplication-tab-pane" role="tabpanel"
                aria-labelledby="pendingApplication-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="applicationTable-pending">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Email') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Institution & Country') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Field of Study') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Experience') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Applied') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="tab-pane fade" id="approvedApplication-tab-pane" role="tabpanel"
                aria-labelledby="approvedApplication-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="applicationTable-approved">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Email') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Institution & Country') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Field of Study') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Experience') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Applied') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="tab-pane fade" id="rejectedApplication-tab-pane" role="tabpanel"
                aria-labelledby="rejectedApplication-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="applicationTable-rejected">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Email') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Institution & Country') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Field of Study') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Experience') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Applied') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</span>

<!-- Approval Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">{{ __('Approve Reviewer Application') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ __('Approving this application will create a reviewer account. The applicant will receive login credentials via email.') }}
                </div>
                <p>{{ __('Are you sure you want to approve this application and create a reviewer account?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" id="confirmApprove">
                    <i class="fas fa-user-check"></i> {{ __('Approve & Create Account') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">{{ __('Reject Reviewer Application') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Please provide a reason for rejection:') }}</p>
                <div class="form-group">
                    <label for="reject_reason">{{ __('Rejection Reason') }} <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="reject_reason" rows="4" required
                        placeholder="{{ __('Please provide a detailed reason for rejection...') }}"></textarea>
                    <small class="text-muted">{{ __('Minimum 10 characters') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmReject">{{ __('Reject Application') }}</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="reviewer-application-list-route" value="{{ route('admin.reviewer-application.index') }}">

@endsection

@push('script')
<script src="{{ asset('admin/custom/js/reviewer-application.js') }}"></script>
@endpush

