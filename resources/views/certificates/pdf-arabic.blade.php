<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; text-align: center; border: 5px double #007bff; padding: 50px; direction: rtl; }
        .logo { font-size: 50px; margin-bottom: 20px; }
        .title { font-size: 40px; font-weight: bold; margin-bottom: 10px; color: #2c3e50; }
        .subtitle { font-size: 20px; color: #7f8c8d; margin-bottom: 30px; }
        .content { margin: 30px auto; max-width: 800px; line-height: 1.6; font-size: 16px; }
        .details { margin: 20px auto; padding: 20px; background: #f8f9fa; text-align: right; width: 80%; }
        .row { margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .label { font-weight: bold; width: 150px; display: inline-block; color: #495057; }
        .footer { margin-top: 50px; width: 100%; display: table; }
        .sig-block { display: table-cell; width: 45%; vertical-align: bottom; text-align: center; padding: 0 10px; position: relative; }
        .line { border-top: 1px solid #000; margin: 10px auto; width: 80%; }
        img.sig { height: 60px; display: block; margin: 0 auto; }
        img.stamp { position: absolute; width: 100px; opacity: 0.8; top: -30px; left: 20px; }
    </style>
</head>
<body>
    <div class="logo">🎓</div>
    <div class="title">شهادة قبول نهائي</div>
    <div class="subtitle">قبول نهائي للنشر</div>

    <div class="content">
        <p>تشهد هذه الشهادة بأن البحث المقدم من:</p>

        <div class="details">
            <div class="row"><span class="label">المؤلف (المؤلفون):</span> {{ $certificate->author_names }}</div>
            <div class="row"><span class="label">عنوان البحث:</span> {{ $certificate->paper_title }}</div>
            <div class="row"><span class="label">رقم التسليم:</span> {{ $certificate->client_order_id }}</div>
            <div class="row"><span class="label">المجلة:</span> {{ $certificate->journal_name }}</div>
            <div class="row"><span class="label">الرقم الدولي:</span> {{ $certificate->issn ?? 'N/A' }}</div>
            <div class="row"><span class="label">المجلد والعدد:</span> المجلد {{ $certificate->volume }}, العدد {{ $certificate->issue }}</div>
            <div class="row"><span class="label">تاريخ النشر:</span> {{ $certificate->date ? $certificate->date->format('Y-m-d') : 'N/A' }}</div>
        </div>

        <p>قد تم قبوله للنشر بعد تحكيم دقيق ويستوفي معايير التميز الأكاديمي المطلوبة من قبل مجلتنا.</p>
    </div>

    <div class="footer">
        <div class="sig-block">
             @if($certificate->signature_path && file_exists(storage_path('app/public/'.$certificate->signature_path)))
                <img src="{{ storage_path('app/public/'.$certificate->signature_path) }}" class="sig">
            @endif
             @if($certificate->stamp_path && file_exists(storage_path('app/public/'.$certificate->stamp_path)))
                <img src="{{ storage_path('app/public/'.$certificate->stamp_path) }}" class="stamp">
            @endif
            <div class="line"></div>
            <div>{{ $certificate->chief_editor_ar }}</div>
            <small>رئيس التحرير</small>
        </div>
        <div style="display: table-cell; width: 10%;"></div>
        <div class="sig-block">
            <div style="height: 60px;"></div>
            <div class="line"></div>
            <div>AJSRP</div>
            <small>الناشر</small>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 12px; color: #999;">
        تاريخ إصدار الشهادة: {{ now()->format('Y-m-d') }} | رقم: {{ $certificate->certificate_number }}
    </div>
</body>
</html>
