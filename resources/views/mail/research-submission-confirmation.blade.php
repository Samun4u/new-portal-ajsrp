<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isArabic ? 'تأكيد استلام نموذج المؤلفين' : 'Authors Form Confirmation' }}</title>
    <style>
        @if($isArabic)
        body {
            direction: rtl;
            text-align: right;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }

        .ltr-content {
            direction: ltr !important;
            text-align: left !important;
            display: inline-block;
        }
        @else
        body {
            direction: ltr;
            text-align: left;
            font-family: 'Arial', sans-serif;
        }
        @endif
    </style>
</head>
<body style="background-color: #e8eff5; margin: 0; padding: 0;">

    <table width="100%" height="100%" bgcolor="#e8eff5" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" valign="middle" style="padding: 20px;">
                <table width="600" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" style="border-radius: 8px; box-shadow: 0px 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td bgcolor="#12233d" style="color: white; font-size: 20px; font-weight: bold; text-align: center; padding: 25px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            @if($isArabic)
                                ✓ تم استلام نموذج المؤلفين
                            @else
                                ✓ Authors Form Received
                            @endif
                        </td>
                    </tr>

                    <!-- Body: Dynamic Content from Database -->
                    <tr>
                        <td style="padding: 30px; color: #333; {{ $isArabic ? 'direction: rtl; text-align: right;' : '' }}">
                            {{-- Dynamic email content --}}
                            <div style="line-height: 1.8; font-size: 15px; white-space: pre-line;">
                                {!! $emailContent ?? '' !!}
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#f5f5f5" style="padding: 20px; text-align: center; font-size: 13px; color: #666; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            <p style="margin: 0 0 5px 0;">
                                © {{ date('Y') }} {{ $isArabic ? 'المجلة العربية للعلوم ونشر الأبحاث' : 'Arab Journal for Science and Research Publishing' }}
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #999;">
                                {{ $isArabic ? 'جميع الحقوق محفوظة' : 'All Rights Reserved' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
