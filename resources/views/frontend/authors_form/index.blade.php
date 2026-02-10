<!DOCTYPE html>
<html lang="ar" dir="rtl" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج بيانات الباحثين والمشرفين - محسّن</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        :root {
            --primary-color: #1E3A8A;
            --primary-hover: #1E40AF;
            --success-color: #0D9488;
            --success-hover: #0F766E;
            --danger-color: #DC2626;
            --danger-hover: #B91C1C;
            --border-color: #D1D5DB;
            --bg-light: #F9FAFB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
        }

        body {
            font-family: 'Noto Sans Arabic', sans-serif;
            background-color: #F3F4F6;
            transition: background-color 0.3s ease;
        }

        /* High Contrast Mode */
        body.high-contrast {
            background-color: #000000;
            color: #FFFFFF;
        }

        body.high-contrast .form-section,
        body.high-contrast .header {
            background-color: #1A1A1A;
            border: 2px solid #FFFFFF;
        }

        body.high-contrast .form-control,
        body.high-contrast .form-select {
            background-color: #000000;
            color: #FFFFFF;
            border: 2px solid #FFFFFF;
        }

        body.high-contrast .form-label {
            color: #FFFFFF;
        }

        /* New High Contrast  */
         body.high-contrast {
            background-color: #000000;
            color: #FFFFFF;
        }

        body.high-contrast .form-section,
        body.high-contrast .header,
        body.high-contrast .feedback-section {
            background-color: #1A1A1A;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
        }

        body.high-contrast .form-control,
        body.high-contrast .form-select,
        body.high-contrast .field-row,
        body.high-contrast .sticky-submit,
        body.high-contrast .auto-save-indicator,
        body.high-contrast .feedback-textarea {
            background-color: #000000;
            color: #FFFFFF;
            /* border: 2px solid #FFFFFF; */
        }

        body.high-contrast .sticky-submit{
            background: linear-gradient(to top, #000000 80%, transparent);;
        }

        body.high-contrast .form-label,
        body.high-contrast .text-dark,
        body.high-contrast .header h1,
        body.high-contrast .header h2,
        body.high-contrast h3,
        body.high-contrast h5,
        body.high-contrast .form-check-label {
            color: #FFFFFF !important;
        }

        body.high-contrast .text-muted,
        body.high-contrast .form-text,
        body.high-contrast .progress-text,
        body.high-contrast .small {
            color: #CCCCCC !important;
        }

        body.high-contrast .btn-close {
            filter: invert(1);
        }

        body.high-contrast .alert {
            background-color: #000000 !important;
            color: #FFFFFF !important;
            border: 2px solid #FFFFFF;
        }

        body.high-contrast .file-upload {
            background-color: #000000;
            color: #FFFFFF;
            border: 2px dashed #FFFFFF;
        }

        body.high-contrast .file-upload:hover {
            background-color: #1A1A1A;
            border-color: #00FF00;
        }

        body.high-contrast .section-header {
            background: linear-gradient(135deg, #000000, #333333);
            color: #FFFFFF;
            border: 2px solid #FFFFFF;
        }

        body.high-contrast .optional-badge {
            background-color: #333333;
            color: #FFFFFF;
        }

        body.high-contrast .btn {
            border: 2px solid #FFFFFF;
        }

        body.high-contrast .btn-primary {
            background-color: #000000;
            color: #FFFFFF;
        }

        body.high-contrast .btn-success {
            background-color: #004400;
            color: #FFFFFF;
        }

        body.high-contrast .btn-danger {
            background-color: #440000;
            color: #FFFFFF;
        }

        body.high-contrast .btn-outline-primary,
        body.high-contrast .btn-outline-success,
        body.high-contrast .btn-outline-danger {
            background-color: #000000;
            color: #FFFFFF;
            border-color: #FFFFFF;
        }


        /* Section Styles */
        .section-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: #FFFFFF;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            background-color: #FFFFFF;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .form-section.collapsed {
            padding-bottom: 0;
            overflow: hidden;
        }

        .form-section.collapsed .section-content {
            display: none;
        }

        .collapse-btn {
            background: none;
            border: none;
            color: #FFFFFF;
            cursor: pointer;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .collapse-btn.collapsed {
            transform: rotate(180deg);
        }

        /* Input Styles */
        .form-control.valid, .form-select.valid {
            border-color: var(--success-color);
            background-color: rgba(13, 148, 136, 0.05);
        }

        .form-control.invalid, .form-select.invalid {
            border-color: var(--danger-color);
            background-color: rgba(220, 38, 38, 0.05);
        }

        /* Field Groups */
        .field-row {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            background-color: var(--bg-light);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .field-group {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        /* Labels */
        .form-label {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-label .required {
            color: var(--danger-color);
        }

        /* Error Messages */
        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
            animation: slideIn 0.3s ease;
        }

        .success-message {
            color: var(--success-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Progress Bar */
        .progress-container {
            background-color: #E5E7EB;
            border-radius: 9999px;
            height: 8px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--success-color), var(--success-hover));
            height: 100%;
            border-radius: 9999px;
            transition: width 0.5s ease;
            box-shadow: 0 0 10px rgba(13, 148, 136, 0.3);
        }

        .progress-text {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #FFFFFF;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .header img {
            max-width: 80px;
            margin-left: 1rem;
        }


        /* add for mobile view start */
        /* Desktop Controls */
        .desktop-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Mobile Controls */
        .mobile-controls {
            display: none;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background-color: #FFFFFF;
            z-index: 1050;
            overflow-x: hidden;
            transition: 0.3s;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        [dir="ltr"] .mobile-menu {
            right: auto;
            left: 0;
        }

        .mobile-menu.open {
            width: 280px;
        }

        .mobile-menu-content {
            padding: 20px;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .mobile-menu-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .mobile-menu-options .btn {
            justify-content: flex-start;
            text-align: right;
        }

        [dir="ltr"] .mobile-menu-options .btn {
            text-align: left;
        }

        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .mobile-menu-overlay.open {
            display: block;
        }

        /* add for mobile view end */


         /* Feedback section styles */
        .feedback-section {
            background-color: #FFFFFF;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .feedback-section h3 {
            color: #1E3A8A;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .feedback-textarea {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-family: inherit;
            resize: vertical;
        }

        .feedback-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        /* Email fields should always be LTR */
        input[type="email"] {
            direction: ltr !important;
            text-align: left !important;
        }

        .optional-badge {
            background-color: #E5E7EB;
            color: #6B7280;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            margin-right: 0.5rem;
        }




        /* Tooltips */
        .tooltip-inner {
            text-align: right;
            max-width: 200px;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background-color: var(--bg-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(30, 58, 138, 0.05);
        }

        .file-upload.dragover {
            border-color: var(--success-color);
            background-color: rgba(13, 148, 136, 0.1);
        }

        /* Auto-save Indicator */
        .auto-save-indicator {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background-color: #FFFFFF;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: none;
            align-items: center;
            gap: 0.5rem;
            z-index: 1000;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        .auto-save-indicator.show {
            display: flex;
        }

        /* Sticky Submit */
        .sticky-submit {
            position: sticky;
            bottom: 0;
            background: linear-gradient(to top, #F3F4F6 80%, transparent);
            padding: 1.5rem 0 1rem;
            z-index: 10;
            margin-top: 2rem;
        }



        /* Loading Spinner */
        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            /* .header {
                flex-direction: column;
                text-align: center;
            }

            .header-content {
                flex-direction: column;
            }

            .controls {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 1rem;
            }

            .field-row {
                flex-direction: column;
                gap: 1rem;
            }

            .field-group {
                min-width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 1rem;
                font-size: 1.1rem;
            }

            .sticky-submit {
                padding: 1rem;
                background: #F3F4F6;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            }

            .auto-save-indicator {
                right: 0.5rem;
                left: 0.5rem;
                width: auto;
            } */

                .header {
                flex-direction: column;
                text-align: center;
            }

            .header-content {
                flex-direction: column;
            }

            .desktop-controls {
                display: none;
            }

            .mobile-controls {
                display: flex;
                margin-top: 1rem;
            }

            .field-row {
                flex-direction: column;
                gap: 1rem;
            }

            .field-group {
                min-width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 1rem;
                font-size: 1.1rem;
            }

            .sticky-submit {
                padding: 1rem;
                background: #F3F4F6;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            }

            .auto-save-indicator {
                right: 0.5rem;
                left: 0.5rem;
                width: auto;
            }

            /* Show only the submit button on mobile */
            .sticky-submit .btn:not(.btn-primary) {
                display: none;
            }

            .feedback-section {
                padding: 1rem;
            }

            .feedback-textarea {
                min-height: 100px;
            }
        }

        /* Print Styles */
        @media print {
            .controls,
            .btn,
            .collapse-btn,
            .auto-save-indicator {
                display: none !important;
            }

            .form-section {
                page-break-inside: avoid;
            }
        }

        /* RTL/LTR specific styles */
        [dir="rtl"] .field-row {
            border-left: 4px solid var(--primary-color);
            border-right: none;
        }

        [dir="ltr"] .field-row {
            border-right: 4px solid var(--primary-color);
            border-left: none;
        }

        [dir="ltr"] .header img {
            margin-left: 0;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <h5 data-lang="ar">خيارات</h5>
                <h5 data-lang="en" style="display: none;">Options</h5>
                <button type="button" class="btn-close" onclick="closeMobileMenu()"></button>
            </div>
            <div class="mobile-menu-options">
                <button class="btn btn-outline-primary" onclick="toggleLanguage()">
                    <span data-lang="ar">تبديل اللغة</span>
                    <span data-lang="en" style="display: none;">Change Language</span>
                    <span class="ms-2" id="mobileLangToggleText">EN</span>
                </button>
                <button class="btn btn-outline-primary" onclick="toggleHighContrast()">
                    <span data-lang="ar">وضع التباين العالي</span>
                    <span data-lang="en" style="display: none;">High Contrast Mode</span>
                    <span class="ms-2">⚡</span>
                </button>
                <button class="btn btn-outline-success" onclick="exportPDF()">
                    <span data-lang="ar">تصدير PDF</span>
                    <span data-lang="en" style="display: none;">Export PDF</span>
                    <span class="ms-2">📄</span>
                </button>
                <button class="btn btn-outline-danger" onclick="clearForm()">
                    <span data-lang="ar">مسح النموذج</span>
                    <span data-lang="en" style="display: none;">Clear Form</span>
                    <span class="ms-2">🗑️</span>
                </button>
                <button class="btn btn-outline-success" onclick="loadDraft()">
                    <span data-lang="ar">استرجاع المسودة</span>
                    <span data-lang="en" style="display: none;">Load Draft</span>
                    <span class="ms-2">📂</span>
                </button>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-3 px-md-4 px-lg-5 max-w-6xl">
        <!-- Auto-save Indicator -->
        <div class="auto-save-indicator" id="autoSaveIndicator" role="status" aria-live="polite">
            <div class="spinner"></div>
            <span data-lang="ar">جاري الحفظ التلقائي...</span>
            <span data-lang="en" style="display: none;">Auto-saving...</span>
        </div>

        <!-- Header with Controls -->
        <div class="header">
            <div class="header-content">
                <img src="{{ getSettingImage('app_logo') }}" alt="شعار المؤسسة العربية للعلوم ونشر الأبحاث" loading="lazy">
                <div>
                    <h1 class="h2 fw-bold text-dark" data-lang="ar">المؤسسة العربية للعلوم ونشر الأبحاث</h1>
                    <h1 class="h2 fw-bold text-dark" data-lang="en" style="display: none;">The Arab Institute for Science and Research Publishing</h1>
                    {{-- <p class="text-muted" data-lang="ar" lang="en" >The Arab Institute for Science and Research Publishing</p>
                    <p class="text-muted" data-lang="en" style="display: none;" lang="en">The Arab Institute for Science and Research Publishing</p> --}}
                </div>
            </div>
            {{-- <div class="controls d-flex gap-2">
                <button class="btn btn-primary" onclick="toggleLanguage()" aria-label="تبديل اللغة">
                    <span id="langToggleText">EN</span>
                </button>
                <button class="btn btn-primary" onclick="toggleHighContrast()" aria-label="وضع التباين العالي">
                    <span>⚡</span>
                </button>
                <button class="btn btn-success" onclick="exportPDF()" aria-label="تصدير كـ PDF">
                    <span data-lang="ar">📄 PDF</span>
                    <span data-lang="en" style="display: none;">📄 PDF</span>
                </button>
            </div> --}}

            <!-- Desktop Controls (visible on larger screens) -->
            <div class="desktop-controls">
                <button class="btn btn-primary" onclick="toggleLanguage()" aria-label="تبديل اللغة">
                    <span id="langToggleText">EN</span>
                </button>
                <button class="btn btn-primary" onclick="toggleHighContrast()" aria-label="وضع التباين العالي">
                    <span>⚡</span>
                </button>
                <button class="btn btn-success" onclick="exportPDF()" aria-label="تصدير كـ PDF">
                    <span data-lang="ar">📄 PDF</span>
                    <span data-lang="en" style="display: none;">📄 PDF</span>
                </button>
            </div>

            <!-- Mobile Controls (visible on smaller screens) -->
            <div class="mobile-controls">
                <button class="btn btn-primary" onclick="toggleMobileMenu()">
                    <span>☰</span>
                </button>
            </div>

        </div>

        <!-- Instructions -->
        <div class="form-section">
            <p class="text-center text-muted" role="note" data-lang="ar">
                سعادة الباحث / الباحثون الكرام، نلفت عنايتكم لضرورة تعبئة جميع البيانات التالية باللغتين العربية والإنجليزية معًا؛ وذلك من أجل إتمام إجراءات النشر وكتابة خطاب النشر
            </p>
            <p class="text-center text-muted" role="note" data-lang="en" style="display: none;">
                Dear Researcher(s), Please note that it is necessary to fill in all the following data in both Arabic and English; in order to complete the publication procedures and write the publication letter
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="form-section">
            <h3 class="fw-semibold mb-2" data-lang="ar">التقدم في تعبئة النموذج</h3>
            <h3 class="fw-semibold mb-2" data-lang="en" style="display: none;">Form Completion Progress</h3>
            <div class="progress-container">
                <div class="progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
            <p class="progress-text" id="progressText" data-lang="ar">0% مكتمل</p>
            <p class="progress-text" id="progressTextEn" style="display: none;">0% Complete</p>
        </div>

        <!-- Research Details Section -->
        <div class="form-section">
            <div class="section-header">
                <span data-lang="ar">تفاصيل البحث</span>
                <span data-lang="en" style="display: none;">Research Details</span>
                <button class="collapse-btn" onclick="toggleSection(this)" aria-label="طي/توسيع القسم">▼</button>
            </div>
            <div class="section-content">
                <div class="field-row">
                    <div class="field-group">
                        <label class="form-label" for="arabic-title">
                            <span data-lang="ar">عنوان البحث باللغة العربية</span>
                            <span data-lang="en" style="display: none;">Research Title in Arabic</span>
                            <span class="required">*</span>
                        </label>
                        <div class="position-relative">
                            <input type="text"
                                   id="arabic-title"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   aria-describedby="arabic-title-error arabic-title-hint"
                                   lang="ar"
                                   placeholder="أدخل عنوان البحث بالعربية"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   data-lang-title-ar="أدخل العنوان الكامل للبحث باللغة العربية"
                                   data-lang-title-en="Enter the full research title in Arabic">
                        </div>
                        <p id="arabic-title-error" class="error-message" role="alert"></p>
                        <p id="arabic-title-success" class="success-message" role="status"></p>
                    </div>
                    <div class="field-group">
                        <label class="form-label" for="english-title">
                            <span data-lang="ar">Research Title in English</span>
                            <span data-lang="en" style="display: none;">Research Title in English</span>
                            <span class="required">*</span>
                        </label>
                        <div class="position-relative">
                            <input type="text"
                                   id="english-title"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   aria-describedby="english-title-error english-title-hint"
                                   lang="en"
                                   placeholder="Enter research title in English"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   data-lang-title-ar="أدخل العنوان الكامل للبحث باللغة الإنجليزية"
                                   data-lang-title-en="Enter the full research title in English">
                        </div>
                        <p id="english-title-error" class="error-message" role="alert"></p>
                        <p id="english-title-success" class="success-message" role="status"></p>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <label class="form-label" for="research-science">
                            <span data-lang="ar">علم البحث</span>
                            <span data-lang="en" style="display: none;">Research Field</span>
                            <span class="required">*</span>
                        </label>
                        <select id="research-science"
                                class="form-select validate-field"
                                required
                                aria-required="true"
                                aria-describedby="research-science-error">
                            <option value="" data-lang="ar">-- اختر علم البحث --</option>
                            <option value="" data-lang="en" style="display: none;">-- Select Research Field --</option>
                            <option value="education" data-lang="ar">التربية - Education</option>
                            <option value="education" data-lang="en" style="display: none;">التربية - Education</option>
                            <option value="economics" data-lang="ar">الاقتصاد - Economics</option>
                            <option value="economics" data-lang="en" style="display: none;">الاقتصاد - Economics</option>
                            <option value="medicine" data-lang="ar">الطب - Medicine</option>
                            <option value="medicine" data-lang="en" style="display: none;">الطب - Medicine</option>
                            <option value="psychology" data-lang="ar">علم النفس - Psychology</option>
                            <option value="psychology" data-lang="en" style="display: none;">علم النفس - Psychology</option>
                            <option value="sociology" data-lang="ar">علم الاجتماع - Sociology</option>
                            <option value="sociology" data-lang="en" style="display: none;">علم الاجتماع - Sociology</option>
                            <option value="engineering" data-lang="ar">الهندسة - Engineering</option>
                            <option value="engineering" data-lang="en" style="display: none;">الهندسة - Engineering</option>
                            <option value="computer_science" data-lang="ar">علوم الحاسوب - Computer Science</option>
                            <option value="computer_science" data-lang="en" style="display: none;">علوم الحاسوب - Computer Science</option>
                            <option value="physics" data-lang="ar">الفيزياء - Physics</option>
                            <option value="physics" data-lang="en" style="display: none;">الفيزياء - Physics</option>
                            <option value="chemistry" data-lang="ar">الكيمياء - Chemistry</option>
                            <option value="chemistry" data-lang="en" style="display: none;">الكيمياء - Chemistry</option>
                            <option value="biology" data-lang="ar">علم الأحياء - Biology</option>
                            <option value="biology" data-lang="en" style="display: none;">علم الأحياء - Biology</option>
                            <option value="mathematics" data-lang="ar">الرياضيات - Mathematics</option>
                            <option value="mathematics" data-lang="en" style="display: none;">الرياضيات - Mathematics</option>
                            <!-- new add option start -->
                             <option value="curriculum_instruction" data-lang="ar">المناهج وطرق التدريس - Curriculum & Instruction</option>
                            <option value="curriculum_instruction" data-lang="en" style="display: none;">المناهج وطرق التدريس - Curriculum & Instruction</option>

                            <option value="humanities" data-lang="ar">العلوم الإنسانية - Humanities</option>
                            <option value="humanities" data-lang="en" style="display: none;">العلوم الإنسانية - Humanities</option>

                            <option value="political_science" data-lang="ar">العلوم السياسية - Political Science</option>
                            <option value="political_science" data-lang="en" style="display: none;">العلوم السياسية - Political Science</option>

                            <option value="arabic_language_literature" data-lang="ar">اللغة العربية وآدابها - Arabic Language & Literature</option>
                            <option value="arabic_language_literature" data-lang="en" style="display: none;">اللغة العربية وآدابها - Arabic Language & Literature</option>

                            <option value="linguistics" data-lang="ar">اللسانيات - Linguistics</option>
                            <option value="linguistics" data-lang="en" style="display: none;">اللسانيات - Linguistics</option>

                            <option value="islamic_studies" data-lang="ar">الدراسات الإسلامية - Islamic Studies</option>
                            <option value="islamic_studies" data-lang="en" style="display: none;">الدراسات الإسلامية - Islamic Studies</option>

                            <option value="theology_sharia" data-lang="ar">اللاهوت والشريعة - Theology & Sharia</option>
                            <option value="theology_sharia" data-lang="en" style="display: none;">اللاهوت والشريعة - Theology & Sharia</option>

                            <option value="information_technology" data-lang="ar">تكنولوجيا المعلومات - Information Technology</option>
                            <option value="information_technology" data-lang="en" style="display: none;">تكنولوجيا المعلومات - Information Technology</option>

                            <option value="pharmacy" data-lang="ar">الصيدلة والعلوم الصيدلانية - Pharmacy & Pharmaceutical Sciences</option>
                            <option value="pharmacy" data-lang="en" style="display: none;">الصيدلة والعلوم الصيدلانية - Pharmacy & Pharmaceutical Sciences</option>

                            <option value="nursing_public_health" data-lang="ar">التمريض والصحة العامة - Nursing & Public Health</option>
                            <option value="nursing_public_health" data-lang="en" style="display: none;">التمريض والصحة العامة - Nursing & Public Health</option>

                            <option value="veterinary_medicine" data-lang="ar">الطب البيطري - Veterinary Medicine</option>
                            <option value="veterinary_medicine" data-lang="en" style="display: none;">الطب البيطري - Veterinary Medicine</option>

                            <option value="agricultural_sciences" data-lang="ar">العلوم الزراعية - Agricultural Sciences</option>
                            <option value="agricultural_sciences" data-lang="en" style="display: none;">العلوم الزراعية - Agricultural Sciences</option>

                            <option value="agribusiness" data-lang="ar">الأعمال الزراعية والاقتصاد الزراعي - Agribusiness & Agricultural Economics</option>
                            <option value="agribusiness" data-lang="en" style="display: none;">الأعمال الزراعية والاقتصاد الزراعي - Agribusiness & Agricultural Economics</option>

                            <option value="environmental_sciences" data-lang="ar">علوم البيئة - Environmental Sciences</option>
                            <option value="environmental_sciences" data-lang="en" style="display: none;">علوم البيئة - Environmental Sciences</option>

                            <option value="climate_change" data-lang="ar">تغير المناخ والاستدامة - Climate Change & Sustainability</option>
                            <option value="climate_change" data-lang="en" style="display: none;">تغير المناخ والاستدامة - Climate Change & Sustainability</option>

                            <option value="business_admin" data-lang="ar">إدارة الأعمال - Business Administration & Management</option>
                            <option value="business_admin" data-lang="en" style="display: none;">إدارة الأعمال - Business Administration & Management</option>

                            <option value="finance_accounting" data-lang="ar">المالية والمحاسبة - Finance & Accounting</option>
                            <option value="finance_accounting" data-lang="en" style="display: none;">المالية والمحاسبة - Finance & Accounting</option>

                            <option value="law" data-lang="ar">القانون والدراسات القانونية - Law & Legal Studies</option>
                            <option value="law" data-lang="en" style="display: none;">القانون والدراسات القانونية - Law & Legal Studies</option>

                            <option value="public_admin" data-lang="ar">الإدارة العامة والسياسات - Public Administration & Policy</option>
                            <option value="public_admin" data-lang="en" style="display: none;">الإدارة العامة والسياسات - Public Administration & Policy</option>

                            <option value="risk_management" data-lang="ar">إدارة المخاطر - Risk Management</option>
                            <option value="risk_management" data-lang="en" style="display: none;">إدارة المخاطر - Risk Management</option>

                            <option value="crisis_management" data-lang="ar">إدارة الأزمات - Crisis Management</option>
                            <option value="crisis_management" data-lang="en" style="display: none;">إدارة الأزمات - Crisis Management</option>

                            <option value="disaster_studies" data-lang="ar">دراسات الكوارث وإدارة الطوارئ - Disaster Studies & Emergency Management</option>
                            <option value="disaster_studies" data-lang="en" style="display: none;">دراسات الكوارث وإدارة الطوارئ - Disaster Studies & Emergency Management</option>

                            <option value="general_science" data-lang="ar">العلوم العامة والبحوث متعددة التخصصات - General Science & Multidisciplinary Research</option>
                            <option value="general_science" data-lang="en" style="display: none;">العلوم العامة والبحوث متعددة التخصصات - General Science & Multidisciplinary Research</option>

                            <!-- new add option end -->
                            <option value="other" data-lang="ar">أخرى - Other</option>
                            <option value="other" data-lang="en" style="display: none;">أخرى - Other</option>
                        </select>
                        <p id="research-science-error" class="error-message" role="alert"></p>
                    </div>

                    <div class="field-group" id="other-science-field" style="display: none;">
                        <label class="form-label" for="other-science">
                            <span data-lang="ar">حدد علم البحث</span>
                            <span data-lang="en" style="display: none;">Specify Research Field</span>
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="other-science"
                               class="form-control"
                               aria-describedby="other-science-error"
                               data-lang-placeholder-ar="مثال: علم الفلك - Astronomy"
                               data-lang-placeholder-en="e.g., Astronomy - علم الفلك"
                               placeholder="مثال: علم الفلك - Astronomy">
                        <p id="other-science-error" class="error-message" role="alert"></p>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <label class="form-label" for="journal-selection">
                            <span data-lang="ar">المجلة المختارة للنشر</span>
                            <span data-lang="en" style="display: none;">Selected Journal for Publication</span>
                            <span class="required">*</span>
                        </label>
                        {{-- <input type="text"
                               id="journal-search"
                               class="form-control"
                               data-lang-placeholder-ar="ابحث عن المجلة..."
                               data-lang-placeholder-en="Search for journal..."
                               placeholder="ابحث عن المجلة..."
                               aria-label="البحث عن مجلة">
                        <select id="journal-selection"
                                class="form-select validate-field"
                                required
                                aria-required="true"
                                aria-describedby="journal-selection-error"
                                style="display: none;">
                            <option value="" data-lang="ar">-- اختر المجلة --</option>
                            <option value="" data-lang="en" style="display: none;">-- Select Journal --</option>
                        </select>
                        <div id="journal-suggestions" class="suggestions-list"></div>
                        <p id="journal-selection-error" class="error-message" role="alert"></p> --}}

                        <select id="journal-selection"
                                class="form-select validate-field"
                                required
                                aria-required="true"
                                aria-describedby="journal-selection-error">
                            <option value="" data-lang="ar">-- اختر المجلة --</option>
                            <option value="" data-lang="en" style="display: none;">-- Select Journal --</option>
                            <!-- Journals will be populated based on research field selection -->
                        </select>
                        <p id="journal-selection-error" class="error-message" role="alert"></p>

                    </div>

                    <div class="field-group" style="display: none;">
                        <label class="form-label" for="keywords">
                            <span data-lang="ar">الكلمات المفتاحية - Keywords</span>
                            <span data-lang="en" style="display: none;">Keywords - الكلمات المفتاحية</span>
                        </label>
                        <input type="text"
                               id="keywords"
                               class="form-control"
                               data-lang-placeholder-ar="كلمة1، كلمة2، word1, word2"
                               data-lang-placeholder-en="word1, word2, كلمة1، كلمة2"
                               placeholder="كلمة1، كلمة2، word1, word2"
                               aria-describedby="keywords-hint">
                        <small id="keywords-hint" class="form-text text-muted" data-lang="ar">افصل بين الكلمات بفاصلة</small>
                        <small id="keywords-hint-en" class="form-text text-muted" data-lang="en" style="display: none;">Separate words with a comma</small>
                    </div>
                </div>

                <!-- Paper ID -->
                <div class="field-row">
                    <div class="field-group">
                        <label class="form-label" for="paper-id-ar">
                            <span data-lang="ar">رقم البحث/الورقة (Paper ID)</span>
                            <span data-lang="en" style="display: none;">Paper ID (Arabic)</span>
                        </label>
                        <input type="text"
                            id="paper-id-ar"
                            class="form-control"
                            data-lang-placeholder-ar="أدخل رقم البحث بالعربية"
                            data-lang-placeholder-en="Enter paper ID in Arabic"
                            placeholder="أدخل رقم البحث بالعربية"
                            lang="ar"
                            oninput="syncPaperID('ar', this.value)">
                    </div>
                    <div class="field-group">
                        <label class="form-label" for="paper-id-en">
                            <span data-lang="ar">Paper ID</span>
                            <span data-lang="en" style="display: none;">Paper ID (English)</span>
                        </label>
                        <input type="text"
                            id="paper-id-en"
                            class="form-control"
                            data-lang-placeholder-ar="Enter paper ID in English"
                            data-lang-placeholder-en="Enter paper ID in English"
                            placeholder="Enter paper ID in English"
                            lang="en"
                            oninput="syncPaperID('en', this.value)">
                    </div>
                </div>

                <!-- Thesis -->
                <div class="field-row">
                    <div class="field-group">
                        <label class="form-label">
                            <span data-lang="ar">هل هذا البحث مستلّ من رسالة الماجستير/الدكتوراه الخاصة بك؟</span>
                            <span data-lang="en" style="display: none;">Is this research extracted from your Master's/Ph.D. thesis?</span>
                        </label>
                        <div class="mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="thesis-extraction" id="thesis-yes" value="yes">
                                <label class="form-check-label" for="thesis-yes">
                                    <span data-lang="ar">نعم</span>
                                    <span data-lang="en" style="display: none;">Yes</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="thesis-extraction" id="thesis-no" value="no" checked>
                                <label class="form-check-label" for="thesis-no">
                                    <span data-lang="ar">لا</span>
                                    <span data-lang="en" style="display: none;">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="field-row" style="display: none;">
                    <div class="field-group">
                        <label class="form-label">
                            <span data-lang="ar">رفع المخطوطة (اختياري)</span>
                            <span data-lang="en" style="display: none;">Upload Manuscript (Optional)</span>
                        </label>
                        <div class="file-upload"
                             id="fileUploadArea"
                             ondrop="handleDrop(event)"
                             ondragover="handleDragOver(event)"
                             ondragleave="handleDragLeave(event)"
                             onclick="document.getElementById('fileInput').click()">
                            <input type="file"
                                   id="fileInput"
                                   accept=".pdf,.doc,.docx"
                                   style="display: none;"
                                   onchange="handleFileSelect(event)"
                                   aria-label="رفع الملف">
                            <p data-lang="ar">📁 اسحب الملف هنا أو انقر للاختيار</p>
                            <p data-lang="en" style="display: none;">📁 Drag file here or click to choose</p>
                            <p class="text-muted small" data-lang="ar">PDF, DOC, DOCX (حد أقصى 10 ميجابايت)</p>
                            <p class="text-muted small" data-lang="en" style="display: none;">PDF, DOC, DOCX (Max 10MB)</p>
                            <div id="fileList" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authors Container -->
        <div id="authorsContainer">
            <!-- Authors will be added dynamically -->
        </div>

        <!-- Add Author Button -->
        <div class="form-section text-center">
            <button class="btn btn-success" onclick="addAuthor()" id="addAuthorBtn">
                <i class="bi bi-plus-circle"></i>
                <span data-lang="ar"> إضافة باحث آخر</span>
                <span data-lang="en" style="display: none;"> Add Another Researcher</span>
            </button>
            <p class="text-muted small mt-2" data-lang="ar">يمكنك إضافة حتى 12 باحث</p>
            <p class="text-muted small mt-2" data-lang="en" style="display: none;">You can add up to 12 researchers</p>
        </div>

        <!-- Feedback Section -->
        <div class="feedback-section">
            <h3>
                <span class="optional-badge" data-lang="ar">اختياري</span>
                <span data-lang="ar">ملاحظات أو اقتراحات</span>
                <span class="optional-badge" data-lang="en" style="display: none;">Optional</span>
                <span data-lang="en" style="display: none;">Feedback or Suggestions</span>
            </h3>
            <textarea
                id="feedback"
                class="feedback-textarea"
                placeholder="إذا كان لديك أي ملاحظات أو اقتراحات، يرجى كتابتها هنا..."
                data-lang-placeholder-ar="إذا كان لديك أي ملاحظات أو اقتراحات، يرجى كتابتها هنا..."
                data-lang-placeholder-en="If you have any feedback or suggestions, please write them here..."></textarea>
        </div>

        <!-- Submit Section -->
        <div class="sticky-submit">
            <div class=" gap-3 justify-content-center flex-wrap text-center">
                <button class="btn btn-primary btn-lg" onclick="validateAndSubmit()" id="submitButton" style="font-weight: 600;font-size: 1.3rem;">
                    <i class="bi bi-send"></i>
                    <span data-lang="ar"> إرسال </span>
                    <span data-lang="en" style="display: none;"> Send </span>
                </button>
                <button class="btn btn-danger btn-sm" onclick="clearForm()">
                    <i class="bi bi-trash"></i>
                    <span data-lang="ar"> مسح النموذج</span>
                    <span data-lang="en" style="display: none;"> Clear Form</span>
                </button>
                <button class="btn btn-success btn-sm" onclick="loadDraft()">
                    <i class="bi bi-folder2-open"></i>
                    <span data-lang="ar"> استرجاع المسودة</span>
                    <span data-lang="en" style="display: none;"> Load Draft</span>
                </button>
            </div>
        </div>

        <!-- Feedback Button -->
        {{-- <button class="fixed-bottom ms-4 mb-4 btn btn-primary rounded-circle p-3" style="width: 60px; height: 60px; left: unset;" onclick="showFeedback()" aria-label="إرسال ملاحظات">
            <i class="bi bi-chat-dots"></i>
        </button> --}}
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" data-lang="ar">شاركنا رأيك</h5>
                    <h5 class="modal-title" data-lang="en" style="display: none;">Share Your Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="4" data-lang-placeholder-ar="اكتب ملاحظاتك هنا..." data-lang-placeholder-en="Write your feedback here..." placeholder="اكتب ملاحظاتك هنا..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitFeedback()">
                        <span data-lang="ar">إرسال</span>
                        <span data-lang="en" style="display: none;">Submit</span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span data-lang="ar">إلغاء</span>
                        <span data-lang="en" style="display: none;">Cancel</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- html2pdf library for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        // Global Variables
        let authorCount = 0;
        const maxAuthors = 12;
        let autoSaveTimer;
        let formData = {};
        let currentLang = localStorage.getItem('languagePreference') || 'ar';

        // Title options data
        const titleOptions = [
            { value: "mr", ar: "السيد", en: "Mr." },
            { value: "ms", ar: "الآنسة", en: "Ms." },
            { value: "mrs", ar: "السيدة", en: "Mrs." },
            { value: "dr", ar: "الدكتور", en: "Dr." },
            { value: "prof", ar: "الأستاذ الدكتور", en: "Prof." },
            { value: "eng", ar: "المهندس", en: "Eng." },
            { value: "other", ar: "أخرى", en: "Other" }
        ];

        // Degree options data
        const degreeOptions = [
            { value: "diploma", ar: "دبلوم", en: "Diploma" },
            { value: "bachelor", ar: "بكالوريوس", en: "Bachelor" },
            { value: "master", ar: "ماجستير", en: "Master" },
            { value: "phd", ar: "دكتوراه", en: "PhD / Doctorate" },
            { value: "md", ar: "دكتور في الطب", en: "MD (Doctor of Medicine)" },
            { value: "pharmd", ar: "دكتور في الصيدلة", en: "PharmD (Doctor of Pharmacy)" },
            { value: "dds", ar: "دكتور في جراحة الأسنان", en: "DDS / DMD (Doctor of Dental Surgery / Medicine)" },
            { value: "jd", ar: "دكتور في القانون", en: "JD / LLB (Law Degree)" },
            { value: "dsc", ar: "دكتور في العلوم", en: "DSc / ScD (Doctor of Science)" },
            { value: "dba", ar: "دكتور في إدارة الأعمال", en: "DBA (Doctor of Business Administration)" },
            { value: "postdoc", ar: "باحث ما بعد الدكتوراه", en: "Postdoc / Research Fellow" },
            { value: "professor", ar: "أستاذ", en: "Professor" },
            { value: "other", ar: "أخرى", en: "Other" }
        ];

        // Journal Data
        const journals = [
            // { id: "JEPS", name: "مجلة العلوم التربوية والنفسية (JEPS)", nameEn: "Journal of Educational and Psychological Sciences", sciences: ["education", "psychology"] },
            // { id: "JEALS", name: "مجلة العلوم الإقتصادية والإدارية والقانونية (JEALS)", nameEn: "Journal of Economic, Administrative and Legal Sciences", sciences: ["economics", "business", "law"] },
            // { id: "JHSS", name: "مجلة العلوم الإنسانية والإجتماعية (JHSS)", nameEn: "Journal of Human and Social Sciences", sciences: ["sociology", "history", "philosophy"] },
            // { id: "JNSLAS", name: "مجلة العلوم الطبيعية والحياتية والتطبيقية (JNSLAS)", nameEn: "Journal of Natural Sciences, Life and Applied Sciences", sciences: ["physics", "chemistry", "biology", "mathematics"] },
            // { id: "JMPS", name: "مجلة العلوم الطبية والصيدلانية (JMPS)", nameEn: "Journal of Medical and Pharmaceutical Sciences", sciences: ["medicine", "pharmacy", "nursing"] },
            // { id: "JESIT", name: "مجلة العلوم الهندسية وتكنولوجيا المعلومات (JESIT)", nameEn: "Journal of Engineering Sciences and Information Technology", sciences: ["engineering", "computer_science", "architecture"] },
            // { id: "JAEVS", name: "مجلة العلوم الزراعية والبيئية والبيطرية (JAEVS)", nameEn: "Journal of Agricultural, Environmental and Veterinary Sciences", sciences: ["agriculture", "environmental_science"] },
            // { id: "JRCM", name: "مجلة إدارة المخاطر والأزمات (JRCM)", nameEn: "Journal of Risk and Crisis Management", sciences: ["politics", "sociology"] },
            // { id: "JIS", name: "مجلة العلوم الإسلامية (JIS)", nameEn: "Journal of Islamic Sciences", sciences: ["others"] },
            // { id: "AJSRP", name: "المجلة العربية للعلوم و نشر الأبحاث (AJSRP)", nameEn: "Arab Journal of Sciences & Research Publishing", sciences: ["others"] },
            // { id: "JALSL", name: "مجلة علوم اللغة العربية وآدابها (JALSL)", nameEn: "Journal of Arabic Language Sciences and Literature", sciences: ["others"] },
            // { id: "JCTM", name: "مجلة المناهج وطرق التدريس (JCTM)", nameEn: "Journal of Curriculum and Teaching Methodology", sciences: ["education"] }

            { id: "JEPS", name: "مجلة العلوم التربوية والنفسية (JEPS)", nameEn: "Journal of Educational and Psychological Sciences", sciences: ["education", "psychology"] },
            { id: "JCTM", name: "مجلة المناهج وطرق التدريس (JCTM)", nameEn: "Journal of Curriculum and Teaching Methodology", sciences: ["curriculum_instruction", "education"] },
            { id: "JHSS", name: "مجلة العلوم الإنسانية والإجتماعية (JHSS)", nameEn: "Journal of Humanities and Social Sciences", sciences: ["humanities", "sociology", "political_science"] },
            { id: "JALSL", name: "مجلة علوم اللغة العربية وآدابها (JALSL)", nameEn: "Journal of Arabic Language Sciences and Literature", sciences: ["arabic_language_literature", "linguistics"] },
            { id: "JIS", name: "مجلة العلوم الإسلامية (JIS)", nameEn: "Journal of Islamic Sciences", sciences: ["islamic_studies", "theology_sharia"] },
            { id: "JNSLAS", name: "مجلة العلوم الطبيعية والحياتية والتطبيقية (JNSLAS)", nameEn: "Journal of Natural Sciences, Life and Applied Sciences", sciences: ["biology", "chemistry", "physics", "mathematics", "environmental_sciences", "climate_change"] },
            { id: "JESIT", name: "مجلة العلوم الهندسية وتكنولوجيا المعلومات (JESIT)", nameEn: "Journal of Engineering Sciences and Information Technology", sciences: ["engineering", "information_technology", "computer_science"] },
            { id: "JMPS", name: "مجلة العلوم الطبية والصيدلانية (JMPS)", nameEn: "Journal of Medical and Pharmaceutical Sciences", sciences: ["medicine", "pharmacy", "nursing_public_health"] },
            { id: "JAEVS", name: "مجلة العلوم الزراعية والبيئية والبيطرية (JAEVS)", nameEn: "Journal of Agricultural, Environmental and Veterinary Sciences", sciences: ["veterinary_medicine", "agricultural_sciences", "agribusiness", "environmental_sciences", "climate_change"] },
            { id: "JEALS", name: "مجلة العلوم الإقتصادية والإدارية والقانونية (JEALS)", nameEn: "Journal of Economic, Administrative and Legal Sciences", sciences: ["economics", "business_admin", "finance_accounting", "law", "public_admin", "agribusiness"] },
            { id: "JRCM", name: "مجلة إدارة المخاطر والأزمات (JRCM)", nameEn: "Journal of Risk and Crisis Management", sciences: ["risk_management", "crisis_management", "disaster_studies"] },
            { id: "AJSRP", name: "المجلة العربية للعلوم و نشر الأبحاث (AJSRP)", nameEn: "Arab Journal of Sciences & Research Publishing", sciences: ["general_science"] }
        ];

        // Country Data (common countries)
        const countries =  @json(config('countries_data_ar_en'));


        // Initialize Form
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialize modal
            var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));

            addAuthor(); // Add first author section
            initializeAutoSave();
            loadDraft();
            setupRealTimeValidation();
            // setupJournalSearch();
            setupJournalSelection();
            detectCountry();
            trackFormAnalytics();

            // Load language preference
            if (localStorage.getItem('languagePreference') === 'en') {
                toggleLanguage(true);
            }
        });

        // Mobile Menu Functions
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');

            mobileMenu.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function closeMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');

            mobileMenu.classList.remove('open');
            overlay.classList.remove('open');
        }

        // Add Author Function
        function addAuthor() {
            if (authorCount >= maxAuthors) {
                if (currentLang === 'ar') {
                    alert('لقد وصلت إلى الحد الأقصى من الباحثين (12)');
                } else {
                    alert('You have reached the maximum number of researchers (12)');
                }
                return;
            }

            authorCount++;
            const authorSection = document.createElement('div');
            authorSection.className = 'form-section';
            authorSection.id = `author-${authorCount}`;

            authorSection.innerHTML = `
                <div class="section-header">
                    <span data-lang="ar">بيانات الباحث ${getArabicOrdinal(authorCount)}</span>
                    <span data-lang="en" style="display: none;">Researcher ${authorCount} Details</span>
                    <div class="d-flex gap-2">
                        ${authorCount > 1 ? `<button class="btn btn-danger btn-sm" onclick="removeAuthor(${authorCount})">
                            <span data-lang="ar">حذف</span>
                            <span data-lang="en" style="display: none;">Delete</span>
                        </button>` : ''}
                        <button class="collapse-btn" onclick="toggleSection(this)" aria-label="طي/توسيع القسم">▼</button>
                    </div>
                </div>
                <div class="section-content">
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-title-ar">
                                <span data-lang="ar">اللقب (عربي)</span>
                                <span data-lang="en" style="display: none;">Title (Arabic)</span>
                            </label>
                            <select id="author-${authorCount}-title-ar"
                                    class="form-select"
                                    onchange="handleTitleChange(this, ${authorCount}, 'ar')">
                                <option value="" data-lang="ar">-- اختر اللقب --</option>
                                <option value="" data-lang="en" style="display: none;">-- Select Title --</option>
                                <!-- Title options will be populated dynamically -->
                            </select>
                            <div id="author-${authorCount}-other-title-ar" style="display: none; margin-top: 0.5rem;">
                                <input type="text"
                                       class="form-control"
                                       placeholder="حدد اللقب"
                                       data-lang-placeholder-ar="حدد اللقب"
                                       data-lang-placeholder-en="Specify title">
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-title-en">
                                <span data-lang="ar">Title (English)</span>
                                <span data-lang="en" style="display: none;">Title (English)</span>
                            </label>
                            <select id="author-${authorCount}-title-en"
                                    class="form-select"
                                    onchange="handleTitleChange(this, ${authorCount}, 'en')">
                                <option value="" data-lang="ar">-- Select Title --</option>
                                <option value="" data-lang="en" style="display: none;">-- Select Title --</option>
                                <!-- Title options will be populated dynamically -->
                            </select>
                            <div id="author-${authorCount}-other-title-en" style="display: none; margin-top: 0.5rem;">
                                <input type="text"
                                       class="form-control"
                                       placeholder="Specify title"
                                       data-lang-placeholder-ar="حدد اللقب"
                                       data-lang-placeholder-en="Specify title">
                            </div>
                        </div>
                    </div>
                    <!-- First Name: Arabic and English side-by-side -->
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-first-name-ar">
                                <span data-lang="ar">الاسم الأول (عربي)</span>
                                <span data-lang="en" style="display: none;">First Name (Arabic)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-first-name-ar"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="الاسم الأول"
                                   data-lang-placeholder-en="First Name"
                                   placeholder="الاسم الأول"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-first-name-en">
                                <span data-lang="ar">First Name (English)</span>
                                <span data-lang="en" style="display: none;">First Name (English)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-first-name-en"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="First Name"
                                   data-lang-placeholder-en="First Name"
                                   placeholder="First Name"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                    </div>

                    <!-- Middle Name: Arabic and English side-by-side -->
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-middle-name-ar">
                                <span data-lang="ar">الاسم الأوسط (عربي)</span>
                                <span data-lang="en" style="display: none;">Middle Name (Arabic)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-middle-name-ar"
                                   class="form-control"
                                   data-lang-placeholder-ar="الاسم الأوسط"
                                   data-lang-placeholder-en="Middle Name"
                                   placeholder="الاسم الأوسط"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-middle-name-en">
                                <span data-lang="ar">Middle Name (English)</span>
                                <span data-lang="en" style="display: none;">Middle Name (English)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-middle-name-en"
                                   class="form-control"
                                   data-lang-placeholder-ar="Middle Name"
                                   data-lang-placeholder-en="Middle Name"
                                   placeholder="Middle Name"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                    </div>

                    <!-- Last Name: Arabic and English side-by-side -->
                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-last-name-ar">
                                <span data-lang="ar">الاسم الأخير (عربي)</span>
                                <span data-lang="en" style="display: none;">Last Name (Arabic)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-last-name-ar"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="الاسم الأخير"
                                   data-lang-placeholder-en="Last Name"
                                   placeholder="الاسم الأخير"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-last-name-en">
                                <span data-lang="ar">Last Name (English)</span>
                                <span data-lang="en" style="display: none;">Last Name (English)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-last-name-en"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="Last Name"
                                   data-lang-placeholder-en="Last Name"
                                   placeholder="Last Name"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-email">
                                <span data-lang="ar">البريد الإلكتروني</span>
                                <span data-lang="en" style="display: none;">Email Address</span>
                                <span class="required">*</span>
                            </label>
                            <input type="email"
                                   id="author-${authorCount}-email"
                                   class="form-control validate-field"
                                   dir="ltr"
                                   style="text-align: left;"
                                   required
                                   aria-required="true"
                                   data-validation="email"
                                   placeholder="example@domain.com">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-phone">
                                <span data-lang="ar">رقم الهاتف</span>
                                <span data-lang="en" style="display: none;">Phone Number</span>
                                <span class="required">*</span>
                            </label>
                            <input type="tel"
                                   id="author-${authorCount}-phone"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="phone"
                                   data-lang-placeholder-ar="+966 5X XXX XXXX"
                                   data-lang-placeholder-en="+966 5X XXX XXXX"
                                   placeholder="+966 5X XXX XXXX">
                            <p class="error-message" role="alert"></p>
                            <p class="success-message" role="status"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-degree-ar">
                                <span data-lang="ar">الدرجة العلمية (عربي)</span>
                                <span data-lang="en" style="display: none;">Academic Degree (Arabic)</span>
                                <span class="required">*</span>
                            </label>
                            <select id="author-${authorCount}-degree-ar"
                                    class="form-select validate-field"
                                    required
                                    aria-required="true"
                                    data-validation="required"
                                    onchange="handleDegreeChange(this, ${authorCount}, 'ar')">
                                <option value="" data-lang="ar">-- اختر الدرجة العلمية --</option>
                                <option value="" data-lang="en" style="display: none;">-- Select Academic Degree --</option>
                                <!-- Degree options will be populated dynamically -->
                            </select>
                            <div id="author-${authorCount}-other-degree-ar" style="display: none; margin-top: 0.5rem;">
                                <input type="text"
                                       class="form-control"
                                       placeholder="حدد الدرجة العلمية"
                                       data-lang-placeholder-ar="حدد الدرجة العلمية"
                                       data-lang-placeholder-en="Specify degree">
                            </div>
                            <p class="error-message" role="alert"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-degree-en">
                                <span data-lang="ar">Degree (English)</span>
                                <span data-lang="en" style="display: none;">Academic Degree (English)</span>
                                <span class="required">*</span>
                            </label>
                            <select id="author-${authorCount}-degree-en"
                                    class="form-select validate-field"
                                    required
                                    aria-required="true"
                                    data-validation="required"
                                    onchange="handleDegreeChange(this, ${authorCount}, 'en')">
                                <option value="" data-lang="ar">-- Select Academic Degree --</option>
                                <option value="" data-lang="en" style="display: none;">-- Select Academic Degree --</option>
                                <!-- Degree options will be populated dynamically -->
                            </select>
                            <div id="author-${authorCount}-other-degree-en" style="display: none; margin-top: 0.5rem;">
                                <input type="text"
                                       class="form-control"
                                       placeholder="Specify degree"
                                       data-lang-placeholder-ar="حدد الدرجة العلمية"
                                       data-lang-placeholder-en="Specify degree">
                            </div>
                            <p class="error-message" role="alert"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-department-ar">
                                <span data-lang="ar">القسم (عربي)</span>
                                <span data-lang="en" style="display: none;">Department (Arabic)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-department-ar"
                                   class="form-control"
                                   required
                                   aria-required="true"
                                   data-lang-placeholder-ar="مثال: قسم علوم الحاسوب"
                                   data-lang-placeholder-en="e.g., Computer Science Department"
                                   placeholder="مثال: قسم علوم الحاسوب"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-department-en">
                                <span data-lang="ar">Department (English)</span>
                                <span data-lang="en" style="display: none;">Department (English)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-department-en"
                                   class="form-control"
                                   required
                                   aria-required="true"
                                   data-lang-placeholder-ar="e.g., Computer Science Department"
                                   data-lang-placeholder-en="e.g., Computer Science Department"
                                   placeholder="e.g., Computer Science Department"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-faculty-ar">
                                <span data-lang="ar">الكلية (عربي)</span>
                                <span data-lang="en" style="display: none;">Faculty (Arabic)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-faculty-ar"
                                   class="form-control"
                                   required
                                   aria-required="true"
                                   data-lang-placeholder-ar="مثال: كلية الهندسة"
                                   data-lang-placeholder-en="e.g., Faculty of Engineering"
                                   placeholder="مثال: كلية الهندسة"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-faculty-en">
                                <span data-lang="ar">Faculty (English)</span>
                                <span data-lang="en" style="display: none;">Faculty (English)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-faculty-en"
                                   class="form-control"
                                   required
                                   aria-required="true"
                                   data-lang-placeholder-ar="e.g., Faculty of Engineering"
                                   data-lang-placeholder-en="e.g., Faculty of Engineering"
                                   placeholder="e.g., Faculty of Engineering"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-university-ar">
                                <span data-lang="ar">الجامعة، معهد بحثي، مستشفى، منظمة، مؤسسة، أو شركة (عربي)</span>
                                <span data-lang="en" style="display: none;">University, Research Institute, Hospital, Organization, Institution, or Company (Arabic)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-university-ar"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="مثال: جامعة الملك سعود"
                                   data-lang-placeholder-en="e.g., King Saud University"
                                   placeholder="مثال: جامعة الملك سعود"
                                   lang="ar">
                            <p class="error-message" role="alert"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-university-en">
                                <span data-lang="ar">University, Research Institute, Hospital, Organization, Institution, or Company (English)</span>
                                <span data-lang="en" style="display: none;">University, Research Institute, Hospital, Organization, Institution, or Company (English)</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-university-en"
                                   class="form-control validate-field"
                                   required
                                   aria-required="true"
                                   data-validation="required"
                                   data-lang-placeholder-ar="e.g., King Saud University"
                                   data-lang-placeholder-en="e.g., King Saud University"
                                   placeholder="e.g., King Saud University"
                                   lang="en">
                            <p class="error-message" role="alert"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-country">
                                <span data-lang="ar">البلد</span>
                                <span data-lang="en" style="display: none;">Country</span>
                                <span class="required">*</span>
                            </label>
                            <select id="author-${authorCount}-country"
                                    class="form-select validate-field"
                                    required
                                    aria-required="true"
                                    data-validation="required">
                                <option value="" data-lang="ar">-- اختر البلد --</option>
                                <option value="" data-lang="en" style="display: none;">-- Select Country --</option>
                                <!-- Countries will be populated dynamically -->
                            </select>
                            <p class="error-message" role="alert"></p>
                        </div>
                        <div class="field-group">
                            <label class="form-label" for="author-${authorCount}-orcid">
                                <span data-lang="ar">ORCID iD (اختياري)</span>
                                <span data-lang="en" style="display: none;">ORCID iD (Optional)</span>
                            </label>
                            <input type="text"
                                   id="author-${authorCount}-orcid"
                                   class="form-control"
                                   placeholder="0000-0000-0000-0000"
                                   pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{3}[0-9X]">
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <div class="form-check mt-4 pt-2">
                                <input class=""
                                       type="checkbox"
                                       name="corresponding-author"
                                       id="corresponding-author-${authorCount}"
                                       value="${authorCount}"
                                       onchange="handleCorrespondingAuthor(this)">
                                <label class="form-check-label" for="corresponding-author-${authorCount}">
                                    <span data-lang="ar">الباحث المراسل - Corresponding Author</span>
                                    <span data-lang="en" style="display: none;">Corresponding Author - الباحث المراسل</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('authorsContainer').appendChild(authorSection);

            // Populate country dropdown for this author
            populateCountryDropdown(`author-${authorCount}-country`);

            // Populate title dropdowns for this author
            populateTitleDropdowns(authorCount);

            // Populate degree dropdowns for this author
            populateDegreeDropdowns(authorCount);

            // Update language for the new author section
            updateLanguageForElement(authorSection);

            // Re-initialize validation for new fields
            setupRealTimeValidation();
            updateProgress();

            // Update button state
            if (authorCount >= maxAuthors) {
                document.getElementById('addAuthorBtn').disabled = true;
                if (currentLang === 'ar') {
                    document.getElementById('addAuthorBtn').querySelector('[data-lang="ar"]').textContent = 'وصلت للحد الأقصى';
                } else {
                    document.getElementById('addAuthorBtn').querySelector('[data-lang="en"]').textContent = 'Maximum reached';
                }
            }
        }

        // Populate title dropdowns
        function populateTitleDropdowns(authorId) {
            const arSelect = document.getElementById(`author-${authorId}-title-ar`);
            const enSelect = document.getElementById(`author-${authorId}-title-en`);

            // Clear previous options except the first one
            while (arSelect.options.length > 2) {
                arSelect.remove(2);
            }
            while (enSelect.options.length > 2) {
                enSelect.remove(2);
            }

            // Add title options to the dropdowns
            titleOptions.forEach(title => {
                // Arabic dropdown
                const arOption = document.createElement('option');
                arOption.value = title.value;
                arOption.textContent = title.ar;
                arOption.setAttribute('data-lang-ar', title.ar);
                arOption.setAttribute('data-lang-en', title.en);
                arSelect.appendChild(arOption);

                // English dropdown
                const enOption = document.createElement('option');
                enOption.value = title.value;
                enOption.textContent = title.en;
                enOption.setAttribute('data-lang-ar', title.ar);
                enOption.setAttribute('data-lang-en', title.en);
                enSelect.appendChild(enOption);
            });
        }

        // Handle title selection change
        function handleTitleChange(selectElement, authorId, lang) {
            const otherField = document.getElementById(`author-${authorId}-other-title-${lang}`);

            if (selectElement.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }

            // Sync the other language dropdown
            const otherLang = lang === 'ar' ? 'en' : 'ar';
            const otherSelect = document.getElementById(`author-${authorId}-title-${otherLang}`);

            if (otherSelect.value !== selectElement.value) {
                otherSelect.value = selectElement.value;

                // Also show/hide the other field for the other language
                const otherOtherField = document.getElementById(`author-${authorId}-other-title-${otherLang}`);
                if (selectElement.value === 'other') {
                    otherOtherField.style.display = 'block';
                } else {
                    otherOtherField.style.display = 'none';
                }
            }

            autoSave();
        }

        // Populate degree dropdowns
        function populateDegreeDropdowns(authorId) {
            const arSelect = document.getElementById(`author-${authorId}-degree-ar`);
            const enSelect = document.getElementById(`author-${authorId}-degree-en`);

            console.log('arSelect.options',arSelect.options);

            // Clear previous options except the first one
            while (arSelect.options.length > 2) {
                arSelect.remove(2);
            }
            while (enSelect.options.length > 2) {
                enSelect.remove(2);
            }

            // Add degree options to the dropdowns
            degreeOptions.forEach(degree => {
                // Arabic dropdown
                const arOption = document.createElement('option');
                arOption.value = degree.value;
                arOption.textContent = degree.ar;
                arOption.setAttribute('data-lang-ar', degree.ar);
                arOption.setAttribute('data-lang-en', degree.en);
                arSelect.appendChild(arOption);

                // English dropdown
                const enOption = document.createElement('option');
                enOption.value = degree.value;
                enOption.textContent = degree.en;
                enOption.setAttribute('data-lang-ar', degree.ar);
                enOption.setAttribute('data-lang-en', degree.en);
                enSelect.appendChild(enOption);
            });
        }

        // Handle degree selection change
        function handleDegreeChange(selectElement, authorId, lang) {
            const otherField = document.getElementById(`author-${authorId}-other-degree-${lang}`);

            if (selectElement.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }

            // Sync the other language dropdown
            const otherLang = lang === 'ar' ? 'en' : 'ar';
            const otherSelect = document.getElementById(`author-${authorId}-degree-${otherLang}`);

            if (otherSelect.value !== selectElement.value) {
                otherSelect.value = selectElement.value;

                // Also show/hide the other field for the other language
                const otherOtherField = document.getElementById(`author-${authorId}-other-degree-${otherLang}`);
                if (selectElement.value === 'other') {
                    otherOtherField.style.display = 'block';
                } else {
                    otherOtherField.style.display = 'none';
                }
            }

            // Validate field
            validateFieldRealTime({target: selectElement});
            autoSave();
        }

        // Populate country dropdown
        function populateCountryDropdown(selectId) {
            const selectElement = document.getElementById(selectId);

            // Clear previous options except the first one
            while (selectElement.options.length > 2) {
                selectElement.remove(2);
            }

            // Add countries to the dropdown
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.code;

                if (currentLang === 'ar') {
                    option.textContent = country.name;
                } else {
                    option.textContent = country.nameEn;
                }

                // Store both names as data attributes for language switching
                option.setAttribute('data-lang-ar', country.name);
                option.setAttribute('data-lang-en', country.nameEn);

                selectElement.appendChild(option);
            });
        }

        // Detect user's country based on IP
        function detectCountry() {
            // Use Laravel endpoint to avoid CORS issues
            fetch('/detect-country')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set the detected country for the first author
                        setFieldValue('author-1-country', data.country_code);
                    } else {
                        // Fallback to Saudi Arabia if detection fails
                        setFieldValue('author-1-country', 'SA');
                    }
                })
                .catch(error => {
                    console.error('Error detecting country:', error);
                    // Fallback to Saudi Arabia if request fails
                    setFieldValue('author-1-country', 'SA');
                });
        }

        // Remove Author
        function removeAuthor(authorId) {
            if (currentLang === 'ar') {
                if (!confirm('هل أنت متأكد من حذف هذا الباحث؟')) return;
            } else {
                if (!confirm('Are you sure you want to delete this researcher?')) return;
            }

            document.getElementById(`author-${authorId}`).remove();
            authorCount--;

            // Re-enable add button
            document.getElementById('addAuthorBtn').disabled = false;
            if (currentLang === 'ar') {
                document.getElementById('addAuthorBtn').querySelector('[data-lang="ar"]').textContent = ' إضافة باحث آخر';
            } else {
                document.getElementById('addAuthorBtn').querySelector('[data-lang="en"]').textContent = ' Add Another Researcher';
            }

            updateProgress();
            autoSave();
        }

        // Paper ID synchronization function
        function syncPaperID(sourceLang, value) {
            if (sourceLang === 'ar') {
                document.getElementById('paper-id-en').value = value;
            } else {
                document.getElementById('paper-id-ar').value = value;
            }
            autoSave();
        }

        // Get Arabic Ordinal
        function getArabicOrdinal(number) {
            const ordinals = ['الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس', 'السابع', 'الثامن', 'التاسع', 'العاشر', 'الحادي عشر', 'الثاني عشر'];
            return ordinals[number - 1] || number;
        }

        // Toggle Section Collapse
        function toggleSection(button) {
            const section = button.closest('.form-section');
            const content = section.querySelector('.section-content');

            if (content) {
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
                button.classList.toggle('collapsed');
                section.classList.toggle('collapsed');
            }
        }

        // Language Toggle Function
        function toggleLanguage(initialLoad = false) {
            const html = document.getElementById('html-root');
            const langToggleText = document.getElementById('langToggleText');

            if (currentLang === 'ar') {
                // Switch to English
                currentLang = 'en';
                html.dir = 'ltr';
                html.lang = 'en';
                langToggleText.textContent = 'AR';

                // Hide Arabic text, show English text
                document.querySelectorAll('[data-lang="ar"]').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelectorAll('[data-lang="en"]').forEach(el => {
                    el.style.display = 'inline';
                });

                // Update placeholders and titles
                document.querySelectorAll('[data-lang-placeholder-ar]').forEach(el => {
                    if (el.hasAttribute('data-lang-placeholder-en')) {
                        el.placeholder = el.getAttribute('data-lang-placeholder-en');
                    }
                });

                document.querySelectorAll('[data-lang-title-ar]').forEach(el => {
                    if (el.hasAttribute('data-lang-title-en')) {
                        el.setAttribute('data-bs-original-title', el.getAttribute('data-lang-title-en'));
                    }
                });

                // Update progress text
                const progress = parseInt(document.getElementById('progressBar').style.width);
                document.getElementById('progressText').style.display = 'none';
                document.getElementById('progressTextEn').style.display = 'block';
                document.getElementById('progressTextEn').textContent = progress + '% Complete';

            } else {
                // Switch to Arabic
                currentLang = 'ar';
                html.dir = 'rtl';
                html.lang = 'ar';
                langToggleText.textContent = 'EN';

                // Hide English text, show Arabic text
                document.querySelectorAll('[data-lang="en"]').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelectorAll('[data-lang="ar"]').forEach(el => {
                    el.style.display = 'inline';
                });

                // Update placeholders and titles
                document.querySelectorAll('[data-lang-placeholder-en]').forEach(el => {
                    if (el.hasAttribute('data-lang-placeholder-ar')) {
                        el.placeholder = el.getAttribute('data-lang-placeholder-ar');
                    }
                });

                document.querySelectorAll('[data-lang-title-en]').forEach(el => {
                    if (el.hasAttribute('data-lang-title-ar')) {
                        el.setAttribute('data-bs-original-title', el.getAttribute('data-lang-title-ar'));
                    }
                });

                // Update progress text
                const progress = parseInt(document.getElementById('progressBar').style.width);
                document.getElementById('progressTextEn').style.display = 'none';
                document.getElementById('progressText').style.display = 'block';
                document.getElementById('progressText').textContent = progress + '% مكتمل';
            }

            // Update tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (tooltip) {
                    tooltip.dispose();
                }
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Save language preference
            if (!initialLoad) {
                localStorage.setItem('languagePreference', currentLang);
                autoSave();
            }

            // Close mobile menu after language change
            closeMobileMenu();
        }

        // Update title dropdowns language
        function updateTitleDropdownsLanguage() {
            for (let i = 1; i <= authorCount; i++) {
                const arSelect = document.getElementById(`author-${i}-title-ar`);
                const enSelect = document.getElementById(`author-${i}-title-en`);

                if (arSelect && enSelect) {
                    // Update the displayed text for each option
                    for (let j = 0; j < arSelect.options.length; j++) {
                        const option = arSelect.options[j];
                        if (option.value && option.hasAttribute('data-lang-ar') && option.hasAttribute('data-lang-en')) {
                            option.textContent = currentLang === 'ar' ?
                                option.getAttribute('data-lang-ar') :
                                option.getAttribute('data-lang-en');
                        }
                    }

                    for (let j = 0; j < enSelect.options.length; j++) {
                        const option = enSelect.options[j];
                        if (option.value && option.hasAttribute('data-lang-ar') && option.hasAttribute('data-lang-en')) {
                            option.textContent = currentLang === 'ar' ?
                                option.getAttribute('data-lang-ar') :
                                option.getAttribute('data-lang-en');
                        }
                    }
                }
            }
        }

        // Update degree dropdowns language
        function updateDegreeDropdownsLanguage() {
            for (let i = 1; i <= authorCount; i++) {
                const arSelect = document.getElementById(`author-${i}-degree-ar`);
                const enSelect = document.getElementById(`author-${i}-degree-en`);

                if (arSelect && enSelect) {
                    // Update the displayed text for each option
                    for (let j = 0; j < arSelect.options.length; j++) {
                        const option = arSelect.options[j];
                        if (option.value && option.hasAttribute('data-lang-ar') && option.hasAttribute('data-lang-en')) {
                            option.textContent = currentLang === 'ar' ?
                                option.getAttribute('data-lang-ar') :
                                option.getAttribute('data-lang-en');
                        }
                    }

                    for (let j = 0; j < enSelect.options.length; j++) {
                        const option = enSelect.options[j];
                        if (option.value && option.hasAttribute('data-lang-ar') && option.hasAttribute('data-lang-en')) {
                            option.textContent = currentLang === 'ar' ?
                                option.getAttribute('data-lang-ar') :
                                option.getAttribute('data-lang-en');
                        }
                    }
                }
            }
        }

        // Update language for a specific element
        function updateLanguageForElement(element) {
            if (currentLang === 'ar') {
                // Show Arabic, hide English
                element.querySelectorAll('[data-lang="ar"]').forEach(el => {
                    el.style.display = 'inline';
                });
                element.querySelectorAll('[data-lang="en"]').forEach(el => {
                    el.style.display = 'none';
                });

                // Update placeholders
                element.querySelectorAll('[data-lang-placeholder-ar]').forEach(el => {
                    el.placeholder = el.getAttribute('data-lang-placeholder-ar');
                });
            } else {
                // Show English, hide Arabic
                element.querySelectorAll('[data-lang="en"]').forEach(el => {
                    el.style.display = 'inline';
                });
                element.querySelectorAll('[data-lang="ar"]').forEach(el => {
                    el.style.display = 'none';
                });

                // Update placeholders
                element.querySelectorAll('[data-lang-placeholder-en]').forEach(el => {
                    el.placeholder = el.getAttribute('data-lang-placeholder-en');
                });
            }
        }

        // Real-time Validation
        function setupRealTimeValidation() {
            const fields = document.querySelectorAll('.validate-field');

            fields.forEach(field => {
                // Remove existing listeners to avoid duplicates
                field.removeEventListener('input', validateFieldRealTime);
                field.removeEventListener('blur', validateFieldRealTime);

                // Add new listeners
                field.addEventListener('input', validateFieldRealTime);
                field.addEventListener('blur', validateFieldRealTime);
            });
        }

        function validateFieldRealTime(event) {
            const field = event.target;
            const validationType = field.dataset.validation;
            const errorElement = field.parentElement.querySelector('.error-message');
            const successElement = field.parentElement.querySelector('.success-message');

            let isValid = true;
            let errorMessage = '';

            // Check if field is empty
            if (!field.value.trim()) {
                isValid = false;
                errorMessage = currentLang === 'ar' ? 'هذا الحقل مطلوب' : 'This field is required';
            } else {
                // Specific validation based on type
                switch(validationType) {
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            isValid = false;
                            errorMessage = currentLang === 'ar' ? 'يرجى إدخال بريد إلكتروني صحيح' : 'Please enter a valid email address';
                        }
                        // Check for duplicate emails
                        if (isValid && checkDuplicateEmail(field.value, field.id)) {
                            isValid = false;
                            errorMessage = currentLang === 'ar' ? 'هذا البريد مستخدم بالفعل' : 'This email is already used';
                        }
                        break;

                    case 'phone':
                        const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
                        if (!phoneRegex.test(field.value.replace(/\s/g, ''))) {
                            isValid = false;
                            errorMessage = currentLang === 'ar' ? 'يرجى إدخال رقم هاتف صحيح' : 'Please enter a valid phone number';
                        }
                        break;
                }
            }

            // Update field appearance
            if (isValid) {
                field.classList.remove('invalid');
                field.classList.add('valid');
                if (errorElement) errorElement.style.display = 'none';
                if (successElement) {
                    successElement.textContent = currentLang === 'ar' ? '✓ صحيح' : '✓ Valid';
                    successElement.style.display = 'block';
                    setTimeout(() => {
                        successElement.style.display = 'none';
                    }, 3000);
                }
            } else {
                field.classList.remove('valid');
                field.classList.add('invalid');
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                }
                if (successElement) successElement.style.display = 'none';
            }

            updateProgress();
            autoSave();
        }

        function checkDuplicateEmail(email, currentFieldId) {
            const emailFields = document.querySelectorAll('input[type="email"]');
            let foundDuplicate = false;

            emailFields.forEach(field => {
                if (field.id !== currentFieldId && field.value === email) {
                    foundDuplicate = true;
                }
            });

            return foundDuplicate;
        }

        // Handle Corresponding Author
        function handleCorrespondingAuthor(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="corresponding-author"]');
            checkboxes.forEach(cb => {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            autoSave();
        }

        // File Upload Handling
        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('fileUploadArea').classList.remove('dragover');

            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                processFile(file);
            }
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('fileUploadArea').classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('fileUploadArea').classList.remove('dragover');
        }

        function handleFileSelect(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                processFile(file);
            }
        }

        function processFile(file) {
            // Check file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                if (currentLang === 'ar') {
                    alert('حجم الملف كبير جدًا. الحد الأقصى هو 10 ميجابايت');
                } else {
                    alert('File is too large. Maximum size is 10MB');
                }
                return;
            }

            // Check file type
            const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!validTypes.includes(file.type)) {
                if (currentLang === 'ar') {
                    alert('نوع الملف غير مدعوم. يرجى تحميل ملف PDF أو Word');
                } else {
                    alert('File type not supported. Please upload a PDF or Word file');
                }
                return;
            }

            // Display file info
            const fileList = document.getElementById('fileList');
            const fileElement = document.createElement('div');
            fileElement.className = 'alert alert-info d-flex justify-content-between align-items-center';
            fileElement.innerHTML = `
                <div>
                    <i class="bi bi-file-earmark"></i> ${file.name}
                    <small>(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                </div>
                <button type="button" class="btn-close" onclick="removeFile(this)"></button>
            `;
            fileList.appendChild(fileElement);

            // Store file for submission
            formData.file = file;
            autoSave();
        }

        function removeFile(button) {
            const fileElement = button.closest('.alert');
            fileElement.remove();
            delete formData.file;
            autoSave();
        }

        // Auto-save Functionality
        function initializeAutoSave() {
            // Load saved data if exists
            const savedData = localStorage.getItem('researchFormData');
            if (savedData) {
                try {
                    const parsedData = JSON.parse(savedData);

                    // Populate form fields
                    for (const [key, value] of Object.entries(parsedData)) {
                        const field = document.getElementById(key);
                        if (field && value) {
                            field.value = value;

                            // Trigger validation
                            if (field.classList.contains('validate-field')) {
                                validateFieldRealTime({target: field});
                            }
                        }
                    }

                    // Update progress
                    updateProgress();
                } catch (e) {
                    console.error('Error loading saved data:', e);
                }
            }
        }

        function autoSave() {
            // Clear existing timer
            clearTimeout(autoSaveTimer);

            // Show saving indicator
            const indicator = document.getElementById('autoSaveIndicator');
            indicator.classList.add('show');

            // Collect form data
            const formData = collectFormData();
            // const fields = document.querySelectorAll('input, select, textarea');

            // fields.forEach(field => {
            //     if (field.id) {
            //         formData[field.id] = field.value;
            //     }
            // });

            // Save to localStorage
            localStorage.setItem('researchFormData', JSON.stringify(formData));

            // Hide indicator after delay
            autoSaveTimer = setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        }

        // Fixed loadDraft function
        function loadDraft() {
            const draft = localStorage.getItem('researchFormData');
            if (draft) {
                if (currentLang === 'ar') {
                    if (confirm('هل تريد استرجاع البيانات المحفوظة؟ سيتم فقدان البيانات الحالية.')) {
                        console.log('Loaded draft from localStorage:', draft);
                        try {
                            const data = JSON.parse(draft);
                            fillFormData(data);
                            alert('تم استرجاع المسودة المحفوظة بنجاح');
                        } catch (e) {
                            console.error('Error loading draft:', e);
                            alert('حدث خطأ في استرجاع المسودة');
                        }
                    }
                } else {
                    if (confirm('Do you want to restore saved data? Current data will be lost.')) {
                        try {
                            const data = JSON.parse(draft);
                            fillFormData(data);
                            alert('Draft restored successfully');
                        } catch (e) {
                            console.error('Error loading draft:', e);
                            alert('Error restoring draft');
                        }
                    }
                }
            } else {
                // if (currentLang === 'ar') {
                //     alert('لا توجد مسودة محفوظة');
                // } else {
                //     alert('No saved draft found');
                // }
            }

            closeMobileMenu();
        }

        // Fill form data from saved draft
        function fillFormData(data) {
            console.log('Filling form with data:', data);
            // Fill research details
            if (data.research) {
                setFieldValue('arabic-title', data.research.arabicTitle);
                setFieldValue('english-title', data.research.englishTitle);
                setFieldValue('research-science', data.research.science);
                setFieldValue('other-science', data.research.otherScience);
                // setFieldValue('journal-selection', data.research.journal);
                // Update journal options based on the selected science
                if (data.research.science) {
                    console.log('Updating journal options for science:', data.research.science);
                    console.log('Updating journal options for journal:', data.research.journal);
                    updateJournalOptions(data.research.science);
                    otherScienceField(data.research.science);
                    setFieldValue('journal-selection', data.research.journal);
                }
                setFieldValue('keywords', data.research.keywords);

                // Fill new fields
                setFieldValue('paper-id-ar', data.research.paperIdAr);
                setFieldValue('paper-id-en', data.research.paperIdEn);

                // Set thesis extraction radio button
                if (data.research.thesisExtraction) {
                    const radioButton = document.querySelector(`input[name="thesis-extraction"][value="${data.research.thesisExtraction}"]`);
                    if (radioButton) radioButton.checked = true;
                }
            }

            // Fill feedback if exists
            setFieldValue('feedback', data.feedback || '');

            // Fill author details
            if (data.authors && data.authors.length > 0) {
                // Remove existing authors except the first
                while (authorCount > 1) {
                    removeAuthor(authorCount);
                }

                // Fill first author
                if (data.authors[0]) {
                    fillAuthorData(1, data.authors[0]);
                }

                // Add and fill additional authors
                for (let i = 1; i < data.authors.length; i++) {
                    addAuthor();
                    fillAuthorData(i + 1, data.authors[i]);
                }
            }

            updateProgress();
        }

        // function setFieldValue(fieldId, value) {
        //     const field = document.getElementById(fieldId);
        //     if (field && value) field.value = value;
        // }
        function setFieldValue(fieldId, value) {
            const field = document.getElementById(fieldId);
            if (field && value !== undefined && value !== null) {
                field.value = value;

                // Trigger change event for research-science to update journals
                // if (fieldId === 'research-science') {
                //     field.dispatchEvent(new Event('change'));
                // }

                // // Trigger validation
                // if (field.classList.contains('validate-field')) {
                //     validateFieldRealTime({target: field});
                // }
            }
        }

        function fillAuthorData(index, author) {
            // Handle title data
            if (author.titleAr || author.titleValue) {
                // Check if it's a value from the dropdown options
                const titleOption = titleOptions.find(opt => opt.ar === author.titleAr || opt.en === author.titleAr || opt.value === author.titleValue);
                if (titleOption) {
                    // It's a standard title option
                    setFieldValue(`author-${index}-title-ar`, titleOption.value);
                    setFieldValue(`author-${index}-title-en`, titleOption.value);

                    // If it's "other", also set the custom text field
                    if (titleOption.value === 'other' && author.titleCustom) {
                        setFieldValue(`author-${index}-other-title-ar`, author.titleCustom);
                        setFieldValue(`author-${index}-other-title-en`, author.titleCustom);
                        document.getElementById(`author-${index}-other-title-ar`).style.display = 'block';
                        document.getElementById(`author-${index}-other-title-en`).style.display = 'block';
                    }
                } else if (author.titleAr) {
                    // It's a custom title (from old text field)
                    setFieldValue(`author-${index}-title-ar`, 'other');
                    setFieldValue(`author-${index}-title-en`, 'other');
                    setFieldValue(`author-${index}-other-title-ar`, author.titleAr);
                    setFieldValue(`author-${index}-other-title-en`, author.titleEn || author.titleAr);
                    document.getElementById(`author-${index}-other-title-ar`).style.display = 'block';
                    document.getElementById(`author-${index}-other-title-en`).style.display = 'block';
                }
            }
            setFieldValue(`author-${index}-name-ar`, author.nameAr);
            setFieldValue(`author-${index}-name-en`, author.nameEn);
            setFieldValue(`author-${index}-email`, author.email);
            setFieldValue(`author-${index}-phone`, author.phone);
            // setFieldValue(`author-${index}-degree-ar`, author.degreeAr);
            // setFieldValue(`author-${index}-degree-en`, author.degreeEn);

            // Handle degree data (both old text format and new dropdown format)
            if (author.degreeAr) {
                // Check if it's a value from the dropdown options
                const degreeOption = degreeOptions.find(opt => opt.ar === author.degreeAr || opt.en === author.degreeAr);
                if (degreeOption) {
                    // It's a standard degree option
                    setFieldValue(`author-${index}-degree-ar`, degreeOption.value);
                    setFieldValue(`author-${index}-degree-en`, degreeOption.value);

                    // If it's "other", also set the custom text field
                    if (degreeOption.value === 'other' && author.degreeCustom) {
                        setFieldValue(`author-${index}-other-degree-ar`, author.degreeCustom);
                        setFieldValue(`author-${index}-other-degree-en`, author.degreeCustom);
                        document.getElementById(`author-${index}-other-degree-ar`).style.display = 'block';
                        document.getElementById(`author-${index}-other-degree-en`).style.display = 'block';
                    }
                } else {
                    // It's a custom degree (from old text field)
                    setFieldValue(`author-${index}-degree-ar`, 'other');
                    setFieldValue(`author-${index}-degree-en`, 'other');
                    setFieldValue(`author-${index}-other-degree-ar`, author.degreeAr);
                    setFieldValue(`author-${index}-other-degree-en`, author.degreeEn || author.degreeAr);
                    document.getElementById(`author-${index}-other-degree-ar`).style.display = 'block';
                    document.getElementById(`author-${index}-other-degree-en`).style.display = 'block';
                }
            }



            // setFieldValue(`author-${index}-affiliation-ar`, author.affiliationAr);
            // setFieldValue(`author-${index}-affiliation-en`, author.affiliationEn);
            // Handle both old and new data structures for backward compatibility
            if (author.departmentAr || author.departmentEn) {
                // New structure with separated fields
                setFieldValue(`author-${index}-department-ar`, author.departmentAr);
                setFieldValue(`author-${index}-department-en`, author.departmentEn);
                setFieldValue(`author-${index}-faculty-ar`, author.facultyAr);
                setFieldValue(`author-${index}-faculty-en`, author.facultyEn);
                setFieldValue(`author-${index}-university-ar`, author.universityAr);
                setFieldValue(`author-${index}-university-en`, author.universityEn);
                setFieldValue(`author-${index}-country`, author.country);
            } else if (author.affiliationAr || author.affiliationEn) {
                // Old structure with combined affiliation field
                // Try to parse the old format or set default values
                setFieldValue(`author-${index}-department-ar`, '');
                setFieldValue(`author-${index}-department-en`, '');
                setFieldValue(`author-${index}-faculty-ar`, '');
                setFieldValue(`author-${index}-faculty-en`, '');

                // Try to extract university from old affiliation field
                const universityAr = extractUniversityFromAffiliation(author.affiliationAr, 'ar');
                const universityEn = extractUniversityFromAffiliation(author.affiliationEn, 'en');

                setFieldValue(`author-${index}-university-ar`, universityAr);
                setFieldValue(`author-${index}-university-en`, universityEn);

                // Set default country if not available
                setFieldValue(`author-${index}-country`, author.country || 'SA');
            } else {
                // No affiliation data available
                setFieldValue(`author-${index}-department-ar`, '');
                setFieldValue(`author-${index}-department-en`, '');
                setFieldValue(`author-${index}-faculty-ar`, '');
                setFieldValue(`author-${index}-faculty-en`, '');
                setFieldValue(`author-${index}-university-ar`, '');
                setFieldValue(`author-${index}-university-en`, '');
                setFieldValue(`author-${index}-country`, author.country || 'SA');
            }
            setFieldValue(`author-${index}-orcid`, author.orcid);

            const checkbox = document.querySelector(`input[name="corresponding-author"][value="${index}"]`);
            if (checkbox) checkbox.checked = author.corresponding || false;
        }

        // Collect form data for submission
        function collectFormData() {
            const data = {
                research: {
                    arabicTitle: document.getElementById('arabic-title')?.value || '',
                    englishTitle: document.getElementById('english-title')?.value || '',
                    science: document.getElementById('research-science')?.value || '',
                    otherScience: document.getElementById('other-science')?.value || '',
                    journal: document.getElementById('journal-selection')?.value || '',
                    keywords: document.getElementById('keywords')?.value || '',
                    paperIdAr: document.getElementById('paper-id-ar')?.value || '',
                    paperIdEn: document.getElementById('paper-id-en')?.value || '',
                    thesisExtraction: document.querySelector('input[name="thesis-extraction"]:checked')?.value || 'no'
                },
                authors: [],
                feedback: document.getElementById('feedback')?.value || '' // Add feedback to form data
            };

            for (let i = 1; i <= authorCount; i++) {
                const authorSection = document.getElementById(`author-${i}`);
                if (authorSection) {

                    const titleArSelect = document.getElementById(`author-${i}-title-ar`);
                    const titleEnSelect = document.getElementById(`author-${i}-title-en`);
                    const otherTitleAr = document.getElementById(`author-${i}-other-title-ar`)?.querySelector('input')?.value || '';
                    const otherTitleEn = document.getElementById(`author-${i}-other-title-en`)?.querySelector('input')?.value || '';

                    // Get the selected title values
                    const titleValue = titleArSelect?.value || '';

                    // Get the display text for the title
                    let titleAr = '';
                    let titleEn = '';

                    if (titleValue === 'other') {
                        // Use custom title text
                        titleAr = otherTitleAr;
                        titleEn = otherTitleEn;
                    } else if (titleValue) {
                        // Find the title option
                        const titleOption = titleOptions.find(opt => opt.value === titleValue);
                        if (titleOption) {
                            titleAr = titleOption.ar;
                            titleEn = titleOption.en;
                        }
                    }

                    const degreeArSelect = document.getElementById(`author-${i}-degree-ar`);
                    const degreeEnSelect = document.getElementById(`author-${i}-degree-en`);
                    const otherDegreeAr = document.getElementById(`author-${i}-other-degree-ar`)?.querySelector('input')?.value || '';
                    const otherDegreeEn = document.getElementById(`author-${i}-other-degree-en`)?.querySelector('input')?.value || '';

                    // Get the selected degree values
                    const degreeValue = degreeArSelect?.value || '';

                    // Get the display text for the degree
                    let degreeAr = '';
                    let degreeEn = '';

                    if (degreeValue === 'other') {
                        // Use custom degree text
                        degreeAr = otherDegreeAr;
                        degreeEn = otherDegreeEn;
                    } else if (degreeValue) {
                        // Find the degree option
                        const degreeOption = degreeOptions.find(opt => opt.value === degreeValue);
                        if (degreeOption) {
                            degreeAr = degreeOption.ar;
                            degreeEn = degreeOption.en;
                        }
                    }


                    const author = {
                        titleAr: titleAr,
                        titleEn: titleEn,
                        titleValue: titleValue,
                        titleCustom: titleValue === 'other' ? otherTitleAr : '',
                        // Concatenate name fields
                        nameAr: [
                            document.getElementById(`author-${i}-first-name-ar`)?.value || '',
                            document.getElementById(`author-${i}-middle-name-ar`)?.value || '',
                            document.getElementById(`author-${i}-last-name-ar`)?.value || ''
                        ].filter(Boolean).join(' '),
                        nameEn: [
                            document.getElementById(`author-${i}-first-name-en`)?.value || '',
                            document.getElementById(`author-${i}-middle-name-en`)?.value || '',
                            document.getElementById(`author-${i}-last-name-en`)?.value || ''
                        ].filter(Boolean).join(' '),
                        email: document.getElementById(`author-${i}-email`)?.value || '',
                        phone: document.getElementById(`author-${i}-phone`)?.value || '',
                        degreeAr: degreeAr,
                        degreeEn: degreeEn,
                        degreeValue: degreeValue,
                        degreeCustom: degreeValue === 'other' ? otherDegreeAr : '',
                        // degreeAr: document.getElementById(`author-${i}-degree-ar`)?.value || '',
                        // degreeEn: document.getElementById(`author-${i}-degree-en`)?.value || '',
                        // affiliationAr: document.getElementById(`author-${i}-affiliation-ar`)?.value || '',
                        // affiliationEn: document.getElementById(`author-${i}-affiliation-en`)?.value || '',
                        // New separated affiliation fields
                        departmentAr: document.getElementById(`author-${i}-department-ar`)?.value || '',
                        departmentEn: document.getElementById(`author-${i}-department-en`)?.value || '',
                        facultyAr: document.getElementById(`author-${i}-faculty-ar`)?.value || '',
                        facultyEn: document.getElementById(`author-${i}-faculty-en`)?.value || '',
                        universityAr: document.getElementById(`author-${i}-university-ar`)?.value || '',
                        universityEn: document.getElementById(`author-${i}-university-en`)?.value || '',
                        country: document.getElementById(`author-${i}-country`)?.value || '',

                        orcid: document.getElementById(`author-${i}-orcid`)?.value || '',
                        corresponding: document.querySelector(`input[name="corresponding-author"][value="${i}"]`)?.checked || false
                    };
                    data.authors.push(author);
                }
            }

            return data;
        }

        // Progress Tracking
        function updateProgress() {
            const requiredFields = document.querySelectorAll('.validate-field[required]');
            let filledCount = 0;

            requiredFields.forEach(field => {
                if (field.value.trim() !== '') {
                    filledCount++;
                }
            });

            const progress = Math.round((filledCount / requiredFields.length) * 100);
            document.getElementById('progressBar').style.width = `${progress}%`;

            if (currentLang === 'ar') {
                document.getElementById('progressText').textContent = `${progress}% مكتمل`;
            } else {
                document.getElementById('progressTextEn').textContent = `${progress}% Complete`;
            }
        }

        // Journal Search
        function setupJournalSearch() {
            const searchInput = document.getElementById('journal-search');
            const journalSelect = document.getElementById('journal-selection');
            const suggestionsList = document.getElementById('journal-suggestions');

            // Populate select with journals
            journals.forEach(journal => {
                const option = document.createElement('option');
                option.value = journal.id;
                option.textContent = currentLang === 'ar' ? journal.name : journal.nameEn;
                option.setAttribute('data-lang-ar', journal.name);
                option.setAttribute('data-lang-en', journal.nameEn);
                journalSelect.appendChild(option);
            });

            // Show/hide search and select based on screen size
            function toggleJournalInputs() {
                if (window.innerWidth < 768) {
                    journalSelect.style.display = 'block';
                    searchInput.style.display = 'none';
                    suggestionsList.style.display = 'none';
                } else {
                    journalSelect.style.display = 'none';
                    searchInput.style.display = 'block';
                }
            }

            // Initial toggle
            toggleJournalInputs();

            // Toggle on resize
            window.addEventListener('resize', toggleJournalInputs);

            // Search functionality
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();

                // Clear previous suggestions
                suggestionsList.innerHTML = '';

                if (query.length < 2) {
                    suggestionsList.style.display = 'none';
                    return;
                }

                // Filter journals
                const filteredJournals = journals.filter(journal =>
                    journal.name.toLowerCase().includes(query) ||
                    journal.nameEn.toLowerCase().includes(query)
                );

                // Display suggestions
                if (filteredJournals.length > 0) {
                    suggestionsList.style.display = 'block';

                    filteredJournals.forEach(journal => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.innerHTML = `
                            <div><strong>${currentLang === 'ar' ? journal.name : journal.nameEn}</strong></div>
                            <div class="small text-muted">${currentLang === 'ar' ? journal.nameEn : journal.name}</div>
                        `;
                        li.addEventListener('click', () => {
                            searchInput.value = currentLang === 'ar' ? journal.name : journal.nameEn;
                            journalSelect.value = journal.id;
                            suggestionsList.style.display = 'none';

                            // Validate field
                            validateFieldRealTime({target: journalSelect});
                        });
                        suggestionsList.appendChild(li);
                    });
                } else {
                    suggestionsList.style.display = 'none';
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.style.display = 'none';
                }
            });
        }

        // Setup Journal Selection based on Research Field
        // function setupJournalSelection() {
        //     const researchScienceSelect = document.getElementById('research-science');
        //     const journalSelect = document.getElementById('journal-selection');

        //     researchScienceSelect.addEventListener('change', function() {
        //         const selectedScience = this.value;

        //         // Clear previous options except the first one
        //         while (journalSelect.options.length > 2) {
        //             journalSelect.remove(2);
        //         }

        //         // Show/hide other science field
        //         const otherField = document.getElementById('other-science-field');
        //         if (selectedScience === 'other') {
        //             otherField.style.display = 'block';
        //             document.getElementById('other-science').required = true;
        //         } else {
        //             otherField.style.display = 'none';
        //             document.getElementById('other-science').required = false;
        //         }

        //         // If a science is selected, populate journals
        //         if (selectedScience) {
        //             // Filter journals based on selected science
        //             const filteredJournals = selectedScience !== 'other' ? journals.filter(journal =>
        //                 journal.sciences.includes(selectedScience)
        //             ) : journals;

        //             // Add filtered journals to the select
        //             filteredJournals.forEach(journal => {
        //                 const option = document.createElement('option');
        //                 option.value = journal.id;

        //                 if (currentLang === 'ar') {
        //                     option.textContent = journal.name;
        //                 } else {
        //                     option.textContent = journal.nameEn;
        //                 }

        //                 // Store both names as data attributes for language switching
        //                 option.setAttribute('data-lang-ar', journal.name);
        //                 option.setAttribute('data-lang-en', journal.nameEn);

        //                 journalSelect.appendChild(option);
        //             });

        //             // Show the select (it might be hidden initially)
        //             journalSelect.style.display = 'block';
        //         } else {
        //             // Hide the select if no science is selected
        //             journalSelect.style.display = 'block';
        //         }

        //         // Trigger validation
        //         validateFieldRealTime({target: journalSelect});
        //     });
        // }
        function setupJournalSelection() {
            const researchScienceSelect = document.getElementById('research-science');
            const journalSelect = document.getElementById('journal-selection');

            researchScienceSelect.addEventListener('change', function() {
                updateJournalOptions(this.value);

                // Show/hide other science field
                otherScienceField(this.value);

                // Trigger validation
                validateFieldRealTime({target: journalSelect});
            });
        }
        // Update journal options based on selected science
        function updateJournalOptions(science) {
            const journalSelect = document.getElementById('journal-selection');

            // Clear previous options except the first one
            while (journalSelect.options.length > 2) {
                journalSelect.remove(2);
            }

            // If a science is selected, populate journals
            if (science) {
                // Filter journals based on selected science
                const filteredJournals = science !== 'other' ? journals.filter(journal =>
                    journal.sciences.includes(science)
                ) : journals;

                // Add filtered journals to the select
                filteredJournals.forEach(journal => {
                    const option = document.createElement('option');
                    option.value = journal.id;

                    if (currentLang === 'ar') {
                        option.textContent = journal.name;
                    } else {
                        option.textContent = journal.nameEn;
                    }

                    // Store both names as data attributes for language switching
                    option.setAttribute('data-lang-ar', journal.name);
                    option.setAttribute('data-lang-en', journal.nameEn);

                    journalSelect.appendChild(option);
                });

                // Show the select
                journalSelect.style.display = 'block';
            } else {
                // Show the select even if no science is selected
                journalSelect.style.display = 'block';
            }
        }

        function otherScienceField(scienceValue) {
            const otherField = document.getElementById('other-science-field');
            if (scienceValue === 'other') {
                otherField.style.display = 'block';
                document.getElementById('other-science').required = true;
            } else {
                otherField.style.display = 'none';
                document.getElementById('other-science').required = false;
            }
        }


        // Form Submission
        function validateAndSubmit() {
            // Validate all required fields
            const requiredFields = document.querySelectorAll('.validate-field[required]');
            let allValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(field => {
                validateFieldRealTime({target: field});
                if (field.classList.contains('invalid') || !field.value.trim()) {
                    allValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                }
            });

            // Check corresponding author
            const correspondingAuthor = document.querySelector('input[name="corresponding-author"]:checked');
            if (!correspondingAuthor) {
                if (currentLang === 'ar') {
                    alert('يرجى تحديد الباحث المراسل');
                } else {
                    alert('Please select the corresponding author');
                }
                allValid = false;
            }

            if (!allValid) {
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }

                if (currentLang === 'ar') {
                    alert('يرجى تعبئة جميع الحقول المطلوبة بشكل صحيح');
                } else {
                    alert('Please fill all required fields correctly');
                }
                return;
            }

            // Disable submit button to prevent multiple submissions
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.innerHTML = currentLang === 'ar'
                ? '<div class="spinner-border spinner-border-sm" role="status"></div> جاري الإرسال...'
                : '<div class="spinner-border spinner-border-sm" role="status"></div> Sending...';

            // Collect form data
            const formData = collectFormData();

            // Create FormData object for file upload
            const submitData = new FormData();
            submitData.append('research_data', JSON.stringify(formData));

            // Add file if exists
            const fileInput = document.getElementById('fileInput');
            if (fileInput.files.length > 0) {
                submitData.append('manuscript', fileInput.files[0]);
            }

            // Submit to Laravel controller
            fetch('/submit-research', {
                method: 'POST',
                body: submitData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear saved draft
                    localStorage.removeItem('researchFormData');

                    // Redirect to thank you page
                    window.location.href = '/thank-you';
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (currentLang === 'ar') {
                    alert('حدث خطأ أثناء الإرسال: ' + error.message);
                } else {
                    alert('Error during submission: ' + error.message);
                }

                // Re-enable submit button
                submitButton.disabled = false;
                if (currentLang === 'ar') {
                    submitButton.innerHTML = '<i class="bi bi-send"></i> إرسال ';
                } else {
                    submitButton.innerHTML = '<i class="bi bi-send"></i> Send';
                }
            });
        }

        function clearForm() {
            if (currentLang === 'ar') {
                if (!confirm('هل أنت متأكد من مسح جميع البيانات؟')) return;
            } else {
                if (!confirm('Are you sure you want to clear all data?')) return;
            }

            // Clear all form fields
            document.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.type !== 'button' && field.type !== 'submit') {
                    field.value = '';
                    field.classList.remove('valid', 'invalid');
                }
            });

            // Clear file input and list
            document.getElementById('fileInput').value = '';
            document.getElementById('fileList').innerHTML = '';

            // Remove validation classes
            const fields = document.querySelectorAll('.validate-field');
            fields.forEach(field => {
                field.classList.remove('valid', 'invalid');
            });

            // Hide error messages
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => {
                msg.style.display = 'none';
            });

            // Reset authors to just one
            const authorsContainer = document.getElementById('authorsContainer');
            authorsContainer.innerHTML = '';
            authorCount = 0;
            addAuthor();

            // Clear storage
            localStorage.removeItem('researchFormData');

            // Reset progress
            updateProgress();

            closeMobileMenu();
        }

        // Fixed PDF export function
        function exportPDF() {
            //print
            window.print();
            // // Create a clone of the form content
            // const content = document.createElement('div');
            // content.innerHTML = document.querySelector('.container').innerHTML;

            // // Remove elements that shouldn't be in the PDF
            // content.querySelectorAll('.controls, .btn, .collapse-btn, .auto-save-indicator, .file-upload, #feedbackModal').forEach(el => {
            //     el.remove();
            // });

            // // Show all sections (uncollapse)
            // content.querySelectorAll('.form-section.collapsed').forEach(section => {
            //     section.classList.remove('collapsed');
            //     const content = section.querySelector('.section-content');
            //     if (content) content.style.display = 'block';
            // });

            // // Create a temporary container for PDF generation
            // const pdfContainer = document.createElement('div');
            // pdfContainer.appendChild(content);

            // // Generate PDF
            // const options = {
            //     margin: 10,
            //     filename: 'research-form.pdf',
            //     image: { type: 'jpeg', quality: 0.98 },
            //     html2canvas: { scale: 2 },
            //     jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            // };

            // html2pdf().from(pdfContainer).set(options).save();

            closeMobileMenu();
        }

        // Accessibility Features
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            localStorage.setItem('highContrast', document.body.classList.contains('high-contrast'));

            closeMobileMenu();
        }

        // Analytics (for demo purposes only)
        function trackFormAnalytics() {
            console.log('Form analytics tracking initialized');

            // Track time spent on form
            let timeSpent = 0;
            setInterval(() => {
                timeSpent++;
                if (timeSpent % 30 === 0) {
                    console.log(`User has spent ${timeSpent} seconds on the form`);
                }
            }, 1000);

            // Track field interactions
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('focus', () => {
                    console.log(`User focused on field: ${field.id}`);
                });
            });
        }

        // Feedback Modal
        function showFeedback() {
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        }

        function submitFeedback() {
            const feedbackText = document.querySelector('#feedbackModal textarea').value;
            if (feedbackText.trim()) {
                // Send feedback to server
                fetch('/submit-feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ feedback: feedbackText })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (currentLang === 'ar') {
                            alert('شكرًا لك على ملاحظاتك!');
                        } else {
                            alert('Thank you for your feedback!');
                        }
                        const feedbackModal = bootstrap.Modal.getInstance(document.getElementById('feedbackModal'));
                        feedbackModal.hide();
                        document.querySelector('#feedbackModal textarea').value = '';
                    } else {
                        throw new Error('Feedback submission failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (currentLang === 'ar') {
                        alert('حدث خطأ أثناء إرسال الملاحظات');
                    } else {
                        alert('Error sending feedback');
                    }
                });
            } else {
                if (currentLang === 'ar') {
                    alert('يرجى كتابة ملاحظاتك قبل الإرسال');
                } else {
                    alert('Please write your feedback before sending');
                }
            }
        }

        // Handle research science selection
        document.getElementById('research-science').addEventListener('change', function() {
            const otherField = document.getElementById('other-science-field');
            if (this.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }
        });

        // Load high contrast preference
        if (localStorage.getItem('highContrast') === 'true') {
            document.body.classList.add('high-contrast');
        }

        // Initialize the journal selection when the page loads
        // const researchScienceSelect = document.getElementById('research-science');
        // if (researchScienceSelect.value) {
        //     researchScienceSelect.dispatchEvent(new Event('change'));
        // }
    </script>
</body>
</html>
