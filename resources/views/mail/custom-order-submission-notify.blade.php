<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Notification</title>
</head>
<body style="background-color: #e8eff5; margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <table width="100%" height="100%" bgcolor="#e8eff5" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" valign="middle">
                <table width="600" bgcolor="#ffffff" cellpadding="20" cellspacing="0" border="0" style="border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td bgcolor="#12233d" style="color: white; font-size: 18px; font-weight: bold; text-align: center; padding: 15px; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            Submission Notification of Your Order <span style="color: #f8d210;">[{{$typeData['order']['order_id']}}]</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #333;">
                            <p>Dear {{$userData['name']}},</p>
                            <p>Greetings from the editorial office of <em>American Journal of Science, Engineering and Technology</em>.</p>
                            <table width="100%" bgcolor="#f0f5fb" cellpadding="10" cellspacing="0" border="0" style="border-radius: 5px;">
                                <tr>
                                    <td>
                                        <p>We have received your manuscript with the following details:</p>
                                        <ul>
                                            <li><strong>Order Number:</strong> {{$typeData['order']['order_id']}}</li>
                                            <li><strong>Order Title:</strong> {{$typeData['client_order_submission']['article_title']}}</li>
                                            <li><strong>Journal Title:</strong> <span style="color: #0056b3; font-style: italic;">{{$typeData['client_order_submission']['journal']['title']}}</span></li>
                                            <li><strong>Journal Site:</strong> <a href="http://www.sciencepg.com/journal/ajset" style="color: #0056b3; text-decoration: none;">http://www.sciencepg.com/journal/ajset</a></li>
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
                            <p><strong>Lily White</strong><br>Editorial Assistant</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
