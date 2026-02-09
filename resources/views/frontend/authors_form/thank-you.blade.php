<!DOCTYPE html>
<html lang="ar" dir="rtl" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكرًا لتقديمكم - Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        [dir="rtl"] body {
            font-family: 'Noto Sans Arabic', 'Segoe UI', sans-serif;
        }
        
        .thank-you-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #1E3A8A;
            margin-bottom: 20px;
        }
        
        .lead {
            font-size: 18px;
            margin-bottom: 30px;
            color: #6c757d;
        }
        
        .next-steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: right;
        }
        
        [dir="ltr"] .next-steps {
            text-align: left;
        }
        
        .next-steps h3 {
            color: #1E3A8A;
            margin-bottom: 15px;
        }
        
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        [dir="ltr"] .language-switcher {
            right: auto;
            left: 20px;
        }

        body.high-contrast,
        body.high-contrast .thank-you-container,
        body.high-contrast h1,
        body.high-contrast .next-steps,
        body.high-contrast .next-steps h3
        {
            background-color: #000000;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="language-switcher">
        <button class="btn btn-outline-primary btn-sm" onclick="toggleLanguage()">
            <span id="langToggleText">EN</span>
        </button>
    </div>
    
    <div class="container">
        <div class="thank-you-container">
            <div class="success-icon">✓</div>
            
            <!-- Arabic Content -->
            <div id="arabic-content">
                <h1>شكرًا لتقديمكم!</h1>
                <p class="lead">لقد تم تقديم بحثكم بنجاح إلى المؤسسة العربية للعلوم ونشر الأبحاث.</p>

                <div class="countdown">
                    <p>سيتم توجيهك تلقائيًا إلى الصفحة الرئيسية خلال <span id="countdown" class="countdown-number">10</span> ثواني</p>
                </div>
                
                <div class="next-steps">
                    <h3>الخطوات القادمة</h3>
                    <ul>
                        <li>سوف تتلقون رسالة تأكيد عبر البريد الإلكتروني قريبًا</li>
                        <li>سيقوم فريقنا بمراجعة البحث المقدم</li>
                        <li>سنتواصل معكم خلال 5-7 أيام عمل</li>
                        <!-- <li>يمكنكم تتبع حالة البحث من خلال حسابكم</li> -->
                    </ul>
                </div>
                
                <!-- <div class="mt-4">
                    <a href="/" class="btn btn-primary">العودة إلى الصفحة الرئيسية</a>
                    <a href="/submission-status" class="btn btn-outline-secondary ms-2">التحقق من حالة البحث</a>
                </div> -->
            </div>
            
            <!-- English Content (initially hidden) -->
            <div id="english-content" style="display: none;">
                <h1>Thank You for Your Submission!</h1>
                <p class="lead">Your research has been successfully submitted to the Arab Institute for Science and Research Publishing.</p>

                <div class="countdown">
                    <p>You will be automatically redirected to the homepage in <span id="countdown-en" class="countdown-number">10</span> seconds</p>
                </div>
                
                <div class="next-steps">
                    <h3>What Happens Next?</h3>
                    <ul>
                        <li>You will receive a confirmation email shortly</li>
                        <li>Our team will review your submission</li>
                        <li>We will contact you within 5-7 business days</li>
                        <!-- <li>You can track your submission status through your account</li> -->
                    </ul>
                </div>
                
                <!-- <div class="mt-4">
                    <a href="/" class="btn btn-primary">Return to Homepage</a>
                    <a href="/submission-status" class="btn btn-outline-secondary ms-2">Check Submission Status</a>
                </div> -->
            </div>
        </div>
    </div>

    <script>
        // Check for saved language preference or default to Arabic
        let currentLang = localStorage.getItem('languagePreference') || 'ar';
        let countdownTime = 10; // seconds
        
        // Initialize page with correct language
        document.addEventListener('DOMContentLoaded', function() {
            if (currentLang === 'en') {
                showEnglish();
            } else {
                showArabic();
            }

            // Start the countdown
            startCountdown();
        });

        function startCountdown() {
            const countdownInterval = setInterval(function() {
                countdownTime--;
                
                // Update countdown display
                if (currentLang === 'ar') {
                    document.getElementById('countdown').textContent = countdownTime;
                } else {
                    document.getElementById('countdown-en').textContent = countdownTime;
                }
                
                // Redirect when countdown reaches 0
                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = 'https://ajsrp.com';
                }
            }, 1000);
        }
        
        function toggleLanguage() {
            if (currentLang === 'ar') {
                showEnglish();
                currentLang = 'en';
            } else {
                showArabic();
                currentLang = 'ar';
            }
            
            // Save language preference
            localStorage.setItem('languagePreference', currentLang);
        }
        
        function showArabic() {
            document.getElementById('html-root').dir = 'rtl';
            document.getElementById('html-root').lang = 'ar';
            document.getElementById('arabic-content').style.display = 'block';
            document.getElementById('english-content').style.display = 'none';
            document.getElementById('langToggleText').textContent = 'EN';
        }
        
        function showEnglish() {
            document.getElementById('html-root').dir = 'ltr';
            document.getElementById('html-root').lang = 'en';
            document.getElementById('arabic-content').style.display = 'none';
            document.getElementById('english-content').style.display = 'block';
            document.getElementById('langToggleText').textContent = 'AR';
        }

        // Load high contrast preference
        if (localStorage.getItem('highContrast') === 'true') {
            document.body.classList.add('high-contrast');
        }
    </script>
</body>
</html>