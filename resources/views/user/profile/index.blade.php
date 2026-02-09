@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('user.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="bd-one bd-c-stroke bd-ra-8 bd-bl-ra-0 bd-br-ra-0 pt-25 px-sm-25 px-10 bg-white">
                    <ul class="d-flex flex-wrap rg-20 zList-four">
                        <li>
                            <a href="{{ route('user.profile.index') }}"
                               class="d-flex align-items-center g-10 flex-wrap px-sm-30 px-15 pb-15 active">
                                <div class="flex-shrink-0">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16" cy="16" r="15.5" stroke="#5D697A" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M23 16C23 17.8769 22.2613 19.5812 21.0589 20.8382C19.7889 22.1657 18.0017 22.9942 16.0209 23L16 23C15.9935 23 15.987 23 15.9805 23C15.9803 23 15.98 23 15.9798 23C13.9987 22.9944 12.2112 22.1658 10.9411 20.8382C9.73866 19.5812 9 17.8769 9 16C9 15.7584 9.01224 15.5196 9.03614 15.2843C9.39461 11.7545 12.3756 9 16 9C19.866 9 23 12.134 23 16ZM11.7572 19.5C12.7659 20.7215 14.2921 21.5 16 21.5C17.7079 21.5 19.2341 20.7215 20.2428 19.5C19.2341 18.2785 17.7079 17.5 16 17.5C14.2921 17.5 12.7659 18.2785 11.7572 19.5ZM16.0001 16.5C17.3808 16.5 18.5001 15.3807 18.5001 14C18.5001 12.6193 17.3808 11.5 16.0001 11.5C14.6194 11.5 13.5001 12.6193 13.5001 14C13.5001 15.3807 14.6194 16.5 16.0001 16.5ZM16.0202 23C16.0135 23 16.0067 23 16 23L16.0202 23Z"
                                              fill="#5D697A" />
                                    </svg>
                                </div>
                                <span class="fs-14 fw-600 lh-25 text-para-text">{{ __('Basic Info') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.profile.password') }}"
                               class="d-flex align-items-center g-10 flex-wrap px-sm-30 px-15 pb-15">
                                <div class="flex-shrink-0">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="16" cy="16" r="15.5" stroke="#5D697A" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M17.485 9.85794C17.4881 9.87916 17.4905 9.90377 17.4954 9.953C17.5047 10.0459 17.5094 10.0924 17.5145 10.1234C17.6345 10.8556 18.4821 11.2066 19.0847 10.7738C19.1102 10.7555 19.1463 10.7259 19.2186 10.6668L19.2186 10.6668L19.2187 10.6667C19.2569 10.6354 19.276 10.6198 19.2932 10.6069C19.6783 10.3185 20.2143 10.3452 20.5688 10.6706C20.5846 10.6851 20.6021 10.7026 20.6371 10.7375L21.2625 11.363C21.2975 11.398 21.315 11.4154 21.3295 11.4313C21.6548 11.7858 21.6816 12.3217 21.3931 12.7068C21.3803 12.724 21.3646 12.7431 21.3333 12.7814L21.3333 12.7815C21.2741 12.8538 21.2445 12.89 21.2262 12.9155C20.7933 13.5181 21.1444 14.3656 21.8765 14.4857C21.9076 14.4908 21.9541 14.4954 22.047 14.5047C22.0962 14.5096 22.1208 14.5121 22.1421 14.5151C22.6184 14.5835 22.9785 14.9814 22.9991 15.4621C23 15.4835 23 15.5083 23 15.5578V16.4424C23 16.4917 23 16.5164 22.9991 16.5378C22.9785 17.0186 22.6184 17.4166 22.142 17.4849C22.1208 17.4879 22.0962 17.4904 22.0471 17.4953C21.9543 17.5046 21.9079 17.5092 21.877 17.5143C21.1447 17.6343 20.7936 18.4819 21.2265 19.0846C21.2448 19.1101 21.2743 19.1461 21.3334 19.2183L21.3334 19.2183C21.3647 19.2565 21.3803 19.2756 21.3931 19.2927C21.6816 19.6779 21.6549 20.214 21.3295 20.5685C21.315 20.5843 21.2976 20.6017 21.2628 20.6365L21.2627 20.6366L20.6371 21.2622C20.6022 21.2971 20.5847 21.3146 20.5689 21.3291C20.2143 21.6545 19.6784 21.6812 19.2932 21.3927C19.2761 21.3799 19.2569 21.3642 19.2187 21.3329L19.2186 21.3328C19.1463 21.2737 19.1102 21.2442 19.0847 21.2258C18.4821 20.793 17.6346 21.1441 17.5145 21.8762C17.5094 21.9073 17.5048 21.9538 17.4955 22.0468L17.4955 22.0469C17.4905 22.0962 17.4881 22.1209 17.485 22.1422C17.4166 22.6184 17.0188 22.9784 16.5381 22.9991C16.5166 23 16.4919 23 16.4423 23H15.5579C15.5084 23 15.4837 23 15.4622 22.9991C14.9815 22.9784 14.5836 22.6184 14.5153 22.1421C14.5122 22.1209 14.5098 22.0962 14.5048 22.047C14.4955 21.954 14.4909 21.9074 14.4858 21.8764C14.3657 21.1443 13.5182 20.7933 12.9157 21.226C12.8901 21.2444 12.8539 21.274 12.7816 21.3332C12.7432 21.3645 12.7241 21.3802 12.7069 21.3931C12.3218 21.6815 11.7858 21.6548 11.4313 21.3295C11.4155 21.315 11.398 21.2975 11.363 21.2625L11.363 21.2624L10.7376 20.637C10.7026 20.602 10.6851 20.5846 10.6706 20.5687C10.3453 20.2142 10.3185 19.6783 10.607 19.2931C10.6199 19.276 10.6355 19.2568 10.6669 19.2185L10.6669 19.2185C10.726 19.1462 10.7556 19.1101 10.7739 19.0845C11.2067 18.4819 10.8557 17.6344 10.1236 17.5143C10.0925 17.5093 10.046 17.5046 9.95304 17.4953C9.90378 17.4904 9.87915 17.4879 9.85791 17.4849C9.38162 17.4165 9.02156 17.0186 9.00092 16.5379C9 16.5165 9 16.4917 9 16.4422V15.5579C9 15.5083 9 15.4835 9.00092 15.462C9.0216 14.9814 9.38161 14.5835 9.85783 14.5152C9.87911 14.5121 9.90378 14.5096 9.95312 14.5047C10.0463 14.4954 10.0929 14.4907 10.124 14.4856C10.856 14.3655 11.207 13.5181 10.7743 12.9156C10.7559 12.89 10.7263 12.8537 10.667 12.7813L10.667 12.7813L10.667 12.7812C10.6356 12.7429 10.6199 12.7237 10.607 12.7065C10.3186 12.3214 10.3453 11.7855 10.6706 11.431C10.6851 11.4152 10.7027 11.3976 10.7377 11.3626L10.7377 11.3626L11.3631 10.7372C11.3981 10.7022 11.4156 10.6847 11.4314 10.6702C11.7859 10.3449 12.3218 10.3182 12.7069 10.6066C12.7241 10.6195 12.7433 10.6352 12.7817 10.6665L12.7817 10.6665C12.8539 10.7257 12.8901 10.7553 12.9156 10.7736C13.5182 11.2065 14.3658 10.8554 14.4858 10.1231C14.4909 10.0922 14.4956 10.0458 14.5048 9.95295L14.5048 9.95292L14.5049 9.95288C14.5098 9.90375 14.5122 9.87918 14.5153 9.85799C14.5836 9.38164 14.9815 9.02152 15.4623 9.00092C15.4837 9 15.5084 9 15.5578 9H16.4424C16.4919 9 16.5166 9 16.538 9.00092C17.0188 9.02155 17.4167 9.38163 17.485 9.85794ZM16 18.8C17.5464 18.8 18.8 17.5464 18.8 16C18.8 14.4536 17.5464 13.2 16 13.2C14.4536 13.2 13.2 14.4536 13.2 16C13.2 17.5464 14.4536 18.8 16 18.8Z"
                                              fill="#5D697A" />
                                    </svg>
                                </div>
                                <span class="fs-14 fw-600 lh-25 text-para-text">{{ __('Change Password') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <form class="ajax" action="{{ route('user.profile.update') }}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="p-sm-25 p-15 bd-one bd-t-zero bd-c-stroke bd-ra-8 bd-tl-ra-0 bd-tr-ra-0 bg-white mb-25">
                        <div class="pb-40">
                            <div class="upload-img-box profileImage-upload">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/icon/camera.svg') }}" alt="" />
                                </div>
                                <img src="{{ getFileUrl(auth()->user()->image) }}" />
                                <input type="file" name="image" id="zImageUpload" accept="image/*"
                                       onchange="previewFile(this)" />
                            </div>
                        </div>
                        @php
                            $name = $user->name;
                            $words = explode(' ', $name); // Split the string by spaces
                            $firstName = $words[0]; // Set the first word as first name

                            if (count($words) > 1) {
                                $lastName = $words[count($words) - 1]; // Set the last word as last name if more than one word
                            } else {
                                $lastName = ''; // If it's a single word, leave last name empty
                            }
                        @endphp
                        <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ __('Personal Information') }} :</h4>
                        <div class="row rg-20 pb-40">
                            <div class="col-md-4">
                                <label for="editProfileFirstName" class="zForm-label">{{ __('First Name') }}<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control zForm-control" id="editProfileName"
                                       value="{{ $userDetails && $userDetails->basic_first_name ? $userDetails->basic_first_name : $firstName }}" placeholder="{{ __('First Name') }}" />
                                <div class="error-message-show text-danger mt-1"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="editProfileMiddleName" class="zForm-label">{{ __('Middle Name (Optional)') }}</label>
                                <input type="text" name="middle_name" class="form-control zForm-control" id="editProfileMiddleName"
                                       value="{{ $userDetails->basic_middle_name ?? '' }}" placeholder="{{ __('Middle Name') }}" />
                            </div>
                            <div class="col-md-4">
                                <label for="editProfileLastName" class="zForm-label">{{ __('Last Name') }}<span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control zForm-control" id="editProfileLastName"
                                       value="{{ $userDetails && $userDetails->basic_last_name ? $userDetails->basic_last_name : $lastName }}" placeholder="{{ __('Last Name') }}" />
                                <div class="error-message-show text-danger mt-1"></div>
                            </div>
                        </div>
                        <div class="row rg-20 pb-40">
                            <div class="col-md-6">
                                <label for="editProfileEmail" class="zForm-label">{{ __('Email') }}<span class="text-danger">*</span></label>
                                <input type="text" name="email" class="form-control zForm-control"
                                       value="{{ auth()->user()->email }}" id="editProfileEmail"
                                       placeholder="{{ __('Email') }}" readonly />
                                <div class="error-message-show text-danger mt-1"></div>
                            </div>
                            <div class="col-md-6">
                                <!-- <label for="editProfileEmail" class="zForm-label">{{ __('Email') }}</label>
                                <input type="text" name="email" class="form-control zForm-control"
                                       value="{{ auth()->user()->email }}" id="editProfileEmail"
                                       placeholder="{{ __('Email') }}" readonly /> -->
                                       <label for="editProfileBirthDate" class="zForm-label">{{ __('Birth Date') }}<span class="text-danger">*</span></label>
                                       <input type="date" class="form-control zForm-control" name="date_of_birth" id="editProfileBirthDate" value="{{ auth()->user()->date_of_birth }}">
                                       <div class="error-message-show text-danger mt-1" id="editProfileBirthDate_error"></div>
                            </div>
                            
                            {{-- <div class="col-md-6">
                                <label for="editProfileEmail" class="zForm-label">{{ __('Address') }}</label>
                                <input type="text" name="address" class="form-control zForm-control"
                                       value="{{ $professionalDetails->address }}" id="editProfileEmail"
                                       placeholder="{{ __('Address') }}" />
                            </div> --}}
                        </div>
                        <div class="row rg-20 pb-40">
                            <div class="col-md-6">
                                <label for="editProfilePhoneNumber" class="zForm-label">{{ __('Phone Number') }}<span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control zForm-control" id="editProfilePhoneNumber"
                                       value="{{ auth()->user()->mobile }}" placeholder="{{ __('Phone Number') }}" />
                                       <div class="error-message-show text-danger mt-1" id="editProfilePhoneNumber_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="editProfileWhatsAppNumber" class="zForm-label">{{ __('WhatsApp Number (Optional)') }}</label>
                                <input type="text" name="whatsapp_number" class="form-control zForm-control" id="editProfileWhatsAppNumber"
                                       value="{{ auth()->user()->mobile }}" placeholder="{{ __('WhatsApp Number') }}" />
                            </div>
                        </div>
                        <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ __('Academic & Professional Details') }} :</h4>
                        <div class="row rg-20 pb-40">
                            <div class="col-md-3">
                                <label class="zForm-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleMr" value="Mr." @if( $professionalDetails && ($professionalDetails->title === "Mr.") ) checked @elseif(!$professionalDetails) checked @endif>
                                        <label class="form-check-label" for="titleMr">{{ __('Mr.') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleMs" value="Ms." @if($professionalDetails && ($professionalDetails->title === "Ms.")) checked @endif>
                                        <label class="form-check-label" for="titleMs">{{ __('Ms.') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleMrs" value="Mrs." @if($professionalDetails && ($professionalDetails->title === "Mrs.")) checked @endif >
                                        <label class="form-check-label" for="titleMrs">{{ __('Mrs.')}}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleDr" value="Dr." @if($professionalDetails && ($professionalDetails->title === "Dr.")) checked @endif>
                                        <label class="form-check-label" for="titleDr">{{ __('Dr.') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleProf" value="Prof." @if($professionalDetails && ($professionalDetails->title === "Prof.")) checked @endif >
                                        <label class="form-check-label" for="titleProf">{{ __('Prof.') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleEng" value="Eng." @if($professionalDetails && ($professionalDetails->title === "Eng.")) checked @endif >
                                        <label class="form-check-label" for="titleEng">{{ __('Eng.')}}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="title" id="titleOther" value="Other" @if($professionalDetails && ($professionalDetails->title === "Other")) checked @endif>
                                        <label class="form-check-label" for="titleOther">{{ __('Other') }}</label>
                                    </div>
                                    <input type="text" class="form-control @if($professionalDetails && $professionalDetails->title_spacify) d-block @else d-none @endif" name="title_other" placeholder="{{ __('Specify if needed') }}" value="{{ $professionalDetails && $professionalDetails->title_spacify ? $professionalDetails->title_spacify : '' }}">
                                </div>
                                <div class="error-message-show text-danger mt-1" id="title_error"></div>
                                <div class="error-message-show text-danger mt-1" id="title_other_error"></div>
                            </div>
                            <div class="col-md-3">
                                <label for="degree" class="zForm-label">{{ __('Highest Degree') }} <span class="text-danger">*</span></label>
                                <select class="form-select zForm-control" name="degree" id="degree">
                                    <option value="" @if(!$professionalDetails) selected @endif disabled>{{ __('Select your highest qualification') }}</option>
                                    <option value="PhD" 
                                    @if($professionalDetails && $professionalDetails->highest_degree === "PhD") selected @endif >{{ __('PhD (Doctor of Philosophy)') }}</option>
                                    <option value="MSc" @if($professionalDetails && $professionalDetails->highest_degree === "MSc") selected @endif >{{ __('MSc (Master of Science)') }}</option>
                                    <option value="MA" @if($professionalDetails && $professionalDetails->highest_degree === "MA") selected @endif >{{ __('MA (Master of Arts)') }}</option>
                                    <option value="MBA" @if($professionalDetails && $professionalDetails->highest_degree === "MBA") selected @endif>{{ __('MBA (Master of Business Administration)') }}</option>
                                    <option value="BSc/BA" @if($professionalDetails && $professionalDetails->highest_degree === "BSc/BA") selected @endif >{{ __('BSc/BA (Bachelor of Science/Arts)') }}</option>
                                    <option value="Diploma" @if($professionalDetails && $professionalDetails->highest_degree === "Diploma") selected @endif >{{ __('Diploma or Certification') }}</option>
                                </select>
                                <div class="mt-30 @if($professionalDetails && ($professionalDetails->highest_degree === 'Diploma') && $professionalDetails->diploma_or_certifiction_spacify) d-block @else d-none @endif">
                                    <input type="text" class="form-control" name="degree_diploma" placeholder="{{ __('Specify') }}" value="{{ $professionalDetails && ($professionalDetails->highest_degree === 'Diploma') ? $professionalDetails->diploma_or_certifiction_spacify : '' }}">
                                </div>
                                <div class="error-message-show text-danger mt-1" id="degree_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="zForm-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control zForm-control" name="address" id="address" value="{{ $professionalDetails->address ?? '' }}">
                                <div class="error-message-show text-danger mt-1" id="address_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="zForm-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control zForm-control" name="country" id="country" value="{{ $professionalDetails->country ?? '' }}">
                                <div class="error-message-show text-danger mt-1" id="country_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="institution" class="zForm-label">{{ __('Current Institution') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control zForm-control" name="institution" id="institution" value="{{ $professionalDetails->current_institution ?? '' }}">
                                <div class="error-message-show text-danger mt-1" id="institution_error"></div>
                            </div>
                        </div>
                        <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ __('Academic & Research Information') }} :</h4>
                        @php
                                $bachelor = collect($educationQualification)->firstWhere('qualification', 'bachelor');
                                $master = collect($educationQualification)->firstWhere('qualification', 'master');
                                $phd = collect($educationQualification)->firstWhere('qualification', 'phd');
                                $postdoc = collect($educationQualification)->firstWhere('qualification', 'postdoc');
                                $other = collect($educationQualification)->firstWhere('qualification', 'other');
                        @endphp
                        <div class="row rg-20 pb-40">
                                <div class="col-md-12 mb-3">
                                    <label class="zForm-label">{{ __('Education Qualification') }} <span class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 qualification-checkbox" type="checkbox" name="qualification[]" value="bachelor" {{ $bachelor ? 'checked' : '' }}>
                                            </div>
                                            <span class="input-group-text" style="min-width: 140px;">{{ __("Bachelor's degree") }}</span>
                                            <input type="text" class="form-control" name="qualification_field[bachelor]" placeholder="{{ __('Field of study') }}" value="{{ $bachelor ? $bachelor['field_in_study'] : '' }}">
                                            <div class="error-message-show text-danger mt-1"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 qualification-checkbox" type="checkbox" name="qualification[]" value="master" {{ $master ? 'checked' : '' }}>
                                            </div>
                                            <span class="input-group-text" style="min-width: 140px;">{{ __("Master's in") }}</span>
                                            <input type="text" class="form-control" name="qualification_field[master]" placeholder="{{ __('Field of study') }}" value="{{ $master ? $master['field_in_study'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 qualification-checkbox" type="checkbox" name="qualification[]" value="phd" {{ $phd ? 'checked' : '' }}>
                                            </div>
                                            <span class="input-group-text" style="min-width: 140px;">{{ __('PhD in') }}</span>
                                            <input type="text" class="form-control" name="qualification_field[phd]" placeholder="{{ __('Field of study') }}" value="{{ $phd ? $phd['field_in_study'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 qualification-checkbox" type="checkbox" name="qualification[]" value="postdoc" {{ $postdoc ? 'checked' : '' }}>
                                            </div>
                                            <span class="input-group-text" style="min-width: 140px;">{{ __('Postdoctoral') }}</span>
                                            <input type="text" class="form-control" name="qualification_field[postdoc]" placeholder="{{ __('Field of research') }}" value="{{ $postdoc ? $postdoc['field_in_study'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 qualification-checkbox" type="checkbox" name="qualification[]" value="other" {{ $other ? 'checked' : '' }}>
                                            </div>
                                            <span class="input-group-text" style="min-width: 140px;">{{ __('Other') }}</span>
                                            <input type="text" class="form-control" name="qualification_field[other]" placeholder="{{ __('Specify') }}" value="{{ $other ? $other['field_in_study'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="error-message-show text-danger mt-1" id="qualifications_error"></div>
                                </div>
                                <div class=" col-md-12 mb-3">
                                    <label for="researchInterests" class="zForm-label">{{ __('Research Interests') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control zForm-control" name="researchInterests" id="researchInterests" placeholder="{{ __('e.g., Renewable Energy, Data Science, Climate Change')}}" value="{{ $researchInformation->research_interest ?? ''}}">
                                    <div class="error-message-show text-danger mt-1" id="researchInterests_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="orcidId" class="zForm-label">{{ __('ORCID ID (Optional but Recommended)') }}</label>
                                    <input type="text" class="form-control zForm-control" name="orcidId" id="orcidId" placeholder="{{__('0000-0000-0000-0000')}}" value="{{ $researchInformation->orcid_id ?? ''}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="scholarProfile" class="zForm-label">{{ __('Google Scholar Profile (If available)') }}</label>
                                    <input type="url" class="form-control zForm-control" name="scholarProfile" id="scholarProfile" placeholder="https://scholar.google.com/citations?user=xxxxxxx" value="{{ $researchInformation->google_scholar_profile ?? ''}}">
                                </div>
                        </div>
                        <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ __('Professional & Publications Details') }} :</h4>
                        <div class="row rg-20 pb-40">
                            <div class="mb-3">
                                <label for="professionalBio" class="zForm-label">{{ __('Professional Bio') }}</label>
                                <textarea class="form-control zForm-control" id="professionalBio" name="professionalBio" rows="4" placeholder="{{ __('A short description of your academic background and achievements...')}}"> @if($professionalDetails){{$professionalDetails->professional_bio}} @endif</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="zForm-label">{{ __('Publications (If applicable)') }}</label>
                                <div class="publication-list">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ __('1') }}</span>
                                        <input type="text" class="form-control zForm-control" name="publications[]" placeholder="{{ __('e.g., \'Title of Paper\' - Published in Journal, Year') }}" value="{{ $publicationDetails[0]['published_work'] ?? ''}}">
                                    </div>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ __('2') }}</span>
                                        <input type="text" class="form-control zForm-control" name="publications[]" placeholder="{{ __('e.g., \'Book Title\' - Published by Publisher, Year')}}" value="{{ $publicationDetails[1]['published_work'] ?? ''}}">
                                    </div>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ __('3') }}</span>
                                        <input type="text" class="form-control zForm-control" name="publications[]" placeholder="{{ __('e.g., \'Conference Paper\' - Presented at Conference, Year')}}" value="{{ $publicationDetails[2]['published_work'] ?? ''}}">
                                    </div>
                                    @if($publicationDetails && count($publicationDetails) > 3)
                                        @for ($i = 3; $i < count($publicationDetails); $i++)
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">{{ $i + 1 }}</span>
                                            <input type="text" class="form-control zForm-control" name="publications[]" value="{{ $publicationDetails[$i]['published_work'] ?? ''}}">
                                            <button type="button" class="btn btn-danger btn-sm delete-pub">×</button>
                                        </div>
                                        @endfor
                                    @endif

                                </div>
                                <button type="button" class="btn btn-outline-secondary mt-2 btn-sm btn-add-pub">{{ __('+ Add More Publications') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-12">
                        <button
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script>
    (function($) {
        "use strict";

        // Show/hide title_other input
        $('input[name="title"]').change(function() {
            if ($('#titleOther').is(':checked')) {
                $('input[name="title_other"]').removeClass('d-none');
            } else {
                $('input[name="title_other"]').addClass('d-none');
            }
        });

        // Show/hide diploma specification input
        $('#degree').change(function() {
            if ($(this).val() === 'Diploma') {
                $('input[name="degree_diploma"]').closest('.mt-30').removeClass('d-none');
            } else {
                $('input[name="degree_diploma"]').closest('.mt-30').addClass('d-none');
            }
        });

        // Add new publication field with delete button
        $(document).on('click', '.btn-add-pub', function() {
            // Calculate next publication number
            let currentNumbers = [];
            $('.publication-list .input-group-text').each(function() {
                const num = parseInt($(this).text());
                if (!isNaN(num)) currentNumbers.push(num);
            });
            
            const newNumber = currentNumbers.length ? Math.max(...currentNumbers) + 1 : 4;

            const newGroup = $(`<div class="input-group mb-2">
                <span class="input-group-text">${newNumber}</span>
                <input type="text" class="form-control zForm-control" name="publications[]">
                <button type="button" class="btn btn-danger btn-sm delete-pub">×</button>
            </div>`);

            $('.publication-list').append(newGroup);
        });

        // Remove dynamic publication fields
        $(document).on('click', '.delete-pub', function() {
            $(this).closest('.input-group').remove();
        });


             // Initialize error containers
             function initErrorContainers() {
            // Add error containers for all fields
            $('[data-validation]').each(function() {
                if (!$(this).next('.error-message-show').length) {
                    $(this).after('<div class="error-message-show text-danger mt-1"></div>');
                }
            });
        }

        // Main validation function
        function validateForm() {
            let isValid = true;
            $('.error-message-show').text(''); // Clear previous errors

            // Required field validation
            const requiredFields = [
                '#editProfileName', 
                '#editProfileLastName',
                '#editProfileBirthDate',
                '#editProfilePhoneNumber',
                '#address',
                '#country',
                '#institution',
                '#researchInterests'
            ];

            requiredFields.forEach(selector => {
                const field = $(selector);
                if (!field.val().trim()) {
                    field.next('.error-message-show').text('This field is required');
                    isValid = false;
                    console.log('error');
                }
            });

            // Title validation
            const titleChecked = $('input[name="title"]:checked');
            if (!titleChecked.length) {
                $('#title_error').text('Please select a title');
                isValid = false;
                console.log('error01');
            } else if (titleChecked.val() === 'Other' && !$('input[name="title_other"]').val().trim()) {
                $('#title_other_error').text('Please specify your title');
                isValid = false;
                console.log('error01');
            }

            // Phone number format
            // const phone = $('#editProfilePhoneNumber').val().trim();
            // if (phone && !/^\d+$/.test(phone)) {
            //     $('#editProfilePhoneNumber_error').text('Invalid phone number format');
            //     isValid = false;
            //     console.log('error02');
            // }

            // Birth date validation
            const birthDate = $('#editProfileBirthDate').val();
            if (birthDate) {
                const dob = new Date(birthDate);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                if (today.getMonth() < dob.getMonth() || 
                   (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())) {
                    age--;
                }
                if (age < 18) {
                    $('#editProfileBirthDate_error').text('You must be at least 18 years old');
                    isValid = false;
                    console.log('error03');
                }
            }

            // Research qualifications validation
            let hasQualifications = false;
            $('.qualification-checkbox:checked').each(function() {
                hasQualifications = true;
                const inputField = $(this).closest('.input-group').find('input[type="text"]');
                if (!inputField.val().trim()) {
                    inputField.next('.error-message-show').text('Field of study is required');
                    isValid = false;
                    console.log('error04');
                }
            });
            
            if (!hasQualifications) {
                $('#qualifications_error').text('At least one qualification is required');
                isValid = false;
                console.log('error05');
            }

            return isValid;
        }

        // Form submission handler
        $('form').on('submit', function(e) {
            initErrorContainers();
            if (!validateForm()) {
                e.preventDefault(); // Stop form submission
                console.log('errrorrr')
                return false;
                console.log('error06');
            }
            console.log('fsdlafk');
            //return true; // Proceed with normal submission
        });

    })(jQuery);
</script>
@endpush
