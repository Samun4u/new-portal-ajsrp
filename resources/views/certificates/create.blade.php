@extends('admin.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background: #f5f7fa;
        padding: 20px;
    }

    .arabic-text {
        font-family: 'Arial', 'Tahoma', sans-serif;
        direction: rtl;
        text-align: right;
    }

    /*.container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 30px;
    }*/

    .header {
        margin-bottom: 30px;
    }

    h1 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .article-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        gap: 30px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        gap: 8px;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        color: #6c757d;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #28a745;
        color: white;
    }

    .btn-primary:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-outline {
        background: white;
        color: #007bff;
        border: 2px solid #007bff;
    }

    .btn-outline:hover {
        background: #007bff;
        color: white;
    }

    .btn:disabled {
        background: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 1200px;
        margin: 2% auto;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.3);
        max-height: 90vh;
        overflow-y: auto;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 25px 30px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #2c3e50;
        font-size: 24px;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        color: #6c757d;
        cursor: pointer;
        padding: 0;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .close-btn:hover {
        background: #f8f9fa;
        color: #495057;
    }

    .modal-body {
        padding: 30px;
    }

    .language-selector {
        background: #fff3cd;
        border: 2px solid #ffc107;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .language-selector label {
        font-weight: 600;
        color: #856404;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .language-toggle {
        display: flex;
        gap: 10px;
    }

    .lang-btn {
        padding: 8px 20px;
        border: 2px solid #ffc107;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .lang-btn.active {
        background: #ffc107;
        color: white;
    }

    .journal-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 4px solid #007bff;
    }

    .journal-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .journal-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .journal-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    thead {
        background: #343a40;
        color: white;
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        color: #495057;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-published {
        background: #d4edda;
        color: #155724;
    }

    .status-scheduled {
        background: #fff3cd;
        color: #856404;
    }

    .status-draft {
        background: #e2e3e5;
        color: #383d41;
    }

    .radio-cell {
        text-align: center;
    }

    input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .selected-info {
        color: #6c757d;
        font-size: 14px;
    }

    .selected-info strong {
        color: #28a745;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .preview-section {
        margin-top: 30px;
        padding: 25px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .preview-header h3 {
        color: #2c3e50;
        font-size: 20px;
    }

    .certificate-preview {
        background: white;
        padding: 50px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
        min-height: 700px;
    }

    .certificate-preview.arabic {
        direction: rtl;
        text-align: right;
    }

    .certificate-border {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 3px double #007bff;
        pointer-events: none;
    }

    .certificate-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .certificate-content.arabic {
        text-align: center;
    }

    .cert-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        font-weight: bold;
    }

    .cert-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cert-title.arabic {
        font-family: 'Arial', 'Tahoma', sans-serif;
        letter-spacing: 0;
    }

    .cert-subtitle {
        font-size: 18px;
        color: #6c757d;
        margin-bottom: 30px;
    }

    .cert-body {
        text-align: left;
        max-width: 700px;
        margin: 30px auto;
        line-height: 1.8;
        color: #495057;
    }

    .cert-body.arabic {
        text-align: right;
        font-family: 'Arial', 'Tahoma', sans-serif;
        direction: rtl;
    }

    .cert-details {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        margin: 25px 0;
        text-align: left;
    }

    .cert-details.arabic {
        text-align: right;
        direction: rtl;
    }

    .cert-detail-row {
        display: flex;
        gap: 15px;
        margin-bottom: 10px;
    }

    .cert-detail-row.arabic {
        /* flex-direction: row-reverse; */
        /* Removed to allow natural RTL flow (Label on Right, Value on Left) */
    }

    .cert-detail-row:last-child {
        margin-bottom: 0;
    }

    .cert-footer {
        margin-top: 40px;
        display: flex;
        justify-content: space-around;
        padding-top: 30px;
    }

    .signature-block {
        text-align: center;
        position: relative;
    }

    .signature-image {
        width: 80px;
        height: 80px;
        margin: 10px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .signature-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .signature-placeholder {
        width: 80px;
        height: 60px;
        border: 2px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 12px;
        border-radius: 4px;
    }

    .stamp-image {
        width: 80px;
        height: 80px;
        margin: 10px auto; /* Adjusted margin for consistency */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        z-index: 0;
    }

    .stamp-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .signature-line {
        width: 200px;
        border-top: 2px solid #2c3e50;
        margin: 10px auto;
    }

    .signature-name {
        font-weight: 600;
        color: #2c3e50;
        margin-top: 5px;
    }

    .signature-title {
        font-size: 13px;
        color: #6c757d;
    }

    .cert-date {
        text-align: center;
        margin-top: 25px;
        color: #6c757d;
        font-size: 14px;
    }

    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .tab-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .tab-btn {
        padding: 10px 20px;
        background: #e9ecef;
        border: none;
        border-radius: 6px 6px 0 0;
        cursor: pointer;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s;
    }

    .tab-btn.active {
        background: white;
        color: #007bff;
        border-bottom: 3px solid #007bff;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .upload-section {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .upload-section h4 {
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .upload-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .upload-item {
        background: white;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .upload-item label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .file-input {
        display: none;
    }

    .file-label {
        display: block;
        padding: 8px 12px;
        background: #007bff;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        font-size: 13px;
        transition: all 0.3s;
    }

    .file-label:hover {
        background: #0056b3;
    }

    .file-preview {
        margin-top: 10px;
        text-align: center;
    }

    .file-preview img {
        max-width: 100%;
        max-height: 80px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .file-name {
        font-size: 11px;
        color: #6c757d;
        margin-top: 5px;
        word-break: break-all;
    }

    .journal-selector {
        margin-bottom: 25px;
    }

    .journal-selector label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .journal-selector select {
        width: 100%;
        padding: 12px;
        border: 2px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .journal-selector select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .abbrev-tag {
        display: inline-block;
        padding: 2px 8px;
        background: #e9ecef;
        color: #495057;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }

    .note-box {
        background: #d1ecf1;
        border-left: 4px solid #17a2b8;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .note-box strong {
        color: #0c5460;
    }

    .note-box p {
        color: #0c5460;
        margin-top: 5px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .modal-content {
            width: 95%;
            margin: 5% auto;
        }

        .journal-details {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 10px 8px;
        }

        .certificate-preview {
            padding: 30px 20px;
        }

        .cert-title {
            font-size: 24px;
        }

        .upload-grid {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .action-buttons {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }

        .selected-info {
            text-align: center;
            margin-bottom: 10px;
        }
    }
</style>
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
   <div class="page-content-wrapper bg-white bd-ra-15 p-20">
        <div class="header">
            <h1>📄 Final Acceptance Certificate Generator</h1>
            <p style="color: #6c757d; margin-top: 10px;">Generate bilingual certificates with signatures and stamps</p>
        </div>

        <div class="article-info">
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Article Title:</span>
                    <span class="info-value">{{ $submission->article_title ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Submission ID:</span>
                    <span class="info-value">{{ $submission->order_number ?? $submission->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Journal:</span>
                    <span class="info-value">{{ $submission->journal->name ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Authors:</span>
                    <span class="info-value">{{ $submission->corresponding_author_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Accepted Date:</span>
                    <span class="info-value">{{ $submission->acceptance_date ? $submission->acceptance_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="badge badge-success">{{ $submission->payment_status ?? 'Payment Completed' }}</span>
                </div>
            </div>
        </div>

        @if($submission->certificate)
            <div style="background: #d4edda; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <p style="color: #155724; margin-bottom: 8px; font-weight: 600;">✓ Certificate issued on {{ $submission->certificate->updated_at->format('F d, Y') }}</p>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('certificates.download', $submission->certificate->id) }}" class="btn btn-primary" style="background: #155724; border-color: #155724;">
                            📥 Download Existing
                        </a>
                    </div>
                </div>
                <div>
                    <button class="btn btn-outline" onclick="openModal()" style="border-color: #dc3545; color: #dc3545;">
                        🔄 Regenerate Certificate
                    </button>
                </div>
            </div>
        @else
            <div style="text-align: right; margin-bottom: 20px;">
                <p style="color: #6c757d; font-style: italic; margin-bottom: 10px;">Ready to generate</p>
                <button class="btn btn-primary" onclick="openModal()">
                    <span>📋</span> Generate Final Acceptance Certificate
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Certificate Generation Modal -->
<div id="certificateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>🎓 Generate Final Acceptance Certificate</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab('selection')">1. Journal & Language</button>
                <button class="tab-btn" id="issueTab" onclick="switchTab('issue')" disabled>2. Issue Selection</button>
                <button class="tab-btn" id="previewTab" onclick="switchTab('preview')" disabled>3. Generate</button>
            </div>

            <!-- Tab 1: Journal & Language Selection -->
            <div id="selectionTab" class="tab-content active">
                <div class="language-selector">
                    <label>🌐 Certificate Language:</label>
                    <div class="language-toggle">
                        <button class="lang-btn active" id="englishBtn" onclick="selectLanguage('english')">
                            🇬🇧 English
                        </button>
                        <button class="lang-btn" id="arabicBtn" onclick="selectLanguage('arabic')">
                            🇸🇦 العربية
                        </button>
                    </div>
                </div>

                <div class="journal-selector">
                    <label for="journalSelect">Select Journal</label>
                    <select id="journalSelect" onchange="updateJournalInfo()">
                        <option value="">-- Choose a Journal --</option>
                        @foreach($journals as $journal)
                            @php $jVal = $journal->slug ?? $journal->id; @endphp
                            <option value="{{ $jVal }}" {{ ($submission->journal_id ?? 0) == $journal->id ? 'selected' : '' }}>
                                {{ $journal->title ?? $journal->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="journal-section" id="journalSection" style="display: {{ $submission->journal_id ? 'block' : 'none' }};">
                    <div class="journal-header">
                        <div style="flex: 1;">
                            <div class="journal-title" id="journalName">
                                <span id="journalNameText">{{ $submission->journal->name ?? '' }}</span>
                                <span class="abbrev-tag" id="journalAbbrev">{{ $submission->journal->slug ?? $submission->journal->id ?? '' }}</span>
                            </div>
                            <div class="journal-details">
                                <div class="info-item">
                                    <span class="info-label">ISSN:</span>
                                    <span class="info-value" id="journalISSN">{{ $submission->journal->issn ?? '' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Chief Editor:</span>
                                    <span class="info-value" id="chiefEditor">{{ $submission->journal->chief_editor ?? '' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Publisher:</span>
                                    <span class="info-value" id="publisher">AJSRP - Arab Journal of Sciences & Research Publishing</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">ISSN (Print):</span>
                                    <span class="info-value" id="journalISSN">--</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">ISSN (Online):</span>
                                    <span class="info-value" id="journalISSNOnline">--</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Impact Factor:</span>
                                    <span class="info-value" id="journalImpactFactor">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DOI Section -->
                <div class="form-group" style="padding: 15px 0;">
                    <label for="doiInput" style="font-weight: bold; color: #333;">DOI (Optional)</label>
                    <input type="text" id="doiInput" class="form-control" placeholder="e.g. 10.1234/ajsrp.v5i2.123" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div class="upload-section" id="uploadSection" style="display: {{ $submission->journal_id ? 'block' : 'none' }};">
                    <h4>📝 Signature & Stamp Management</h4>
                    <div class="note-box">
                        <strong>Note:</strong>
                        <p>Upload signature and stamp images. These will be applied to the generated certificate. If previously saved, you can leave these blank.</p>
                    </div>

                    <!-- Editor Names -->
                    <div class="upload-grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label>Chief Editor Name (English)</label>
                            <input type="text" id="chiefEditorName" class="form-control" placeholder="Name in English" style="width: 100%; padding: 8px;">
                        </div>
                        <div>
                            <label>Chief Editor Name (Arabic)</label>
                            <input type="text" id="chiefEditorNameAr" class="form-control" placeholder="الاسم بالعربية" style="width: 100%; padding: 8px; direction: rtl;">
                        </div>
                    </div>

                    </div>

                    <!-- Managing Editor Names -->
                    <div class="upload-grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label>Managing Editor Name (English)</label>
                            <input type="text" id="managingEditorName" class="form-control" placeholder="Name in English" style="width: 100%; padding: 8px;">
                        </div>
                        <div>
                            <label>Managing Editor Name (Arabic)</label>
                            <input type="text" id="managingEditorNameAr" class="form-control" placeholder="الاسم بالعربية" style="width: 100%; padding: 8px; direction: rtl;">
                        </div>
                    </div>

                    <div class="upload-grid">
                        <div class="upload-item">
                            <label>Chief Editor Signature</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="signatureInput" class="file-input" accept="image/*" onchange="handleFileUpload(this, 'signature')">
                                <label for="signatureInput" class="file-label">📸 Choose Signature (Update)</label>
                            </div>
                            <div class="file-preview" id="signaturePreview"></div>
                        </div>
                         <div class="upload-item">
                            <label>Managing Editor Signature</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="managingSignatureInput" class="file-input" accept="image/*" onchange="handleFileUpload(this, 'managing_signature')">
                                <label for="managingSignatureInput" class="file-label">📸 Choose Signature (Update)</label>
                            </div>
                            <div class="file-preview" id="managingSignaturePreview"></div>
                        </div>
                        <div class="upload-item">
                            <label>Official Stamp</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="stampInput" class="file-input" accept="image/*" onchange="handleFileUpload(this, 'stamp')">
                                <label for="stampInput" class="file-label">📸 Choose Stamp (Update)</label>
                            </div>
                            <div class="file-preview" id="stampPreview"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Issue Selection -->
            <div id="issueTabContent" class="tab-content">
                <div id="issuesTableContainer">
                    <h3 style="margin-bottom: 15px; color: #2c3e50;">Select Publication Issue</h3>
                    <p style="color: #6c757d; margin-bottom: 15px;">Choose the issue where this article will be published</p>

                    <table id="issuesTable">
                        <thead>
                            <tr>
                                <th>Volume</th>
                                <th>Issue</th>
                                <th>Status</th>
                                <th>Articles</th>
                                <th>Publication Date</th>
                                <th style="text-align: center;">Select</th>
                            </tr>
                        </thead>
                        <tbody id="issuesTableBody">
                            <!-- Will be populated dynamically via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Generate -->
            <div id="previewTabContent" class="tab-content">
                <div class="preview-section" style="text-align: center; padding: 50px;">
                    <h3>Ready to Generate Certificate</h3>
                    <p>Please review your selections (Authors, DOI, Signatures) before generating.</p>
                    <div style="margin-top: 20px;">
                        <p><strong>Note:</strong> The certificate will be generated as a PDF file and downloaded automatically.</p>
                    </div>
                    <!-- Hidden elements purely to avoid JS errors if existing code references them, though we should clean that up -->
                    <div id="certificatePreview" style="display:none;"></div>
                </div>
            </div>

        <div class="modal-footer">
            <div class="selected-info" id="selectedInfo">
                No journal and language selected
            </div>
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" disabled id="nextToIssueBtn" onclick="proceedToIssue()">
                    Next: Select Issue →
                </button>
                <button class="btn btn-primary" style="display: none;" disabled id="confirmBtn" onclick="proceedToPreview()">
                    Next: Preview →
                </button>
                <button class="btn btn-primary" style="display: none;" id="generateBtn" onclick="generateCertificate()">
                    <span class="loading" style="display: none;" id="loadingIcon"></span>
                    <span id="generateText">✓ Generate & Issue Certificate</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass Laravel data to JavaScript
    const journalsDB = @json($journalsDB ?? []);
    const submissionData = {!! json_encode([
        'id' => $submission->id,
        'order_number' => $submission->order_number ?? $submission->id,
        'article_title' => $submission->article_title ?? '',
        'author_name' => $submission->authors->pluck('name')->implode(', ') ?: ($submission->corresponding_author_name ?? ''),
        'journal_id' => $submission->journal_id ?? ''
    ]) !!};

    // Translations
    const translations = {
        english: {
            title: 'Certificate of Acceptance',
            subtitle: 'Final Acceptance for Publication',
            intro: 'This is to certify that the manuscript submitted by:',
            conclusion: 'has been accepted for publication after rigorous peer review and meets the standards of academic excellence required by our journal.',
            author: 'Author(s):',
            articleTitle: 'Article Title:',
            submissionId: 'Submission ID:',
            journal: 'Journal:',
            issn: 'ISSN:',
            volume: 'Volume & Issue:',
            pubDate: 'Publication Date:',
            chiefEditor: 'Chief Editor',
            publisher: 'Publisher',
            issuedOn: 'Certificate issued on:'
        },
        arabic: {
            title: 'شهادة قبول نهائي',
            subtitle: 'قبول نهائي للنشر',
            intro: 'تشهد هذه الشهادة بأن البحث المقدم من:',
            conclusion: 'قد تم قبوله للنشر بعد تحكيم دقيق ويستوفي معايير التميز الأكاديمي المطلوبة من قبل مجلتنا.',
            author: 'المؤلف (المؤلفون):',
            articleTitle: 'عنوان البحث:',
            submissionId: 'رقم التسليم:',
            journal: 'المجلة:',
            issn: 'الرقم الدولي:',
            volume: 'المجلد والعدد:',
            pubDate: 'تاريخ النشر:',
            chiefEditor: 'رئيس التحرير',
            publisher: 'الناشر',
            issuedOn: 'تاريخ إصدار الشهادة:'
        }
    };

    let selectedLanguage = 'english';
    let selectedIssue = null;
    let selectedJournal = null;
    let currentTab = 'selection';
    let signatureFile = null;
    let stampFile = null;

    function selectLanguage(lang) {
        selectedLanguage = lang;

        document.getElementById('englishBtn').classList.toggle('active', lang === 'english');
        document.getElementById('arabicBtn').classList.toggle('active', lang === 'arabic');

        checkSelectionComplete();
    }

    function openModal() {
        document.getElementById('certificateModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('certificateModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        resetModal();
    }

    function resetModal() {
        currentTab = 'selection';
        selectedIssue = null;
        selectedLanguage = 'english';
        signatureFile = null;
        stampFile = null;

        document.getElementById('nextToIssueBtn').disabled = true;
        document.getElementById('confirmBtn').disabled = true;
        document.getElementById('selectedInfo').innerHTML = 'No journal and language selected';

        document.getElementById('englishBtn').classList.add('active');
        document.getElementById('arabicBtn').classList.remove('active');

        switchTab('selection');
    }

    function updateJournalInfo() {
        const select = document.getElementById('journalSelect');
        const abbrev = select.value;

        if (!abbrev || !journalsDB[abbrev]) {
            document.getElementById('journalSection').style.display = 'none';
            document.getElementById('uploadSection').style.display = 'none';
            document.getElementById('nextToIssueBtn').disabled = true;
            selectedJournal = null;
            return;
        }

        selectedJournal = journalsDB[abbrev];

        document.getElementById('journalNameText').textContent = selectedJournal.name;
        document.getElementById('journalAbbrev').textContent = selectedJournal.abbrev;
        document.getElementById('journalISSN').textContent = selectedJournal.issn;
        // Check if elements exist before setting textContent to avoid errors if the detailed view isn't fully updated yet
        if(document.getElementById('journalISSNOnline')) document.getElementById('journalISSNOnline').textContent = selectedJournal.issn_online || '-';
        if(document.getElementById('journalImpactFactor')) document.getElementById('journalImpactFactor').textContent = selectedJournal.impact_factor || '-';

        document.getElementById('chiefEditor').textContent = selectedJournal.editor;

        // Pre-fill Editor Names
        document.getElementById('chiefEditorName').value = selectedJournal.editor || '';
        document.getElementById('chiefEditorNameAr').value = selectedJournal.editorAr || '';
        document.getElementById('managingEditorName').value = selectedJournal.managingEditor || '';
        document.getElementById('managingEditorNameAr').value = selectedJournal.managingEditorAr || '';

        // Update Previews
        const sigPreview = document.getElementById('signaturePreview');
        if(sigPreview) {
            sigPreview.innerHTML = selectedJournal.signature ? `<img src="${selectedJournal.signature}" class="preview-img">` : '';
            if(selectedJournal.signature) sigPreview.style.display = 'block';
        }

        const manSigPreview = document.getElementById('managingSignaturePreview');
        if(manSigPreview) {
            manSigPreview.innerHTML = selectedJournal.managing_signature ? `<img src="${selectedJournal.managing_signature}" class="preview-img">` : '';
            if(selectedJournal.managing_signature) manSigPreview.style.display = 'block';
        }

        const stampPreview = document.getElementById('stampPreview');
        if(stampPreview) {
            stampPreview.innerHTML = selectedJournal.stamp ? `<img src="${selectedJournal.stamp}" class="preview-img">` : '';
            if(selectedJournal.stamp) stampPreview.style.display = 'block';
        }

        document.getElementById('journalSection').style.display = 'block';
        document.getElementById('uploadSection').style.display = 'block';

        // Fetch issues via AJAX
        fetchJournalIssues(selectedJournal.id);

        checkSelectionComplete();
    }

    function fetchJournalIssues(journalId) {
        fetch(`/admin/submissions/journal/${journalId}/issues`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('issuesTableBody');
                tbody.innerHTML = '';

                const issues = data.data ? data.data.issues : (data.issues || []);

                issues.forEach((issue, index) => {
                    const row = document.createElement('tr');
                    const statusClass = (issue.status || 'unknown').toLowerCase().replace(' ', '-');
                    row.innerHTML = `
                        <td>${issue.volume || 'N/A'}</td>
                        <td>${issue.number || issue.issue || 'N/A'}</td>
                        <td><span class="status-badge status-${statusClass}">${issue.status || 'Unknown'}</span></td>
                        <td>${issue.articles_count || 0}</td>
                        <td>${issue.publication_date || 'Not set'}</td>
                        <td class="radio-cell">
                            ${issue.selectable !== false ?
                                `<input type="radio" name="issue" value="${issue.id}" data-index="${index}" onchange="enableConfirm()">` :
                                '—'
                            }
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Store issues for later use
                window.journalIssues = issues;
            })
            .catch(error => {
                console.error('Error fetching issues:', error);
                alert('Error loading journal issues. Please try again.');
            });
    }

    function checkSelectionComplete() {
        if (selectedJournal && selectedLanguage) {
            document.getElementById('nextToIssueBtn').disabled = false;
            const langText = selectedLanguage === 'english' ? 'English' : 'العربية';
            document.getElementById('selectedInfo').innerHTML = `
                Selected: <strong>${selectedJournal.abbrev}</strong> | Language: <strong>${langText}</strong>
            `;
        }
    }

    function handleFileUpload(input, type) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const reader = new FileReader();

        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size too large. Max 2MB.');
            input.value = '';
            return;
        }

        reader.onload = function(e) {
            let previewId = type + 'Preview'; // 'signaturePreview' or 'stampPreview'
            if (type === 'managing_signature') {
                previewId = 'managingSignaturePreview';
            }

            const previewDiv = document.getElementById(previewId);
            if (!previewDiv) {
                console.error('Preview div not found for:', previewId);
                return;
            }

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-img';

            previewDiv.innerHTML = '';
            previewDiv.appendChild(img);

            const fileName = document.createElement('div');
            fileName.className = 'file-name';
            fileName.textContent = file.name;
            previewDiv.appendChild(fileName);

            if (type === 'signature') {
                signatureFile = e.target.result;
            } else if (type === 'stamp') {
                stampFile = e.target.result;
            } else if (type === 'managing_signature') {
                // Should we store managing sig file here?
                // For logic's sake, we might not use this variable elsewhere in updateCertificatePreview but good to keep consistency if needed.
            }
        };
        reader.readAsDataURL(file);
    }

    function switchTab(tab) {
        currentTab = tab;

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        document.getElementById('nextToIssueBtn').style.display = 'none';
        document.getElementById('confirmBtn').style.display = 'none';
        document.getElementById('generateBtn').style.display = 'none';

        if (tab === 'selection') {
            document.querySelectorAll('.tab-btn')[0].classList.add('active');
            document.getElementById('selectionTab').classList.add('active');
            document.getElementById('nextToIssueBtn').style.display = 'inline-flex';
        } else if (tab === 'issue') {
            document.querySelectorAll('.tab-btn')[1].classList.add('active');
            document.getElementById('issueTabContent').classList.add('active');
            document.getElementById('confirmBtn').style.display = 'inline-flex';
        } else if (tab === 'preview') {
            document.querySelectorAll('.tab-btn')[2].classList.add('active');
            document.getElementById('previewTabContent').classList.add('active');
            document.getElementById('generateBtn').style.display = 'inline-flex';
            updateCertificatePreview();
        }
    }

    function proceedToIssue() {
        if (!selectedJournal || !selectedLanguage) return;

        document.getElementById('issueTab').disabled = false;
        switchTab('issue');
    }

    function enableConfirm() {
        if (!selectedJournal) return;

        const selected = document.querySelector('input[name="issue"]:checked');
        if (selected && window.journalIssues) {
            const issueId = parseInt(selected.value);
            selectedIssue = window.journalIssues.find(issue => issue.id === issueId);

            document.getElementById('confirmBtn').disabled = false;
            const langText = selectedLanguage === 'english' ? 'English' : 'العربية';
            document.getElementById('selectedInfo').innerHTML = `
                <strong>${selectedJournal.abbrev}</strong> - Vol ${selectedIssue.volume}, Issue ${selectedIssue.number || selectedIssue.issue} | <strong>${langText}</strong>
            `;
        }
    }

    function proceedToPreview() {
        if (!selectedIssue || !selectedJournal) return;

        document.getElementById('previewTab').disabled = false;
        // updateCertificatePreview();
        switchTab('preview');
    }

    function updateCertificatePreview() {
        // Logic removed as preview is now server-side generated
        console.log('Preview update skipped - Server Side Generation active');
    }



    function generateCertificate() {
        const btn = document.getElementById('generateBtn');
        const loadingIcon = document.getElementById('loadingIcon');
        const generateText = document.getElementById('generateText');

        if (!selectedIssue) {
            alert('Please select an Issue first.');
            return;
        }

        btn.disabled = true;
        loadingIcon.style.display = 'inline-block';
        generateText.textContent = 'Generating PDF...';

        const formData = new FormData();
        formData.append('submission_id', submissionData.id);
        formData.append('journal_abbrev', selectedJournal.abbrev || selectedJournal.slug || selectedJournal.id); // Use slug/id/abbrev consistently
        formData.append('volume', selectedIssue.volume);
        formData.append('issue', selectedIssue.number || selectedIssue.issue);
        formData.append('publication_date', selectedIssue.publication_date);
        formData.append('language', selectedLanguage);

        // Send full journal name if available
        const journalName = (selectedLanguage === 'arabic') ? (selectedJournal.nameAr || selectedJournal.name) : selectedJournal.name;
        formData.append('journal_name', journalName);

        // New Fields
        const doi = document.getElementById('doiInput').value;
        if(doi) formData.append('doi', doi);

        const chiefName = document.getElementById('chiefEditorName').value;
        if(chiefName) formData.append('chief_editor_name', chiefName);

        const chiefNameAr = document.getElementById('chiefEditorNameAr').value;
        if(chiefNameAr) formData.append('chief_editor_name_ar', chiefNameAr);

        const managingName = document.getElementById('managingEditorName').value;
        if(managingName) formData.append('managing_editor_name', managingName);

        const managingNameAr = document.getElementById('managingEditorNameAr').value;
        if(managingNameAr) formData.append('managing_editor_name_ar', managingNameAr);

        const sigInput = document.getElementById('signatureInput');
        if (sigInput.files[0]) {
            formData.append('signature_file', sigInput.files[0]);
        }

        const managingSigInput = document.getElementById('managingSignatureInput');
        if (managingSigInput.files[0]) {
            formData.append('managing_editor_signature_file', managingSigInput.files[0]);
        }

        const stampInput = document.getElementById('stampInput');
        if (stampInput.files[0]) {
            formData.append('stamp_file', stampInput.files[0]);
        }

        // CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/api/certificates/generate', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success!
                // Remove loading icon
                btn.disabled = false;
                loadingIcon.style.display = 'none';
                generateText.textContent = 'Generate Certificate';

                // Open View URL
                if (data.view_url) {
                    window.open(data.view_url, '_blank');
                    // Reload to update status?
                    // setTimeout(() => location.reload(), 2000);
                    // Close modal??
                    closeModal();
                } else {
                     alert('Certificate Generated Successfully! (URL not returned)');
                }
            } else {
                alert('Error generating certificate: ' + (data.message || 'Unknown error'));
                resetBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please check console.');
            resetBtn();
        });

        function resetBtn() {
            btn.disabled = false;
            loadingIcon.style.display = 'none';
            generateText.textContent = 'Generate & Download';
        }
    }

    function printCertificate() {
        window.print();
    }

    window.onclick = function(event) {
        const modal = document.getElementById('certificateModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if($submission->journal_id)
            updateJournalInfo();
        @endif
    });
</script>

@endsection

