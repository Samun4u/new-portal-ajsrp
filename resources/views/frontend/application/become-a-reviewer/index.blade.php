@extends('frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    :root {
        --primary-color: #1a3a6c;
        --secondary-color: #e63946;
        --accent-color: #2a9d8f;
        --light-bg: #f8f9fa;
        --dark-bg: #212529;
    }
    
    .application-container {
        /* max-width: 900px; */
        margin: 2rem auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .language-toggle {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 100;
    }

    .btn-language {
        background-color: #1a3a6c;
        border: 1px solid rgba(255, 255, 255, 0.5);
        color: white;
        border-radius: 20px;
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }
    
    .btn-language:hover {
        background-color: rgba(255, 255, 255, 0.3);
        border-color: white;
    }
    
    .header-section {
        background: linear-gradient(135deg, var(--primary-color), #0d2b52);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
    }
    
    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #eee;
    }
    
    .section-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--accent-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    
    .progress-container {
        padding: 1.5rem 2rem;
        background-color: #f1f7fd;
        border-bottom: 1px solid #dee2e6;
    }
    
    .progress {
        height: 1.5rem;
        border-radius: 0.75rem;
    }
    
    .progress-bar {
        background-color: var(--accent-color);
        transition: width 0.5s ease;
    }
    
    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        flex-wrap: wrap; /* Allow wrapping on small screens */
    }
    
    .progress-step {
        color: #6c757d;
        text-align: center;
        flex: 0 0 16%; /* 6 items per row */
        padding: 0.2rem;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .progress-step.active {
        color: var(--primary-color);
        font-weight: bold;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.3rem;
    }
    
    .required-field::after {
        content: " *";
        color: var(--secondary-color);
    }
    
    .optional-field::after {
        content: " (optional)";
        color: #6c757d;
        font-weight: normal;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background-color: #0d2b52;
        border-color: #0d2b52;
    }
    
    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(42, 157, 143, 0.25);
    }
    
    .tag-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
        min-height: 2.5rem;
        padding: 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
    }
    
    .tag {
        background-color: #e9ecef;
        border-radius: 1rem;
        padding: 0.25rem 0.75rem;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    
    .tag-remove {
        margin-left: 0.5rem;
        cursor: pointer;
        color: #6c757d;
    }
    
    .tag-remove:hover {
        color: var(--secondary-color);
    }
    
    .tag-input {
        flex-grow: 1;
        border: none;
        outline: none;
        min-width: 100px;
    }
    
    .tag-input:focus {
        box-shadow: none;
    }
    
    .file-upload-container {
        border: 2px dashed #ced4da;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .file-upload-container:hover {
        border-color: var(--accent-color);
        background-color: #f1f7fd;
    }
    
    .file-info {
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .file-preview {
        max-width: 100%;
        margin-top: 1rem;
        border-radius: 0.5rem;
        display: none;
    }
    
    .btn-remove {
        margin-top: 0.5rem;
        display: none;
    }
    
    .collapse-btn {
        background: none;
        border: none;
        padding: 0;
        font-weight: 500;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .collapse-btn:hover {
        color: #0d2b52;
        text-decoration: underline;
    }
    
    .collapse-btn i {
        margin-right: 0.5rem;
        transition: transform 0.3s;
    }
    
    .collapse-btn.collapsed i {
        transform: rotate(-90deg);
    }
    
    .info-tooltip {
        color: var(--accent-color);
        margin-left: 0.3rem;
        cursor: help;
    }
    
    .confirmation-message {
        display: none;
        padding: 3rem;
        text-align: center;
    }
    
    .confirmation-icon {
        font-size: 4rem;
        color: var(--accent-color);
        margin-bottom: 1.5rem;
    }
    
    footer {
        text-align: center;
        padding: 1.5rem;
        background-color: var(--dark-bg);
        color: #f8f9fa;
        margin-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .application-container {
            margin: 0;
            border-radius: 0;
        }
        
        .header-section, .form-section {
            padding: 1.5rem;
        }

        .progress-steps {
            gap: 0.5rem;
            justify-content: center;
        }
        
        .progress-step {
            flex: 0 0 30%; /* 3 items per row on mobile */
            font-size: 0.75rem;
            padding: 0.1rem;
        }

        .language-toggle {
            top: 0.5rem;
            right: 0.5rem;
        }
        
        .btn-language {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }
    }
    @media (max-width: 576px) {
        .progress-step {
            flex: 0 0 48%; /* 2 items per row on very small screens */
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
<div class="col-md-2"></div>
<!-- Content -->
<div class="col-md-8">
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="application-container">

        <!-- Language Toggle Button -->
        <div class="language-toggle">
            @php
                $currentLang = session('local', 'en');
                $otherLang = $currentLang === 'en' ? 'ar' : 'en';
                $otherLangName = $currentLang === 'en' ? 'العربية' : 'English';
            @endphp
            <a href="{{ route('local', $otherLang) }}" class="btn btn-language">
                <i class="bi bi-translate me-1"></i> {{ $otherLangName }}
            </a>
        </div>
        <div class="header-section">
            <h1><i class="bi bi-people-fill me-2"></i>{{ __('Become a Reviewer') }}</h1> 
            <p class="lead mt-3">{{ __('Help uphold academic excellence and integrity by becoming a peer reviewer') }}</p> 

             <p class=" mt-3">{{ __('Thank you for your interest in joining the Peer Reviewer Board of Arab Journal of Sciences and Research Publishing (AJSRP). Our peer reviewers play a critical role in upholding academic excellence and integrity. We welcome applications from qualified researchers and professionals. Please complete the form below to apply.') }}</p> 
        </div>

        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="progress-steps">
                <span class="progress-step active">{{ __('Personal Info') }}</span>
                <span class="progress-step">{{ __('Background') }}</span>
                <span class="progress-step">{{ __('Expertise') }}</span>
                <span class="progress-step">{{ __('Experience') }}</span>
                <span class="progress-step">{{ __('Documents') }}</span>
                <span class="progress-step">{{ __('Agreement') }}</span>
            </div>
        </div>

        <form id="reviewerForm" class="needs-validation" novalidate action="{{ route('join.application.reviewer.save') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="subject_areas" id="subjectAreasInput">
            <input type="hidden" name="keywords" id="keywordsInput">

            <!-- Section 1: Applicant Information -->
            <div class="form-section" id="section1">
                <h2 class="section-title">{{ __('Applicant Information') }}</h2>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="fullName" class="form-label required-field">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" placeholder="{{ __('e.g., Dr. Jane Smith') }}" required>
                        <div class="invalid-feedback">{{ __('Please enter your full name.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label required-field">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('e.g., jane.smith@university.edu') }}" required>
                        <div class="invalid-feedback">{{ __('Please enter a valid email address.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="institution" class="form-label required-field">{{ __('Current Institution/Organization') }}</label>
                        <input type="text" class="form-control" id="institution" placeholder="{{ __('e.g., University of Example') }}" name="institution" required>
                        <div class="invalid-feedback">{{ __('Please enter your institution/organization.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label required-field">{{ __('Country of Residence') }}</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="" selected disabled>Select your country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country['code'] }}">{{ session('local') !== 'en' ? $country['name'] : $country['nameEn'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ __('Please select your country.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="orcid" class="form-label">{{ __('ORCID iD') }} <span class="text-muted">{{ __('(Strongly Recommended)') }}</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="orcid" placeholder="{{ __('e.g., 0000-0001-2345-6789') }}" name="orcid">
                            <a href="https://orcid.org" target="_blank" class="btn btn-outline-secondary">{{ __('What is ORCID?') }}</a>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="collapse-btn" data-bs-toggle="collapse" data-bs-target="#profileLinks">
                    <i class="bi bi-chevron-down"></i> {{ __('Professional Profile Links (Optional)') }}
                </button>
                
                <div class="collapse" id="profileLinks">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="googleScholar" class="form-label optional-field">{{ __('Google Scholar Profile') }}</label>
                            <input type="url" class="form-control" id="googleScholar" placeholder="https://scholar.google.com/..." name="profile_links[google_scholar]">
                            <div class="invalid-feedback">{{ __('Please enter a valid URL.') }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="linkedin" class="form-label optional-field">{{ __('LinkedIn Profile') }}</label>
                            <input type="url" class="form-control" id="linkedin" placeholder="https://linkedin.com/..." name="profile_links[linkedin]">
                            <div class="invalid-feedback">{{ __('Please enter a valid URL.') }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="researchgate" class="form-label optional-field">{{ __('ResearchGate Profile') }}</label>
                            <input type="url" class="form-control" id="researchgate" placeholder="https://researchgate.net/..." name="profile_links[researchgate]">
                            <div class="invalid-feedback">{{ __('Please enter a valid URL.') }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label optional-field">{{ __('Personal Academic Website') }}</label>
                            <input type="url" class="form-control" id="website" placeholder="https://yourwebsite.com" name="profile_links[website]">
                            <div class="invalid-feedback">{{ __('Please enter a valid URL.') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary" disabled>{{ __('Previous') }}</button>
                    <button type="button" class="btn btn-primary next-section">{{ __('Next Section') }}</button>
                </div>
            </div>
            
            <!-- Section 2: Academic & Professional Background -->
            <div class="form-section d-none" id="section2">
                <h2 class="section-title">{{ __('Academic & Professional Background') }}</h2>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="qualification" class="form-label required-field">{{ __('Highest Educational Qualification') }}</label>
                        <input type="text" class="form-control" id="qualification" placeholder="{{ __('e.g., PhD, MD, MSc') }}" name="qualification" required>
                        <div class="invalid-feedback">{{ __('Please enter your highest qualification.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="field" class="form-label required-field">{{ __('Field of Study/Specialization') }}</label>
                        <input type="text" class="form-control" id="field" placeholder="{{ __('e.g., Molecular Biology, Artificial Intelligence') }}" name="field_of_study" required>
                        <div class="invalid-feedback">{{ __('Please enter your field of study.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label required-field">{{ __('Current Position/Title') }}</label>
                        <input type="text" class="form-control" id="position" placeholder="{{ __('e.g., Associate Professor, Senior Researcher') }}" name="position" required>
                        <div class="invalid-feedback">{{ __('Please enter your current position.') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="experience" class="form-label required-field">{{ __('Years of Post-Qualification Experience') }}</label>
                        <input type="number" class="form-control" id="experience" min="0" max="100" placeholder="{{ __('e.g., 5') }}" name="experience_years" required>
                        <div class="invalid-feedback">{{ __('Please enter a valid number of years (0-100).') }}</div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-section">{{ __('Previous') }}</button>
                    <button type="button" class="btn btn-primary next-section">{{ __('Next Section') }}</button>
                </div>
            </div>
            
            <!-- Section 3: Areas of Expertise -->
            <div class="form-section d-none" id="section3">
                <h2 class="section-title">{{ __('Areas of Expertise') }}</h2>
                
                <div class="mb-4">
                    <label class="form-label required-field">{{ __('Broad Subject Areas') }}</label>
                    <p class="text-muted small">{{ __('Select all that apply') }}</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ai" name="subjectAreas" value="artificial_intelligence_&_computer Science">
                                <label class="form-check-label" for="ai">{{ __('Artificial Intelligence & Computer Science') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="health" name="subjectAreas" value="health_&_medical_science">
                                <label class="form-check-label" for="health">{{ __('Health & Medical Sciences') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="engineering" name="subjectAreas" value="engineering_&_technology">
                                <label class="form-check-label" for="engineering">{{ __('Engineering & Technology') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="physical" name="subjectAreas" value="physical_science">
                                <label class="form-check-label" for="physical">{{ __('Physical Sciences (Physics, Chemistry, Earth Science)') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="life" name="subjectAreas" value="life_science">
                                <label class="form-check-label" for="life">{{ __('Life Sciences (Biology, Genetics, Neuroscience)') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="environment" name="subjectAreas" value="environmental_&_climate_science">
                                <label class="form-check-label" for="environment">{{ __('Environmental & Climate Sciences') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="social" name="subjectAreas" value="social_sciences_&_sociology">
                                <label class="form-check-label" for="social">{{ __('Social Sciences & Sociology') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="education" name="subjectAreas" value="education_&_pedagogy">
                                <label class="form-check-label" for="education">{{ __('Education & Pedagogy') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="economics" name="subjectAreas" value="economics_&_finance_&_management">
                                <label class="form-check-label" for="economics">{{ __('Economics, Finance & Management') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="arts" name="subjectAreas" value="arts_&_humanities">
                                <label class="form-check-label" for="arts">{{ __('Arts & Humanities') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="otherSubject" name="subjectAreas" value="other">
                                <label class="form-check-label" for="otherSubject">{{ __('Other') }}</label>
                            </div>
                        </div>

                    </div>
                    
                    <div class="mt-3" id="otherSubjectInput" style="display:none;">
                        <label for="otherSubjectText" class="form-label">{{ __('Please specify') }}</label>
                        <input type="text" class="form-control" id="otherSubjectText">
                    </div>

                    <div class="invalid-feedback" id="subjectError" style="display:none;">{{ __('Please select at least one subject area.') }}</div>
                </div>
                
                <div class="mb-4">
                    <label for="keywords" class="form-label required-field">{{ __('Specific Keywords of Expertise') }}</label>
                    <p class="text-muted small">{{ __('Provide 3-10 keywords describing your niche research areas to help match you with relevant manuscripts (e.g., Machine Learning, Cancer Immunotherapy, Structural Engineering)') }}</p>
                    
                    <div class="tag-container" id="tagContainer">
                        <input type="text" class="tag-input" id="keywordInput" placeholder="{{ __('Enter a keyword and press Enter') }}">
                    </div>
                    <div class="form-text">{{ __('Minimum 3 keywords, maximum 10. Press Enter after each keyword.') }}</div>
                    <div class="invalid-feedback" id="keywordError" style="display:none;">{{ __('Please enter between 3 and 10 keywords.') }}</div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-section">{{ __('Previous') }}</button>
                    <button type="button" class="btn btn-primary next-section">{{ __('Next Section') }}</button>
                </div>
            </div>
            
            <!-- Section 4: Reviewing and Publishing History -->
            <div class="form-section d-none" id="section4">
                <h2 class="section-title">{{ __('Reviewing and Publishing History') }}</h2>
                
                <div class="mb-4">
                    <label for="reviewExperience" class="form-label optional-field">{{ __('Previous Reviewing Experience') }}</label>
                    <p class="text-muted small">{{ __('List journals, conferences, or publishers where you have served as a peer reviewer. Leave blank if none.') }}</p>
                    <textarea class="form-control" id="reviewExperience" rows="3" placeholder="{{ __('e.g., Journal of Applied Physics, IEEE Conference') }}" name="review_experience"></textarea>
                </div>
                
                <div class="alert alert-info">
                    <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>{{ __('Publication Record') }}</h5>
                    <p>{{ __('Your publication history will be evaluated based on your uploaded CV and professional profiles provided in Section 1. No additional input is required here.') }}</p>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-section">{{ __('Previous') }}</button>
                    <button type="button" class="btn btn-primary next-section">{{ __('Next Section') }}</button>
                </div>
            </div>
            
            <!-- Section 5: Required Documentation -->
            <div class="form-section d-none" id="section5">
                <h2 class="section-title">{{ __('Required Documentation') }}</h2>
                
                <div class="mb-4">
                    <label class="form-label required-field">{{ __('Curriculum Vitae (CV)') }}</label>
                    <p class="text-muted small">{{ __('Upload your full academic CV (PDF or DOCX, max 5MB)') }}</p>
                    
                    <div class="file-upload-container" id="cvUpload">
                        <i class="bi bi-file-earmark-arrow-up fs-1 text-primary"></i>
                        <h5>{{ __('Upload your CV') }}</h5>
                        <p class="text-muted">{{ __('Drag & drop your file here or click to browse') }}</p>
                        <input type="file" class="d-none" id="cvFile" name="cv" accept=".pdf,.doc,.docx">
                        <div class="file-info" id="cvFileInfo">{{ __('No file selected') }}</div>
                    </div>
                    <div class="invalid-feedback" id="cvError" style="display:none;">{{ __('Please upload a valid CV file (PDF or DOCX, max 5MB).') }}</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label optional-field">{{ __('Professional Headshot') }}</label>
                    <p class="text-muted small">{{ __('Upload a photo for potential use on our Reviewer Board page (JPG or PNG, max 5MB)') }}</p>
                    
                    <div class="file-upload-container" id="photoUpload">
                        <i class="bi bi-image fs-1 text-primary"></i>
                        <h5>{{ __('Upload your photo') }}</h5>
                        <p class="text-muted">{{ __('Drag & drop your file here or click to browse') }}</p>
                        <input type="file" class="d-none" id="photoFile" name="photo" accept=".jpg,.jpeg,.png">
                        <div class="file-info" id="photoFileInfo">{{ __('No file selected') }}</div>
                    </div>
                    <img id="photoPreview" class="file-preview" alt="{{ __('Preview of uploaded photo') }}">
                    <button type="button" class="btn btn-outline-danger btn-remove" id="removePhoto">{{ __('Remove Photo') }}</button>
                    <div class="invalid-feedback" id="photoError" style="display:none;">{{ __('Please upload a valid image file (JPG or PNG, max 5MB).') }}</div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-section">{{ __('Previous') }}</button>
                    <button type="button" class="btn btn-primary next-section">{{ __('Next Section') }}</button>
                </div>
            </div>
            
            <!-- Section 6: Reviewer Agreement & Consent -->
            <div class="form-section d-none" id="section6">
                <h2 class="section-title">{{ __('Reviewer Agreement & Consent') }}</h2>
                
                <div class="mb-4">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="reviewerAgreement" name="agreement" required>
                        <label class="form-check-label required-field" for="reviewerAgreement">
                            {{ __('By checking this box, I confirm my commitment to:') }}
                        </label>
                        <ul class="mt-2" style="list-style-type: disc; padding-left: 20px;">
                            <li>{{ __('Review manuscripts in my expertise within agreed timelines') }}</li>
                            <li>{{ __('Maintain strict confidentiality of unpublished work') }}</li>
                            <li>{{ __('Provide objective, impartial, and constructive feedback') }}</li>
                            <li>{{ __('Declare any conflicts of interest before accepting review invitations') }}</li>
                        </ul>
                        <div class="invalid-feedback" id="agreementError">{{ __('You must agree to the reviewer terms before submitting.') }}</div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">{{ __('Public Acknowledgment') }}</label>
                    <p class="text-muted small">{{ __('We value our reviewers and may list your name, affiliation, and (if provided) photo on our Reviewer Board page.') }}</p>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="acknowledgment" id="consentYes" value="yes">
                        <label class="form-check-label" for="consentYes">{{ __('I consent to public acknowledgment') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="acknowledgment" id="consentNo" value="no" checked>
                        <label class="form-check-label" for="consentNo">{{ __('I do not consent to public acknowledgment') }}</label>
                    </div>
                </div>
                
                <div class="alert alert-light">
                    <h6 class="alert-heading">{{ __('Privacy Statement') }}</h6>
                    <p>{{ __('Your data will be used solely for evaluating your application and managing the peer review process for Arab Journal of Sciences and Research Publishing (AJSRP). Read our') }} <a href="#" target="_blank">{{ __('Privacy Policy') }}</a> {{ __('for details.') }}</p>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-section">{{ __('Previous') }}</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Submit Application') }}</button>
                </div>
            </div>
        </form>
        
        <!-- Confirmation Message -->
        <div class="confirmation-message" id="confirmationMessage">
            <i class="bi bi-check-circle-fill confirmation-icon"></i>
            <h2>{{ __('Thank You for Your Application!') }}</h2>
            <p class="lead">{{ __('Our editorial team will review your application and contact you within 2-4 weeks.') }}</p>
            <p>{{ __('Your expertise is vital to advancing scholarly research.') }}</p>
            <p id="countdown"></p>
            <button class="btn btn-outline-primary mt-3" id="newApplication">{{ __('Submit Another Application') }}</button>
        </div>
    </div>
</div>
</div>
<div class="col-md-2"></div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form navigation
        const sections = document.querySelectorAll('.form-section');
        const nextButtons = document.querySelectorAll('.next-section');
        const prevButtons = document.querySelectorAll('.prev-section');
        const progressBar = document.querySelector('.progress-bar');
        const progressSteps = document.querySelectorAll('.progress-step');
        let currentSection = 0;
        
        // Initialize progress
        updateProgress();

        //detect country
        detectCountry();
        
        // Next button functionality
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (validateSection(currentSection)) {
                    sections[currentSection].classList.add('d-none');
                    currentSection++;
                    sections[currentSection].classList.remove('d-none');
                    updateProgress();
                }
            });
        });
        
        // Previous button functionality
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                sections[currentSection].classList.add('d-none');
                currentSection--;
                sections[currentSection].classList.remove('d-none');
                updateProgress();
            });
        });
        
        // Update progress bar and steps
        function updateProgress() {
            const progress = ((currentSection + 1) / sections.length) * 100;
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', progress);
            
            progressSteps.forEach((step, index) => {
                if (index <= currentSection) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }
        
        // Section validation
        function validateSection(sectionIndex) {
            let isValid = true;
            const section = sections[sectionIndex];
            
            // Section 1 validation
            if (sectionIndex === 0) {
                const requiredFields = section.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.checkValidity()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Email format validation
                const emailField = section.querySelector('#email');
                if (emailField.value && !validateEmail(emailField.value)) {
                    emailField.classList.add('is-invalid');
                    emailField.nextElementSibling.textContent = '{{ __("Please enter a valid email address.") }}';
                    isValid = false;
                }
            }
            
            // Section 2 validation
            if (sectionIndex === 1) {
                const requiredFields = section.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.checkValidity()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
            }
            
            // Section 3 validation
            if (sectionIndex === 2) {
                // Check if at least one subject area is selected
                const subjectCheckboxes = section.querySelectorAll('input[name="subjectAreas"]:checked');
                if (subjectCheckboxes.length === 0) {
                    document.querySelector('#subjectError').style.display = 'block';
                    isValid = false;
                } else {
                    document.querySelector('#subjectError').style.display = 'none';
                }
                
                // Check if other subject is selected but not specified
                if (document.getElementById('otherSubject').checked && 
                    !document.getElementById('otherSubjectText').value.trim()) {
                    document.getElementById('otherSubjectText').classList.add('is-invalid');
                    isValid = false;
                } else if (document.getElementById('otherSubjectText').classList.contains('is-invalid')) {
                    document.getElementById('otherSubjectText').classList.remove('is-invalid');
                }
                
                // Keyword validation
                const keywordTags = section.querySelectorAll('.tag');
                if (keywordTags.length < 3 || keywordTags.length > 10) {
                    document.getElementById('keywordError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('keywordError').style.display = 'none';
                }
            }
            
            // Section 5 validation
            if (sectionIndex === 4) {
                const cvFile = document.getElementById('cvFile');
                if (!cvFile.files.length) {
                    document.getElementById('cvError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('cvError').style.display = 'none';
                }
            }

            // Section 6 validation - FIXED AGREEMENT VALIDATION
            if (sectionIndex === 5) {
                const agreementCheckbox = document.getElementById('reviewerAgreement');
                const agreementError = document.getElementById('agreementError');
                
                if (!agreementCheckbox.checked) {
                    agreementError.style.display = 'block';
                    isValid = false;
                } else {
                    agreementError.style.display = 'none';
                }
            }
            
            return isValid;
        }
        
        // Email validation function
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Show/hide other subject input
        document.getElementById('otherSubject').addEventListener('change', function() {
            document.getElementById('otherSubjectInput').style.display = 
                this.checked ? 'block' : 'none';
        });
        
        // Keyword tag functionality
        const tagContainer = document.getElementById('tagContainer');
        const keywordInput = document.getElementById('keywordInput');
        
        keywordInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = keywordInput.value.trim();
                if (keyword && !keywordInput.disabled) {
                    addKeywordTag(keyword);
                    keywordInput.value = '';
                }
            }
        });
        
        function addKeywordTag(keyword) {
            const tags = tagContainer.querySelectorAll('.tag');
            if (tags.length >= 10) {
                keywordInput.disabled = true;
                return;
            }
            
            const tag = document.createElement('div');
            tag.className = 'tag';
            tag.innerHTML = `
                ${keyword}
                <span class="tag-remove" onclick="removeKeywordTag(this)">&times;</span>
            `;
            tagContainer.insertBefore(tag, keywordInput);
            keywordInput.disabled = tags.length >= 9;
        }
        
        window.removeKeywordTag = function(removeBtn) {
            const tag = removeBtn.parentElement;
            tag.remove();
            keywordInput.disabled = tagContainer.querySelectorAll('.tag').length >= 10;
        };
        
        // File upload functionality
        document.getElementById('cvUpload').addEventListener('click', function() {
            document.getElementById('cvFile').click();
        });
        
        document.getElementById('photoUpload').addEventListener('click', function() {
            document.getElementById('photoFile').click();
        });
        
        document.getElementById('cvFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileInfo = document.getElementById('cvFileInfo');
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                
                // Validate file type and size
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    fileInfo.textContent = '{{ __("Invalid file type. Please upload a PDF or DOCX file.") }}';
                    fileInfo.style.color = 'var(--bs-danger)';
                    this.value = '';
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    fileInfo.textContent = '{{ __("File is too large (max 5MB)") }}';
                    fileInfo.style.color = 'var(--bs-danger)';
                    this.value = '';
                    return;
                }
                
                fileInfo.textContent = `${file.name} (${fileSize} MB)`;
                fileInfo.style.color = 'var(--bs-success)';
                document.getElementById('cvError').style.display = 'none';
            }
        });
        
        document.getElementById('photoFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileInfo = document.getElementById('photoFileInfo');
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                
                // Validate file type and size
                const validTypes = ['image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    fileInfo.textContent = '{{ __("Invalid file type. Please upload a JPG or PNG file.") }}';
                    fileInfo.style.color = 'var(--bs-danger)';
                    this.value = '';
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    fileInfo.textContent = '{{ __("File is too large (max 5MB)") }}';
                    fileInfo.style.color = 'var(--bs-danger)';
                    this.value = '';
                    return;
                }
                
                fileInfo.textContent = `${file.name} (${fileSize} MB)`;
                fileInfo.style.color = 'var(--bs-success)';
                document.getElementById('photoError').style.display = 'none';
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.getElementById('removePhoto').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Remove photo
        document.getElementById('removePhoto').addEventListener('click', function() {
            document.getElementById('photoFile').value = '';
            document.getElementById('photoPreview').style.display = 'none';
            document.getElementById('photoFileInfo').textContent = '{{ __("No file selected") }}';
            document.getElementById('photoFileInfo').style.color = '#6c757d';
            this.style.display = 'none';
        });
        
        // Form submission
        document.getElementById('reviewerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Final validation
            if (validateSection(currentSection)) {
                // In a real application, this would submit via AJAX
                document.getElementById('section6').classList.add('d-none');
                document.getElementById('confirmationMessage').style.display = 'block';
                
                // Reset progress bar to 100%
                progressBar.style.width = '100%';
            }
        });
        
        // New application button
        document.getElementById('newApplication').addEventListener('click', function() {
            location.reload();
        });

        // Form submission
        document.getElementById('reviewerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Final validation
            if (!validateSection(currentSection)) return;
            
            // Prepare form data
            const form = this;
            const formData = new FormData(form);
            
            // Collect subject areas
            const subjectAreas = Array.from(document.querySelectorAll('input[name="subjectAreas"]:checked'))
                .map(checkbox => checkbox.value);
            // Add "other" subject if specified
            if (document.getElementById('otherSubject').checked) {
                const otherText = document.getElementById('otherSubjectText').value.trim();
                if (otherText) subjectAreas.push(otherText);
            }
            
            // Collect keywords
            const keywords = Array.from(document.querySelectorAll('.tag'))
                .map(tag => tag.firstChild.textContent.trim());
            
            // Add arrays to form data
            subjectAreas.forEach(area => formData.append('subject_areas[]', area));
            keywords.forEach(keyword => formData.append('keywords[]', keyword));
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Show confirmation message
                    document.getElementById('section6').classList.add('d-none');
                    document.getElementById('confirmationMessage').style.display = 'block';
                    progressBar.style.width = '100%';

                    // Set timeout for redirection after 10 seconds
                    let secondsLeft = 10;
                    const countdownElement = document.getElementById('countdown');
                    
                    const countdownInterval = setInterval(function() {
                        secondsLeft--;
                        countdownElement.textContent = `You will be redirected to our homepage in ${secondsLeft} seconds...`;
                        
                        if (secondsLeft <= 0) {
                            clearInterval(countdownInterval);
                            window.location.href = 'https://www.ajsrp.com';
                        }
                    }, 1000);


                } else {
                    // Clear previous errors
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                    
                    // Handle validation errors
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const input = document.querySelector(`[name="${field}"], [name="${field}[]"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                errorDiv.textContent = messages[0];
                                input.parentNode.appendChild(errorDiv);
                            } else {
                                // Handle array fields
                                if (field.startsWith('subject_areas')) {
                                    document.getElementById('subjectError').textContent = messages[0];
                                    document.getElementById('subjectError').style.display = 'block';
                                } else if (field.startsWith('keywords')) {
                                    document.getElementById('keywordError').textContent = messages[0];
                                    document.getElementById('keywordError').style.display = 'block';
                                }
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Detect user's country based on IP
        function detectCountry() {
            // Use Laravel endpoint to avoid CORS issues
            fetch('/detect-country')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set the detected country for the first author
                        setFieldValue('country', data.country_code);
                    } else {
                        // Fallback to Saudi Arabia if detection fails
                        setFieldValue('country', 'SA');
                    }
                })
                .catch(error => {
                    console.error('Error detecting country:', error);
                    // Fallback to Saudi Arabia if request fails
                    setFieldValue('country', 'SA');
                });
        }

        function setFieldValue(fieldId, value) {
            const field = document.getElementById(fieldId);
            if (field && value !== undefined && value !== null) {
                field.value = value;
            }
        }
    });
</script>
@endpush