<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isArabic ? 'طلب نموذج المؤلفين - يتطلب مراجعة' : 'Authors Form - Requires Review' }}</title>
    <style>
        @if($isArabic)
        body {
            direction: rtl;
            text-align: right;
            font-family: 'Tahoma', 'Arial', sans-serif;
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
                        <td bgcolor="#dc3545" style="color: white; font-size: 20px; font-weight: bold; text-align: center; padding: 25px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            @if($isArabic)
                                ⚠ طلب نموذج المؤلفين - يتطلب مراجعة
                            @else
                                ⚠ Authors Form - Requires Review
                            @endif
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333; {{ $isArabic ? 'direction: rtl; text-align: right;' : '' }}">
                            @if($isArabic)
                                <h2 style="color: #dc3545; margin-top: 0;">عزيزي / عزيزتي الباحث،</h2>

                                <p style="line-height: 1.8; font-size: 15px;">
                                    شكراً لك على تقديم نموذج المؤلفين.
                                </p>

                                <table width="100%" bgcolor="#fff3cd" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-right: 4px solid #ffc107; margin: 20px 0;">
                                    <tr>
                                        <td style="direction: rtl;">
                                            <p style="margin: 0; color: #856404; font-weight: bold;">
                                                ⚠ يتطلب المراجعة
                                            </p>
                                            <p style="margin: 10px 0 0 0; color: #666;">
                                                بعد مراجعة طلبك، نحتاج إلى بعض التعديلات أو المعلومات الإضافية.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                @if($research->admin_notes)
                                <h3 style="color: #12233d;">ملاحظات المراجع:</h3>
                                <table width="100%" bgcolor="#f8d7da" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-right: 4px solid #dc3545;">
                                    <tr>
                                        <td style="direction: rtl; color: #721c24;">
                                            {{ $research->admin_notes }}
                                        </td>
                                    </tr>
                                </table>
                                @endif

                                <p style="line-height: 1.8; font-size: 15px; margin-top: 20px;">
                                    يرجى مراجعة المعلومات المقدمة وإعادة تقديم النموذج مع التصحيحات المطلوبة.
                                </p>

                                <p style="text-align: center; margin: 30px 0;">
                                    <a href="{{ route('authors.form') }}" style="display: inline-block; background-color: #12233d; color: white; text-decoration: none; padding: 15px 40px; border-radius: 5px; font-weight: bold;">
                                        إعادة تقديم النموذج
                                    </a>
                                </p>

                                <p style="line-height: 1.8; margin-top: 30px;">
                                    مع أطيب التحيات،<br>
                                    <strong>فريق التحرير</strong><br>
                                    <span style="color: #666;">المجلة العربية للعلوم ونشر الأبحاث</span>
                                </p>

                            @else
                                <h2 style="color: #dc3545; margin-top: 0;">Dear Researcher,</h2>

                                <p style="line-height: 1.8; font-size: 15px;">
                                    Thank you for submitting your authors form.
                                </p>

                                <table width="100%" bgcolor="#fff3cd" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0; color: #856404; font-weight: bold;">
                                                ⚠ Requires Review
                                            </p>
                                            <p style="margin: 10px 0 0 0; color: #666;">
                                                After reviewing your submission, we need some modifications or additional information.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                @if($research->admin_notes)
                                <h3 style="color: #12233d;">Reviewer Notes:</h3>
                                <table width="100%" bgcolor="#f8d7da" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-left: 4px solid #dc3545;">
                                    <tr>
                                        <td style="color: #721c24;">
                                            {{ $research->admin_notes }}
                                        </td>
                                    </tr>
                                </table>
                                @endif

                                <p style="line-height: 1.8; font-size: 15px; margin-top: 20px;">
                                    Please review the provided information and resubmit the form with the required corrections.
                                </p>

                                <p style="text-align: center; margin: 30px 0;">
                                    <a href="{{ route('authors.form') }}" style="display: inline-block; background-color: #12233d; color: white; text-decoration: none; padding: 15px 40px; border-radius: 5px; font-weight: bold;">
                                        Resubmit Form
                                    </a>
                                </p>

                                <p style="line-height: 1.8; margin-top: 30px;">
                                    Best regards,<br>
                                    <strong>Editorial Team</strong><br>
                                    <span style="color: #666;">Arab Journal for Science and Research Publishing</span>
                                </p>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#f5f5f5" style="padding: 20px; text-align: center; font-size: 13px; color: #666; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            <p style="margin: 0 0 5px 0;">
                                © {{ date('Y') }} {{ $isArabic ? 'المجلة العربية للعلوم ونشر الأبحاث' : 'Arab Journal for Science and Research Publishing' }}
                            </p>
                            <p style="margin: 0;">
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

