@extends(auth()->user()->role == USER_ROLE_CLIENT || auth()->user()->role == USER_ROLE_REVIEWER ? 'user.layouts.app' : 'admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
        <div class="mt-4 step-seven-content">

                <!-- Step 1: Select a Journal -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Journal')}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{__('Field') }}</th>
                                        <th scope="col">{{__('Response') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{__('Selected Journal') }}</td>
                                        <td>
                                            <a href="#" class="text-decoration-none">
                                                @if (currentLang() == 'ar') {{      $clientOrderSubmission->journal->arabic_title ? $clientOrderSubmission->journal->arabic_title : $clientOrderSubmission->journal->title }} @else {{ $clientOrderSubmission->journal->title }}
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Manuscript Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Manuscript Information') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{__('Field') }}</th>
                                        <th scope="col">{{__('Response') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{__('Article Type') }}</td>
                                        <td>{{ $clientOrderSubmission->article_type->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Article Title') }}</td>
                                        <td>{{ $clientOrderSubmission->article_title }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Abstract') }}</td>
                                        <td>{{ $clientOrderSubmission->article_abstract }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Keywords') }}</td>
                                        <td>{{ $clientOrderSubmission->article_keywords }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Upload Files -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Uploaded Files') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{__('Field') }}</th>
                                        <th scope="col">{{__('Response') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{__('Full Article File') }}</td>
                                        <td>
                                            <a href="{{ getFileUrl($clientOrderSubmission->full_article_file) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-2"></i>
                                                {{ getFileData($clientOrderSubmission->full_article_file,'file_name') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @if($clientOrderSubmission->covert_letter_file)
                                    <tr>
                                        <td>{{__('Cover Letter') }}</td>
                                        <td>
                                            <a href="{{ getFileUrl($clientOrderSubmission->covert_letter_file) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-2"></i>
                                                {{ getFileData($clientOrderSubmission->covert_letter_file,'file_name') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($clientOrderSubmission->supplyment_material_files && count($clientOrderSubmission->supplyment_material_files) > 0)
                                    <tr>
                                        <td>{{__('Supplementary Materials') }}</td>
                                        <td>
                                            @foreach($clientOrderSubmission->supplyment_material_files as $supplyment_material_file)
                                            <a href="{{ getFileUrl($supplyment_material_file->file_id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-2"></i>
                                                {{ getFileData($supplyment_material_file->file_id,'file_name') }}
                                            </a>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Authors & Institutions -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Authors & Institutions') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{__('Field') }}</th>
                                        <th scope="col">{{__('Response') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($clientOrderSubmission->has_author)
                                        @foreach($clientOrderSubmission->authors as $index => $author)
                                            <tr>
                                                <td>{{__('Author') }} #{{ $index + 1 }}</td>
                                                <td>
                                                    <h6 class="mb-1">
                                                        <span class="fw-bold">{{__('Name') }}: </span> {{ $author->first_name }} {{ $author->last_name }}
                                                        <br>
                                                        <span class="fw-bold">{{__('Email') }}: </span> <a href="mailto:{{ $author->email }}" class="text-decoration-none">
                                                            {{ $author->email }}
                                                        </a>
                                                        <br>
                                                        <span class="fw-bold">{{__('Nationality') }}: </span> {{ $author->nationality }}
                                                        @if($author->whatsapp_number)
                                                        <br>
                                                        <span class="fw-bold">{{__('WhatsApp Number') }}: </span> {{ $author->whatsapp_number }}
                                                        @endif
                                                        @if($author->date_of_birth)
                                                        <br>
                                                        <span class="fw-bold">{{__('Date of Birth') }}: </span> {{ $author->date_of_birth }}
                                                        @endif
                                                    </h6>
                                                    @php $affiliations = $author->affiliation; @endphp

                                                    <span class="fw-bold">{{__('Affiliation') }}: </span>
                                                    @if(is_array($affiliations) || is_object($affiliations))
                                                        @foreach ($affiliations as $affiliation)
                                                            @if(is_string($affiliation))
                                                                <p class="mb-1 badge bg-primary">{{ $affiliation }}</p>
                                                            @else
                                                                @php $aff = (object)$affiliation; @endphp
                                                                <p class="mb-1 badge bg-primary">{{ $aff->department ?? '' }}, {{$aff->faculty ?? ''}}, {{$aff->university ?? ''}}, {{$aff->city ?? ''}}, {{$aff->country ?? ''}}</p>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <p class="mb-1 badge bg-primary">{{ $affiliations }}</p>
                                                    @endif
                                                    @php
                                                        $authorRoles = \App\Models\AuthorContributorRole::with('contributor_role')
                                                            ->where('client_order_submission_id', $clientOrderSubmission->id)
                                                            ->where('author_details_id', $author->id)
                                                            ->get();
                                                        $roleName = implode(', ', $authorRoles->pluck('contributor_role.role_name')->toArray());
                                                    @endphp
                                                    <p class="mb-1 fw-bold"><span class="fw-bold">{{__('Roles') }}: </span> {{ $roleName }}</p>
                                                    @if($author->corresponding_author)
                                                    <p class="mb-0 text-primary">* {{__('Corresponding Author') }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Declarations -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Declarations') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{__('I confirm this manuscript is not currently under consideration for publication elsewhere and has not been previously published by any other journal or publication forum.') }}
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('I am aware that accepted manuscripts are subject to an Article Processing Charge of $:charge, which is payable upon receipt of invoice, billed upon acceptance of submission for publication.', ['charge' => intval($clientOrderSubmission->journal->charges)]) }}
                                </li>

                                <li class="mb-3">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('I confirm all co-authors have read and agreed on the current version of this manuscript.') }}

                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{__('By submitting this manuscript to Science Publishing Group, I agree that if accepted, it will be published as open access, distributed under the terms of the Creative Commons Attribution 4.0 License') }} (
                                    <a href="">
                                        {{__('http://creativecommons.org/licenses/by/4.0/') }} <i
                                            class="fas fa-external-link-alt"></i>
                                    </a> ).
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{__('I confirm that this study was conducted in accordance with the local legislation and institutional requirements.') }}
                                </li>
                            </ul>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">{{__('Field') }}</th>
                                        <th scope="col">{{__('Response') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($clientOrderSubmission->has_conflict_of_interest)
                                    <tr>
                                        <td>{{__('Conflicts of Interest') }}</td>
                                        <td>{{ $clientOrderSubmission->conflict_details }}</td>
                                    </tr>
                                    @endif

                                    @if($clientOrderSubmission->has_funding)
                                        @foreach ($clientOrderSubmission->funders as $funder)
                                        <tr>
                                            <td>Funding</td>
                                            <td>{{ $funder->funder }} @if($funder->grant_number) ({{ $funder->grant_number }}) @endif</td>
                                        </tr>
                                        @endforeach
                                    @endif

                                    @if($clientOrderSubmission->has_data_availability_statement)
                                    @php
                                        $dataAvailabilityStatement = config('constants.DATA_AVAILABILITY_STATEMENTS');
                                    @endphp
                                    <tr>
                                        <td>{{__('Data Availability Statement') }}</td>
                                        <td>
                                            @if(array_key_exists($clientOrderSubmission->data_availability_statement, $dataAvailabilityStatement))
                                            {{ $dataAvailabilityStatement[$clientOrderSubmission->data_availability_statement] }}
                                            @endif
                                            @if($clientOrderSubmission->data_availability_url)
                                            <a href="{{ $clientOrderSubmission->data_availability_url }}" class="text-decoration-none">
                                                {{ $clientOrderSubmission->data_availability_url }}
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Reviewers -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check me-2"></i>
                            {{__('Suggested & Opposed Reviewers') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th colspan="2" class="text-center">{{__('Suggested Reviewers') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    @if($clientOrderSubmission->suggested_reviewers)
                                                        @foreach ($clientOrderSubmission->__suggested_reviewers as $suggested_reviewer)
                                                        <div class="mb-10">
                                                            <p class="mb-1">{{__('Referred Article Title') }}: <span class="fw-bold">{{ $suggested_reviewer->referred_article_title }}</span>
                                                            </p>
                                                            <p class="mb-1">
                                                            {{__('Corresponding author') }}:
                                                                <span class="fw-bold">{{ $suggested_reviewer->corresponding_author_first_name }} {{ $suggested_reviewer->corresponding_author_last_name }}</span>
                                                                (<a href="mailto:{{ $suggested_reviewer->corresponding_author_email }}" class="text-decoration-none">
                                                                    {{ $suggested_reviewer->corresponding_author_email }}
                                                                </a>)
                                                            </p>
                                                            <p class="mb-0">
                                                            {{__('First author') }}:
                                                                <span class="fw-bold">{{ $suggested_reviewer->first_author_first_name }} {{ $suggested_reviewer->first_author_last_name }}</span>
                                                                (<a href="mailto:{{ $suggested_reviewer->first_author_email }}" class="text-decoration-none">
                                                                    {{ $suggested_reviewer->first_author_email }}
                                                                </a>)
                                                            </p>
                                                        </div>
                                                        @endforeach
                                                    @else
                                                    <p class="text-muted mb-0">{{__('No reviewers have been added') }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th colspan="2" class="text-center">{{__('Opposed Reviewers') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    @if($clientOrderSubmission->has_opposed_reviewers)
                                                        @foreach ($clientOrderSubmission->__opposed_reviewers as $opposed_reviewer)
                                                        <div class="mb-10">
                                                            <p class="mb-1 fw-bold">{{__('Name') }}: <span class="fw-bold">{{ $opposed_reviewer->first_name }} {{ $opposed_reviewer->last_name }}</span></p>
                                                            <p class="mb-1">
                                                            {{__('Email') }}:
                                                                <a href="mailto:{{ $opposed_reviewer->email }}" class="text-decoration-none">
                                                                    {{ $opposed_reviewer->email }}
                                                                </a>
                                                            </p>
                                                            <p class="mb-0">{{__('Affiliation') }}: {{ $opposed_reviewer->affiliation }}</p>
                                                        </div>
                                                        @endforeach
                                                    @else
                                                    <p class="text-muted mb-0">{{__('No opposed reviewers have been added') }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


@endsection

@push('script')
    <script></script>
@endpush
