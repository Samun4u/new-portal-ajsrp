
<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <h4>
                {{ __('Submit to Journal') }}
                </h4>
                <!-- fas fa-check -->
                <!-- step 1 -->

                <?php 
                  
                  $clientOrderId = null;
                  if(isset($clientOrder) && $clientOrder){
                    $clientOrderId = $clientOrder->order_id;      
                  }

                    $bySubjectRoute = route('user.submission.select-a-journal',['by' => 'by-subject']);
                    if(isset($clientOrderId) && $clientOrderId){
                        $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update', 'id' => $clientOrderId]);
                    }elseif(isset($selectedJournal) && $selectedJournal){
                        $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update']);
                    }
                
                ?>


               
                <a class="step-one-route" href="{{ $bySubjectRoute }}">
                    <button class="nav-link {{$step === 'stepOne' ? 'active' : ''}}" id="v-pills-stepOne-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-stepOne" type="submit" role="tab" aria-controls="v-pills-stepOne"
                        aria-selected="true">
                        <i class="@if($step === 'stepOne') fas fa-edit @else fas fa-check @endif"></i>
                        {{ __('Step 1') }}: {{ __('Select a Journal') }}
                    </button>
                </a>
                


                <!-- step 2 -->
                 @if(!$clientOrderId)
                    <button class="nav-link" id="v-pills-stepTwo-tab" type="button">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 2') }}: {{ __('Manuscript Information') }}
                    </button>
                 @else
                    <a href="{{ $clientOrderId ? route('user.submission.article.information', ['action' => 'update','id' => $clientOrderId]) : route('user.submission.article.information') }}" class="step-two-route">
                        <button class="nav-link {{$step === 'stepTwo' ? 'active' : ''}}" id="v-pills-stepTwo-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-stepTwo" type="submit" role="tab" aria-controls="v-pills-stepTwo"
                            aria-selected="false">
                            <i class="@if($step === 'stepTwo') fas fa-edit @else fas fa-check @endif"></i>
                            {{ __('Step 2') }}: {{ __('Manuscript Information') }}
                        </button>
                    </a>
                 @endif
                

                <!-- step 3 -->
                 @if(!$clientOrderId || ($step !== 'stepThree' && !$clientOrderSubmission->full_article_file))
                    <button class="nav-link" id="v-pills-stepThree-tab"type="button">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 3') }}: {{ __('Upload Files') }}
                    </button>
                 @else
                    <a href="{{ route('user.submission.upload.files', ['id' => $clientOrderId]) }}" class="step-three-route" >
                        <button class="nav-link {{$step === 'stepThree' ? 'active' : ''}}" id="v-pills-stepThree-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-stepThree" type="button" role="tab" aria-controls="v-pills-stepThree"
                            aria-selected="false">

                            <i class="@if($step === 'stepThree') fas fa-edit @else fas fa-check @endif"></i>
                            {{ __('Step 3') }}: {{ __('Upload Files') }}
                        </button>
                    </a>
                 @endif
                

                <!-- step 4 -->
                @if(!$clientOrderId  || ($step !== 'stepFour' && is_null($clientOrderSubmission->has_author)))
                    <button class="nav-link {{$step === 'stepFour' ? 'active' : ''}}" id="v-pills-stepFour-tab"type="button">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 4') }}: {{ __('Authors & Institutions') }}
                    </button>
                 @else
                  <a href="{{ route('user.submission.add.authors', ['id' => $clientOrderId]) }}" class="step-four-route">
                    <button class="nav-link {{$step === 'stepFour' ? 'active' : ''}}" id="v-pills-stepFour-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-stepFour" type="button" role="tab" aria-controls="v-pills-stepFour"
                        aria-selected="false">

                        <i class="@if($step === 'stepFour') fas fa-edit @else fas fa-check @endif"></i>
                        {{ __('Step 4') }}: {{ __('Authors & Institutions') }}
                    </button>
                  </a>
                 @endif
                
                <!-- step 5 -->
                @if(!$clientOrderId || ($step !== 'stepFive' && is_null($clientOrderSubmission->has_funding)))
                    <button class="nav-link {{$step === 'stepFive' ? 'active' : ''}}" id="v-pills-stepFour-tab"type="button">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 5') }}: {{ __('Declarations') }}
                    </button>
                 @else
                  <a href="{{ route('user.submission.declarations', ['id' => $clientOrderId]) }}" class="step-five-route">
                    <button class="nav-link {{$step === 'stepFive' ? 'active' : ''}}" id="v-pills-stepFive-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-stepFive" type="button" role="tab" aria-controls="v-pills-stepFive"
                        aria-selected="false">

                        <i class="@if($step === 'stepFive') fas fa-edit @else fas fa-check @endif"></i>
                        {{ __('Step 5') }}: {{ __('Declarations') }}
                    </button>
                  </a>
                 @endif
                

                <!-- step 6 -->
                @if(!$clientOrderId || ($step !== 'stepSix' && is_null($clientOrderSubmission->add_reviewers) && is_null($clientOrderSubmission->suggested_reviewers) && is_null($clientOrderSubmission->has_opposed_reviewers)))
                <button class="nav-link step-six-button {{$step === 'stepFix' ? 'active' : ''}}" id="v-pills-StepSix-tab"  type="button">
                    <div class="step-six-title">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 6') }}: {{ __('Add Reviewers') }}
                    </div>

                    <!-- sub-tab -->
                    <ul>
                        <li>
                            <!-- step 5 -->
                            <div class="nav-link" id="v-pills-StepSixSubOne-tab"  type="button" >

                                <i class="fas fa-lock"></i>
                                {{ __('Suggest Reviewers') }}
                            </div>
                        </li>

                        <li>
                            <!-- step 5 -->
                            <div class="nav-link" id="v-pills-StepSixSubTwo-tab"  type="button">

                                <i class="fas fa-lock"></i>
                                {{ __('Opposed Reviewers') }}
                            </div>
                        </li>
                    </ul>

                </button>
                @else
                <button class="nav-link {{ $step === 'stepSix' ? 'active' : ''}} step-six-button" id="v-pills-StepSix-tab"  type="button">

                    <div class="step-six-title">
                        <a href="{{ route('user.submission.add-reviewers.index', ['id' => $clientOrderId]) }}" class="step-six-route" style="all: unset; ">

                        @if(
                            ($step === 'stepSix' || $step === 'stepSixSubOne' || $step === 'stepSixSubTwo' ) && 
                           (is_null($clientOrderSubmission->suggested_reviewers) || is_null($clientOrderSubmission->has_opposed_reviewers))
                        )
                        <i class="fas fa-edit"></i>
                        @else
                        <i class="fas fa-check"></i>
                        @endif
                        {{ __('Step 6') }}: {{ __('Add Reviewers') }}
                        </a>
                    </div>

                    <!-- sub-tab -->
                    <ul>
                        <li>
                            <!-- step 5 -->
                            <div class="nav-link {{ $step === 'stepSixSubOne' ? 'active' : ''}}" id="v-pills-StepSixSubOne-tab" type="button">
                                <a href="{{ route('user.submission.add-reviewers.from-references', ['id' => $clientOrderId]) }}" class="step-six-sub-one-route" style="all: unset; ">
                                    @if(($step === 'stepSix' || $step === 'stepSixSubOne' || $step === 'stepSixSubTwo') && is_null($clientOrderSubmission->suggested_reviewers))
                                    <i class="fas fa-edit"></i>
                                    @else
                                    <i class="fas fa-check"></i>
                                    @endif
                                    {{ __('Suggest Reviewers') }}
                                </a>
                            </div>
                        </li>

                        <li>
                            <!-- step 5 -->
                            <div class="nav-link {{ $step === 'stepSixSubTwo' ? 'active' : ''}}" id="v-pills-StepSixSubTwo-tab" type="button">
                                <a href="{{ route('user.submission.add-reviewers.opposed', ['id' => $clientOrderId]) }}" class="step-six-sub-two-route" style="all: unset; ">
                                    @if(($step === 'stepSix' || $step === 'stepSixSubOne' || $step === 'stepSixSubTwo') && is_null($clientOrderSubmission->has_opposed_reviewers))
                                    <i class="fas fa-edit"></i>
                                    @else
                                    <i class="fas fa-check"></i>
                                    @endif
                                    {{ __('Opposed Reviewers') }}
                                </a>
                            </div>
                        </li>
                    </ul>

                </button>
                @endif
                

                <!-- step 7 -->
                @if(!$clientOrderId || ($step !== 'stepSeven' && is_null($clientOrderSubmission->final_submit_success)))
                    <button class="nav-link {{$step === 'stepSeven' ? 'active' : ''}}" id="v-pills-stepSeven-tab"type="button">
                        <i class="fas fa-lock"></i>
                        {{ __('Step 7') }}: {{ __('Review and Submit') }}
                    </button>
                 @else
                  <a href="{{ route('user.submission.review', ['id' => $clientOrderId]) }}" class="step-seven-route">
                    <button class="nav-link {{$step === 'stepSeven' ? 'active' : ''}}" id="v-pills-stepSeven-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-stepSeven" type="button" role="tab" aria-controls="v-pills-stepSeven"
                        aria-selected="false">

                        <i class="@if($step === 'stepSeven') fas fa-edit @else fas fa-check @endif"></i>
                        {{ __('Step 7') }}: {{ __('Review and Submit') }}
                    </button>
                  </a>
                 @endif
            </div>