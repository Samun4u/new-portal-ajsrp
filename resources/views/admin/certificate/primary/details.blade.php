<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        body {
            font-family: 'Helvetica', 'arabicfont', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
        }
        .container {
            width: 85%;
            margin: 0 auto;
            padding: 40px 0;
            position: relative;
            min-height: 1000px;
        }
        .header-line {
            border-top: 2px solid #000;
            margin-bottom: 20px;
        }
        .footer-line {
            border-top: 1px solid #ccc;
            margin-top: 50px;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        @if(selectedLanguage()->iso_code == 'ar')
        .container {
            direction: rtl;
        }
        .meta-info, .signature-section {
            text-align: left;
        }
        .manuscript-details td {
            text-align: right;
        }
        @else
        .meta-info, .signature-section {
            text-align: right;
        }
        @endif
        .title {
            text-align: center;
            margin: 30px 0;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .meta-info {
            margin-bottom: 30px;
            font-style: italic;
            line-height: 1.6;
        }
        .content {
            margin: 30px 0;
            line-height: 1.8;
            font-size: 16px;
            text-align: justify;
        }
        .manuscript-details {
            margin: 30px 40px;
        }
        .manuscript-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .manuscript-details td {
            vertical-align: top;
            padding: 8px 0;
        }
        .manuscript-details .label {
            width: 200px;
            font-style: italic;
        }
        .signature-section {
            margin-top: 80px;
        }
        .footer-details {
            font-size: 9px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    @php
        $isArabic = (selectedLanguage()->iso_code == 'ar');
    @endphp
    <div class="container">
        <div class="header-line"></div>

        <div class="logo-section">
            @php $logo = public_path('assets/images/logo.png'); @endphp
            @if(file_exists($logo))
            <img src="{{ $logo }}" style="max-height: 70px;"><br>
            @endif
            <div style="font-size: 14px; margin-top: 5px; font-weight: bold;">AJSRP Portal</div>
            <div style="font-size: 11px; color: #666;">www.ajsrp.com</div>
        </div>

        <div class="title">
            @if($isArabic)
                {{ $headerOneStatic }}
            @else
                Certificate of Primary Acceptance
            @endif
        </div>

        <div class="meta-info">
            Ref. AJSRP/PRIM/{{ $ref_no ?? $order_id ?? 'N/A' }}
        </div>

        <div class="content">
            @if($isArabic)
                {{ $paraOneStatic ?? '' }} <strong>{{ $author ?? '' }}</strong> {{ $paraTwoStatic ?? '' }} <strong>{{ $affiliation ?? '' }}</strong> {{ $paraThreeStatic ?? '' }}
            @else
                This is to certify that <strong>{{ $author ?? '' }}</strong> from <strong>{{ $affiliation ?? '' }}</strong> has submitted the paper titled:
            @endif
        </div>

        <div class="manuscript-details">
            <table>
                <tr>
                    <td class="label">@if($isArabic) عنوان البحث @else Title of the Manuscript: @endif</td>
                    <td><strong>{{ $paper_title ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">@if($isArabic) اسم المجلة @else Journal Name: @endif</td>
                    <td><strong>{{ $journal_name ?? '' }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="content">
            @if($isArabic)
                {{ $journalInfoParaOneStatic ?? '' }} {{ $journal_name ?? '' }}.
            @else
                The manuscript is currently under consideration for publication in <strong>{{ $journal_name ?? '' }}</strong>.
            @endif
        </div>

        <div class="closing">
            <p>@if($isArabic) مع خالص الشكر والتقدير @else Sincerely yours, @endif</p>
        </div>

        <div class="signature-section">
            <div style="font-weight: bold;">
                @if($isArabic)
                    {{ $signatureParaStatic }}
                @else
                    Head of Editorial Board
                @endif
            </div>
            <div>
                {{ $signature }}
            </div>
        </div>

        <div class="footer-line"></div>
        <div class="footer-details">
            Reg. Offices:<br>
            AJSRP Portal, International Academic Publishing Center.<br>
            Website: www.ajsrp.com | Email: info@ajsrp.com
        </div>
    </div>
</body>
</html>
