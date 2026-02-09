<!DOCTYPE html>
<html lang="{{ $language == 'arabic' ? 'ar' : 'en' }}" dir="{{ $language == 'arabic' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $language == 'arabic' ? 'شهادة قبول نهائي' : 'Certificate of Acceptance' }}</title>
    <style>
        @font-face {
            font-family: 'arabicfont';
            src: url('{{ public_path('fonts/arial.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'arabicfont', sans-serif;
            background: white;
            padding: 0;
            color: #2c3e50;
        }

        .certificate-container {
            width: 100%;
            height: 100%;
            padding: 40px;
            position: relative;
            background: #fff;
        }

        .certificate-border {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 4px double #007bff;
            pointer-events: none;
        }

        .inner-border {
            border: 1px solid #dee2e6;
            height: 100%;
            padding: 40px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            margin-bottom: 15px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .content {
            margin-top: 40px;
            font-size: 16px;
            line-height: 1.8;
            text-align: {{ $language == 'arabic' ? 'right' : 'left' }};
        }

        .center-text {
            text-align: center;
        }

        .details-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .detail-row {
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            min-width: 150px;
        }

        .value {
            color: #2c3e50;
        }

        .footer {
            margin-top: 60px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            position: relative;
        }

        .signature-line {
            width: 90%;
            border-top: 1px solid #2c3e50;
            margin: 5px auto;
        }

        .signature-img {
            max-width: 180px;
            max-height: 70px;
            display: block;
            margin: 0 auto 5px auto;
        }

        .stamp-img {
            position: absolute;
            width: 100px;
            opacity: 0.7;
            z-index: 10;
            top: -30px;
            left: 50%;
            margin-left: -50px;
        }

        .issue-date {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }

        .arabic {
            direction: rtl;
        }
    </style>
</head>
<body class="{{ $language == 'arabic' ? 'arabic' : '' }}">
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="inner-border">
                <div class="header">
                    @if(isset($logo))
                        <img src="{{ $logo }}" class="logo">
                    @else
                        <div style="font-size: 48px; color: #007bff; margin-bottom: 15px;">🎓</div>
                    @endif
                    <h1 class="title">{{ $language == 'arabic' ? 'شهادة قبول نهائي' : 'Certificate of Acceptance' }}</h1>
                    <p class="subtitle">{{ $language == 'arabic' ? 'قبول نهائي للنشر' : 'Final Acceptance for Publication' }}</p>
                </div>

                <div class="content">
                    <p class="center-text">
                        {{ $language == 'arabic' ? 'تشهد هذه الشهادة بأن البحث المقدم من:' : 'This is to certify that the manuscript submitted by:' }}
                    </p>

                    <div class="details-box">
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'المؤلف (المؤلفون):' : 'Author(s):' }}</span>
                            <span class="value">{{ $author_names }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'عنوان البحث:' : 'Article Title:' }}</span>
                            <span class="value">"{{ $paper_title }}"</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'رقم التسليم:' : 'Submission ID:' }}</span>
                            <span class="value">{{ $client_order_id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'المجلة:' : 'Journal:' }}</span>
                            <span class="value">{{ $journal_name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'المجلد والعدد:' : 'Volume & Issue:' }}</span>
                            <span class="value">{{ $volume }}, {{ $issue }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">{{ $language == 'arabic' ? 'تاريخ النشر المتوقع:' : 'Expected Publication Date:' }}</span>
                            <span class="value">{{ $publication_date }}</span>
                        </div>
                    </div>

                    <p class="center-text">
                        {{ $language == 'arabic' ? 'قد تم قبوله للنشر بعد تحكيم دقيق ويستوفي معايير التميز الأكاديمي المطلوبة من قبل مجلتنا.' : 'has been accepted for publication after rigorous peer review and meets the standards of academic excellence required by our journal.' }}
                    </p>
                </div>

                <div class="footer">
                    <table class="signature-table" style="direction: ltr !important;">

                        <tr>
                            <td class="signature-cell">
                                @if(isset($signature_path))
                                    <img src="{{ public_path($signature_path) }}" class="signature-img">
                                @else
                                    <div style="height: 80px;"></div>
                                @endif
                                <div class="signature-line"></div>
                                <div style="font-weight: bold;">{{ $chief_editor }}</div>
                                <div style="font-size: 13px; color: #6c757d;">{{ $language == 'arabic' ? 'رئيس التحرير' : 'Chief Editor' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div style="height: 80px;"></div>
                                <div class="signature-line"></div>
                                <div style="font-weight: bold;">AJSRP</div>
                                <div style="font-size: 13px; color: #6c757d;">{{ $language == 'arabic' ? 'الناشر' : 'Publisher' }}</div>

                                @if(isset($stamp_path))
                                    <img src="{{ public_path($stamp_path) }}" class="stamp-img">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="issue-date">
                    {{ $language == 'arabic' ? 'تاريخ إصدار الشهادة:' : 'Certificate issued on:' }} <strong>{{ $issue_date }}</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
