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
                    <button class="nav-link active bg-transparent researchStatusTab" id="allResearch-tab"
                        data-bs-toggle="tab" data-bs-target="#allResearch-tab-pane" type="button" role="tab"
                        aria-controls="allResearch-tab-pane" aria-selected="true"
                        data-status="all">{{ __('All') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent researchStatusTab" id="pendingResearch-tab"
                        data-bs-toggle="tab" data-bs-target="#pendingResearch-tab-pane" type="button" role="tab"
                        aria-controls="pendingResearch-tab-pane" aria-selected="false"
                        data-status="pending">{{ __('Pending') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent researchStatusTab" id="approvedResearch-tab"
                        data-bs-toggle="tab" data-bs-target="#approvedResearch-tab-pane" type="button" role="tab"
                        aria-controls="approvedResearch-tab-pane" aria-selected="false"
                        data-status="approved">{{ __('Approved') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent researchStatusTab" id="rejectedResearch-tab"
                        data-bs-toggle="tab" data-bs-target="#rejectedResearch-tab-pane" type="button" role="tab"
                        aria-controls="rejectedResearch-tab-pane" aria-selected="false"
                        data-status="rejected">{{ __('Rejected') }}</button>
                </li>
            </ul>
        </div>
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!-- All Research -->
            <div class="tab-pane fade show active researchStatusTab" id="allResearch-tab-pane" role="tabpanel"
                aria-labelledby="allResearch-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="researchTable-all">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Author Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Title') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('User') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Language') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Certificate') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Submitted') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Pending Research -->
            <div class="tab-pane fade" id="pendingResearch-tab-pane" role="tabpanel"
                aria-labelledby="pendingResearch-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="researchTable-pending">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Author Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Title') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('User') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Language') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Certificate') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Submitted') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Approved Research -->
            <div class="tab-pane fade" id="approvedResearch-tab-pane" role="tabpanel"
                aria-labelledby="approvedResearch-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="researchTable-approved">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Author Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Title') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('User') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Language') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Certificate') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Submitted') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Rejected Research -->
            <div class="tab-pane fade" id="rejectedResearch-tab-pane" role="tabpanel"
                aria-labelledby="rejectedResearch-tab" tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="researchTable-rejected">
                        <thead>
                            <tr>
                                <th>
                                    <div>{{ __('SL') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Author Name') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Title') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('User') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Language') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Status') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('Certificate') }}</div>
                                </th>
                                <th>
                                    <div class="text-nowrap">{{ __('Submitted') }}</div>
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
                <h5 class="modal-title" id="approveModalLabel">{{ __('Approve Research Submission') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to approve this submission and send the primary certificate?') }}</p>
                <div class="form-group">
                    <label for="approve_notes">{{ __('Notes (Optional)') }}</label>
                    <textarea class="form-control" id="approve_notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" id="confirmApprove">{{ __('Approve & Send Certificate') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">{{ __('Reject Research Submission') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Please provide a reason for rejection:') }}</p>
                <div class="form-group">
                    <label for="reject_notes">{{ __('Rejection Reason') }} <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="reject_notes" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmReject">{{ __('Reject') }}</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="research-submission-list-route" value="{{ route('admin.research-submission.index') }}">

@endsection

@push('script')
<script src="{{ asset('admin/custom/js/research-submission.js') }}"></script>
@endpush
