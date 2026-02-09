@extends('user.submission.main')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/submission.css') }}" />
    <style>
        .letter-filter:hover {
            cursor: pointer; /* Change cursor to pointer on hover */
        }
    </style>
@endpush
@section('submission-content')
<!-- step 1  -->
<div class="tab-pane fade show active" id="v-pills-stepOne" role="tabpanel" aria-labelledby="v-pills-stepOne-tab" tabindex="0">

    <div class="step-one">

        <h2>{{ __('Step 1') }}: {{ __('Select a Journal') }}</h2>
        <h5>{{ __('Here are two ways to find a journal that aligns with your research field.') }}</h5>

        <!-- tab-one-tabs  -->
        <div class="step-one-tabs">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php
                    $bySubjectRoute = route('user.submission.select-a-journal',['by' => 'by-subject']);
                    $byTitleRoute = route('user.submission.select-a-journal',['by' => 'by-title']);
                    if(isset($selectedJournal) && $selectedJournal && isset($clientOrderId) && $clientOrderId){
                        $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update', 'id' => $clientOrderId]);
                        $byTitleRoute = route('user.submission.select-a-journal', ['by' => 'by-title','action' => 'update', 'id' => $clientOrderId]);
                    }elseif(isset($selectedJournal) && $selectedJournal){
                        $bySubjectRoute = route('user.submission.select-a-journal', ['by' => 'by-subject','action' => 'update']);
                        $byTitleRoute = route('user.submission.select-a-journal', ['by' => 'by-title','action' => 'update']);
                    }
                ?>
                <li class="nav-item" role="presentation">
                    <form action="{{ $bySubjectRoute }}" method="GET">
                        <button class="nav-link {{ $by === 'by-subject' ? 'active' : ''}}" >{{ __('By Subject') }}</button>
                    </form>
                </li>
                
                <li class="nav-item" role="presentation">
                    <form action="{{ $byTitleRoute }}" method="GET">
                            <button class="nav-link {{ $by === 'by-title' ? 'active' : ''}}" >{{ __('By Title') }}</button>
                    </form>
                </li>
                
            </ul>

            <div class="tab-content" id="myTabContent">

                <!-- tab 1 -->
                <div class="tab-pane fade {{ $by === 'by-subject' ? 'show active' : ''}}" id="bySubject-tab-pane" role="tabpanel"
                    aria-labelledby="bySubject-tab" tabindex="0">

                    <div class="step-one-tab-one">

                        <h5>{{ __('Browse by subject to view a collection of journals spanning a wide range of disciplines.') }}</h5>

                        <ul class="ul-list-item">
                            @foreach ($journalSubjects as $index => $subject)
                                <li>
                                    <label for="{{$index}}" name="bySubject">
                                        <input  type="radio" 
                                                name="bySubject"  
                                                class="subject-radio" 
                                                id="{{$index}}"  
                                                data-id="{{ $subject->id }}" 
                                                value="{{$subject->id}}" 
                                                {{ isset($selectedJournal) && $selectedJournal->subjects->isNotEmpty() && $subject->id == $selectedJournal->subjects->first()->id ? 'checked' : '' }}>
                                        @if (currentLang() == 'ar') {{ $subject->arabic_name ? $subject->arabic_name : $subject->name }} @else {{ $subject->name }} @endif
                                    </label>
                                </li>
                            @endforeach
                            

                            <!-- <li class="active">
                                <label for="one" name="bySubject">
                                    <input type="radio" name="bySubject" checked id="one">
                                    Life Sciences, Agriculture & Food
                                </label>
                            </li> -->
                        </ul>

                        <!-- step one data -->
                        <div class="step-one-search-data" >

                            <!-- search -->
                            <div class="step-one-data-search">
                                <input type="text" placeholder="{{ __('Search Journal Title') }}" style="margin-right: 42px;">
                                <button>
                                    <i class="fas fa-search"></i>
                                </button>

                            </div>

                            <div class="step-one-search-data-content">

                                <ul>
                                    <li>
                                        <h4>{{ __('Journal Title') }}</h4>
                                        <h4>{{ __('Article Processing Charges') }}</h4>
                                    </li>

                                    <li>
                                        <label for="searchOne" name="bySearch">
                                            <div class="left">
                                                <input type="radio" name="bySearch" id="searchOne">
                                                <span class="journal-name">{{ __('Journal of Food and Nutrition Sciences') }}</span>
                                                <a href="">
                                                {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                            <h4>{{ __('85 USD') }}</h4>
                                        </label>
                                    </li>

                                    <li>
                                        <label for="searchTwo" name="bySearch">
                                            <div class="left">
                                                <input type="radio" name="bySearch" id="searchTwo">
                                                <span class="journal-name">{{ __('International Journal of Nutrition and Food Sciences') }} </span>
                                                <a href="">
                                                {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                            <h4>{{ __('85 USD') }}</h4>
                                        </label>
                                    </li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- tab 2 -->
                <div class="tab-pane fade {{ $by === 'by-title' ? 'show active' : ''}}" id="byTitle-tab-pane" role="tabpanel"
                    aria-labelledby="byTitle-tab" tabindex="0">

                    <div class="step-one-tab-one two">

                        <h5>{{ __('Browse by subject to view a collection of journals spanning a wide range of disciplines.') }}</h5>

                        <!-- step one data -->
                        <div class="step-one-search-data">

                            <!-- search -->
                            <div class="step-one-data-search">
                                <input type="text" placeholder="{{ __('Search Journal Title') }}" style="margin-right: 42px;">
                                <button>
                                    <i class="fas fa-search"></i>
                                </button>

                                <div class="a-to-z">
                                <span class="cursor-pointer letter-filter">A</span>
                                <span class="cursor-pointer letter-filter">B</span>
                                <span class="cursor-pointer letter-filter">C</span>
                                <span class="gray">D</span>
                                <span class="cursor-pointer letter-filter">E</span>
                                <span class="cursor-pointer letter-filter">F</span>
                                <span class="gray">G</span>
                                <span class="cursor-pointer letter-filter">H</span>
                                <span class="cursor-pointer letter-filter">I</span>
                                <span class="cursor-pointer letter-filter">J</span>
                                <span class="gray">K</span>
                                <span class="cursor-pointer letter-filter">L</span>
                                <span class="cursor-pointer letter-filter">M</span>
                                <span class="cursor-pointer letter-filter">N</span>
                                <span class="cursor-pointer letter-filter">O</span>
                                <span class="cursor-pointer letter-filter">P</span>
                                <span class="gray">Q</span>
                                <span class="cursor-pointer letter-filter">R</span>
                                <span class="cursor-pointer letter-filter">S</span>
                                <span class="cursor-pointer letter-filter">T</span>
                                <span class="cursor-pointer letter-filter">U</span>
                                <span class="gray">V</span>
                                <span class="cursor-pointer letter-filter">W</span>
                                <span class="gray">X</span>
                                <span class="gray">Y</span>
                                <span class="gray">Z</span>

                                </div>

                            </div>

                            <div class="step-one-search-data-content">
                                @if ($by === "by-title")
                                    <ul>
                                        <li>
                                            <h4>{{ __('Journal Title') }}</h4>
                                            <h4>{{ __('Article Processing Charges') }}</h4>
                                        </li>
                                        @foreach ( $journals as $journal)
                                            <li>
                                                <label>
                                                    <div class="left">
                                                        <input type="radio" name="bySearch" value="{{$journal->id}}">
                                                        <span class="journal-name">@if (currentLang() == 'ar') {{ $journal->arabic_title ? $journal->arabic_title : $journal->title }} @else {{ $journal->title }} @endif</span>
                                                        <a href="{{$journal->website}}" target="_blank">
                                                        {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                    <h4>{{$journal->service->price}} {{ __('USD') }}</h4>
                                                </label>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
                                @endif
                                
                            </div>

                        </div>

                    </div>

                </div>

                <form id="selectionForm" method="POST" action="{{ route('user.submission.select-a-journal.save') }}">
                    @csrf
                    <input type="hidden" name="selected_journal" id="selectedJournal">
                    @if (isset($clientOrderId) && $clientOrderId)
                        <input type="hidden" name="id" value="{{$clientOrderId}}">
                    @endif
                </form> 
                <!-- Selection Box (Hidden Initially) -->
            </div>

        </div>

        <!-- continue -->
        <div class="continue-button">

            <button class="continue">{{ __('Continue') }}</button>

        </div>

    </div>

</div>
@endsection
@push('script')
    <script>
        

(function($) {
    "use strict";

    $(document).ready(function () {


        //current language
        var currentLang = "{{ currentLang() }}";
        
        
        // Hide step one search data initially
        $(".step-one-search-data").hide();

        var selectedSubjectId = $(".subject-radio:checked").val();
        var selectedJournalId = "{{ $selectedJournal->id ?? null }}";
        getJournalsBySubject(selectedSubjectId,selectedJournalId)

        // Subject selection - Show search data & fetch journals
        $(".subject-radio").on("change", function () {
            var subjectId = $(this).val();
            //var searchDataDiv = $(".step-one-search-data");
            //var contentDiv = $(".step-one-search-data-content ul");

            // Add active class to the parent li when radio is checked
            $("li").removeClass("active");
            if ($(this).prop("checked")) {
                $(this).closest("li").addClass("active");
            }

            getJournalsBySubject(subjectId,null);
            // if (subjectId) {
            //     searchDataDiv.show(); // Show search data
            //     contentDiv.html('<li>Loading...</li>'); // Show loading state

            //     $.ajax({
            //         url: "{{ route('user.submission.getJournalsBySubject') }}",
            //         type: "POST",
            //         data: { subject_id: subjectId },
            //         headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            //         success: function (response) {
            //             contentDiv.html(`
            //                 <li>
            //                     <h4>Journal Title</h4>
            //                     <h4>Article Processing Charges</h4>
            //                 </li>
            //             `);

            //             $.each(response.journals, function (index, journal) {
            //                 contentDiv.append(`
            //                     <li>
            //                         <label>
            //                             <div class="left">
            //                                 <input type="radio" name="bySearch" value="${journal.id}">
            //                                 <span class="journal-name">${journal.title}</span>
            //                                 <a href="${journal.website}" target="_blank">
            //                                     Website <i class="fas fa-external-link-alt"></i>
            //                                 </a>
            //                             </div>
            //                             <h4>${journal.charges} USD</h4>
            //                         </label>
            //                     </li>
            //                 `);
            //             });
            //         },
            //         error: function () {
            //             contentDiv.html('<li>Error loading journals. Please try again.</li>');
            //         }
            //     });
            // } else {
            //     searchDataDiv.hide(); // Hide if no subject selected
            // }
        });

        // Search Journals Dynamically
        $(".step-one-data-search input").on("keyup", function () {
            var query = $(this).val();
            var contentDiv = $(".step-one-search-data-content ul");

            $.ajax({
                url: "{{ route('user.submission.searchJournals') }}", // Adjust with your actual search route
                type: "GET",
                data: { query_data: query },
                success: function (response) {
                    contentDiv.html(`
                        <li>
                            <h4>{{ __('Journal Title') }}</h4>
                            <h4>{{ __('Article Processing Charges') }}</h4>
                        </li>
                    `);

                    $.each(response.journals, function (index, journal) {
                        const titleToShow = currentLang === 'ar' 
                            ? (journal.arabic_title || journal.title) 
                            : journal.title;
                        contentDiv.append(`
                            <li>
                                <label>
                                    <div class="left">
                                        <input type="radio" name="bySearch" value="${journal.id}">
                                        <span class="journal-name">${titleToShow}</span>
                                        <a href="${journal.website}" target="_blank">
                                            {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    <h4>${journal.service.price} {{ __('USD') }}</h4>
                                </label>
                            </li>
                        `);
                    });
                }
            });
        });

        $(document).on("change", "input[name='bySearch']", function () {
            var bySearchThis = $(this);
            getContinueButton(bySearchThis);
        });

        function getContinueButton(checkedRadio){
            var selectedText = checkedRadio.closest("label").find(".journal-name").text().trim();
            
            // Remove the previous .select-item if it exists
            $(".select-item").remove();

            // Create a new .select-item
            var newSelectItem = `
                <div class="select-item">
                    <h4><i class="fas fa-check"></i>
                        <span>
                            <span class="select-item-title">
                                <i>${selectedText}</i> {{ __('has been selected for you.') }}
                            </span>
                            <button class="select-item-btn">{{ __('Continue') }}</button>
                        </span>
                    </h4>
                </div>
            `;
            
            // Append the new .select-item after the selected journal's <li> and fade it in
            $(newSelectItem).insertAfter(checkedRadio.closest("li")).fadeIn();

            // Set the selected journal in the hidden input field
            $("#selectedJournal").val(checkedRadio.val());
        }

        // Continue button event
        $(document).on("click", ".select-item-btn", function () {
            var selectedJournal = $("#selectedJournal").val();
            if (selectedJournal) {
                $("#selectionForm").submit();
            } else {
                alert("{{ __('Please select a journal first!') }}");
            }
        });

        // Continue button event
        $(document).on("click", ".continue", function () {
            var selectedJournal = $("#selectedJournal").val();
            if (selectedJournal) {
                $("#selectionForm").submit();
            } else {
                alert("{{ __('Please select a journal first!') }}");
            }
        });

        var by = "{{ $by }}";
        if(by === "by-title"){
            var searchDataDiv = $(".step-one-search-data");
            searchDataDiv.show();
        } 

         // Listen for click events on span elements that are not gray
        $(document).on("click",".letter-filter", function() {
            var selectedText = $(this).text(); // Get the text of the clicked span
            var searchDataDiv = $(".step-one-search-data");
            var contentDiv = $(".step-one-search-data-content ul");

            if (selectedText) {
                searchDataDiv.show(); // Show search data
                contentDiv.html("<li>{{ __('Loading') }}...</li>"); // Show loading state

                $.ajax({
                    url: "{{ route('user.submission.getJournalsByLetter') }}",
                    type: "POST",
                    data: { letter: selectedText },
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: function (response) {
                        contentDiv.html(`
                            <li>
                                <h4>{{ __('Journal Title') }}</h4>
                                <h4>{{ __('Article Processing Charges') }}</h4>
                            </li>
                        `);

                        $.each(response.journals, function (index, journal) {
                            const titleToShow = currentLang === 'ar' 
                            ? (journal.arabic_title || journal.title) 
                            : journal.title;
                            contentDiv.append(`
                                <li>
                                    <label>
                                        <div class="left">
                                            <input type="radio" name="bySearch" value="${journal.id}">
                                            <span class="journal-name">${titleToShow}</span>
                                            <a href="${journal.website}" target="_blank">
                                                {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                        <h4>${journal.service.price} {{ __('USD') }}</h4>
                                    </label>
                                </li>
                            `);
                        });
                    },
                    error: function () {
                        contentDiv.html("<li>{{ __('Error loading journals') }}, {{ __('Please try again')}}.</li>.");
                    }
                });
            } else {
                searchDataDiv.hide(); // Hide if no subject selected
            }
        });

        //get journals by subject
        function getJournalsBySubject(subjectId,selectedJournalId = null) {

            var searchDataDiv = $(".step-one-search-data");
            var contentDiv = $(".step-one-search-data-content ul");

            if(subjectId){
                searchDataDiv.show(); // Show search data
                contentDiv.html("<li>{{ __('Loading') }}...</li>"); // Show loading state

                $.ajax({
                        url: "{{ route('user.submission.getJournalsBySubject') }}",
                        type: "POST",
                        data: { subject_id: subjectId },
                        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                        success: function (response) {
                            contentDiv.html(`
                                <li>
                                    <h4>{{ __('Journal Title') }}</h4>
                                    <h4>{{ __('Article Processing Charges') }}</h4>
                                </li>
                            `);
    
                            $.each(response.journals, function (index, journal) {
                                const isChecked = selectedJournalId == journal.id ? 'checked' : '';

                                const titleToShow = currentLang === 'ar' 
                            ? (journal.arabic_title || journal.title) 
                            : journal.title;
                                
                                contentDiv.append(`
                                    <li>
                                        <label>
                                            <div class="left">
                                                <input type="radio" name="bySearch" value="${journal.id}" ${isChecked}>
                                                <span class="journal-name">${titleToShow}</span>
                                                <a href="${journal.website}" target="_blank">
                                                    {{ __('Website') }} <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                            <h4>${journal.service.price} {{ __('USD') }}</h4>
                                        </label>
                                    </li>
                                `);
                            });

                            // **On Document Ready**: Get the checked radio button (if any)
                            if(selectedJournalId){
                                var checkedRadio = $("input[name='bySearch']:checked");
                                if (checkedRadio.length > 0) {
                                    getContinueButton(checkedRadio);
                                }
                            }
                           
                        },
                        error: function () {
                            contentDiv.html("<li>{{ __('Error loading journals') }}. {{ __('Please try again') }}.</li>");
                        }
                });

            }else{
                searchDataDiv.hide(); // Hide if no subject selected
            }
        }
    });
})(jQuery);




        
    </script>
@endpush