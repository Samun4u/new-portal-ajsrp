@extends('admin.layouts.app')

@section('content')
<style>
    /* Scoped styles from user's premium HTML template */
    .premium-container {
        max-width: 1200px;
        margin: 0 auto;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 30px;
        height: 100vh;
    }

    /* Modal-like structure for the generator */
    .generator-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        margin-top: 20px;
        position: relative;
        z-index: 10;
    }

    /* Force nice-select to show on top and prevent cutting */
    .nice-select .list {
        z-index: 9999 !important;
    }

    .generator-body {
        padding: 30px;
        overflow: visible !important;
    }

    .generator-header {
        padding: 25px 30px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .generator-body {
        padding: 30px;
    }

    /* Tab Navigation - Premium Style */
    .tab-buttons {
        display: flex;
        gap: 5px;
        margin-bottom: 30px;
        border-bottom: 1px solid #dee2e6;
    }

    .tab-btn {
        padding: 12px 25px;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        cursor: pointer;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s;
        font-size: 14px;
    }

    .tab-btn.active {
        background: white;
        color: #007bff;
        border-bottom: 3px solid #007bff;
        z-index: 2;
        margin-bottom: -1px;
    }

    .tab-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Language Control */
    .language-selector {
        background: #fff3cd;
        border: 2px solid #ffc107;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .lang-btn {
        padding: 8px 20px;
        border: 2px solid #ffc107;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lang-btn.active {
        background: #ffc107;
        color: white;
    }

    /* Journal Info */
    .journal-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 4px solid #007bff;
    }

    /* Upload Cards */
    .upload-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .upload-card {
        background: white;
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }

    .preview-placeholder {
        height: 100px;
        border: 2px dashed #dee2e6;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        margin-bottom: 10px;
        background: #fcfcfc;
    }

    .preview-placeholder img {
        max-height: 90px;
        max-width: 90%;
    }

    /* Certificate Preview Container */
    .certificate-preview-wrap {
        background: #eeeeee;
        padding: 40px;
        border-radius: 8px;
        overflow-x: auto;
    }

    /* This part matches the exact A4 landscape proportions */
    .certificate-preview {
        width: 1040px;
        background: white;
        padding: 60px;
        position: relative;
        margin: 0 auto;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        background-image: url('{{ asset("assets/images/certificate_bg_pattern.png") }}'); /* Optional pattern */
        background-repeat: repeat;
    }

    .certificate-border {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 4px double #007bff;
        pointer-events: none;
    }

    .certificate-content {
        text-align: center;
        position: relative;
        z-index: 10;
    }

    .cert-logo {
        font-size: 50px;
        margin-bottom: 20px;
    }

    .cert-title {
        font-size: 32px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cert-subtitle {
        font-size: 18px;
        color: #6c757d;
        margin-bottom: 25px;
    }

    .cert-body {
        max-width: 800px;
        margin: 0 auto;
        font-size: 16px;
        line-height: 1.8;
        text-align: left;
    }

    .cert-body.arabic {
        text-align: right;
        direction: rtl;
    }

    .cert-details-box {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin: 25px 0;
        text-align: left;
        border: 1px solid #e9ecef;
    }

    .cert-details-box.arabic {
        text-align: right;
        direction: rtl;
    }

    .detail-row {
        display: flex;
        margin-bottom: 8px;
        gap: 15px;
    }

    .detail-label {
        font-weight: 700;
        min-width: 150px;
        color: #343a40;
    }

    .cert-footer {
        display: flex;
        justify-content: space-around;
        margin-top: 50px;
        padding-top: 20px;
    }

    .signature-block {
        text-align: center;
        width: 200px;
        position: relative;
    }

    .signature-line {
        border-top: 1px solid #2c3e50;
        margin: 5px 0;
    }

    .signature-image {
        height: 80px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        margin-bottom: 5px;
    }

    .signature-image img {
        max-height: 80px;
        max-width: 180px;
    }

    .stamp-image {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 110px;
        z-index: 5;
        opacity: 0.8;
    }

    .stamp-image img {
        width: 100%;
    }

    .cert-issued-date {
        margin-top: 40px;
        font-size: 14px;
        color: #6c757d;
    }

    /* Buttons */
    .generator-footer {
        padding: 20px 30px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .action-group {
        display: flex;
        gap: 10px;
    }

    @media print {
        body * { visibility: hidden; }
        .certificate-preview-wrap, .certificate-preview-wrap * { visibility: visible; }
        .certificate-preview-wrap { position: absolute; left: 0; top: 0; padding: 0; }
        .certificate-preview { box-shadow: none; margin: 0; border: none; }
    }
</style>

<div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
    <div class="p-sm-30 p-15">
        <div class="premium-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fs-22 fw-700 text-title-black"><i class="fa fa-certificate text-primary"></i> {{__($pageTitle)}}</h4>
                <a href="{{ route('admin.certificate.final.list') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to List</a>
            </div>

            <div class="generator-card">
                <div class="generator-header">
                    <div class="d-flex align-items-center gap-3 w-50">
                        <label for="orderSelect" class="zForm-label mb-0 overflow-hidden text-nowrap">Select Client Order:</label>
                        <select class="sf-select-without-search" id="orderSelect">
                            <option value="">{{ __('--- Choose Order ---') }}</option>
                            @foreach ($orderList as $order)
                                <option value="{{$order->order_id}}">{{$order->order_id.' ('.getEmailByUserId($order->client_id).')'}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="orderStatus"></div>
                </div>

                <div class="generator-body">
                    <!-- Progress Tabs -->
                    <div class="tab-buttons" id="generatorTabs" style="display: none;">
                        <button class="tab-btn active" data-tab="selection">1. Journal & Language</button>
                        <button class="tab-btn" data-tab="issue" id="issueTabBtn" disabled>2. Issue Selection</button>
                        <button class="tab-btn" data-tab="preview" id="previewTabBtn" disabled>3. Preview & Generate</button>
                    </div>

                    <!-- Step 1: Selection -->
                    <div id="selectionTab" class="tab-content active">
                        <div class="language-selector">
                            <label class="fw-700 m-0">🌐 Certificate Language:</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="lang-btn active" data-lang="english">🇬🇧 English</button>
                                <button type="button" class="lang-btn" data-lang="arabic">🇸🇦 العربية</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="zForm-label">Journal Name</label>
                                <select id="journalSelect" class="form-control sf-select-without-search">
                                    <option value="">-- Choose target journal --</option>
                                    <option value="JEPS">Journal of Educational and Psychological Sciences</option>
                                    <option value="JEALS">Journal of Economic, Administrative and Legal Sciences</option>
                                    <option value="JHSS">Journal of Humanitys and Social Sciences</option>
                                    <option value="JNSLAS">Journal of natural sciences, life and applied sciences</option>
                                    <option value="JMPS">Journal of medical and pharmaceutical sciences</option>
                                    <option value="JESIT">Journal of engineering sciences and information technology</option>
                                    <option value="JAEVS">Journal of agricultural, environmental and veterinary sciences</option>
                                    <option value="JRCM">Journal of Risk and Crisis Management</option>
                                    <option value="JIS">Journal of Islamic Sciences</option>
                                    <option value="AJSRP">Arab Journal of Sciences & Research Publishing</option>
                                    <option value="JALSL">Journal of Arabic Language Sciences and Literature</option>
                                    <option value="JCTM">Journal of Curriculum and Teaching Methodology</option>
                                </select>
                            </div>
                        </div>

                        <div id="journalInfo" class="journal-section" style="display: none;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-700 mb-1" id="displayJournalName"></h5>
                                    <div class="fs-14 text-muted">
                                        <span class="badge bg-secondary" id="displayJournalAbbrev"></span>
                                        <span class="ms-2">ISSN: <strong id="displayISSN"></strong></span>
                                        <span class="ms-2">| Editor: <strong id="displayEditor"></strong></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="editJournalBtn">✏️ Edit Details</button>
                            </div>

                            <div id="editJournalFields" style="display: none;" class="mt-3 p-3 bg-white border rounded">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small fw-bold">Journal Name (EN)</label>
                                        <input type="text" id="inputJournalName" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold">Journal Name (AR)</label>
                                        <input type="text" id="inputJournalNameAr" class="form-control" dir="rtl">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold">ISSN</label>
                                        <input type="text" id="inputISSN" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold">Editor (EN)</label>
                                        <input type="text" id="inputEditor" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold">Editor (AR)</label>
                                        <input type="text" id="inputEditorAr" class="form-control" dir="rtl">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="signatureSection" style="display: none;">
                            <h6 class="fw-bold mb-3 mt-4">🎨 Signatures & Assets</h6>
                            <div class="upload-grid">
                                <div class="upload-card">
                                    <label class="small fw-bold d-block mb-2">Chief Editor Signature</label>
                                    <div id="signaturePreview" class="preview-placeholder">
                                        <span class="text-muted small">No Signature Uploaded</span>
                                    </div>
                                    <input type="file" id="signatureInput" class="d-none" accept="image/*">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('signatureInput').click()"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                                <div class="upload-card">
                                    <label class="small fw-bold d-block mb-2">Official Stamp</label>
                                    <div id="stampPreview" class="preview-placeholder">
                                        <span class="text-muted small">No Stamp Uploaded</span>
                                    </div>
                                    <input type="file" id="stampInput" class="d-none" accept="image/*">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('stampInput').click()"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Issue Selection -->
                    <div id="issueTab" class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-hover bd-one">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Volume</th>
                                        <th>Number / Issue</th>
                                        <th>Year / Year</th>
                                        <th>Date / التاريخ</th>
                                        <th class="text-center">Select</th>
                                    </tr>
                                </thead>
                                <tbody id="issueTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 3: Preview -->
                    <div id="previewTab" class="tab-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                                <button type="button" class="btn btn-sm btn-info text-white" id="downloadPdfBtn"><i class="fa fa-download"></i> Download PDF</button>
                            </div>
                            <div class="text-muted small">Preview generated based on your selections.</div>
                        </div>

                        <div class="certificate-preview-wrap">
                            <div class="certificate-preview" id="certPreviewArea">
                                <div class="certificate-border"></div>
                                <div class="certificate-content" id="certContentArea">
                                    <div class="cert-logo">🎓</div>
                                    <h1 class="cert-title" id="previewCertTitle">CERTIFICATE OF ACCEPTANCE</h1>
                                    <p class="cert-subtitle" id="previewCertSubtitle">Final Acceptance for Publication</p>

                                    <div class="cert-body" id="previewBody">
                                        <p id="previewIntro">This is to certify that the manuscript submitted by:</p>

                                        <div class="cert-details-box" id="previewDetailsBox">
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelAuthors">Author(s):</div>
                                                <div id="previewAuthors"></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelTitle">Article Title:</div>
                                                <div id="previewArticleTitle"></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelSubmission">Submission ID:</div>
                                                <div id="previewSubmissionId"></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelJournal">Journal:</div>
                                                <div id="previewJournal"></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelVolIssue">Volume & Issue:</div>
                                                <div id="previewVolIssue"></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label" id="labelPubDate">Publication Date:</div>
                                                <div id="previewPubDate"></div>
                                            </div>
                                        </div>

                                        <p id="previewConclusion">has been accepted for publication after rigorous peer review and meets the standards of academic excellence required by our journal.</p>
                                    </div>

                                    <div class="cert-footer" id="certFooter">
                                        <div class="signature-block">
                                            <div class="signature-image" id="previewSignature"></div>
                                            <div class="signature-line"></div>
                                            <div class="signature-name" id="previewEditorName"></div>
                                            <div class="signature-title" id="previewEditorTitle">Chief Editor</div>
                                        </div>
                                        <div class="signature-block">
                                            <div class="signature-image"></div>
                                            <div class="signature-line"></div>
                                            <div class="signature-name">AJSRP</div>
                                            <div class="signature-title" id="previewPublisherTitle">Publisher</div>
                                            <div class="stamp-image" id="previewStamp"></div>
                                        </div>
                                    </div>

                                    <div class="cert-issued-date">
                                        <span id="labelIssuedOn">Certificate issued on:</span> <strong id="previewIssueDate"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="generator-footer">
                    <div id="summaryText" class="text-muted small">Please select an order to begin.</div>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;"><i class="fa fa-chevron-left"></i> Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn" style="display: none;">Next: Select Issue <i class="fa fa-chevron-right"></i></button>

                        <form id="finalSubmitForm" action="{{route('admin.certificate.final.store')}}" method="POST" class="ajax reset" data-handler="commonResponseRedirect" data-redirect-url="{{route('admin.certificate.final.list')}}">
                            @csrf
                            <input type="hidden" name="client_order_id" id="hiddenOrderId">
                            <input type="hidden" name="author_names" id="hiddenAuthors">
                            <input type="hidden" name="author_affiliations" id="hiddenAffiliations">
                            <input type="hidden" name="paper_title" id="hiddenPaperTitle">
                            <input type="hidden" name="journal_name" id="hiddenJournalName">
                            <input type="hidden" name="volume" id="hiddenVolume">
                            <input type="hidden" name="issue" id="hiddenIssue">
                            <input type="hidden" name="date" id="hiddenDate">
                            <input type="hidden" name="language" id="hiddenLanguage">
                            <input type="hidden" name="chief_editor" id="hiddenEditor">
                            <input type="hidden" name="chief_editor_ar" id="hiddenEditorAr">
                            <input type="hidden" name="issn" id="hiddenISSN">
                            <input type="hidden" name="signature_url" id="hiddenSignatureUrl">
                            <input type="hidden" name="stamp_url" id="hiddenStampUrl">
                            <input type="hidden" name="pdf_file_base64" id="hiddenPdfBase64">

                            <button type="submit" class="btn btn-success" id="generateBtn" style="display: none;"><i class="fa fa-check-circle"></i> ✓ Generate & Issue Certificate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value='{{ route("admin.certificate.final.order-details") }}' id="orderDetailsUrl">

@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    const journalsDB = {
        'JEPS': { name: 'Journal of Educational and Psychological Sciences', nameAr: 'مجلة العلوم التربوية والنفسية', abbrev: 'JEPS', issn: '2522-3399', freq: 'Monthly', editor: 'Prof. Dr. Fahad Saleh Maghrib Al Maamari', editorAr: 'أ.د. فهد صالح مغربي المعمري' },
        'JEALS': { name: 'Journal of Economic, Administrative and Legal Sciences', nameAr: 'مجلة العلوم الاقتصادية والإدارية والقانونية', abbrev: 'JEALS', issn: '2522-3372', freq: 'Monthly', editor: 'Prof. Dr. Muhammad Al-Moataz Al-Mujtaba Ibrahim Taha', editorAr: 'أ.د. محمد المعتز المجتبى إبراهيم طه' },
        'JHSS': { name: 'Journal of Humanitys and Social Sciences', nameAr: 'مجلة العلوم الإنسانية والاجتماعية', abbrev: 'JHSS', issn: '2522-3380', freq: 'Monthly', editor: 'Prof. Dr. Samir Sheikh Ali', editorAr: 'أ.د. سمير الشيخ علي' },
        'JNSLAS': { name: 'Journal of natural sciences, life and applied sciences', nameAr: 'مجلة العلوم الطبيعية والحياتية والتطبيقية', abbrev: 'JNSLAS', issn: '2522-3356', freq: 'Quarterly', editor: 'Prof. Dr. Ibrahim Omar Saeed Al-Hamdani', editorAr: 'أ.د. إبراهيم عمر سعيد الحمداني' },
        'JMPS': { name: 'Journal of medical and pharmaceutical sciences', nameAr: 'مجلة العلوم الطبية والصيدلانية', abbrev: 'JMPS', issn: '2522-333X', freq: 'Quarterly', editor: 'Prof. Dr. Wansa Abdul-Azim Al-Husseini', editorAr: 'أ.د. ونسا عبدالعظيم الحسيني' },
        'JESIT': { name: 'Journal of engineering sciences and information technology', nameAr: 'مجلة العلوم الهندسية وتكنولوجيا المعلومات', abbrev: 'JESIT', issn: '2522-3321', freq: 'Quarterly', editor: 'Prof. Dr. Bassam Mahmoud El-Ajy', editorAr: 'أ.د. بسام محمود العجي' },
        'JAEVS': { name: 'Journal of agricultural, environmental and veterinary sciences', nameAr: 'مجلة العلوم الزراعية والبيئية والبيطرية', abbrev: 'JAEVS', issn: '2522-3364', freq: 'Quarterly', editor: 'Prof. Dr. Sherein Saeid Abdelgayed', editorAr: 'أ.د. شيرين سعيد عبدالجيد' },
        'JRCM': { name: 'Journal of Risk and Crisis Management', nameAr: 'مجلة إدارة المخاطر والأزمات', abbrev: 'JRCM', issn: '2664-6285', freq: 'Semi-annually', editor: 'Prof. Dr. Alakhder Abu Alaa Azzi', editorAr: 'أ.د. الأخضر أبو العلا عزي' },
        'JIS': { name: 'Journal of Islamic Sciences', nameAr: 'مجلة العلوم الإسلامية', abbrev: 'JIS', issn: '2664-4347', freq: 'Quarterly', editor: 'Prof. Dr. Hussein Abdel Aal Hussein Mohamed', editorAr: 'أ.د. حسين عبدالعال حسين محمد' },
        'AJSRP': { name: 'Arab Journal of Sciences & Research Publishing', nameAr: 'المجلة العربية للعلوم ونشر الأبحاث', abbrev: 'AJSRP', issn: '2518-5780', freq: 'Quarterly', editor: 'Prof. Dr. Fahad Saleh Maghrib Al Maamari', editorAr: 'أ.د. فهد صالح مغربي المعمري' },
        'JALSL': { name: 'Journal of Arabic Language Sciences and Literature', nameAr: 'مجلة علوم اللغة العربية وآدابها', abbrev: 'JALSL', issn: '2790-7317', freq: 'Quarterly', editor: 'Prof. Dr. Maher Fouad Al-Jabali', editorAr: 'أ.د. ماهر فؤاد الجبالي' },
        'JCTM': { name: 'Journal of Curriculum and Teaching Methodology', nameAr: 'مجلة المناهج وطرق التدريس', abbrev: 'JCTM', issn: '2790-7333', freq: 'Monthly', editor: 'Prof. Dr. Fawaz Hassan Shehadeh', editorAr: 'أ.د. فواز حسن شحادة' }
    };

    const translations = {
        english: {
            title: 'CERTIFICATE OF ACCEPTANCE',
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

    let state = {
        currentTab: 'selection', language: 'english',
        selectedOrder: null, submissionData: null,
        selectedJournal: null, selectedIssue: null,
        signatureUrl: null, stampUrl: null
    };

    $(document).ready(function() {
        // Order Selection
        $('#orderSelect').on('change', function() {
            const orderId = $(this).val();
            if (orderId) {
                $.ajax({
                    url: $('#orderDetailsUrl').val(),
                    data: { order_id: orderId },
                    success: function(res) {
                        state.selectedOrder = orderId;
                        state.submissionData = res;
                        $('#generatorTabs').fadeIn();
                        $('#orderStatus').html('<span class="badge bg-success">Order Ready</span>');
                        autoSelectJournal(res.journal_name);
                        switchTab('selection');
                        updateSummary();
                    }
                });
            } else {
                resetGenerator();
            }
        });

        // Navigation
        $('#nextBtn').on('click', function() {
            if (state.currentTab === 'selection') switchTab('issue');
            else if (state.currentTab === 'issue') switchTab('preview');
        });

        $('#prevBtn').on('click', function() {
            if (state.currentTab === 'issue') switchTab('selection');
            else if (state.currentTab === 'preview') switchTab('issue');
        });

        $('.tab-btn').on('click', function() {
            const tab = $(this).data('tab');
            if (!$(`.tab-btn[data-tab="${tab}"]`).prop('disabled')) switchTab(tab);
        });

        // Language
        $('.lang-btn').on('click', function() {
            state.language = $(this).data('lang');
            $('.lang-btn').removeClass('active');
            $(this).addClass('active');
            updateSummary();
            if (state.currentTab === 'preview') updatePreview();
        });

        // Journal
        $('#journalSelect').on('change', function() {
            const val = $(this).val();
            if (val) {
                state.selectedJournal = journalsDB[val];
                displayJournalInfo();
                loadIssues(val);
            } else {
                state.selectedJournal = null;
                $('#journalInfo, #signatureSection').hide();
            }
            updateSummary();
        });

        // Edit Journal
        $('#editJournalBtn').on('click', function() {
            const fields = $('#editJournalFields');
            if (fields.is(':visible')) {
                state.selectedJournal.name = $('#inputJournalName').val();
                state.selectedJournal.nameAr = $('#inputJournalNameAr').val();
                state.selectedJournal.issn = $('#inputISSN').val();
                state.selectedJournal.editor = $('#inputEditor').val();
                state.selectedJournal.editorAr = $('#inputEditorAr').val();
                displayJournalInfo();
                fields.hide();
                $(this).text('✏️ Edit Details');
            } else {
                $('#inputJournalName').val(state.selectedJournal.name);
                $('#inputJournalNameAr').val(state.selectedJournal.nameAr);
                $('#inputISSN').val(state.selectedJournal.issn);
                $('#inputEditor').val(state.selectedJournal.editor);
                $('#inputEditorAr').val(state.selectedJournal.editorAr);
                fields.show();
                $(this).text('💾 Save Details');
            }
        });

        $('#signatureInput').on('change', function(e) { readImage(e, 'signature'); });
        $('#stampInput').on('change', function(e) { readImage(e, 'stamp'); });

        $('#downloadPdfBtn').on('click', function() {
             generatePdfLocally().save();
        });

        $('#finalSubmitForm').on('submit', function(e) {
            if ($('#hiddenPdfBase64').val()) return true;
            e.preventDefault();
            const btn = $('#generateBtn');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating PDF...');

            populateForm();
            generatePdfLocally().outputPdf('datauristring').then(function(pdfBase64) {
                $('#hiddenPdfBase64').val(pdfBase64);
                btn.html('<i class="fa fa-check"></i> Saving...');
                setTimeout(() => { $('#finalSubmitForm').trigger('submit'); }, 150);
            }).catch(err => {
                btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> ✓ Generate & Issue Certificate');
                alert('PDF Generation failed: ' + err);
            });
        });
    });

    function populateForm() {
        $('#hiddenOrderId').val(state.selectedOrder);
        $('#hiddenAuthors').val(state.submissionData.author_names);
        $('#hiddenAffiliations').val(state.submissionData.author_affiliations);
        $('#hiddenPaperTitle').val(state.submissionData.paper_title);
        $('#hiddenJournalName').val(state.language === 'arabic' ? state.selectedJournal.nameAr : state.selectedJournal.name);
        $('#hiddenVolume').val(state.selectedIssue.volume);
        $('#hiddenIssue').val(state.selectedIssue.issue);
        $('#hiddenDate').val(state.selectedIssue.date);
        $('#hiddenLanguage').val(state.language);
        $('#hiddenEditor').val(state.language === 'arabic' ? state.selectedJournal.editorAr : state.selectedJournal.editor);
        $('#hiddenEditorAr').val(state.selectedJournal.editorAr);
        $('#hiddenISSN').val(state.selectedJournal.issn);
        $('#hiddenSignatureUrl').val(state.signatureUrl);
        $('#hiddenStampUrl').val(state.stampUrl);
    }

    function generatePdfLocally() {
        const element = document.getElementById('certPreviewArea');
        const opt = {
            margin: 0,
            filename: `certificate-${state.selectedOrder}.pdf`,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };
        return html2pdf().set(opt).from(element);
    }

    function switchTab(tabId) {
        state.currentTab = tabId;
        $('.tab-btn').removeClass('active');
        $(`.tab-btn[data-tab="${tabId}"]`).addClass('active');
        $('.tab-content').removeClass('active');
        $(`#${tabId}Tab`).addClass('active');

        $('#prevBtn').toggle(tabId !== 'selection');
        if (tabId === 'selection') {
            $('#nextBtn').show().text('Next: Select Issue →').prop('disabled', false);
            $('#generateBtn').hide();
        } else if (tabId === 'issue') {
            $('#nextBtn').show().text('Next: Preview & Generate →').prop('disabled', false);
            $('#generateBtn').hide();
        } else {
            $('#nextBtn').hide();
            $('#generateBtn').show();
            updatePreview();
        }
    }

    function updateSummary() {
        if (!state.selectedOrder) { $('#summaryText').text('Select an order to begin.'); return; }
        let t = `Order: <strong>#${state.selectedOrder}</strong>`;
        if (state.selectedJournal) t += ` | Journal: <strong>${state.selectedJournal.abbrev}</strong>`;
        if (state.language) t += ` | Language: <strong>${state.language.toUpperCase()}</strong>`;
        if (state.selectedIssue) t += ` | Issue: <strong>Vol ${state.selectedIssue.volume} No ${state.selectedIssue.issue}</strong>`;
        $('#summaryText').html(t);
        $('#issueTabBtn').prop('disabled', false);
        $('#previewTabBtn').prop('disabled', false);
    }

    function updatePreview() {
        const lang = state.language;
        const trans = translations[lang];
        const j = state.selectedJournal;
        const sub = state.submissionData;
        const issue = state.selectedIssue;

        $('#certPreviewArea').css('direction', lang === 'arabic' ? 'rtl' : 'ltr');
        $('#previewBody, #previewDetailsBox').toggleClass('arabic', lang === 'arabic');

        // Dynamic content based on user template
        $('#previewCertTitle').text(trans.title);
        $('#previewCertSubtitle').text(trans.subtitle);
        $('#previewIntro').text(trans.intro);
        $('#previewConclusion').text(trans.conclusion);

        // Labels
        $('#labelAuthors').text(trans.author);
        $('#labelTitle').text(trans.articleTitle);
        $('#labelSubmission').text(trans.submissionId);
        $('#labelJournal').text(trans.journal);
        $('#labelVolIssue').text(trans.volume);
        $('#labelPubDate').text(trans.pubDate);
        $('#labelIssuedOn').text(trans.issuedOn);

        // Dynamic Values
        $('#previewAuthors').text(sub.author_names);
        $('#previewArticleTitle').text(sub.paper_title);
        $('#previewSubmissionId').text(state.selectedOrder);
        $('#previewJournal').text(lang === 'arabic' ? j.nameAr : j.name);

        const volText = lang === 'arabic' ? 'المجلد ' : 'Volume ';
        const issText = lang === 'arabic' ? '، العدد ' : ', Issue ';
        $('#previewVolIssue').text(volText + issue.volume + issText + issue.issue);

        $('#previewPubDate').text(issue.date);
        $('#previewEditorName').text(lang === 'arabic' ? j.editorAr : j.editor);
        $('#previewEditorTitle').text(trans.chiefEditor);
        $('#previewPublisherTitle').text(trans.publisher);

        const today = new Date().toLocaleDateString(lang === 'arabic' ? 'ar-SA' : 'en-US', {
            year: 'numeric', month: 'long', day: 'numeric'
        });
        $('#previewIssueDate').text(today);

        if (state.signatureUrl) $('#previewSignature').html(`<img src="${state.signatureUrl}">`);
        else $('#previewSignature').html('<div class="text-muted small">Signature</div>');

        if (state.stampUrl) $('#previewStamp').html(`<img src="${state.stampUrl}">`);
        else $('#previewStamp').html('<div class="text-muted small">Stamp</div>');
    }

    function displayJournalInfo() {
        const j = state.selectedJournal;
        const lang = state.language;

        $('#displayJournalName').text(lang === 'arabic' ? j.nameAr : j.name);
        $('#displayJournalAbbrev').text(j.abbrev);
        $('#displayISSN').text(j.issn);
        $('#displayEditor').text(lang === 'arabic' ? j.editorAr : j.editor);

        $('#journalInfo, #signatureSection').fadeIn();
    }

    function readImage(e, type) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                if (type === 'signature') {
                    state.signatureUrl = ev.target.result;
                    $('#signaturePreview').html(`<img src="${ev.target.result}">`);
                } else {
                    state.stampUrl = ev.target.result;
                    $('#stampPreview').html(`<img src="${ev.target.result}">`);
                }
                if (state.currentTab === 'preview') updatePreview();
            };
            reader.readAsDataURL(file);
        }
    }

    function loadIssues(abbrev) {
        const issues = generateMockIssues(state.selectedJournal.freq);
        const tbody = $('#issueTableBody').empty();
        issues.forEach((issue, idx) => {
            const issueJson = JSON.stringify(issue).replace(/"/g, '&quot;');
            tbody.append(`
                <tr style="cursor: pointer" onclick="$(this).find('input').click()">
                    <td>Volume ${issue.volume}</td>
                    <td>Issue ${issue.issue}</td>
                    <td>${issue.year}</td>
                    <td>${issue.date}</td>
                    <td class="text-center">
                        <input type="radio" name="issue_select" onclick="selectIssue('${issueJson}')">
                    </td>
                </tr>
            `);
        });
    }

    function selectIssue(issueJson) {
        state.selectedIssue = JSON.parse(issueJson);
        updateSummary();
        $('#nextBtn').prop('disabled', false);
    }

    function autoSelectJournal(title) {
        for (let key in journalsDB) {
            if (title.toLowerCase().includes(journalsDB[key].name.toLowerCase()) ||
                title.toLowerCase().includes(key.toLowerCase()) ||
                title.toLowerCase().includes(journalsDB[key].abbrev.toLowerCase())) {
                $('#journalSelect').val(key).trigger('change');
                // Support NiceSelect if present
                if ($('#journalSelect').niceSelect) $('#journalSelect').niceSelect('update');
                break;
            }
        }
    }

    function generateMockIssues(freq) {
        const issues = []; const curYear = 2025;
        if (freq === 'Monthly') {
            for (let i = 1; i <= 12; i++) issues.push({ volume: '10', issue: i, year: curYear, date: `Issue ${i}, ${curYear}` });
        } else if (freq === 'Quarterly') {
            for (let i = 1; i <= 4; i++) issues.push({ volume: '8', issue: i, year: curYear, date: `Quarter ${i}, ${curYear}` });
        } else {
            issues.push({ volume: '5', issue: '1', year: curYear, date: `June, ${curYear}` }, { volume: '5', issue: '2', year: curYear, date: `December, ${curYear}` });
        }
        return issues;
    }

    function resetGenerator() {
        state = { currentTab: 'selection', language: 'english', selectedOrder: null, submissionData: null, selectedJournal: null, selectedIssue: null, signatureUrl: null, stampUrl: null };
        $('#generatorTabs, #journalInfo, #signatureSection').hide();
        $('.tab-content').removeClass('active'); $('#selectionTab').addClass('active');
        $('#orderStatus').empty();
        $('#summaryText').text('Select an order to begin.');
        $('#nextBtn, #prevBtn, #generateBtn').hide();
    }
</script>
@endpush
