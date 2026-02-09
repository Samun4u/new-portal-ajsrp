<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شهادة قبول المخطوطة</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;600;800;900&family=Tajawal:wght@400;700;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: #0a1628;
            --deep-ink: #162238;
            --muted-ink: #4a5568;
            --paper: #fefdfb;
            --accent: #d4af37;
            --accent-deep: #b8941f;
            --seal: #1a2f4a;
            --border: rgba(10, 22, 40, 0.15);
            --highlight: #f0e6d2;
        }

        @page {
            margin: 0px;
        }

        body,
        html,
        .container,
        div,
        section,
        article {
            page-break-inside: avoid !important;
            margin: 0px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            page-break-inside: avoid !important;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
            break-inside: avoid !important;
            break-after: avoid !important;
            break-before: avoid !important;
        }

        body {
            font-family: 'Cormorant Garamond', serif;
            background: linear-gradient(135deg, #e8edf2 0%, #f5f7fa 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            color: var(--ink);
        }

        .certificate-wrapper {
            max-width: 1100px;
            width: 100%;
            position: relative;
        }

        .certificate-container {
            background: var(--paper);
            padding: 80px 90px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.12),
                0 8px 16px rgba(0, 0, 0, 0.08);
            position: relative;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        /* Ornate corner decorations */
        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 3px solid var(--accent);
            opacity: 0.4;
        }

        .corner-decoration.top-left {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }

        .corner-decoration.top-right {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }

        .corner-decoration.bottom-left {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }

        .corner-decoration.bottom-right {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-family: 'Playfair Display', serif;
            font-size: 120px;
            font-weight: 900;
            color: var(--accent);
            opacity: 0.03;
            pointer-events: none;
            z-index: 1;
            letter-spacing: 8px;
        }

        .content-layer {
            position: relative;
            z-index: 2;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 30px;
        }

        .emblem {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border: 3px solid var(--accent);
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ffffff 0%, #fefdfb 100%);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
            position: relative;
        }

        .emblem::before {
            content: '';
            position: absolute;
            inset: 8px;
            border: 1px solid var(--accent);
            border-radius: 50%;
            opacity: 0.4;
        }

        .emblem-inner {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 32px;
            color: var(--deep-ink);
            letter-spacing: 2px;
        }

        .journal-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--deep-ink);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .journal-subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted-ink);
            margin-bottom: 16px;
        }

        .issn {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            color: var(--muted-ink);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            text-align: center;
            margin: 50px 0 30px;
            letter-spacing: 2px;
            color: var(--deep-ink);
            text-transform: uppercase;
            position: relative;
        }

        .certificate-title::after {
            content: '';
            display: block;
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            margin: 20px auto 0;
        }

        .certificate-number {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent-deep);
            letter-spacing: 2px;
            margin-bottom: 35px;
        }

        .content {
            font-size: 19px;
            line-height: 1.9;
            color: var(--ink);
            text-align: justify;
            font-weight: 600;
        }

        .content p {
            margin-bottom: 22px;
        }

        .highlight {
            font-weight: 700;
            color: var(--deep-ink);
            background: linear-gradient(180deg, transparent 60%, var(--highlight) 60%);
            padding: 0 4px;
        }

        .manuscript-details {
            margin: 40px 0;
            padding: 30px;
            border: 2px solid var(--accent);
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.03) 0%, rgba(212, 175, 55, 0.01) 100%);
            position: relative;
        }

        .manuscript-details::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
        }

        .manuscript-details h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--accent-deep);
            margin-bottom: 20px;
            text-align: center;
        }

        .detail-grid {
            display: grid;
            gap: 16px;
        }

        .detail-item {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 20px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            font-size: 17px;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: var(--muted-ink);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-value {
            color: var(--ink);
            font-weight: 600;
        }

        .verification-box {
            background: var(--paper);
            border: 2px dashed var(--accent);
            padding: 20px;
            margin: 35px 0;
            text-align: center;
        }

        .verification-box h4 {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent-deep);
            margin-bottom: 16px;
        }

        .qr-code-container {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .qr-code {
            width: 190px;
            height: 190px;
            border: 3px solid var(--accent);
            padding: 8px;
            background: white;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* SVG Sizing */
        .qr-code>svg {
            width: 100%;
            height: 100%;
        }

        .verification-url {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            color: var(--muted-ink);
            letter-spacing: 1px;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 40px;
            align-items: center;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid var(--accent);
        }

        .signature-block {
            text-align: center;
        }

        .sig-line {
            width: 100%;
            max-width: 280px;
            margin: 0 auto 12px;
            border-bottom: 2px solid var(--deep-ink);
            height: 60px;
            position: relative;
        }

        /* .sig-line::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 50px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><path d="M10,35 Q15,25 25,30 T45,35 Q50,30 58,32 Q65,34 70,28 Q75,22 82,30 L90,35 Q95,38 102,35 Q108,32 115,35 T135,32 Q142,30 148,35 Q153,40 160,35 Q165,30 172,33 Q178,36 185,32" stroke="%231a2f4a" stroke-width="2.5" fill="none" stroke-linecap="round"/><path d="M15,45 Q25,42 35,45 T55,43 Q65,41 75,45 Q82,48 90,45 T110,43 Q120,41 130,44 Q138,47 145,44 T165,45 Q172,46 180,43" stroke="%231a2f4a" stroke-width="1.8" fill="none" opacity="0.7" stroke-linecap="round"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.7;
        } */

        .signature-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--deep-ink);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-title {
            font-size: 14px;
            color: var(--muted-ink);
            font-style: italic;
            line-height: 1.5;
        }

        .official-seal {
            width: 160px;
            height: 160px;
            border: 4px solid var(--accent);
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1), transparent 70%);
            position: relative;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .official-seal::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 2px dashed var(--accent);
            border-radius: 50%;
            opacity: 0.5;
        }

        .seal-content {
            text-align: center;
        }

        .seal-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.4;
            color: var(--deep-ink);
        }

        .seal-year {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 900;
            margin-top: 8px;
            color: var(--accent-deep);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--muted-ink);
            line-height: 1.8;
        }

        .footer-item {
            margin: 6px 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .issue-date {
            margin-top: 16px;
            font-style: italic;
            color: var(--accent-deep);
            font-weight: 600;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .certificate-container {
                box-shadow: none;
                padding: 60px;
            }
        }

        @media (max-width: 900px) {
            .certificate-container {
                padding: 50px 40px;
            }

            .certificate-title {
                font-size: 38px;
            }

            .journal-title {
                font-size: 28px;
            }

            .content {
                font-size: 17px;
            }

            .detail-item {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .signature-section {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .sig-line {
                width: 240px;
            }

            .corner-decoration {
                width: 50px;
                height: 50px;
            }
        }

        /* Security features */
        .security-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                repeating-linear-gradient(45deg,
                    transparent,
                    transparent 10px,
                    rgba(212, 175, 55, 0.01) 10px,
                    rgba(212, 175, 55, 0.01) 20px);
            pointer-events: none;
            z-index: 0;
        }

        /* Holographic effect */
        .holographic-strip {
            position: absolute;
            top: 35%;
            right: -10px;
            width: 15px;
            height: 30%;
            background: linear-gradient(180deg,
                    rgba(212, 175, 55, 0.1) 0%,
                    rgba(212, 175, 55, 0.3) 50%,
                    rgba(212, 175, 55, 0.1) 100%);
            transform: skewY(-5deg);
            opacity: 0.4;
        }

        .action-bar {
            position: fixed;
            top: 20px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 20px;
            z-index: 9999;
            padding: 0 20px;
        }

        .action-bar.bottom {
            top: auto;
            bottom: 20px;
        }

        .print-btn {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .print-btn:hover {
            background-color: var(--accent-deep);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        /* Prevent page breaks inside specific elements */
        .no-page-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Prevent page breaks after elements */
        .no-break-after {
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Prevent page breaks before elements */
        .no-break-before {
            page-break-before: avoid;
            break-before: avoid;
        }

        /* For the entire certificate */
        .certificate-container {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    </style>
</head>

<body>

    @if (!isset($isPdf) || !$isPdf)
        <div class="action-bar bottom" style="text-align: center; margin: 20px;">
            <button onclick="downloadAsPdf()" id="downloadBtn" class="print-btn"
                style="text-decoration: none; display: inline-flex; justify-content: center; align-items: center; font-size: 16px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="margin-left: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span id="btnText">تنزيل الشهادة (PDF)</span>
            </button>
        </div>

        <form id="pdfForm" action="{{ route('certificates.download.image') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="image" id="imageData">
            <input type="hidden" name="certificate_number" value="{{ $certNum }}">
            <input type="hidden" name="width" id="imgWidth">
            <input type="hidden" name="height" id="imgHeight">
        </form>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
            function downloadAsPdf() {
                const btn = document.getElementById('downloadBtn');
                const btnText = document.getElementById('btnText');
                const originalText = btnText.innerText;

                btn.disabled = true;
                btnText.innerText = 'جاري إنشاء ملف PDF...';

                const element = document.querySelector('.certificate-container');

                // Use html2canvas options for better quality
                html2canvas(element, {
                    scale: 2, // higher scale for better resolution
                    useCORS: true, // allow cross-origin images
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    onclone: (clonedDoc) => {
                        // Ensure cloned element is visible and styled for capture
                        const clonedElement = clonedDoc.querySelector('.certificate-container');
                        clonedElement.style.margin = '0';
                        clonedElement.style.transform = 'none';
                    }
                }).then(canvas => {
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.9); // Use JPEG for slightly smaller size if needed
                    document.getElementById('imageData').value = dataUrl;
                    document.getElementById('imgWidth').value = canvas.width;
                    document.getElementById('imgHeight').value = canvas.height;
                    document.getElementById('pdfForm').submit();

                    setTimeout(() => {
                        btn.disabled = false;
                        btnText.innerText = originalText;
                    }, 3000);
                }).catch(err => {
                    console.error(err);
                    alert('Error generating PDF');
                    btn.disabled = false;
                    btnText.innerText = originalText;
                });
            }
        </script>
    @endif

    <div class="certificate-wrapper" id="certificate-content">
        <div class="certificate-container">
            <div class="security-pattern"></div>
            <div class="holographic-strip"></div>
            <div class="watermark">رسمي</div>

            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>

            <div class="content-layer">
                <div class="header">
                    <div class="emblem">
                        <div class="emblem-inner">IA</div>
                    </div>
                    <h1 class="journal-title">{{ $journal->title ?? ($journal->name ?? 'Journal') }}</h1>
                    <div class="journal-subtitle">منشور أكاديمي محكّم</div>
                    <div class="issn">ISSN: {{ $journal->issn ?? '' }}</div>
                </div>

                <h2 class="certificate-title">شهادة قبول مخطوطة بحثية</h2>
                <div class="certificate-number">CERTIFICATE NO: {{ $certNum }}</div>

                <div class="content">
                    <p>
                        تشهد هذه الوثيقة رسمياً بأن المخطوطة البحثية الموسومة بـ
                        <strong>"{{ $certificate->paper_title }}"</strong> قد تم
                        قبولها للنشر في <strong>{{ $journal->title ?? ($journal->name ?? 'Journal') }}</strong>،
                        وذلك بعد خضوعها لإجراءات التحكيم العلمي الدقيق والتقييم التحريري المتخصص.
                    </p>

                    <p>
                        بناءً على التقارير الشاملة للمحكمين وقرار هيئة التحرير بالإجماع، فقد تبيّن أن المخطوطة
                        تستوفي أعلى المعايير العلمية والمنهجية والأخلاقية المتبعة في المجلة.
                        لا حاجة لإجراء أية مراجعات علمية جوهرية إضافية، وستنتقل المخطوطة مباشرةً إلى مرحلة الإنتاج
                        للمراجعة اللغوية والتنضيد والتنسيق النهائي.
                    </p>

                    <div class="manuscript-details">
                        <h3>سجل المخطوطة الرسمي</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">رقم المخطوطة</span>
                                <span class="detail-value">{{ $certificate->client_order_id }}</span>
                            </div>
                            @if ($doi)
                                <div class="detail-item">
                                    <span class="detail-label">DOI</span>
                                    <span class="detail-value">{{ $doi ?? 'N/A' }}</span>
                                </div>
                            @endif
                            <div class="detail-item">
                                <span class="detail-label">الباحثون</span>
                                <span class="detail-value">{{ $certificate->author_names }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">تاريخ القبول</span>
                                <span class="detail-value">{{ $date }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">النشر المتوقع</span>
                                <span class="detail-value">المجلد {{ $volume }}، العدد
                                    {{ $issue }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="verification-box">
                        <h4>امسح رمز الاستجابة السريعة للتحقق من الشهادة</h4>
                        <div class="qr-code-container">
                            <div class="qr-code" id="qrcode"></div>
                        </div>
                        {{-- <div class="verification-url">{{ $verifyUrl }}</div> --}}
                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                    <script>
                        new QRCode(document.getElementById("qrcode"), {
                            text: "{{ $verifyUrl }}",
                            width: 120,
                            height: 120,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    </script>

                    <p>
                        سيتلقى الباحث المراسل تعليمات تفصيلية بشأن اتفاقيات نقل حقوق النشر
                        وخيارات ترخيص المؤلف ومراجعة البروفات وسياسات النشر مفتوح الوصول. أما رسوم النشر
                        إن وُجدت، فسيتم إبلاغ الباحث بها بشكل منفصل وفقاً لاتفاقية المؤلف المعتمدة في المجلة.
                    </p>

                    <p>
                        تُعد هذه الشهادة وثيقة رسمية لإثبات قبول المخطوطة البحثية، ويمكن استخدامها للأغراض الأكاديمية
                        والمهنية والمؤسسية. تبقى الشهادة سارية المفعول شريطة إتمام جميع متطلبات النشر بنجاح
                        والالتزام بالمعايير الأخلاقية للنشر المعتمدة في المجلة.
                    </p>
                </div>

                <div class="signature-section">
                    <div class="signature-block">
                        <div class="sig-line">
                            @if ($signatureImage)
                                <img src="{{ $signatureImage }}" style="max-height: 50px; margin-top: 5px;">
                            @endif
                        </div>
                        <div class="signature-name">{{ $chiefEditor }}</div>
                        <div class="signature-title">رئيس التحرير</div>
                        <div class="signature-title">{{ $journal->title ?? 'Journal' }}</div>
                    </div>

                    <div class="official-seal">
                        @if ($stampImage)
                            <img src="{{ $stampImage }}"
                                style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                        @else
                            <div class="seal-content">
                                <div class="seal-text">الختم<br>الأكاديمي<br>الرسمي</div>
                                <div class="seal-year">{{ date('Y') }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="signature-block">
                        <div class="sig-line">
                            @if (isset($managingSignatureImage) && $managingSignatureImage)
                                <img src="{{ $managingSignatureImage }}" style="max-height: 50px; margin-top: 5px;">
                            @endif
                        </div>
                        <div class="signature-name">{{ $managingEditor ?? 'مدير التحرير' }}</div>
                        <div class="signature-title">مدير التحرير</div>
                        <div class="signature-title">هيئة التحرير</div>
                    </div>
                </div>

                <div class="footer">
                    <div class="footer-item">الناشر: دار النشر الدولية الأكاديمية</div>
                    <div class="footer-item">مكتب التحرير: ٤٢٥ ريسيرتش باركواي، كامبريدج، ماساتشوستس ٠٢١٣٨، الولايات
                        المتحدة</div>
                    <div class="footer-item">البريد الإلكتروني: editorial@ijars-journal.org | الموقع:
                        www.ijars-journal.org</div>
                    <div class="footer-item issue-date">
                        تاريخ إصدار الشهادة: {{ $date }}
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
