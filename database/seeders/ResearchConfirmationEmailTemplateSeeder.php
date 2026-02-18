<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class ResearchConfirmationEmailTemplateSeeder extends Seeder
{
    public function run()
    {
        // Delete if exists
        EmailTemplate::where('slug', 'research-confirmation')->delete();

        EmailTemplate::create([
            'category' => 'research-confirmation',
            'slug' => 'research-confirmation',
            'title' => 'Research Submission Confirmation',
            
            // English
            'subject' => 'Authors Form Confirmation - {{research_title}}',
            'body' => 'Dear Researcher,

Thank you for submitting the Authors Form to the Arab Journal for Science and Research Publishing.

Your form has been successfully received.

Research Details:
- Title: {{research_title}}
- Field: {{research_field}}
- Journal: {{journal}}
- ID: {{research_id}}

Next Steps:
Your form will be reviewed by our team and we will verify all provided information. The primary certificate will be sent to you after approval. The review process typically takes 2-3 business days.

Important Note:
You will receive an email notification once your submission is approved and the primary certificate is issued.

If you have any questions, please don\'t hesitate to contact us.

Best regards,
Editorial Team
Arab Journal for Science and Research Publishing

---
Available Placeholders:
{{research_title}} - Research title
{{research_field}} - Research field  
{{journal}} - Selected journal
{{research_id}} - Research ID',

            // Arabic
            'subject_ar' => 'تأكيد استلام نموذج المؤلفين - {{research_title}}',
            'body_ar' => 'عزيزي / عزيزتي الباحث،

شكراً لك على تقديم نموذج المؤلفين للمجلة العربية للعلوم ونشر الأبحاث.

تم استلام نموذجك بنجاح.

تفاصيل البحث:
- العنوان: {{research_title}}
- المجال: {{research_field}}
- المجلة: {{journal}}
- المعرف: {{research_id}}

الخطوات التالية:
سيتم مراجعة نموذجك من قبل فريقنا وسنقوم بالتحقق من جميع المعلومات المقدمة. سيتم إرسال الشهادة الأولية إليك بعد الموافقة. تستغرق عملية المراجعة عادةً من 2-3 أيام عمل.

ملاحظة مهمة:
سيتم إرسال إشعار إليك عبر البريد الإلكتروني فور الموافقة على طلبك وإصدار الشهادة الأولية.

إذا كان لديك أي استفسارات، لا تتردد في التواصل معنا.

مع أطيب التحيات،
فريق التحرير
المجلة العربية للعلوم ونشر الأبحاث

---
الحقول المتاحة:
{{research_title}} - عنوان البحث
{{research_field}} - مجال البحث
{{journal}} - المجلة المختارة
{{research_id}} - معرف البحث',

            'status' => 1,
            'default' => 1,
            'language' => 'both',
        ]);

        $this->command->info('✓ Research confirmation email template created with placeholder support');
    }
}
