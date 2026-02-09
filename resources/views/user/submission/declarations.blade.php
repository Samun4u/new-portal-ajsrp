@extends('user.submission.main')
@push('style')
<style>
    /* Style to display labels and inputs on the same line */
/* .yes-no label {
    display: inline-block !important;
    margin-right: 10px;
}

.yes-no input[type="radio"] {
    display: inline-block !important;
    margin-right: 5px;
} */

/* Hide textarea by default */
.yes-no textarea {
    display: none;
}

/* Hide funding information by default */
.step-four-bg {
    display: none;
}

/* Add space between funding blocks when cloned */
.step-four-bg .step-four-bg-white {
    margin-bottom: 20px;
}

/* Add these styles to your existing <style> section */
.error-message {
    color: red;
    margin-top: 5px;
    font-size: 14px;
    display: block;
}

.error-message i {
    margin-right: 5px;
}

input.error, textarea.error {
    border-color: red !important;
}

.step-five-item.error label {
    color: red;
}

</style>
@endpush
@section('submission-content')
 <!-- step 5 -->
 <div class="tab-pane fade {{ $step === 'stepFive' ? 'show active' : '' }}" id="v-pills-StepFive" role="tabpanel" aria-labelledby="v-pills-StepFive-tab"
                    tabindex="0">

                    <form action="{{ route('user.submission.declarations.save') }}"  method="POST" data-handler="commonResponse" class="ajax">
                        @csrf
                        <input type="hidden" class="step-five-client-order-id"  name="id" value="{{ $clientOrderId }}" >
                    <div class="step-five">
                        <div class="header-title">
                            <h2>{{ __('Step 5') }}: {{ __('Declarations') }}</h2>
                        </div>

                        <h3>{{ __('Declarations') }} <span>*</span></h3>

                        <h5>{{ __('Before continuing, please make sure you have reviewed the items below:') }}</h5>

                        <div class="step-five-bg-content">

                            <!-- item -->
                             @foreach($declarations as $item)
                            <div class="step-five-item">

                                <label for="stepFiveCheck_{{ $item->id }}">

                                    <div class="checkbox">
                                        <span>*</span>
                                        <input type="checkbox"
                                        id="stepFiveCheck_{{ $item->id }}"
                                        name="declarations[][{{$item->id}}]" value="{{ $item->id }}"
                                        @if(isset($clientOrderSubmission->declarations) && in_array($item->id, $clientOrderSubmission->declarations->pluck('declaration_id')->toArray())) checked @endif
                                        >
                                    </div>

                                    <h6>
                                        @if (session()->has('local') && session('local') == 'ar')
                                            @php
                                                $selectedJournalCharges = intval($service->price);
                                                $modifiedTitle = str_replace('85', $selectedJournalCharges, $item->arabic_title);
                                            @endphp
                                        @else
                                            @php
                                                $selectedJournalCharges = intval($service->price);
                                                $modifiedTitle = str_replace('85', $selectedJournalCharges, $item->title);
                                            @endphp
                                        @endif
                                        {{ $modifiedTitle }}
                                    </h6>

                                </label>

                            </div>
                            @endforeach
                        </div>

                        <ul class="step-five-list">

                            <!-- item -->
                            <li>
                                <h3>{{ __('Declarations') }} <span>*</span></h3>

                                <h6>
                                    {{ __('Please describe any of the authors’ potential conflicts of interest, such as financial interests, affiliations, or personal interests or beliefs, that could be perceived to affect the objectivity or neutrality of the manuscript.') }}
                                    <a href="">{{ __('Learn More About Conflicts of Interest.') }} <i class="fas fa-external-link-alt"></i></a>
                                </h6>

                                <!-- yes/no -->
                                <div class="yes-no">

                                <label for="yes" style="display: inline-flex; align-items: center;">
                                    <input type="radio"
                                    name="conflict_interest" id="yes"
                                    style="margin-right: 5px;" value="1"
                                    @if(($clientOrderSubmission->has_conflict_of_interest ?? 0) == 1) checked @endif
                                    >
                                    {{ __('Yes') }}
                                </label>

                                    <textarea  rows="5" name="conflict_description"
                                    placeholder="{{ __('Please type here') }}" id="conflictOfInterest"
                                    @if(($clientOrderSubmission->has_conflict_of_interest ?? 0) == 1) style="display:block" @endif
                                    > {{ $clientOrderSubmission->conflict_details ?? '' }}</textarea>

                                    <label for="no" style="display: inline-flex; align-items: center;">
                                        <input type="radio"
                                        name="conflict_interest" id="no"
                                        style="margin-right: 5px;" value="0"
                                        @if(($clientOrderSubmission->has_conflict_of_interest ?? 0) == 0) checked @endif
                                        >
                                        {{ __('No') }}
                                    </label>
                                </div>

                            </li>

                            <!-- item -->
                            <li>
                                <h3>{{ __('Funding Statement') }} <span>*</span></h3>

                                <h6>
                                {{ __('Please confirm whether this research has received any funding. If yes, please provide the name of the funder and the grant number.') }}
                                </h6>

                                <!-- yes/no -->
                                <div class="yes-no">

                                    <label for="yesTwo" style="display: inline-flex; align-items: center;">
                                        <input type="radio"
                                        name="funding_received" id="yesTwo"
                                        style="margin-right: 5px;" value="1"
                                        @if(($clientOrderSubmission->has_funding ?? 0) == 1) checked @endif
                                        >
                                        {{ __('Yes') }}
                                    </label>

                                    <label for="noTwo" style="display: inline-flex; align-items: center;">
                                        <input type="radio"
                                        name="funding_received" id="noTwo"
                                        style="margin-right: 5px;" value="0"
                                        @if(($clientOrderSubmission->has_funding ?? 0) == 0) checked @endif
                                        >
                                        {{ __('No') }}
                                    </label>

                                    <div class="step-four-bg" @if(($clientOrderSubmission->has_funding ?? 0) == 1) style="display:block" @endif>

                                    @if(isset($clientOrderSubmission->funders) && count($clientOrderSubmission->funders) > 0)
                                        @foreach($clientOrderSubmission->funders as $index => $funder)
                                        <div class="step-four-bg-white step-five-bg">
                                            <div class="heading">
                                                <h4>{{ __('Funding information') }} #{{ $index + 1 }}<span>*</span></h4>

                                                    <button type="button" @if($index === 0) style="display:none;" @endif>
                                                        <span><i class="far fa-trash-alt"></i></span>
                                                        {{ __('Delete') }}
                                                    </button>

                                            </div>
                                            <ul class="ul-input-list">
                                                <li>
                                                    <input type="text" name="funderInfo[{{$index}}][funder]"
                                                        value="{{ $funder['funder'] ?? '' }}"
                                                        placeholder="{{ __('Please type the funder') }} *">
                                                </li>
                                                <li>
                                                    <input type="text" name="funderInfo[{{$index}}][grant]"
                                                        value="{{ $funder['grant_number'] ?? '' }}"
                                                        placeholder="{{ __('Please type the grant/award number') }}">
                                                </li>
                                            </ul>
                                        </div>
                                        @endforeach
                                    @else

                                        <div class="step-four-bg-white step-five-bg">

                                            <div class="heading">
                                                <h4>{{ __('Funding information') }} #1 <span>*</span></h4>
                                                <button>
                                                    <span> <i class="far fa-trash-alt"></i></span>
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>

                                                <ul class="ul-input-list">
                                                    <li>
                                                        <label for="">{{ __('Funder') }}: <span>*</span></label>
                                                        <input type="text" name="funderInfo[0][funder]" placeholder="{{ __('Please type the funder') }} *" >
                                                    </li>

                                                    <li>
                                                        <label for="">{{ __('Grant/Award Number') }}:</label>
                                                        <input type="text" name="funderInfo[0][grant]" placeholder="{{ __('Please type the grant/award number') }}">
                                                    </li>

                                                </ul>

                                        </div>

                                    @endif

                                        <!-- add-one-more -->
                                        <button class="add-one-more" type="button">
                                            <span>
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            {{ __('Add One More') }}
                                        </button>

                                    </div>

                            </li>

                            <!-- item -->
                            <li>
                                <h3>{{ __('Data Availability Statement') }} <span>*</span></h3>

                                <div class="step-five-data-availability">
                                @php
                                    $dataAvailability = $clientOrderSubmission->data_availability_statement ?? '';
                                    $dataUrl = $clientOrderSubmission->data_availability_url ?? '';
                                @endphp
                                    <label for="dataAvailabilityOne">
                                        <input type="radio" name="data_availability" id="dataAvailabilityOne" value="dataAvailabilityOne" @if($dataAvailability === 'dataAvailabilityOne') checked @endif>
                                        {{ __('The data that support the findings of this study can be found at:') }}
                                        <input type="text" name="data_repository_url" id="dataRepositoryUrl" placeholder="{{ __('Please provide a publicly available repository url.') }}" @if($dataAvailability !== 'dataAvailabilityOne') readonly @endif value="{{ $dataUrl }}" >
                                    </label>

                                    <label for="dataAvailabilityTwo">
                                        <input type="radio" name="data_availability" id="dataAvailabilityTwo" value="dataAvailabilityTwo" @if($dataAvailability === 'dataAvailabilityTwo') checked @endif>
                                        {{ __('The data is available from the corresponding author upon reasonable request.') }}
                                    </label>

                                    <label for="dataAvailabilityThree">
                                        <input type="radio" name="data_availability" id="dataAvailabilityThree" value="dataAvailabilityThree" @if($dataAvailability === 'dataAvailabilityThree') checked @endif>
                                        {{ __('The data supporting the outcome of this research work has been reported in this manuscript.') }}
                                    </label>

                                    <label for="dataAvailabilityFour">
                                        <input type="radio" name="data_availability" id="dataAvailabilityFour" value="dataAvailabilityFour" @if($dataAvailability === 'dataAvailabilityFour') checked @endif>
                                        {{ __('No data was used.') }}
                                    </label>

                                    <label for="dataAvailabilityFive">
                                        <input type="radio" name="data_availability" id="dataAvailabilityFive" value="dataAvailabilityFive" @if($dataAvailability === 'dataAvailabilityFive') checked @endif>
                                        {{ __('Not applicable.') }}
                                    </label>
                                </div>

                            </li>

                        </ul>
                    </div>

                    <!-- continue -->
                    <div class="continue-button">
                        <!-- left -->
                        <div class="left">
                            <button type="submit" id="save_button" value="save" class="previous">{{ __('Save') }}</button>
                            <button type="submit" id="save_and_continue_button" value="save_and_continue" class="continue"> {{ __('Save and Continue') }}
                            </button>
                        </div>

                        <!-- right -->
                        <div class="right">
                            <a href="{{ route('user.submission.add.authors', ['id' => $clientOrderId]) }}">
                                <button class="previous" type="button">{{ __('Previous Step') }}</button>
                            </a>
                        </div>


                    </div>
                    </form>
                </div>
@endsection
@push('script')
<script>
 (function ($) {
    "use strict";

    // Add error message function with specific placement
    function addErrorMessage(element, message) {
        // Remove existing error message if any
        removeErrorMessage(element);

        // Create error message HTML
        const errorHtml = `<div class="error-message" style="color: red; margin-top: 10px; margin-bottom: 10px;"><i class="fas fa-exclamation-triangle"></i> ${message}</div>`;

        // For declaration checkboxes (step-five-bg-content)
        if ($(element).closest('.step-five-item').length) {
            // Add error message at the end of step-five-bg-content div
            if (!$('.step-five-bg-content > .error-message').length) {
                $('.step-five-bg-content').append(errorHtml);
            }
        }
        // For items within li elements
        else if ($(element).closest('li').length) {
            // Add error message before the end of li
            $(element).closest('li').append(errorHtml);
        }
        // Fallback for other elements
        else {
            $(element).after(errorHtml);
        }
    }

    // Function to remove error messages
    function removeErrorMessage(element) {
        if ($(element).closest('.step-five-item').length) {
            $('.step-five-bg-content > .error-message').remove();
        } else if ($(element).closest('li').length) {
            $(element).closest('li').find('.error-message').remove();
        } else {
            $(element).siblings('.error-message').remove();
        }
    }

    // Function to toggle textarea visibility based on radio button selection
    function toggleTextarea(radioSelector, textareaSelector) {
        $(radioSelector).on('change', function () {
            $(this).closest('.yes-no').find(textareaSelector).toggle($(this).prop('checked') && $(this).attr('id') === 'yes');
            removeErrorMessage(this);
        });
    }

    // Function to toggle section visibility based on radio button selection
    function toggleSection(radioSelector, sectionSelector) {
        $(radioSelector).on('change', function () {
            $(this).closest('.yes-no').find(sectionSelector).toggle($(this).prop('checked') && $(this).attr('id') === 'yesTwo');
            if ($(this).attr('id') === 'yesTwo') {
                updateAuthorButtons();
            }
            removeErrorMessage(this);
        });
    }

    // Function to update the numbering of funding sections
    function updateFundingNumbering() {
        $(".step-four-bg-white").each(function (index) {
            $(this).find("h4").text("{{ __('Funding information') }} #" + (index + 1)); // Update the numbering

            // Update the name attributes for funder and grant inputs
            $(this).find('input[name^="funderInfo["]').each(function () {
                let name = $(this).attr('name');
                name = name.replace(/\[\d+\]\[/, '[' + index + ']['); // Replace the index
                $(this).attr('name', name);
            });
        });
    }

    // Function to clone funding information section
    function cloneFundingSection() {
        $('.add-one-more').on('click', function () {
            var newFounder = $(this).closest('.step-four-bg').find('.step-four-bg-white').first().clone();
            newFounder.find("input").val(""); // Clear input values
            newFounder.find(".error-message").remove(); // Remove any error messages

            $(this).before(newFounder); // Append the clone before the button
            updateFundingNumbering(); // Update numbering after adding a new section
            updateAuthorButtons(); // Update delete button visibility
        });
    }

    // Function to handle delete button
    function handleDeleteButton() {
        $(document).on("click", ".heading button", function () {
            if ($(".step-four-bg-white").length > 1) { // Ensure at least one funding information remains
                $(this).closest(".step-four-bg-white").remove(); // Remove the clicked section
                updateFundingNumbering(); // Update numbering after removal
                updateAuthorButtons(); // Update delete button visibility
            } else {
                alert("{{ __('At least one funding information is required.') }}");
            }
        });
    }

    // Function to ensure Funding Information #1 always hides the delete button
    function updateAuthorButtons() {
        $(".step-four-bg-white").each(function (index) {
            if (index === 0) {
                $(this).find(".heading button").hide(); // Hide delete button for Funding Information #1
            } else {
                $(this).find(".heading button").show(); // Show for others
            }
        });
    }

    // Function to enable/disable input field based on radio button selection
    function toggleInputField(radioSelector, inputSelector) {
        $(radioSelector).on('change', function () {
            $(inputSelector).prop('readonly', $(this).attr('id') !== 'dataAvailabilityOne');
            removeErrorMessage(inputSelector);
        });
    }

    // Add event listeners to remove error messages when fields are corrected
    function setupErrorRemoval() {
        // Remove error on checkbox change
        $('.step-five-item input[type="checkbox"]').on('change', function() {
            if ($('.step-five-item input[type="checkbox"]:not(:checked)').length === 0) {
                $('.step-five-bg-content > .error-message').remove();
            }
        });

        // Remove error on text input
        $('input[type="text"]').on('input', function() {
            if ($(this).val().trim() !== '') {
                removeErrorMessage(this);
            }
        });

        // Remove error on textarea input
        $('textarea').on('input', function() {
            if ($(this).val().trim() !== '') {
                removeErrorMessage(this);
            }
        });

        // Remove error on data availability selection
        $('.step-five-data-availability input[type="radio"]').on('change', function() {
            removeErrorMessage(this);
        });
    }

    // Function to validate the form before submission
    function validateForm() {
        let isValid = true;

        // Clear all existing error messages first
        $('.error-message').remove();

        // Validate step-five-item checkboxes
        let hasUncheckedDeclarations = false;
        $('.step-five-item input[type="checkbox"]').each(function () {
            if (!$(this).prop('checked')) {
                hasUncheckedDeclarations = true;
            }
        });

        if (hasUncheckedDeclarations) {
            isValid = false;
            // Single error message at the end of step-five-bg-content
            addErrorMessage($('.step-five-item').first(), "{{ __('To proceed, kindly ensure that you confirm all the items mentioned above as required.') }}");
        }

        // Validate Declarations textarea
        const declarationsYes = $('#yes').prop('checked');
        const declarationsTextarea = $('#conflictOfInterest');
        if (declarationsYes && declarationsTextarea.val().trim() === '') {
            isValid = false;
            addErrorMessage(declarationsTextarea, "{{ __('Please provide details about the conflict of interest.') }}");
        }

        // Validate Funding Statement
        const fundingYes = $('#yesTwo').prop('checked');
        if (fundingYes) {
            $('.step-four-bg input[name^="funderInfo["]').each(function () {
                if ($(this).attr('name').includes('[funder]') && $(this).val().trim() === '') {
                    // Only validate the "Funder" field (required)
                    isValid = false;
                    addErrorMessage(this, "{{ __('Funder name is required.') }}");
                }
            });
        }

        // Validate if conflict of interest selection was made
        if (!$('input[name="conflict_interest"]:checked').length) {
            isValid = false;
            addErrorMessage($('#yes').closest('li'), "{{ __('Please select Yes or No for conflict of interest.') }}");
        }

        // Validate if funding received selection was made
        if (!$('input[name="funding_received"]:checked').length) {
            isValid = false;
            addErrorMessage($('#yesTwo').closest('li'), "{{ __('Please select Yes or No for funding received.') }}");
        }

        // Validate Data Availability Statement
        if (!$('input[name="data_availability"]:checked').length) {
            isValid = false;
            addErrorMessage($('.step-five-data-availability').closest('li'), "{{ __('Please select a data availability option.') }}");
        }

        // Validate Data Repository URL if option one is selected
        const dataAvailabilityOne = $('#dataAvailabilityOne').prop('checked');
        const dataRepositoryUrl = $('#dataRepositoryUrl');
        if (dataAvailabilityOne && dataRepositoryUrl.val().trim() === '') {
            isValid = false;
            addErrorMessage(dataRepositoryUrl, "{{ __('Please provide a publicly available repository URL.') }}");
        }

        return isValid;
    }

    // Initialize functions
    toggleTextarea('#yes, #no', 'textarea');
    toggleSection('#yesTwo, #noTwo', '.step-four-bg');
    cloneFundingSection();
    handleDeleteButton();
    updateAuthorButtons();
    toggleInputField('#dataAvailabilityOne, #dataAvailabilityTwo, #dataAvailabilityThree, #dataAvailabilityFour, #dataAvailabilityFive', '#dataRepositoryUrl');
    setupErrorRemoval();

    // Form submission validation
    $('form').on('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
            return false;
        }
    });

})(jQuery);
</script>
@endpush
