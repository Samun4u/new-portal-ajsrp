<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Final Acceptance Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 10;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'arabicfont', sans-serif;
            background-color: #f8f9fa;
        }

        .certificate {
            border: 10px solid #343a40;
            padding: 50px 30px;
            max-width: 800px;
            background-color: #ffffff;
            text-align: center;
        }

        .certificate h1 {
            font-size: 2.2rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .certificate p {
            font-size: 1rem;
            font-weight: 300;
            color: #333;
            margin: 10px 0;
        }

        .certificate h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 15px 0;
        }

        .certificate h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 15px 0;
        }

        .certificate .journal-info {
            font-style: italic;
            color: #333;
            font-size: 1rem;
        }

        .certificate .signature {
            margin-top: 50px;
            font-weight: bold;
        }

        .authors-list {
            margin: 15px 0;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>

    <div class="certificate">
        @php
            $isLanguageArabic = false;
            if (selectedLanguage()->iso_code == 'ar') {
                $isLanguageArabic = true;
            }
        @endphp
        @if ($isLanguageArabic)
            <h1>{{ $headerOneStatic ?? 'شهادة القبول النهائي' }}</h1>
            <p>{{ $paraOneStatic ?? 'هذه الشهادة تمنح إلى' }}</p>
            <div class="authors-list">
                <h2>{{ $authors }}</h2>
            </div>
            <p>{{ $paraTwoStatic ?? 'من' }} {{ $affiliations }}</p>
            <p>{{ $paraThreeStatic ?? 'لنشر البحث العلمي المعنون' }}:</p>
            <h4>"{{ $paper_title }}"</h4>
            <p class="journal-info">
                {{ $journalInfoParaOneStatic ?? 'نشر في' }}
                {{ $journal_name }}{{ $acceptanceDateStatic ? ', ' . $acceptanceDateStatic . ': ' . $acceptance_date : '' }}
            </p>
            <div class="signature" style="text-align: left;">
                <h6>{{ $signatureParaStatic ?? 'رئيس هيئة التحرير' }}</h6>
                <h6>{{ $signature }}</h6>
            </div>
        @else
            <h1>Certificate of Final Acceptance</h1>
            <p>This is to certify that</p>
            <div class="authors-list">
                <h2>{{ $authors }}</h2>
            </div>
            <p>from {{ $affiliations }}</p>
            <p>has been accepted for publication of the paper:</p>
            <h4>"{{ $paper_title }}"</h4>
            <p class="journal-info">
                Published in
                {{ $journal_name }}{{ $acceptance_date ? ', Date of Acceptance: ' . $acceptance_date : '' }}
            </p>
            <div class="signature" style="text-align: right;">
                <h6>Head of Editorial Board</h6>
                <h6>{{ $signature }}</h6>
            </div>
        @endif
    </div>

</body>

</html>



