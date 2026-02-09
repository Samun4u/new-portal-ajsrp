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
            font-family: 'DejaVu Sans', sans-serif;
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
        @if($isArabic)
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
        }
        .signature-section {
            margin-top: 40px;
        }
        .footer-details {
            font-size: 9px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-line"></div>

        <div class="logo-section">
            @php
                $logo = $logo_image ?? public_path('assets/images/logo.png');
            @endphp
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
                TO WHOM IT MAY CONCERN
            @endif
        </div>

        <div class="meta-info">
            @if($isArabic) {{ $labelDateStatic ?? 'التاريخ' }}: @else Date: @endif {{ $date ?? \Carbon\Carbon::now()->format('d/m/Y') }}<br>
            @if($isArabic) {{ $labelRefNoStatic ?? 'الرقم المرجعي' }}: @else Ref. No: @endif {{ $ref_no ?? 'N/A' }}
        </div>

        <div class="content">
            @if($isArabic)
                {{ $paraOneStatic }} <strong>{{ $title }} {{ $reviewer_name }}</strong> {{ $paraTwoStatic }} <strong>{{ $affiliations }}</strong> {{ $paraThreeStatic }}
            @else
                We hereby certify that <strong>{{ $title }} {{ $reviewer_name }}</strong> of <strong>{{ $affiliations }}</strong> was invited for peer reviewing of the below mentioned Manuscript.
            @endif
        </div>

        <div class="manuscript-details">
            <table>
                <tr>
                    <td class="label">@if($isArabic) اسم المجلة @else Journal Name: @endif</td>
                    <td><strong>{{ $journal_name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">@if($isArabic) رقم المخطوطة @else Manuscript Number: @endif</td>
                    <td><strong>{{ $order_id ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">@if($isArabic) عنوان البحث @else Title of the Manuscript: @endif</td>
                    <td><strong>{{ $paper_title }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="content">
            @if($isArabic)
                قد أتم <strong>{{ $title }} {{ $reviewer_name }}</strong> المراجعة في الوقت المحدد وقدم ملاحظات علمية قيمة ساهمت في الحفاظ على المعايير العالية لمراجعة الأقران في هذه المجلة الدولية.
            @else
                <strong>{{ $title }} {{ $reviewer_name }}</strong> completed the review in time and submitted academically important review comments, which helped to maintain the high peer review standard of this international journal.
            @endif
        </div>

        <div class="closing">
            <p>@if($isArabic) مع خالص الشكر والتقدير @else Thanking you. @endif</p>
        </div>

        <div class="signature-section">
            @if(isset($signature_image) && file_exists($signature_image))
                <div style="margin-bottom: 5px;">
                    <img src="{{ $signature_image }}" style="max-height: 80px;">
                </div>
            @endif
            <div style="font-weight: bold;">
                ({{ $signature }})
            </div>
            <div>
                @if($isArabic)
                    رئيس تحرير بوابة AJSRP
                @else
                    Chief Managing Editor, AJSRP Portal
                @endif
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
