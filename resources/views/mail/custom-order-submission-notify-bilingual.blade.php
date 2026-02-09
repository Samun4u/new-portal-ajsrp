<!DOCTYPE html>
<html dir="{{ $isArabic ? 'rtl' : 'ltr' }}" lang="{{ $isArabic ? 'ar' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isArabic ? 'إشعار تقديم الطلب' : 'Submission Notification' }}</title>
    <style>
        @if($isArabic)
        body {
            direction: rtl;
            text-align: right;
        }

        table {
            direction: rtl;
        }

        td {
            direction: rtl;
            text-align: right;
        }

        /* URLs and emails should be LTR */
        .ltr-content {
            direction: ltr !important;
            text-align: left !important;
            display: inline-block;
        }
        @endif
    </style>
</head>
<body style="background-color: #e8eff5; margin: 0; padding: 0; font-family: {{ $isArabic ? 'Tahoma, Arial, sans-serif' : 'Arial, sans-serif' }}; direction: {{ $isArabic ? 'rtl' : 'ltr' }};">

    <table width="100%" height="100%" bgcolor="#e8eff5" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" valign="middle">
                <table width="600" bgcolor="#ffffff" cellpadding="20" cellspacing="0" border="0" style="border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); {{ $isArabic ? 'direction: rtl;' : '' }}">
                    <tr>
                        <td bgcolor="#12233d" style="color: white; font-size: 18px; font-weight: bold; text-align: center; padding: 15px; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            @if($isArabic)
                                إشعار تقديم طلبك <span style="color: #f8d210;">[{{$typeData['order']['order_id']}}]</span>
                            @else
                                Submission Notification of Your Order <span style="color: #f8d210;">[{{$typeData['order']['order_id']}}]</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #333; padding: 20px; {{ $isArabic ? 'direction: rtl; text-align: right;' : '' }}">
            @if($isArabic)
                <p>عزيزي / عزيزتي <strong>{{$userData['name']}}</strong>،</p>
                <p>تحية طيبة من مكتب التحرير في مجلة <em>{{ $journalTitle }}</em>.</p>
                <table width="100%" bgcolor="#f0f5fb" cellpadding="10" cellspacing="0" border="0" style="border-radius: 5px;">
                    <tr>
                        <td style="direction: rtl; text-align: right;">
                            <p style="margin-bottom: 10px;">لقد تلقينا مخطوطتك بالتفاصيل التالية:</p>
                            <ul style="list-style-type: none; padding-right: 0; margin-right: 20px;">
                                <li style="margin-bottom: 8px;"><strong>رقم الطلب:</strong> {{$typeData['order']['order_id']}}</li>
                                <li style="margin-bottom: 8px;"><strong>عنوان البحث:</strong> {{$typeData['client_order_submission']['article_title']}}</li>
                                <li style="margin-bottom: 8px;"><strong>المجلة:</strong> <span style="color: #0056b3; font-style: italic;">{{ $journalTitle }}</span></li>
                                @if($journalSite)
                                <li style="margin-bottom: 8px;"><strong>موقع المجلة:</strong> <a href="{{ $journalSite }}" style="color: #0056b3; text-decoration: none;">{{ $journalSite }}</a></li>
                                @endif
                            </ul>
                        </td>
                    </tr>
                </table>
                <p style="line-height: 1.8;">مخطوطتك قيد المعالجة الآن، وسيتم إرسال التقييم الأولي إليك في غضون <strong>4 أيام عمل</strong>. إذا لم تستلمه، يرجى الاتصال بنا مباشرة عبر هذا البريد الإلكتروني.</p>
                <p style="line-height: 1.8;">لتتبع حالة مخطوطتك، يرجى تسجيل الدخول باستخدام التفاصيل التالية:</p>
                <ul style="list-style-type: none; padding-right: 0; margin-right: 20px; direction: rtl;">
                    <li style="margin-bottom: 8px;"><strong>رابط تسجيل الدخول:</strong> <a href="https://portal.ajsrp.com/login" style="color: #0056b3; text-decoration: none; direction: ltr;">https://portal.ajsrp.com/login</a></li>
                    <li style="margin-bottom: 8px;"><strong>اسم المستخدم:</strong> <a href="mailto:{{$userData['email']}}" style="color: #0056b3; text-decoration: none; direction: ltr;">{{$userData['email']}}</a></li>
                    <li style="margin-bottom: 8px;"><strong>كلمة المرور:</strong> ***</li>
                </ul>
                <p style="line-height: 1.8;">شكراً لك على تقديم عملك إلى هذه المجلة.</p>
                <p style="line-height: 1.8;">مع أطيب التحيات،</p>
                <p><strong>فريق التحرير</strong><br>المجلة العربية للعلوم ونشر الأبحاث</p>
                            @else
                                <p>Dear {{$userData['name']}},</p>
                                <p>Greetings from the editorial office of <em>{{ $journalTitle }}</em>.</p>
                                <table width="100%" bgcolor="#f0f5fb" cellpadding="10" cellspacing="0" border="0" style="border-radius: 5px;">
                                    <tr>
                                        <td>
                                            <p>We have received your manuscript with the following details:</p>
                                            <ul>
                                                <li><strong>Order Number:</strong> {{$typeData['order']['order_id']}}</li>
                                                <li><strong>Order Title:</strong> {{$typeData['client_order_submission']['article_title']}}</li>
                                                <li><strong>Journal Title:</strong> <span style="color: #0056b3; font-style: italic;">{{ $journalTitle }}</span></li>
                                                @if($journalSite)
                                                <li><strong>Journal Site:</strong> <a href="{{ $journalSite }}" style="color: #0056b3; text-decoration: none;">{{ $journalSite }}</a></li>
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <p>Now your manuscript is under processing, and the initial assessment will be sent to you within <strong>4 working days</strong>. If you do not receive it, please contact us directly <strong>via this email</strong>.</p>
                                <p>To track the status of your manuscript, please log in using the following details:</p>
                                <ul>
                                    <li><strong>Login URL:</strong> <a href="https://portal.ajsrp.com/login" style="color: #0056b3; text-decoration: none;">https://portal.ajsrp.com/login</a></li>
                                    <li><strong>Username:</strong> <a href="mailto:{{$userData['email']}}" style="color: #0056b3; text-decoration: none;">{{$userData['email']}}</a></li>
                                    <li><strong>Password:</strong> ***</li>
                                </ul>
                                <p>Thank you for submitting your work to this journal.</p>
                                <p>Best regards,</p>
                                <p><strong>Editorial Team</strong><br>{{ config('app.name') }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

