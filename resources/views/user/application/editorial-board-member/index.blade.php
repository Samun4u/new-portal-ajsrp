@extends('user.layouts.app')
@push('title')
{{$pageTitle}}
@endpush

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
<style>
    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    .section-title {
        color: #0d6efd;
        margin-bottom: 1.5rem;
    }
    .required-field::after {
        content: "*";
        color: #dc3545;
        margin-left: 4px;
    }
    .word-count {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .word-count.warning {
        color: #dc3545;
    }
    .iti {
        display: block;
    }
    .file-upload-info {
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .photo-preview {
        max-width: 150px;
        max-height: 150px;
        margin-top: 10px;
        display: none;
    }
    .form-tooltip {
        cursor: help;
        color: #0d6efd;
    }
    .progress-bar {
        transition: width 0.5s ease;
    }
</style>
@endpush

@section('content')
<!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="text-center mb-5">
        <h1 class="mb-3">{{ __('Join as Editorial Board Member') }}</h1>
        <p class="lead text-muted">{{ __('Thank you for your interest in joining the Editorial Board of Arab Journal of Sciences and Research Publishing (AJSRP) ') }}</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">{{ __('Editorial Board Member Application') }}</h2>
        </div>
        <div class="card-body">
            <p>{{ __('Our board members are vital to ensuring academic rigor and advancing scholarly research. Please complete the form below to apply. All fields marked with an asterisk') }} (<span class="text-danger">*</span>)  {{ __('are required.') }}</p>

            <form id="ebmApplicationForm" method="POST" action="{{ route('user.join.application.editorial-board-member.save') }}" enctype="multipart/form-data">
                @csrf

                <!-- Progress Indicator -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Progress') }}</span>
                        <span id="progressPercentage">0%</span>
                    </div>
                    <div class="progress">
                        <div id="formProgress" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Section 1: Personal Information -->
                <div class="form-section" data-aos="fade-right" data-aos-delay="100">
                    <h2 class="section-title">1. {{ __('Personal Information') }} </h2>
                    
                    <div class="mb-3">
                        <label for="fullName" class="form-label required-field">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" placeholder="{{ __('e.g., Dr. Sarah Johnson') }}" minlength="2" required>
                        <div class="invalid-feedback">{{ __('Please provide your full name (minimum 2 characters).') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label required-field">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('e.g., sarah.johnson@university.edu') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide a valid email address.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('Phone Number (Optional)') }}</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                        <div class="invalid-feedback">{{ __('Please provide a valid phone number.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label required-field">{{ __('Country of Residence') }}</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="" selected disabled>{{ __('Select your country') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ __('Please select your country.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">{{ __('LinkedIn or Personal Website (Optional)') }}
                            <span class="form-tooltip" data-bs-toggle="tooltip" title="{{ __('Providing a LinkedIn profile or personal website helps us verify your professional background.') }}">?</span>
                        </label>
                        <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="{{ __('e.g., https://linkedin.com/in/username') }}">
                        <div class="invalid-feedback">{{ __('Please provide a valid URL.') }}</div>
                    </div>
                </div>

                <!-- Section 2: Academic and Professional Background -->
                <div class="form-section" data-aos="fade-left" data-aos-delay="150">
                    <h2 class="section-title">2. {{ __('Academic and Professional Background') }}</h2>
                    
                    <div class="mb-3">
                        <label for="degree" class="form-label required-field">{{ __('Highest Academic Degree') }}</label>
                        <input type="text" class="form-control" id="degree" name="degree" placeholder="{{ __('e.g., PhD, MSc, MD') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide your highest academic degree.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="specialization" class="form-label required-field">{{ __('Field of Specialization') }}</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" placeholder="{{ __('e.g., Computational Biology, Modernist Literature') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide your field of specialization.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label required-field">{{ __('Current Academic/Professional Title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('e.g., Associate Professor, Senior Data Scientist') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide your current title.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="institution" class="form-label required-field">{{ __('Institution/Organization Name') }}</label>
                        <input type="text" class="form-control" id="institution" name="institution" placeholder="{{ __('e.g., University of Example') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide your institution name.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="experience" class="form-label required-field">{{ __('Years of Post-Qualification Experience in Your Field') }}</label>
                        <input type="number" class="form-control" id="experience" name="experience" min="0" max="50" placeholder="{{ __('e.g., 7') }}" required>
                        <div class="invalid-feedback">{{ __('Please provide a valid number (0-50).') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="publications" class="form-label">{{ __('List of Relevant Publications (Optional)') }}</label>
                        <p class="small text-muted">{{ __('Provide citations or links to your key publications. Alternatively, upload a document below.') }}</p>
                        <textarea class="form-control" id="publications" name="publications" rows="3" placeholder="{{ __('e.g., Smith, J. (2023). Journal Name. DOI:10.1000/xyz; https://scholar.google.com/...') }}"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supportingDoc" class="form-label">{{ __('Upload Supporting Document (Optional)') }}</label>
                        <input type="file" class="form-control" id="supportingDoc" name="supporting_doc" accept="{{ __('.pdf,.doc,.docx') }}">
                        <div class="file-upload-info" id="supportingDocInfo">{{ __('Maximum file size: 5MB (PDF or DOCX)') }}</div>
                        <div class="invalid-feedback">{{ __('Please upload a valid file (PDF or DOCX, max 5MB).') }}</div>
                    </div>
                </div>

                <!-- Section 3: Editorial and Reviewing Experience -->
                <div class="form-section" data-aos="fade-right" data-aos-delay="200">
                    <h2 class="section-title">3. {{ __('Editorial and Reviewing Experience') }}</h2>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Have you served on an editorial board before?') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="editorial_board_exp" id="editorialYes" value="1">
                            <label class="form-check-label" for="editorialYes">{{ __('Yes') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="editorial_board_exp" id="editorialNo" value="0" checked>
                            <label class="form-check-label" for="editorialNo">{{ __('No') }}</label>
                        </div>
                        
                        <div id="editorialDetailsContainer" class="mt-3" style="display: none;">
                            <label for="editorialDetails" class="form-label">{{ __('If Yes, specify journal(s), role(s), and duration:') }}</label>
                            <textarea class="form-control" id="editorialDetails" name="editorial_details" rows="2" placeholder="{{ __('e.g., Journal of Applied Sciences, Associate Editor, 2019–2022 ') }}"></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Have you worked as a peer reviewer?') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="peer_reviewer_exp" id="reviewerYes" value="1">
                            <label class="form-check-label" for="reviewerYes">{{ __('Yes') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="peer_reviewer_exp" id="reviewerNo" value="0" checked>
                            <label class="form-check-label" for="reviewerNo">{{ __('No') }}</label>
                        </div>
                        
                        <div id="reviewerDetailsContainer" class="mt-3" style="display: none;">
                            <label for="reviewerDetails" class="form-label">{{ __('If Yes, list journals or publishers reviewed for:') }}</label>
                            <textarea class="form-control" id="reviewerDetails" name="reviewer_details" rows="2" placeholder="{{ __('e.g., Nature Communications, IEEE Transactions') }}"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Research Interests and Contributions -->
                <div class="form-section" data-aos="fade-left" data-aos-delay="250">
                    <h2 class="section-title">4. {{ __('Research Interests and Contributions') }}</h2>
                    
                    <div class="mb-3">
                        <label class="form-label required-field">{{ __('Areas of Interest') }}</label>
                        <p class="small text-muted">{{ __('Select all that apply or specify other areas.') }}</p>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestAI" value="Artificial Intelligence & Machine Learning">
                            <label class="form-check-label" for="interestAI">{{ __('Artificial Intelligence & Machine Learning') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestMedical" value="Medical & Health Sciences">
                            <label class="form-check-label" for="interestMedical">{{ __('Medical & Health Sciences') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestEngineering" value="Engineering & Technology">
                            <label class="form-check-label" for="interestEngineering">{{ __('Engineering & Technology ') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestHumanities" value="Humanities & Arts">
                            <label class="form-check-label" for="interestHumanities">{{ __('Humanities & Arts ') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestEducation" value="Education & Pedagogy">
                            <label class="form-check-label" for="interestEducation">{{ __('Education & Pedagogy ') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestBusiness" value="Business, Economics & Management">
                            <label class="form-check-label" for="interestBusiness">{{ __('Business, Economics & Management ') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="interests[]" id="interestOther" value="Other">
                            <label class="form-check-label" for="interestOther">{{ __('Other') }}</label>
                        </div>
                        
                        <div id="otherInterestContainer" class="mt-2" style="display: none;">
                            <input type="text" class="form-control" id="otherInterest" name="other_interest" placeholder="{{ __('Please specify your area of interest') }}">
                        </div>
                        <div class="invalid-feedback">{{ __('Please select at least one area of interest.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="purpose" class="form-label required-field">{{ __('Statement of Purpose') }}</label>
                        <p class="small text-muted">{{ __('In 200 words or less, explain why you wish to join the Editorial Board and how your expertise aligns with our mission.') }}</p>
                        <textarea class="form-control" id="purpose" name="purpose" rows="5" placeholder="{{ __('I am eager to contribute to Arab Journal of Sciences and Research Publishing (AJSRP) because...') }}" maxlength="1000" required></textarea>
                        <div class="word-count" id="wordCount">{{ __('Words: 0/200') }}</div>
                        <div class="invalid-feedback">{{ __('Please provide a statement of purpose (200 words maximum).') }}</div>
                    </div>
                </div>

                <!-- Section 5: Attachments -->
                <div class="form-section" data-aos="fade-right" data-aos-delay="300">
                    <h2 class="section-title">5. {{ __('Attachments') }}</h2>
                    
                    <div class="mb-3">
                        <label for="cv" class="form-label required-field">{{ __('Curriculum Vitae/Resume') }}</label>
                        <p class="small text-muted">{{ __('Upload your CV/Resume (PDF or DOCX, max 5MB).') }}</p>
                        <input type="file" class="form-control" id="cv" name="cv" accept="{{ __('.pdf,.doc,.docx') }}" required>
                        <div class="file-upload-info" id="cvFileInfo">{{ __('No file selected') }}</div>
                        <div class="invalid-feedback">{{ __('Please upload your CV/Resume (PDF or DOCX, max 5MB).') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="photo" class="form-label">{{ __('Professional Photo (Optional)') }}</label>
                        <p class="small text-muted">{{ __('Upload a professional headshot for potential use on our website (JPG or PNG, max 5MB).') }}</p>
                        <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png">
                        <div class="file-upload-info" id="photoFileInfo">{{ __('No file selected') }}</div>
                        <img id="photoPreview" class="photo-preview img-thumbnail" alt="Photo preview">
                        <div class="invalid-feedback">{{ __('Please upload a valid image (JPG or PNG, max 5MB).') }}</div>
                    </div>
                </div>

                <!-- Section 6: Commitment and Consent -->
                <div class="form-section" data-aos="fade-left" data-aos-delay="350">
                    <h2 class="section-title">6. {{ __('Commitment and Consent') }}</h2>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="commitment" name="commitment" required>
                        <label class="form-check-label required-field" for="commitment">{{ __('Commitment to Editorial Duties') }}</label>
                        <p class="small">{{ __('I confirm my willingness to contribute to the peer review process and editorial decisions, including timely reviews, impartial feedback, and conflict-of-interest disclosures.') }}</p>
                        <div class="invalid-feedback">{{ __('You must agree to the commitment statement.') }}</div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="acknowledgment" name="acknowledgment">
                        <label class="form-check-label" for="acknowledgment">{{ __('Public Acknowledgment (Optional)') }}</label>
                        <p class="small">{{ __('I agree to have my name, affiliation, and (if provided) photo listed on the Arab Journal of Sciences and Research Publishing (AJSRP) website.') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="small">{{ __('Privacy Statement: Your data will be used solely for evaluating your application and managing editorial processes.') }}' <a href="/privacy-policy" target="_blank">{{ __('Read our Privacy Policy') }}</a> {{ __('for details.') }}</p>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">{{ __('Submit Application') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="confirmationModalLabel">{{ __('Application Submitted') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Thank you for your application. We will review it and contact you within 2–4 weeks.') }}</p>
                <p>{{ __('A confirmation email has been sent to ') }} <span id="confirmEmail"></span>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize phone input with intl-tel-input
        const phoneInput = window.intlTelInput(document.querySelector("#phone"), {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
            preferredCountries: ['us', 'gb', 'ca', 'au', 'in'],
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                fetch("https://ipapi.co/json")
                    .then(function(res) { return res.json(); })
                    .then(function(data) { callback(data.country_code); })
                    .catch(function() { callback('us'); });
            }
        });

        // Show/hide conditional fields - FIXED
        // For editorial board experience
        const editorialRadios = document.querySelectorAll('input[name="editorial_board_exp"]');
        editorialRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('editorialDetailsContainer').style.display = 
                    document.getElementById('editorialYes').checked ? 'block' : 'none';
                updateProgress();
            });
        });

        // For peer reviewer experience
        const reviewerRadios = document.querySelectorAll('input[name="peer_reviewer_exp"]');
        reviewerRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('reviewerDetailsContainer').style.display = 
                    document.getElementById('reviewerYes').checked ? 'block' : 'none';
                updateProgress();
            });
        });

        document.getElementById('interestOther').addEventListener('change', function() {
            document.getElementById('otherInterestContainer').style.display = 
                this.checked ? 'block' : 'none';
            updateProgress();
        });

        // Word count for statement of purpose
        const purposeTextarea = document.getElementById('purpose');
        const wordCountDisplay = document.getElementById('wordCount');
        
        purposeTextarea.addEventListener('input', function() {
            const text = this.value.trim();
            const wordCount = text ? text.split(/\s+/).length : 0;
            wordCountDisplay.textContent = `Words: ${wordCount}/200`;
            
            if (wordCount > 200) {
                wordCountDisplay.classList.add('warning');
                this.classList.add('is-invalid');
            } else {
                wordCountDisplay.classList.remove('warning');
                this.classList.remove('is-invalid');
            }
            
            updateProgress();
        });

        // File upload display
        document.getElementById('cv').addEventListener('change', function() {
            const fileInfo = document.getElementById('cvFileInfo');
            if (this.files.length > 0) {
                fileInfo.textContent = `Selected: ${this.files[0].name}`;
            } else {
                fileInfo.textContent = 'No file selected';
            }
            updateProgress();
        });

        document.getElementById('supportingDoc').addEventListener('change', function() {
            const fileInfo = document.getElementById('supportingDocInfo');
            if (this.files.length > 0) {
                fileInfo.textContent = `Selected: ${this.files[0].name}`;
            } else {
                fileInfo.textContent = 'Maximum file size: 5MB (PDF or DOCX)';
            }
            updateProgress();
        });

        document.getElementById('photo').addEventListener('change', function() {
            const fileInfo = document.getElementById('photoFileInfo');
            const preview = document.getElementById('photoPreview');
            
            if (this.files.length > 0) {
                fileInfo.textContent = `Selected: ${this.files[0].name}`;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                fileInfo.textContent = 'No file selected';
                preview.style.display = 'none';
            }
            updateProgress();
        });

        // Form validation and progress tracking
        const form = document.getElementById('ebmApplicationForm');
        const requiredFields = form.querySelectorAll('[required]');
        const progressBar = document.getElementById('formProgress');
        const progressPercentage = document.getElementById('progressPercentage');
        
        function updateProgress() {
            let filledCount = 0;
            
            requiredFields.forEach(field => {
                if (field.type === 'checkbox') {
                    if (field.checked) filledCount++;
                } else if (field.type === 'file') {
                    if (field.files.length > 0) filledCount++;
                } else if (field.type === 'radio') {
                    const name = field.name;
                    const checkedRadio = form.querySelector(`input[name="${name}"]:checked`);
                    if (checkedRadio && checkedRadio.value === '1') {
                        // For conditional fields, check if the additional info is provided
                        const detailsContainer = document.getElementById(`${name.replace('_exp', 'DetailsContainer')}`);
                        if (!detailsContainer || (detailsContainer && detailsContainer.querySelector('textarea').value.trim() !== '')) {
                            filledCount++;
                        }
                    } else if (checkedRadio) {
                        filledCount++;
                    }
                } else if (field.tagName === 'SELECT') {
                    if (field.value) filledCount++;
                } else {
                    if (field.value.trim() !== '') filledCount++;
                }
            });
            
            // Check if at least one interest is selected
            const interests = form.querySelectorAll('input[name="interests[]"]:checked');
            if (interests.length > 0) filledCount++;
            
            const percentage = Math.round((filledCount / (requiredFields.length + 1)) * 100);
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage);
            progressPercentage.textContent = `${percentage}%`;
        }
        
        // Add event listeners to all form fields
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });
        
        // Initial progress update
        updateProgress();
        
        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            let isValid = true;
            
            // Check required fields
            requiredFields.forEach(field => {
                if (field.type === 'checkbox' && !field.checked) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field.type === 'file' && field.files.length === 0) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field.type === 'radio') {
                    const name = field.name;
                    const checkedRadio = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checkedRadio) {
                        form.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                            radio.classList.add('is-invalid');
                        });
                        isValid = false;
                    } else if (checkedRadio.value === '1') {
                        const detailsContainer = document.getElementById(`${name.replace('_exp', 'DetailsContainer')}`);
                        if (detailsContainer && detailsContainer.querySelector('textarea').value.trim() === '') {
                            detailsContainer.querySelector('textarea').classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                } else if (field.tagName === 'SELECT' && !field.value) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if ((field.type === 'text' || field.type === 'email' || field.type === 'textarea') && field.value.trim() === '') {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field.id === 'purpose') {
                    const wordCount = field.value.trim() ? field.value.trim().split(/\s+/).length : 0;
                    if (wordCount > 200) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                }
            });
            
            // Check interests
            const interests = form.querySelectorAll('input[name="interests[]"]:checked');
            if (interests.length === 0) {
                document.querySelector('input[name="interests[]"]').classList.add('is-invalid');
                isValid = false;
            } else if (document.getElementById('interestOther').checked && document.getElementById('otherInterest').value.trim() === '') {
                document.getElementById('otherInterest').classList.add('is-invalid');
                isValid = false;
            }
            
            if (isValid) {
                // // In a real application, you would submit the form here
                // // For this demo, we'll show a confirmation modal
                // document.getElementById('confirmEmail').textContent = document.getElementById('email').value;
                // const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                // modal.show();
                
                // // Reset form after submission
                // setTimeout(() => {
                //     form.reset();
                //     document.getElementById('photoPreview').style.display = 'none';
                //     document.getElementById('wordCount').textContent = 'Words: 0/200';
                //     document.getElementById('cvFileInfo').textContent = 'No file selected';
                //     document.getElementById('supportingDocInfo').textContent = 'Maximum file size: 5MB (PDF or DOCX)';
                //     document.getElementById('photoFileInfo').textContent = 'No file selected';
                //     document.getElementById('editorialDetailsContainer').style.display = 'none';
                //     document.getElementById('reviewerDetailsContainer').style.display = 'none';
                //     document.getElementById('otherInterestContainer').style.display = 'none';
                //     phoneInput.setNumber("");
                //     updateProgress();
                // }, 3000);

                // Create FormData object
                const formData = new FormData(form);
                
                // Append the phone number with country code
                const phoneNumber = phoneInput.getNumber();
                formData.set('phone', phoneNumber);

                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

                // AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else if (response.status === 422) {
                        return response.json().then(errors => { throw errors; });
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .then(data => {
                    // Show success modal
                    document.getElementById('confirmEmail').textContent = document.getElementById('email').value;
                    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                    modal.show();
                    
                    // Reset form
                    form.reset();
                    document.getElementById('photoPreview').style.display = 'none';
                    document.getElementById('wordCount').textContent = 'Words: 0/200';
                    document.getElementById('cvFileInfo').textContent = 'No file selected';
                    document.getElementById('supportingDocInfo').textContent = 'Maximum file size: 5MB (PDF or DOCX)';
                    document.getElementById('photoFileInfo').textContent = 'No file selected';
                    document.getElementById('editorialDetailsContainer').style.display = 'none';
                    document.getElementById('reviewerDetailsContainer').style.display = 'none';
                    document.getElementById('otherInterestContainer').style.display = 'none';
                    phoneInput.setNumber("");
                    updateProgress();
                })
                .catch(error => {
                    if (error.errors) {
                        // Clear previous errors
                        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                        // Display new errors
                        Object.entries(error.errors).forEach(([field, messages]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = messages[0];
                                }
                            }
                            
                            // Special handling for interests
                            if (field === 'interests') {
                                const interestsContainer = document.querySelector('.interests-container');
                                if (interestsContainer) {
                                    interestsContainer.classList.add('is-invalid');
                                    const feedback = interestsContainer.querySelector('.invalid-feedback');
                                    if (feedback) {
                                        feedback.textContent = messages[0];
                                    }
                                }
                            }
                        });
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit Application';
                });
            } else {
                // Scroll to the first invalid field
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Remove invalid class when user starts typing
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
                
                // For radio buttons, remove invalid from all in group
                if (this.type === 'radio') {
                    const name = this.name;
                    form.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                        radio.classList.remove('is-invalid');
                    });
                    
                    // Also remove invalid from associated textarea if exists
                    const detailsContainer = document.getElementById(`${name.replace('_exp', 'DetailsContainer')}`);
                    if (detailsContainer) {
                        detailsContainer.querySelector('textarea').classList.remove('is-invalid');
                    }
                }
            });
        });
    });
</script>
@endpush