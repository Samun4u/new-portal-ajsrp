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

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333; {{ $isArabic ? 'direction: rtl; text-align: right;' : '' }}">
                            @if($isArabic)
                                <h2 style="color: #12233d; margin-top: 0;">عزيزي / عزيزتي الباحث،</h2>

                                <p style="line-height: 1.8; font-size: 15px; margin-bottom: 20px;">
                                    شكراً لك على تقديم نموذج المؤلفين للمجلة العربية للعلوم ونشر الأبحاث.
                                </p>

                                <!-- Success Box -->
                                <table width="100%" bgcolor="#e8f5e9" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-right: 4px solid #4caf50; margin-bottom: 20px;">
                                    <tr>
                                        <td style="direction: rtl; text-align: right;">
                                            <p style="margin: 0; color: #2e7d32; font-weight: bold;">
                                                ✓ تم استلام نموذجك بنجاح
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <h3 style="color: #12233d; border-bottom: 2px solid #f0f5fb; padding-bottom: 10px;">
                                    معلومات البحث المقدم:
                                </h3>

                                <table width="100%" cellpadding="8" cellspacing="0" style="margin-bottom: 20px;">
                                    @if(!empty($research->english_title))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>العنوان بالإنجليزية:</strong></td>
                                        <td style="color: #333;">{{ $research->english_title }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->arabic_title))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>العنوان بالعربية:</strong></td>
                                        <td style="color: #333;">{{ $research->arabic_title }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->field))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>مجال البحث:</strong></td>
                                        <td style="color: #333;">{{ $research->field }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->journal))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>المجلة:</strong></td>
                                        <td style="color: #333;">{{ $research->journal }}</td>
                                    </tr>
                                    @endif
                                </table>

                                <h3 style="color: #12233d; border-bottom: 2px solid #f0f5fb; padding-bottom: 10px;">
                                    الخطوات التالية:
                                </h3>

                                <ul style="line-height: 2; padding-right: 20px; margin-right: 0;">
                                    <li>سيتم مراجعة نموذجك من قبل فريقنا</li>
                                    <li>سنقوم بالتحقق من جميع المعلومات المقدمة</li>
                                    <li>سيتم إرسال الشهادة الأولية إليك بعد الموافقة</li>
                                    <li>ستستغرق عملية المراجعة من 2-3 أيام عمل</li>
                                </ul>

                                <table width="100%" bgcolor="#fff8e1" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-right: 4px solid #ffc107; margin: 20px 0;">
                                    <tr>
                                        <td style="direction: rtl; text-align: right;">
                                            <p style="margin: 0; color: #f57c00; font-weight: bold;">
                                                ℹ️ ملاحظة مهمة:
                                            </p>
                                            <p style="margin: 10px 0 0 0; color: #666;">
                                                سيتم إرسال إشعار إليك عبر البريد الإلكتروني فور الموافقة على طلبك وإصدار الشهادة الأولية.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="line-height: 1.8; font-size: 15px; margin-top: 30px;">
                                    إذا كان لديك أي استفسارات، لا تتردد في التواصل معنا.
                                </p>

                                <p style="line-height: 1.8; margin-top: 30px;">
                                    مع أطيب التحيات،<br>
                                    <strong>فريق التحرير</strong><br>
                                    <span style="color: #666;">المجلة العربية للعلوم ونشر الأبحاث</span>
                                </p>

                            @else
                                <h2 style="color: #12233d; margin-top: 0;">Dear Researcher,</h2>

                                <p style="line-height: 1.8; font-size: 15px; margin-bottom: 20px;">
                                    Thank you for submitting your authors form to the Arab Journal for Science and Research Publishing.
                                </p>

                                <!-- Success Box -->
                                <table width="100%" bgcolor="#e8f5e9" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-left: 4px solid #4caf50; margin-bottom: 20px;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0; color: #2e7d32; font-weight: bold;">
                                                ✓ Your form has been successfully received
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <h3 style="color: #12233d; border-bottom: 2px solid #f0f5fb; padding-bottom: 10px;">
                                    Submitted Research Information:
                                </h3>

                                <table width="100%" cellpadding="8" cellspacing="0" style="margin-bottom: 20px;">
                                    @if(!empty($research->english_title))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>English Title:</strong></td>
                                        <td style="color: #333;">{{ $research->english_title }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->arabic_title))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>Arabic Title:</strong></td>
                                        <td style="color: #333;">{{ $research->arabic_title }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->field))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>Research Field:</strong></td>
                                        <td style="color: #333;">{{ $research->field }}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($research->journal))
                                    <tr>
                                        <td style="width: 150px; color: #666; vertical-align: top;"><strong>Journal:</strong></td>
                                        <td style="color: #333;">{{ $research->journal }}</td>
                                    </tr>
                                    @endif
                                </table>

                                <h3 style="color: #12233d; border-bottom: 2px solid #f0f5fb; padding-bottom: 10px;">
                                    Next Steps:
                                </h3>

                                <ul style="line-height: 2; padding-left: 20px;">
                                    <li>Your form will be reviewed by our team</li>
                                    <li>We will verify all provided information</li>
                                    <li>Primary certificate will be sent after approval</li>
                                    <li>Review process takes 2-3 business days</li>
                                </ul>

                                <table width="100%" bgcolor="#fff8e1" cellpadding="15" cellspacing="0" border="0" style="border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0; color: #f57c00; font-weight: bold;">
                                                ℹ️ Important Note:
                                            </p>
                                            <p style="margin: 10px 0 0 0; color: #666;">
                                                You will receive an email notification once your submission is approved and the primary certificate is issued.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="line-height: 1.8; font-size: 15px; margin-top: 30px;">
                                    If you have any questions, please don't hesitate to contact us.
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

