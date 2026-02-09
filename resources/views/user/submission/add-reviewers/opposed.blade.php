@extends('user.submission.main')
@section('submission-content')
<!-- step 6 sub-two -->
<div class="tab-pane fade {{ $step === 'stepSixSubTwo' ? 'show active' : '' }}" id="v-pills-StepSixSubTwo" role="tabpanel" aria-labelledby="v-pills-StepSixSubTwo-tab" tabindex="0">

    <form action="{{ route('user.submission.add-reviewers.opposed.save') }}"  method="POST" data-handler="commonResponse" class="ajax">
    @csrf
    <input type="hidden" class="step-six-sub-two-client-order-id"  name="id" value="{{ $clientOrderId }}" >

    <div class="step-six-sub-two">

        <div class="header-title">
            <h2>{{ __('Opposed Reviewers') }}</h2>
            <h6>{{ __('If you have experts in your field whom you object to as potential peer reviewers for this work, please provide their information.') }}</h6>
        </div>

        <!-- yes/no -->
        <div class="yes-no">

            <label for="opposedReviewersYes" style="display: inline-flex; align-items: center;">
                <input type="radio" name="opposed_reviewers_radio" id="opposedReviewersYes" style="margin-right: 5px;" value="1" {{ isset($clientOrderSubmission->__opposed_reviewers) && count(json_decode($clientOrderSubmission->__opposed_reviewers ?? '[]', true)) > 0 ? 'checked' : '' }}>
                {{ __('Yes') }}
            </label>

            <label for="opposedReviewersNo" style="display: inline-flex; align-items: center;">
                <input type="radio" name="opposed_reviewers_radio" id="opposedReviewersNo" style="margin-right: 5px;" value="0" {{ !isset($clientOrderSubmission->__opposed_reviewers) || count(json_decode($clientOrderSubmission->__opposed_reviewers ?? '[]', true)) <= 0 ? 'checked' : '' }}>
                {{ __('No') }}
            </label>

        </div>

        <div class="step-six-sub-one-bg">
            @php
                $opposedReviewers = json_decode($clientOrderSubmission->__opposed_reviewers ?? '[]', true);
                $reviewersCount = max(1, count($opposedReviewers));
            @endphp

            @for ($i = 0; $i < $reviewersCount; $i++)
                <!-- item {{ $i + 1 }} -->
                <div class="step-six-sub-one-bg-white">

                    <!-- heading -->
                    <div class="heading">
                        <h6>{{ __('Opposed Reviewer') }} #{{ $i + 1 }}</h6>
                        <button style="{{ $i == 0 ? 'display: none;' : '' }}">
                            <span> <i class="far fa-trash-alt"></i></span>
                            Delete
                        </button>
                    </div>

                    <ul class="form-input">
                        <li>
                            <label for="">{{ __('First Name') }}: <span>*</span></label>
                            <input type="text" placeholder="{{ __('First Name') }}" name="opposed_reviewers[{{ $i }}][first_name]" value="{{ $opposedReviewers[$i]['first_name'] ?? '' }}">
                        </li>

                        <li>
                            <label for="">{{ __('Last Name') }}: <span>*</span></label>
                            <input type="text" placeholder="{{ __('Last Name') }}" name="opposed_reviewers[{{ $i }}][last_name]" value="{{ $opposedReviewers[$i]['last_name'] ?? '' }}">
                        </li>

                        <li>
                            <label for="">{{ __('Email') }}: <span>*</span></label>
                            <input type="text" placeholder="{{ __('Email') }}" name="opposed_reviewers[{{ $i }}][email]" value="{{ $opposedReviewers[$i]['email'] ?? '' }}">
                        </li>

                        <li>
                            <label for="">{{ __('Affiliation') }}: <span>*</span></label>
                            <input type="text" placeholder="{{ __('Affiliation') }}" name="opposed_reviewers[{{ $i }}][affiliation]" value="{{ $opposedReviewers[$i]['affiliation'] ?? '' }}">
                        </li>

                    </ul>

                </div>
            @endfor

            <!-- add-one-more -->
            <button class="add-one-more" type="button">
                <span>
                    <i class="fas fa-plus"></i>
                </span>
                {{ __('Add One More') }}
            </button>

        </div>

        <!-- continue -->
        <div class="continue-button">

            <!-- right -->
            <div class="right">
            <a href="{{ route('user.submission.add-reviewers.from-references', ['id' => $clientOrderId]) }}">
                <button class="previous" type="button">{{ __('Previous Step') }}</button>
            </a>
            </div>

            <!-- left -->
            <div class="left">
                <button type="submit" id="save_button" value="save" class="previous">{{ __('Save') }}</button>
                <button type="submit" id="save_and_continue_button" value="save_and_continue" class="continue"> {{ __('Save and Continue') }}
                </button>
            </div>

        </div>

    </div>
    </form>
</div>
@endsection
@push('script')
<script>
(function ($) {
    "use strict";
    
    $(document).ready(function () {
        let reviewerCount = {{ $reviewersCount }}; // Initialize with existing count

        // Show/hide based on radio button selection
        $('input[name="opposed_reviewers_radio"]').change(function () {
            $(".step-six-sub-one-bg").toggle($("#opposedReviewersYes").is(":checked"));
        }).trigger('change');

        // Add new item with proper dynamic names
        $(".add-one-more").click(function () {
            const $lastItem = $(".step-six-sub-one-bg-white").last();
            const $newItem = $lastItem.clone(true);
            
            // Update input names with new index
            $newItem.find('input').each(function() {
                const name = $(this).attr('name').replace(/\[\d+\]/g, `[${reviewerCount}]`);
                $(this).attr('name', name).val('');
            });

            $newItem.insertBefore(this);
            reviewerCount++;
            updateReviewerNumbers();
        });

        // Delete item
        $(document).on("click", ".step-six-sub-one-bg-white .heading button", function () {
            if ($(".step-six-sub-one-bg-white").length > 1) {
                $(this).closest(".step-six-sub-one-bg-white").remove();
                reviewerCount--;
                updateReviewerNumbers();
            }
        });

        // Validation handler
        $('form.ajax').on('submit', function(e) {
            const isYes = $('#opposedReviewersYes').is(':checked');
            let isValid = true;

            clearErrors();

            if (isYes) {
                const messages = {
                    required: "{{ __(':field is required') }}",
                    invalidEmail: "{{ __('Invalid email format') }}",
                    first_name: "{{ __('First Name') }}",
                    last_name: "{{ __('Last Name') }}",
                    email: "{{ __('Email') }}",
                    affiliation: "{{ __('Affiliation') }}"
                };

                $('.step-six-sub-one-bg-white').each(function() {
                    const $reviewer = $(this);
                    const fields = {
                        first_name: $reviewer.find('input[name$="[first_name]"]'),
                        last_name: $reviewer.find('input[name$="[last_name]"]'),
                        email: $reviewer.find('input[name$="[email]"]'),
                        affiliation: $reviewer.find('input[name$="[affiliation]"]')
                    };

                    // Validate each field
                    Object.entries(fields).forEach(([field, input]) => {
                        const value = input.val().trim();
                        if (!value) {
                            let fieldName = messages[field];
                            showError(input, messages.required.replace(':field', fieldName));
                            isValid = false;
                        }
                        if (field === 'email' && value && !isValidEmail(value)) {
                            showError(input, messages.invalidEmail);
                            isValid = false;
                        }
                    });
                });
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });

        function clearErrors() {
            $('.error-message-opposed').remove();
            $('input').removeClass('error-border');
        }

        function showError(input, message) {
            const $input = $(input);
            $input.addClass('error-border');
            $input.closest('li').append(`
                <div class="error-message-opposed">
                    <i class="fas fa-exclamation-triangle error-icon"></i>
                    ${message}
                </div>
            `);
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Update error on input change (only after user stops typing)
        let timeout;
        $(document).on('input', 'input', function() {
            clearTimeout(timeout);
            const $input = $(this);
            
            timeout = setTimeout(() => {
                $input.removeClass('error-border');
                $input.siblings('.error-message-opposed').remove();
                
                if ($input.val().trim() && ($input.attr('name').includes('email') ? isValidEmail($input.val()) : true)) {
                    $input.removeClass('error-border');
                    $input.siblings('.error-message-opposed').remove();
                }
            }, 500);
        });

        function updateReviewerNumbers() {
            $(".step-six-sub-one-bg-white").each(function(index) {
                $(this).find("h6").text(`Opposed Reviewer #${index + 1}`);
                $(this).find(".heading button").toggle(index > 0);
            });
        }
    });
})(jQuery);
</script>
<style>
.error-border { border: 1px solid #dc3545 !important; }
.error-message-opposed { color: #dc3545; font-size: 0.875em; margin-top: 0.25rem; }
.error-icon { margin-right: 0.5rem; }
</style>
@endpush