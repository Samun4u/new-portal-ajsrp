@extends('user.submission.main')
@section('submission-content')
 <!-- step 6 -->
 <div class="tab-pane fade {{ $step === 'stepSix' ? 'show active' : '' }}" id="v-pills-StepSix" role="tabpanel" aria-labelledby="v-pills-StepSix-tab"
                    tabindex="0">
                    <form action="{{ route('user.submission.add-reviewers.save') }}"  method="POST" data-handler="commonResponse" class="ajax">
                        @csrf
                        <input type="hidden" class="step-six-client-order-id"  name="id" value="{{ $clientOrderId }}" >
                    <div class="step-six">
                            <!-- header-title -->
                            <div class="header-title">
                                <h2>{{ __('Step 6') }}: {{ __('Add Reviewers') }}</h2>
                                <h4>{{ __('SciencePG operates a rigorous peer review process that aims to establish the credibility of your manuscript. To provide more comprehensive comments that can really improve the quality of your article, we would like to invite you to recommend potential reviewers who possess the expertise to offer valuable feedback on your work. Your suggestions will contribute to maintaining the high standards of our peer-review process.') }}</h4>
                            </div>

                            <!-- step-six-reviewer-criteria -->
                            <div class="step-six-reviewer-criteria">
                                <h4>{{ __('Reviewer Criteria') }}</h4>

                                <ul>
                                    <li>{{ __('Hold no conflicts of interest with any of the authors;') }}</li>
                                    <li>{{ __('Should not have published together with the authors in the last three years;') }}</li>
                                    <li>{{ __('Have relevant experience and have a proven publication record in the relevant field of the submitted paper (during the last 3 years).') }}</li>
                                </ul>
                            </div>

                            <!-- continue -->
                            <div class="continue-button">

                                <!-- right -->
                                <div class="right">
                                    <a href="{{ route('user.submission.declarations', ['id' => $clientOrderId]) }}">
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