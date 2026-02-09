@extends('user.submission.main')
@push('style')
<style>
    .affiliation-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .affiliation-item {
        display: inline-flex;
        align-items: center;
        background-color: #e3f2fd;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        white-space: nowrap;
    }

    .remove-affiliation {
        background: none;
        border: null;
        color: red;
        font-size: 16px;
        margin-left: 5px;
        cursor: pointer;
    }

    .error-message-author {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
        display: none;
        align-items: center;
        gap: 5px;
    }

    .error-message-author i {
        font-size: 14px;
    }

    .global-error {
        color: #dc3545;
        padding: 10px;
        margin: 15px 0;
        border-radius: 4px;
        display: none;
    }

    select {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
    }

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.875em;
        margin-top: 5px;
        display: block;
    }

    .affiliation-set {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }

    .remove-affiliation-set {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        color: red;
        font-size: 20px;
        cursor: pointer;
    }

    .affiliation-set-fields {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    @media (max-width: 768px) {
        .affiliation-set-fields {
            grid-template-columns: 1fr;
        }
    }

    .corresponding-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
@endpush
@section('submission-content')
<!-- step 4 -->
<div class="tab-pane fade {{ $step === 'stepFour' ? 'show active' : '' }}" id="v-pills-stepFour" role="tabpanel" aria-labelledby="v-pills-stepFour-tab"
                    tabindex="0">

                    <div class="step-four">
                        <form action="{{ route('user.submission.add.authors.save') }}"  method="POST" data-handler="commonResponse" class="ajax">
                        @csrf
                        <input type="hidden" class="step-four-client-order-id"  name="id" value="{{ $clientOrderId }}" >
                            <div class="header-title">
                                <h2>{{ __('Step 4') }}: {{ __('Authors & Institutions') }}</h2>
                            </div>

                            <h3>{{ __('Author Details') }}</h3>

                            <div class="step-four-bg">
                            @if(isset($clientOrderSubmission->authors) && count($clientOrderSubmission->authors) > 0)
                                @foreach($clientOrderSubmission->authors as $index => $author)
                                    <div class="step-four-bg-white">
                                        <div class="heading">
                                            <h4>{{ __('Author') }} #{{ $index + 1 }}</h4>
                                                <button type="button" style="@if($index == 0) display: none; @endif">
                                                    <span> <i class="far fa-trash-alt"></i></span>
                                                    {{ __('Delete') }}
                                                </button>
                                        </div>
                                        <ul class="ul-input-list">
                                        <li>
                                            <label for="">{{ __('First Name') }}: <span>*</span></label>
                                            <input type="text" name="authors[{{$index}}][first_name]"
                                                value="{{ $author['first_name'] ?? '' }}"
                                                placeholder="{{ __('First Name') }} *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('First Name is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Last Name') }}: <span>*</span></label>
                                            <input type="text" name="authors[{{$index}}][last_name]"
                                                value="{{ $author['last_name'] ?? '' }}"
                                                placeholder="{{ __('Last Name') }} *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Last Name is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Email') }}: <span>*</span></label>
                                            <input type="email" name="authors[{{$index}}][email]"
                                                value="{{ $author['email'] ?? '' }}"
                                                placeholder="{{ __('Email') }}: *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Email is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('ORCID') }}:</label>
                                            <input type="text" name="authors[{{$index}}][orcid]"
                                                value="{{ $author['orcid'] ?? '' }}">
                                        </li>
                                        <li>
                                            <div>
                                                <label for="">{{ __('Affiliation') }}: <span>*</span></label>

                                                <!-- Affiliation Sets Container -->
                                                <div class="affiliation-sets" id="affiliation-sets-{{$index}}">
                                                    @php
                                                        $affiliations = isset($author['affiliation']) ? json_decode($author['affiliation'], true) : [[]];
                                                        if (empty($affiliations)) $affiliations = [[]];
                                                    @endphp

                                                    @foreach($affiliations as $aIndex => $affiliation)
                                                    <div class="affiliation-set">
                                                        <button type="button" class="remove-affiliation-set @if($aIndex == 0) d-none @endif">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <div class="affiliation-set-fields">
                                                            <div>
                                                                <label>{{ __('Department') }}</label>
                                                                <input type="text" name="authors[{{$index}}][affiliations][{{$aIndex}}][department]"
                                                                    value="{{ $affiliation['department'] ?? '' }}"
                                                                    placeholder="{{ __('Department') }}">
                                                            </div>
                                                            <div>
                                                                <label>{{ __('Faculty') }}</label>
                                                                <input type="text" name="authors[{{$index}}][affiliations][{{$aIndex}}][faculty]"
                                                                    value="{{ $affiliation['faculty'] ?? '' }}"
                                                                    placeholder="{{ __('Faculty') }}">
                                                            </div>
                                                            <div>
                                                                <label>{{ __('University') }} <span>*</span></label>
                                                                <input type="text" name="authors[{{$index}}][affiliations][{{$aIndex}}][university]"
                                                                    value="{{ $affiliation['university'] ?? '' }}"
                                                                    placeholder="{{ __('University') }} *" required>
                                                            </div>
                                                            <div>
                                                                <label>{{ __('City') }} <span>*</span></label>
                                                                <input type="text" name="authors[{{$index}}][affiliations][{{$aIndex}}][city]"
                                                                    value="{{ $affiliation['city'] ?? '' }}"
                                                                    placeholder="{{ __('City') }} *" required>
                                                            </div>
                                                            <div>
                                                                <label>{{ __('Country') }} <span>*</span></label>
                                                                <select name="authors[{{$index}}][affiliations][{{$aIndex}}][country]" required>
                                                                    <option value="">{{ __('Country') }} *</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country['name'] }}"
                                                                            {{ (isset($affiliation['country']) && $affiliation['country'] == $country['name']) ? 'selected' : '' }}>
                                                                            {{ $country['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <button type="button" class="add-affiliation-set" data-author-index="{{$index}}" style="padding: 5px;">
                                                    <i class="fas fa-plus"></i>
                                                    {{ __('Add an Affiliation') }}
                                                </button>

                                                <div class="error-message-author">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <span class="text">{{ __('At least one affiliation is required') }}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Nationality') }}: <span>*</span></label>
                                            <select name="authors[{{$index}}][nationality]" required>
                                                <option value="">{{ __('Select Nationality') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['name'] }}" {{ (isset($author['nationality']) && $author['nationality'] == $country['name']) ? 'selected' : '' }}>
                                                        {{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Nationality is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('WhatsApp Number') }}:</label>
                                            <input type="text" name="authors[{{$index}}][whatsapp]"
                                                value="{{ $author['whatsapp_number'] ?? '' }}"
                                                placeholder="+1234567890"
                                                pattern="^\+[0-9]{1,15}$">
                                            <small class="text-muted">{{ __('Include country code, e.g. +1 for US') }}</small>
                                        </li>
                                        <li class="mb-20">
                                            <label for="">{{ __('Birthday') }}:</label>
                                            <input type="date" name="authors[{{$index}}][birthday]"
                                                value="{{ $author['date_of_birth'] ?? '' }}"
                                                max="{{ date('Y-m-d') }}">
                                        </li>
                            </ul>
                            <div>
                                <h6>{{ __('Contributor Roles') }}:</h6>
                                <h6 class="contributor-bg" style="margin-top: 10px;">
                                {!! __('We kindly recommend referring to CRediT Taxonomy (<a href="https://credit.niso.org" target="_blank">https://credit.niso.org</a>) for the detailed term explanation.') !!}
                                </h6>
                                <div style="margin-top: 10px;">
                                    <ul class="step-four-check-list">
                                        @php
                                            $authorRoles = \App\Models\AuthorContributorRole::where('client_order_submission_id', $clientOrderSubmission->id)->where('author_details_id', $author['id'])->pluck('contributor_role_id')->toArray();
                                        @endphp
                                        @foreach ($contributorRoles as $role)
                                            @php
                                                $roleID = \Illuminate\Support\Str::studly($role->role_name).'_'.$index;
                                                $checked = in_array($role->id, $authorRoles) ? 'checked' : '';
                                            @endphp
                                            <li>
                                                <input type="checkbox" name="authors[{{$index}}][roles][]"
                                                       id="{{$roleID}}"
                                                       value="{{$role->id}}" {{ $checked }}>
                                                <label for="{{$roleID}}">
                                                    @if (session()->has('local') && session('local') == 'ar')
                                                        {{ $role->arabic_name }}
                                                    @else
                                                    {{$role->role_name}}
                                                    @endif
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="error-message-author">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span class="text">{{ __('At least one role is required') }}</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="corresponding-author">
                                <input type="checkbox" class="corresponding-checkbox" id="correspondingAuthor_{{$index}}"
                                       @if(($author['corresponding_author'] ?? 0) == 1) checked @endif>
                                <label for="correspondingAuthor_{{$index}}">{{ __('This is a corresponding author') }}</label>
                                <input type="hidden" name="authors[{{$index}}][corresponding_author]"
                                       value="{{ $author['corresponding_author'] ?? 0 }}">
                            </div>
                        </div>
                    @endforeach


                            @else
                                <div class="step-four-bg-white">
                                    <div class="heading">
                                        <h4>{{ __('Author') }} #1</h4>
                                        <button type="button">
                                            <span> <i class="far fa-trash-alt"></i></span>
                                            Delete
                                        </button>
                                    </div>
                                    <ul class="ul-input-list">
                                        <li>
                                            <label for="">{{ __('First Name') }}: <span>*</span></label>
                                            <input type="text" name="authors[0][first_name]" placeholder="{{ __('First Name') }} *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('First Name is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Last Name') }}: <span>*</span></label>
                                            <input type="text" name="authors[0][last_name]" placeholder="{{ __('Last Name') }} *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Last Name is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Email') }}: <span>*</span></label>
                                            <input type="email" name="authors[0][email]" placeholder="{{ __('Email') }}: *">
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Email is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('ORCID') }}:</label>
                                            <input type="text" name="authors[0][orcid]">
                                        </li>
                                        <li>
                                            <div>
                                                <label for="">{{ __('Affiliation') }}: <span>*</span></label>

                                                <!-- Affiliation Sets Container -->
                                                <div class="affiliation-sets" id="affiliation-sets-0">
                                                    <div class="affiliation-set">
                                                        <button type="button" class="remove-affiliation-set d-none">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <div class="affiliation-set-fields">
                                                            <div>
                                                                <label>{{ __('Department') }}</label>
                                                                <input type="text" name="authors[0][affiliations][0][department]" placeholder="{{ __('Department') }}">
                                                            </div>
                                                            <div>
                                                                <label>{{ __('Faculty') }}</label>
                                                                <input type="text" name="authors[0][affiliations][0][faculty]" placeholder="{{ __('Faculty') }}">
                                                            </div>
                                                            <div>
                                                                <label>{{ __('University') }} <span>*</span></label>
                                                                <input type="text" name="authors[0][affiliations][0][university]" placeholder="{{ __('University') }} *" required>
                                                            </div>
                                                            <div>
                                                                <label>{{ __('City') }} <span>*</span></label>
                                                                <input type="text" name="authors[0][affiliations][0][city]" placeholder="{{ __('City') }} *" required>
                                                            </div>
                                                            <div>
                                                                <label>{{ __('Country') }} <span>*</span></label>
                                                                <select name="authors[0][affiliations][0][country]" required>
                                                                    <option value="">{{ __('Country') }} *</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country['name'] }}"
                                                                            {{ isset($userCountry) && $userCountry == $country['name'] ? 'selected' : '' }}
                                                                        >
                                                                            {{ $country['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="button" class="add-affiliation-set" data-author-index="0" style="padding: 5px;">
                                                    <i class="fas fa-plus"></i>
                                                    {{ __('Add an Affiliation') }}
                                                </button>

                                                <div class="error-message-author">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <span class="text">{{ __('At least one affiliation is required') }}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('Nationality') }}: <span>*</span></label>
                                            <select name="authors[0][nationality]" >
                                                <option value="">{{ __('Select Nationality') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['name'] }}"
                                                        {{
                                                            (isset($author['nationality']) && $author['nationality'] == $country['name'])
                                                            || (!isset($author['nationality']) && isset($userCountry) && $userCountry == $country['name'])
                                                            ? 'selected'
                                                            : ''
                                                        }}
                                                    >
                                                        {{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('Nationality is required') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="">{{ __('WhatsApp Number') }}:</label>
                                            <input type="text" name="authors[0][whatsapp]"
                                                placeholder="+1234567890"
                                                pattern="^\+[0-9]{1,15}$">
                                            <small class="text-muted">{{ __('Include country code, e.g. +1 for US') }}</small>
                                        </li>
                                        <li class="mb-20">
                                            <label for="">{{ __('Birthday') }}:</label>
                                            <input type="date" name="authors[0][birthday]"
                                                max="{{ date('Y-m-d') }}">
                                        </li>
                                    </ul>
                                    <div>
                                        <h6>{{ __('Contributor Roles') }}:</h6>
                                        <h6 class="contributor-bg" style="margin-top: 10px;">
                                        {!! __('We kindly recommend referring to CRediT Taxonomy (<a href="https://credit.niso.org" target="_blank">https://credit.niso.org</a>) for the detailed term explanation.') !!}
                                        </h6>
                                        <div style="margin-top: 10px;">
                                            <ul class="step-four-check-list">
                                                @foreach ($contributorRoles as $role)
                                                @php
                                                    $roleID = \Illuminate\Support\Str::studly($role->role_name);
                                                @endphp
                                                    <li>
                                                        <input type="checkbox" name="authors[0][roles][]" id="{{$roleID}}_0" value="{{$role->id}}">
                                                        <label for="{{$roleID}}_0">
                                                            @if (session()->has('local') && session('local') == 'ar')
                                                                {{ $role->arabic_name }}
                                                            @else
                                                            {{$role->role_name}}
                                                            @endif
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="error-message-author">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span class="text">{{ __('At least one role is required') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="corresponding-author">
                                        <input type="checkbox" class="corresponding-checkbox" id="correspondingAuthor_0">
                                        <label for="correspondingAuthor_0">{{ __('This is a corresponding author') }}</label>
                                        <input type="hidden" name="authors[0][corresponding_author]" value="0">
                                    </div>
                                    <div class="global-error" id="corresponding-author-error" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>{{ __('At least one corresponding author must be selected') }}</span>
                                    </div>
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
                                    <a href="{{ route('user.submission.upload.files', ['id' => $clientOrderId]) }}">
                                        <button class="previous" type="button">{{ __('Previous Step') }}</button>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
@endsection
@push('script')
<script>
(function ($) {
    $(document).ready(function () {
        const $document = $(document);
        let authorCounter = {{ (isset($clientOrderSubmission->authors) && count($clientOrderSubmission->authors) > 0) ? count($clientOrderSubmission->authors) : 1 }};

        // Function to add a new affiliation set
        function addAffiliationSet(authorIndex) {
            const $container = $(`#affiliation-sets-${authorIndex}`);
            const setCount = $container.find('.affiliation-set').length;

            const newSet = `
                <div class="affiliation-set">
                    <button type="button" class="remove-affiliation-set">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="affiliation-set-fields">
                        <div>
                            <label>{{ __('Department') }}</label>
                            <input type="text" name="authors[${authorIndex}][affiliations][${setCount}][department]" placeholder="{{ __('Department') }}">
                        </div>
                        <div>
                            <label>{{ __('Faculty') }}</label>
                            <input type="text" name="authors[${authorIndex}][affiliations][${setCount}][faculty]" placeholder="{{ __('Faculty') }}">
                        </div>
                        <div>
                            <label>{{ __('University') }} <span>*</span></label>
                            <input type="text" name="authors[${authorIndex}][affiliations][${setCount}][university]" placeholder="{{ __('University') }} *" required>
                        </div>
                        <div>
                            <label>{{ __('City') }} <span>*</span></label>
                            <input type="text" name="authors[${authorIndex}][affiliations][${setCount}][city]" placeholder="{{ __('City') }} *" required>
                        </div>
                        <div>
                            <label>{{ __('Country') }} <span>*</span></label>
                            <select name="authors[${authorIndex}][affiliations][${setCount}][country]" required>
                                <option value="">{{ __('Country') }} *</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country['name'] }}">
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            `;

            $container.append(newSet);

            // Show remove button for the first set if there are now multiple sets
            if (setCount === 0) {
                $container.find('.affiliation-set:first .remove-affiliation-set').removeClass('d-none');
            }
        }

        // Function to remove an affiliation set
        function removeAffiliationSet() {
            const $set = $(this).closest('.affiliation-set');
            const $container = $set.closest('.affiliation-sets');

            // Don't remove the last set
            if ($container.find('.affiliation-set').length > 1) {
                $set.remove();

                // Hide remove button if only one set remains
                if ($container.find('.affiliation-set').length === 1) {
                    $container.find('.remove-affiliation-set').addClass('d-none');
                }
            }
        }

        // Event handlers
        $document.on('click', '.add-affiliation-set', function() {
            const authorIndex = $(this).data('author-index');
            addAffiliationSet(authorIndex);
        });

        $document.on('click', '.remove-affiliation-set', removeAffiliationSet);

        // Add a new author block
        function addAuthor() {
            authorCounter++;
            const $originalAuthor = $(".step-four-bg-white").first();
            const $newAuthor = $originalAuthor.clone();

            // Update the author number
            $newAuthor.find("h4").text("{{ __('Author') }} #" + authorCounter);

            // Clear input fields
            $newAuthor.find("input[type=text]").val("");
            $newAuthor.find("input[type=email]").val("");
            $newAuthor.find("input[type=date]").val("");
            $newAuthor.find("input[type=password]").val("");
            $newAuthor.find("textarea").val("");
            $newAuthor.find("select").val("");

            // Uncheck checkboxes and radios without clearing values
            $newAuthor.find("input[type=checkbox]").prop("checked", false);
            $newAuthor.find("input[type=radio]").prop("checked", false);

            // Reset affiliation sets
            $newAuthor.find(".affiliation-sets").html($originalAuthor.find(".affiliation-sets").html());

            // Update the `name` attributes
            $newAuthor.find('input, select, textarea').each(function () {
                const $field = $(this);
                const name = $field.attr('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${authorCounter - 1}]`);
                    $field.attr('name', newName);
                }
            });

            // Update IDs
            $newAuthor.find('[id]').each(function() {
                const oldId = $(this).attr('id');
                const newId = oldId.replace(/\d+$/, authorCounter - 1);
                $(this).attr('id', newId);

                // Update label for attributes
                if ($(this).is('input[type="checkbox"], input[type="radio"]')) {
                    $newAuthor.find(`label[for="${oldId}"]`).attr('for', newId);
                }
            });

            // Update corresponding author checkbox
            const $correspondingCheckbox = $newAuthor.find('.corresponding-checkbox');
            const newCheckboxId = `correspondingAuthor_${authorCounter - 1}`;
            $correspondingCheckbox.attr('id', newCheckboxId);
            $newAuthor.find(`label[for="${$correspondingCheckbox.attr('id')}"]`).attr('for', newCheckboxId);
            $newAuthor.find('input[name$="[corresponding_author]"]').val('0');

            // Update affiliation set buttons
            $newAuthor.find('.add-affiliation-set').attr('data-author-index', authorCounter - 1);

            // Append the new author block
            $(".step-four-bg-white").last().after($newAuthor);

            // Update author delete buttons
            updateAuthorButtons();

            // Clear error messages
            $newAuthor.find('.error-message-author').hide();
        }

        // Function to handle deleting the last author block
        function deleteAuthor() {
            const $authorBlocks = $(".step-four-bg-white");
            if ($authorBlocks.length > 1) {
                $authorBlocks.last().remove();
                authorCounter--;
                updateAuthorNumbers();
            } else {
                alert("At least one author is required.");
            }
        }

        // Function to update author numbers after deletion
        function updateAuthorNumbers() {
            $(".step-four-bg-white").each(function (index) {
                $(this).find("h4").text(`Author #${index + 1}`);
            });
        }

        // Function to ensure Author #1 always hides the delete button
        function updateAuthorButtons() {
            $(".step-four-bg-white").each(function (index) {
                const $deleteButton = $(this).find(".heading button");
                $deleteButton.toggle(index !== 0);
            });
        }

        // Initialize the author buttons
        updateAuthorButtons();

        // Event delegation for dynamic elements
        $document.on('click', '.add-one-more', addAuthor);
        $document.on('click', '.heading button', deleteAuthor);

        // Update corresponding_author hidden input when checkbox is toggled
        $document.on('change', '.corresponding-checkbox', function() {
            const $checkbox = $(this);
            const authorIndex = $checkbox.attr('id').replace('correspondingAuthor_', '');
            const hiddenInput = $(`input[name="authors[${authorIndex}][corresponding_author]"]`);
            hiddenInput.val($checkbox.prop('checked') ? '1' : '0');
        });

        // Form submission validation
        $('form.ajax').on('submit', function(e) {
            let isValid = true;
            const $form = $(this);
            let correspondingAuthorSelected = false;

            // Validate each author
            $(".step-four-bg-white").each(function(index) {
                const $authorBlock = $(this);
                let authorValid = true;

                // Reset error messages
                $authorBlock.find('.error-message-author').hide();

                // Validate required fields
                const requiredFields = $authorBlock.find('input[required], select[required]');
                requiredFields.each(function() {
                    if (!$(this).val()) {
                        $(this).closest('li').find('.error-message-author').show();
                        isValid = false;
                        authorValid = false;
                    }
                });

                // Validate at least one affiliation set has required fields
                const affiliationSets = $authorBlock.find('.affiliation-set');
                let affiliationValid = false;

                affiliationSets.each(function() {
                    const $set = $(this);
                    const university = $set.find('input[name$="[university]"]').val();
                    const city = $set.find('input[name$="[city]"]').val();
                    const country = $set.find('select[name$="[country]"]').val();

                    if (university && city && country) {
                        affiliationValid = true;
                    }
                });

                if (!affiliationValid) {
                    $authorBlock.find('.affiliation-sets').closest('li').find('.error-message-author').show();
                    isValid = false;
                    authorValid = false;
                }

                // Validate at least one role is selected
                const rolesSelected = $authorBlock.find('input[name$="[roles][]"]:checked').length;
                if (!rolesSelected) {
                    $authorBlock.find('.step-four-check-list').closest('div').find('.error-message-author').show();
                    isValid = false;
                    authorValid = false;
                }

                // Validate corresponding author
                if ($authorBlock.find('input[name$="[corresponding_author]"]').val() === '1') {
                    correspondingAuthorSelected = true;
                }
            });

            // Check corresponding author
            if (!correspondingAuthorSelected) {
                $('#corresponding-author-error').show();
                isValid = false;
            } else {
                $('#corresponding-author-error').hide();
            }

            if (!isValid) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });
    });
})(jQuery);
</script>
@endpush
