@extends('user.submission.main')
@section('submission-content')
<!-- step 2  -->
<div class="tab-pane fade {{ $step === 'stepTwo' ? 'show active' : '' }}" id="v-pills-stepTwo" role="tabpanel" aria-labelledby="v-pills-profile-tab"
                    tabindex="0">
                    <div class="step-two">

                        <div class="header-title">
                            <h2>{{ __('Step 2') }}: {{ __('Manuscript Information') }}</h2>
                        </div>

                        <form action="{{ route('user.submission.article.information.save') }}" method="POST" data-handler="commonResponse" class="ajax">
                            @csrf
                            <input type="hidden" name="selected_journal_id" value="{{ $selectedJournal->id }}">
                            <input type="hidden" class="step-tow-client-order-id"
                            @if(isset($clientOrderId) && $clientOrderId)
                                name="id"
                                value="{{ $clientOrderId }}"
                            @endif
                            >
                            <div class="">
                                <label for="">{{ __('Article Type') }}: <span>*</span></label>
                                <select name="article_type_id" required>
                                    <option value="">--{{ __('Select One') }}--</option>
                                    @foreach ($articleTypes as  $articleType)
                                    <option value="{{ $articleType->id }}" {{ isset($clientOrderSubmission) && $clientOrderSubmission->article_type_id == $articleType->id ? 'selected' : '' }}>
                                        @if (session()->has('local') && session('local') == 'ar')
                                            {{ $articleType->arabic_name }}
                                        @else
                                            {{ $articleType->name }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="">
                                <label for="">{{ __('Article Title') }}: <span>*</span></label>
                                <input type="text" name="title" placeholder="{{ __('Please type the article title') }}" value="{{ isset($clientOrderSubmission) ? $clientOrderSubmission->article_title : '' }}" required>
                            </div>

                            <div class="">
                                <label for="">{{ __('Abstract') }}: <span>*</span></label>
                                <textarea name="abstract" rows="10" placeholder="{{ __('Please type the Abstract') }}" required>{{ isset($clientOrderSubmission) ? $clientOrderSubmission->article_abstract : '' }}</textarea>
                            </div>

                            <div class="">
                                <label for="">{{ __('Keywords') }}: <span>*</span></label>
                                <textarea name="keywords" rows="5" placeholder="{{ __('Please type the Keywords') }}" required>{{ isset($clientOrderSubmission) ? $clientOrderSubmission->article_keywords : '' }}</textarea>

                                <h4>1. {{ __('Use comma to separate each keyword') }}.</h4>
                                <h4>2. {{ __('For example: Keyword 1, Keyword 2, Keyword 3') }}...</h4>

                            </div>

                        <!-- continue -->
                        <div class="continue-button">

                            <?php

                                $bySubjectRoute = route('user.submission.select-a-journal',['by' => 'by-subject']);
                                if(isset($selectedJournal) && $selectedJournal && isset($clientOrderId) && $clientOrderId){
                                    $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update', 'id' => $clientOrderId]);
                                }elseif(isset($selectedJournal) && $selectedJournal){
                                    $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update']);
                                }
                                ?>
                                <!-- left -->
                                <div class="left">
                                    <button type="submit" id="save_button" value="save" class="previous save-btn">{{ __('Save') }}</button>
                                    <button type="submit" id="save_and_continue_button" value="save_and_continue" class="continue save-continue-btn"> {{ __('Save and Continue') }}</button>
                                </div>

                            <!-- right -->
                            <div class="right">
                                <a class="step-one-route" href="{{ $bySubjectRoute }}">
                                    <button type="button" class="previous">{{ __('Previous Step') }}</button>
                                </a>
                            </div>


                        </div>

                    </form>

                    </div>

                </div>
@endsection
@push('script')
<script>
  (function($) {
    "use strict";

    $(document).ready(function() {
        // When either save button is clicked
        $('.save-btn, .save-continue-btn').click(function(e) {
            e.preventDefault();

            // Clear any existing error messages
            $('.error-message').remove();

            // Check each required field
            let hasErrors = false;

             // Article Type validation
            if ($('select[name="article_type_id"]').val() === '') {
                showErrorMessage($('select[name="article_type_id"]'),  "{{ __('Article type is required.') }}");
                hasErrors = true;
            }

            // Article Title validation
            if ($('input[name="title"]').val().trim() === '') {
                showErrorMessage($('input[name="title"]'),  "{{ __('Article title is required.') }}");
                hasErrors = true;
            }

            // Abstract validation
            if ($('textarea[name="abstract"]').val().trim() === '') {
                showErrorMessage($('textarea[name="abstract"]'), "{{ __('Abstract is required.') }}");
                hasErrors = true;
            }

            // Keywords validation
            if ($('textarea[name="keywords"]').val().trim() === '') {
                showErrorMessage($('textarea[name="keywords"]'), "{{ __('Keywords are required.') }}");
                hasErrors = true;
            }

            // If no errors, submit the form
            if (!hasErrors) {
                let form = $(this).closest('form');
                form.submit();
            }
        });

        // Function to display error message with icon
        function showErrorMessage(element, message) {
            // Add the error message after the element's parent div
            element.parent('div').append(
                '<div class="error-message" style="color: red; margin-top: 5px;">' +
                '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="margin-right: 5px;"></i>' +
                message + '</div>'
            );
        }
    });

})(jQuery);
</script>
@endpush
