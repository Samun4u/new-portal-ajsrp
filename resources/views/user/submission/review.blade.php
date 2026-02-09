@extends('user.submission.main')
@section('submission-content')
<!-- step 7 -->
<div class="tab-pane fade {{ $step === 'stepSeven' ? 'show active' : '' }}" id="v-pills-stepSeven" role="tabpanel" aria-labelledby="v-pills-stepSeven-tab" tabindex="0">

<form class="ajax" action="{{ route('user.submission.review.save') }}"  method="POST" data-handler="commonResponse" id="stepSevenForm">
@csrf
<input type="hidden" class="step-seven-client-order-id"  name="id" value="{{ $clientOrderId }}" >

    <div class="step-seven">

        <div class="header-title">
            <h2>{{ __('Step 7') }}: {{ __('Review and Submit') }}</h2>
            <h4>{{ __("Please thoroughly review the information presented below and make any necessary corrections if needed. Once you've completed the review, please click 'Submit' to proceed and finish your submission.") }}</h4>
        </div>

        <!-- step-seven-content -->
        <div class="step-seven-content">

            <!-- item -->
            <div class="step-seven-item">
                <h3>
                    <i class="fas fa-check"></i>
                    {{ __('Step 1') }}: {{ __('Select a Journal') }}
                </h3>
                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ __('Selected Journal') }}</td>
                            <td>
                                <a href="#">
                                    @if (currentLang() == 'ar') {{ $clientOrderSubmission->journal->arabic_title ? $clientOrderSubmission->journal->arabic_title : $clientOrderSubmission->journal->title }} @else {{ $clientOrderSubmission->journal->title }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 2') }}: {{ __('Manuscript Information') }}
                    </h3>
                    <a href="{{ route('user.submission.article.information', ['action' => 'update','id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ __('Article Type') }}</td>
                            <td>{{ $clientOrderSubmission->article_type->name }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('Article Title') }}</td>
                            <td>{{ $clientOrderSubmission->article_title }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('Abstract') }}</td>
                            <td>{{ $clientOrderSubmission->article_abstract }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('Keywords') }}</td>
                            <td>{{ $clientOrderSubmission->article_keywords }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 3') }}: {{ __('Upload Files') }}
                    </h3>
                    <a href="{{ route('user.submission.upload.files', ['id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($clientOrderSubmission->full_article_file)
                        <tr>
                            <td>{{ __('Full Article File') }}</td>
                            <td>
                                <a href="{{ getFileUrl($clientOrderSubmission->full_article_file) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                    <i class="fas fa-arrow-down"></i>
                                    <span class="truncate-url">{{ getFileData($clientOrderSubmission->full_article_file, 'original_name') ?: __('Download File') }}</span>
                                </a>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td>{{ __('Full Article File') }}</td>
                            <td>
                                <span class="text-muted">{{ __('No file uploaded') }}</span>
                            </td>
                        </tr>
                        @endif
                        @if($clientOrderSubmission->covert_letter_file)
                        <tr>
                            <td>{{ __('Cover Letter') }}</td>
                            <td>
                                <a href="{{ getFileUrl($clientOrderSubmission->covert_letter_file) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                    <i class="fas fa-arrow-down"></i>
                                    <span class="truncate-url">{{ getFileData($clientOrderSubmission->covert_letter_file, 'original_name') ?: __('Download File') }}</span>
                                </a>
                            </td>
                        </tr>
                        @endif
                        @if(
                            $clientOrderSubmission->supplyment_material_files &&
                            count($clientOrderSubmission->supplyment_material_files) > 0
                        )
                        <tr>
                            <td>{{ __('Supplementary Materials') }}</td>
                            <td>
                                @foreach($clientOrderSubmission->supplyment_material_files as $supplyment_material_file)
                                <a href="{{ getFileUrl($supplyment_material_file->file_id) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; margin-bottom: 8px;">
                                    <i class="fas fa-arrow-down"></i>
                                    <span class="truncate-url">{{ getFileData($supplyment_material_file->file_id, 'original_name') ?: __('Download File') }}</span>
                                </a>
                                @endforeach
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 4') }}: {{ __('Authors & Institutions') }}
                    </h3>
                    <a href="{{ route('user.submission.add.authors', ['id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($clientOrderSubmission->has_author)
                            @foreach($clientOrderSubmission->authors as $index => $author)
                                <tr>
                                    <td>{{ __('Author') }} #{{ $index + 1 }}</td>
                                    <td class="step-four-table">
                                        <h6>
                                            {{ $author->first_name }} {{ $author->last_name }}
                                            <a href="temp: {{ $author->email }}">
                                                {{ $author->email }}
                                            </a>
                                        </h6>
                                        <h6>
                                            Nationality: {{ $author->nationality }}
                                            @if($author->whatsapp_number)
                                                <br>
                                                WhatsApp: {{ $author->whatsapp_number }}
                                            @endif
                                            @if($author->date_of_birth)
                                                <br>
                                                DOB: {{ \Carbon\Carbon::parse($author->date_of_birth)->format('F j, Y') }}
                                            @endif
                                        </h6>
                                        @php
                                            $affiliations = json_decode($author->affiliation);
                                        @endphp
                                        @foreach ($affiliations as $affiliation)
                                        <!-- if deparment is empty -->
                                        @if (is_string($affiliation))
                                        <p>{{ $affiliation }}</p>
                                        @elseif (is_object($affiliation) && !empty($affiliation->department))
                                        <p>{{ $affiliation->department }}, {{ $affiliation->faculty }}, {{ $affiliation->university }}, {{ $affiliation->city }}, {{ $affiliation->country }}</p>
                                        @elseif (is_object($affiliation))
                                        <p>{{ $affiliation->faculty ?? '' }}{{ $affiliation->university ? ', ' . $affiliation->university : '' }}{{ $affiliation->city ? ', ' . $affiliation->city : '' }}{{ $affiliation->country ? ', ' . $affiliation->country : '' }}</p>
                                        @endif
                                        @endforeach
                                        @php
                                            $authorRoles = \App\Models\AuthorContributorRole::with('contributor_role')->where('client_order_submission_id', $clientOrderSubmission->id)->where('author_details_id', $author->id)->get();

                                            $isArabic = session('local') === 'ar';
                                            if($isArabic) {
                                                $roleName = implode(', ', $authorRoles->pluck('contributor_role.arabic_name')->toArray());
                                            }else{

                                                $roleName = implode(', ', $authorRoles->pluck('contributor_role.role_name')->toArray());
                                            }
                                        @endphp
                                        <h5> {{ __('Roles') }}: {{ $roleName }}</h5>
                                        <h5>
                                            @if($author->corresponding_author)
                                             {{ __('Corresponding Author') }}
                                            @endif
                                        </h5>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 5') }}: {{ __('Declarations') }}
                    </h3>
                    <a href="{{ route('user.submission.declarations', ['id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                </div>

                <div class="step-five-declaration">

                    <ul>
                        <li>
                            <i class="fas fa-check"></i>
                            <p>
                            {{ __('I confirm this manuscript is not currently under consideration for publication elsewhere and has not been previously published by any other journal or publication forum.') }}
                            </p>
                        </li>

                        <li>
                            <i class="fas fa-check"></i>
                            <p>
                                {{ __('I am aware that accepted manuscripts are subject to an Article Processing Charge of $:amount, which is payable upon receipt of invoice, billed upon acceptance of submission for publication.', ['amount' => intval($clientOrderSubmission->journal->charges)]) }}
                            </p>

                        </li>

                        <li>
                            <i class="fas fa-check"></i>
                            <p>
                            {{ __('I confirm all co-authors have read and agreed on the current version of this manuscript.') }}

                            </p>
                        </li>

                        <li>
                            <i class="fas fa-check"></i>
                            <p>
                            {{ __('By submitting this manuscript to Science Publishing Group, I agree thatif accepted, it will be published as open access, distributed under the terms of the Creative Commons Attribution 4.0 License') }} (
                                <a href="">
                                {{ __('http://creativecommons.org/licenses/by/4.0/') }} <i
                                        class="fas fa-external-link-alt"></i>
                                </a> ).
                            </p>
                        </li>

                        <li>
                            <i class="fas fa-check"></i>
                            <p>
                            {{ __('I confirm that this study was conducted in accordance with the local legislation and institutional requirements.') }}
                            </p>
                        </li>
                    </ul>

                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($clientOrderSubmission->has_conflict_of_interest)
                        <tr>
                            <td>{{ __('Conflicts of Interest') }}</td>
                            <td>{{ $clientOrderSubmission->conflict_details }}</td>
                        </tr>
                        @endif

                        @if($clientOrderSubmission->has_funding)
                            @foreach ($clientOrderSubmission->funders as $funder)
                            <tr>
                                <td>{{ __('Funding') }}</td>
                                <td>{{ $funder->funder }} @if($funder->grant_number) ({{ $funder->grant_number }}) @endif</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($clientOrderSubmission->has_data_availability_statement)
                        @php
                            $dataAvailabilityStatement = config('constants.DATA_AVAILABILITY_STATEMENTS');
                        @endphp
                        <tr>
                            <td>{{ __('Data Availability Statement') }} </td>
                            <td>
                                @if(array_key_exists($clientOrderSubmission->data_availability_statement, $dataAvailabilityStatement))
                                {{ $dataAvailabilityStatement[$clientOrderSubmission->data_availability_statement] }}
                                @endif
                                @if($clientOrderSubmission->data_availability_url)
                                {{ $clientOrderSubmission->data_availability_url }}
                                @endif
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 6') }}: {{ __('Suggest Reviewers') }}
                    </h3>
                    <a href="{{ route('user.submission.add-reviewers.from-references', ['id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>{{ __('Suggested Reviewers') }}</td>
                            <td>
                            @if($clientOrderSubmission->suggested_reviewers)
                                @foreach ($clientOrderSubmission->__suggested_reviewers as $suggested_reviewer)
                                    <p>Referred Article Title: {{ $suggested_reviewer->referred_article_title ?? 'N/A' }}</p>
                                    <p><strong>{{ $suggested_reviewer->corresponding_author_first_name }} {{ $suggested_reviewer->corresponding_author_last_name }}</strong> <strong>(</strong><a href="temp: {{ $suggested_reviewer->corresponding_author_email }}">{{ $suggested_reviewer->corresponding_author_email }} </a><strong>)</strong></p>
                                    <p><strong>{{ $suggested_reviewer->first_author_first_name }} {{ $suggested_reviewer->first_author_last_name }}</strong> <strong>(</strong><a href="temp: {{ $suggested_reviewer->first_author_email }}">{{ $suggested_reviewer->first_author_email }} </a><strong>)</strong></p>
                                    <br>
                                @endforeach
                            @else
                            <h6 class="table-data-step-six">
                            {{ __('No reviewers have been added') }}
                                    <a href="{{ route('user.submission.add-reviewers.from-references', ['id' => $clientOrderId]) }}">
                                    {{ __('click to add') }}
                                    </a>
                                    .
                            </h6>
                            @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- item -->
            <div class="step-seven-item">

                <!-- heading -->
                <div class="heading">
                    <h3>
                        <i class="fas fa-check"></i>
                        {{ __('Step 6') }}: {{ __('Opposed Reviewers') }}
                    </h3>
                    <a href="{{ route('user.submission.add-reviewers.opposed', ['id' => $clientOrderId]) }}">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                </div>

                <!-- table -->
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Field') }}</th>
                            <th>{{ __('Response') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ __('Opposed Reviewers') }}</td>
                            <td>
                                @if($clientOrderSubmission->has_opposed_reviewers)
                                    @foreach ($clientOrderSubmission->__opposed_reviewers as $opposed_reviewer)
                                        <p><strong>{{ $opposed_reviewer->first_name }} {{ $opposed_reviewer->last_name }}</strong> <strong>(</strong><a href="temp: {{ $opposed_reviewer->email }}">{{ $opposed_reviewer->email }} </a><strong>)</strong></p>
                                        <p>{{ __('Affiliation') }}: {{ $opposed_reviewer->affiliation ?? 'N/A' }}</p>
                                        <br>
                                    @endforeach
                                @else
                                <h6>
                                {{ __('No opposed reviewers have been added.') }}
                                </h6>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- continue -->
            <div class="continue-button">
                <!-- right -->
                <div class="right">
                {{-- <button class="continue" id="save_and_continue_button" type="submit" value="save_and_continue"> --}}
                <button class="continue" id="save_and_continue_button" type="submit">
                        <span class="submit-text">{{__("Submit")}}</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                </div>

                <!-- left -->
                <div class="left">
                <a href="{{ route('user.submission.add-reviewers.opposed', ['id' => $clientOrderId]) }}">
                    <button class="previous" type="button">{{ __('Previous Step') }}</button>
                </div>


            </div>

        </div>

    </div>
</form>
</div>
@endsection
@push('script')
<script>
    $('#stepSevenForm').on('submit', function () {
        const submitBtn = $('#save_and_continue_button');
        const spinner = submitBtn.find('.spinner-border');
        const submitText = submitBtn.find('.submit-text');

        // Show spinner and disable button
        spinner.removeClass('d-none');
        submitText.text("{{ __('Submitting...') }}");
        submitBtn.prop('disabled', true);

    });

    // // Handle AJAX completion using custom event
    // $(document).on('commonResponse', function(event, response) {
    //     const submitBtn = $('#save_and_continue_button');
    //     const spinner = submitBtn.find('.spinner-border');
    //     const submitText = submitBtn.find('.submit-text');

    //     // Hide spinner and re-enable button
    //     spinner.addClass('d-none');
    //     submitText.text("{{ __('Submit') }}");
    //     submitBtn.prop('disabled', false);
    // });


</script>
@endpush
