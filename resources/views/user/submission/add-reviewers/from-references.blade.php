@extends('user.submission.main')
@section('submission-content')
<!-- step 6 sub-one -->
<div class="tab-pane fade {{ $step === 'stepSixSubOne' ? 'show active' : '' }}" id="v-pills-StepSixSubOne" role="tabpanel"
    aria-labelledby="v-pills-StepSixSubOne-tab" tabindex="0">
    <form action="{{ route('user.submission.add-reviewers.from-references.save') }}" method="POST" data-handler="commonResponse" class="ajax">
        @csrf
        <input type="hidden" class="step-six-sub-one-client-order-id" name="id" value="{{ $clientOrderId }}">

        <div class="step-six-sub-one step-six">
            <div class="header-title">
                <h2>{{ __('Suggest Reviewers') }}</h2>
                <h4>{{ __("The references you've included in your article align closely with the research focus of your article. We suggest exploring authors from these cited works who possess a well-suited background to potentially serve as reviewers for your manuscript.") }}
                </h4>
            </div>

            <!-- Reviewer criteria list -->
            <div class="step-six-reviewer-criteria">
                <ul>
                    <li>{{ __("It is highly recommended to choose among the corresponding author and the first author;") }}</li>
                    <li>{{ __('To ensure the effectiveness of the study, the chosen articles are recommended to be published within the last 3 years.') }}</li>
                </ul>
            </div>

            <!-- Suggested reviewers entries -->
            <div class="step-six-sub-one-bg">
                @forelse(($clientOrderSubmission->__suggested_reviewers ?? []) as $index => $reviewer)
                    <div class="step-six-sub-one-bg-white">
                        <div class="heading">
                            <h6>{{ __('Referred Article Title') }} #{{ $index + 1 }}</h6>
                            <button type="button">
                                <span><i class="far fa-trash-alt"></i></span>
                            </button>
                        </div>
                        <ul class="form-input">
                            <!-- Referred article title -->
                            <li>
                                <input type="text" placeholder="{{ __('Referred Article Title') }}" 
                                    name="suggested_reviewers[{{ $index }}][referred_article_title]" 
                                    value="{{ $reviewer['referred_article_title'] ?? '' }}">
                            </li>
                            
                            <!-- Corresponding author fields -->
                            <li><p>{{ __('Corresponding author') }}</p></li>
                            <li>
                                <input type="text" placeholder="{{ __('First Name') }}" 
                                    name="suggested_reviewers[{{ $index }}][corresponding_author_first_name]" 
                                    value="{{ $reviewer['corresponding_author_first_name'] ?? '' }}">
                            </li>
                            <li>
                                <input type="text" placeholder="{{ __('Last Name') }}" 
                                    name="suggested_reviewers[{{ $index }}][corresponding_author_last_name]" 
                                    value="{{ $reviewer['corresponding_author_last_name'] ?? '' }}">
                            </li>
                            <li>
                                <input type="email" placeholder="{{ __('Email') }}" 
                                    name="suggested_reviewers[{{ $index }}][corresponding_author_email]" 
                                    value="{{ $reviewer['corresponding_author_email'] ?? '' }}">
                            </li>
                            
                            <!-- First author fields -->
                            <li><p>{{ __('First author') }}</p></li>
                            <li>
                                <input type="text" placeholder="{{ __('First Name') }}" 
                                    name="suggested_reviewers[{{ $index }}][first_author_first_name]" 
                                    value="{{ $reviewer['first_author_first_name'] ?? '' }}">
                            </li>
                            <li>
                                <input type="text" placeholder="{{ __('Last Name') }}" 
                                    name="suggested_reviewers[{{ $index }}][first_author_last_name]" 
                                    value="{{ $reviewer['first_author_last_name'] ?? '' }}">
                            </li>
                            <li>
                                <input type="email" placeholder="{{ __('Email') }}" 
                                    name="suggested_reviewers[{{ $index }}][first_author_email]" 
                                    value="{{ $reviewer['first_author_email'] ?? '' }}">
                            </li>
                        </ul>
                    </div>
                @empty
                    <!-- Default empty items if no reviewers exist -->
                    @for($i = 0; $i < 1; $i++)
                        <div class="step-six-sub-one-bg-white">
                            <div class="heading">
                                <h6>{{ __('Referred Article Title') }} #{{ $i + 1 }}</h6>
                                <button type="button">
                                    <span><i class="far fa-trash-alt"></i></span>
                                </button>
                            </div>
                            <ul class="form-input">
                                <li>
                                    <input type="text" placeholder="{{ __('Referred Article Title') }}" 
                                        name="suggested_reviewers[{{ $i }}][referred_article_title]">
                                </li>
                                <li><p>{{ __('Corresponding author') }}</p></li>
                                <li>
                                    <input type="text" placeholder="{{ __('First Name') }}" 
                                        name="suggested_reviewers[{{ $i }}][corresponding_author_first_name]">
                                </li>
                                <li>
                                    <input type="text" placeholder="{{ __('Last Name') }}" 
                                        name="suggested_reviewers[{{ $i }}][corresponding_author_last_name]">
                                </li>
                                <li>
                                    <input type="email" placeholder="{{ __('Email') }}" 
                                        name="suggested_reviewers[{{ $i }}][corresponding_author_email]">
                                </li>
                                <li><p>{{ __('First author') }}</p></li>
                                <li>
                                    <input type="text" placeholder="{{ __('First Name') }}" 
                                        name="suggested_reviewers[{{ $i }}][first_author_first_name]">
                                </li>
                                <li>
                                    <input type="text" placeholder="{{ __('Last Name') }}" 
                                        name="suggested_reviewers[{{ $i }}][first_author_last_name]">
                                </li>
                                <li>
                                    <input type="email" placeholder="{{ __('Email') }}" 
                                        name="suggested_reviewers[{{ $i }}][first_author_email]">
                                </li>
                            </ul>
                        </div>
                    @endfor
                @endforelse
                <button class="add-one-more" type="button">
                    <span><i class="fas fa-plus"></i></span>
                    {{ __('Add One More') }}
                </button>
            </div>
        </div>

        <!-- Navigation buttons -->
        <div class="continue-button">
            <div class="right">
                <a href="{{ route('user.submission.add-reviewers.index', ['id' => $clientOrderId]) }}">
                    <button class="previous" type="button">{{ __('Previous Step') }}</button>
                </a>
            </div>
            <div class="left">
                <button type="submit" id="save_button" value="save" class="previous">{{ __('Save') }}</button>
                <button type="submit" id="save_and_continue_button" value="save_and_continue" class="continue">
                {{ __('Save and Continue') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
(function ($) {
    "use strict";
    
    $(document).ready(function() {
        function updateArticleNumbers() {
            const $items = $('.step-six-sub-one-bg-white');
            
            $items.each(function(index) {
                const $item = $(this);
                $item.find('h6').text("{{ __('Referred Article Title') }} #" + (index + 1));
                $item.attr('data-index', index);
                
                $item.find('[name*="suggested_reviewers"]').each(function() {
                    const name = $(this).attr('name').replace(/\[\d+\]/g, `[${index}]`);
                    $(this).attr('name', name);
                });
            });

            $items.find('.heading button').prop('disabled', $items.length === 1);
            $items.find('.heading button').css('opacity', $items.length === 1 ? 0 : 1);
        }

        $('.add-one-more').click(function() {
            const $lastItem = $('.step-six-sub-one-bg-white').last();
            const $newItem = $lastItem.clone(true);
            $newItem.find('input').val('');
            $newItem.insertBefore(this);
            updateArticleNumbers();
        });

        $(document).on('click', '.step-six-sub-one-bg-white button:not(:disabled)', function() {
            $(this).closest('.step-six-sub-one-bg-white').remove();
            updateArticleNumbers();
        });

        updateArticleNumbers();
    });
})(jQuery);
</script>
@endpush