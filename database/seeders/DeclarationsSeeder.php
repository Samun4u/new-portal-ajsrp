<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeclarationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = [
             [
            'en' => 'I confirm this manuscript is not currently under consideration for publication elsewhere and has not been previously published by any other journal or publication forum.',
            'ar' => 'أؤكد أن هذه المخطوطة ليست قيد النظر للنشر في مكان آخر ولم يتم نشرها مسبقًا في أي مجلة أو منتدى نشر آخر.'
        ],
        [
            'en' => 'I am aware that accepted manuscripts are subject to an Article Processing Charge of 85 USD, which is payable upon receipt of invoice, and billed upon acceptance of submission for publication.',
            'ar' => 'أعلم أن المخطوطات المقبولة تخضع لرسوم معالجة المقالة بقيمة 85 دولارًا أمريكيًا، تُدفع عند استلام الفاتورة، ويتم إصدارها عند قبول المخطوطة للنشر.'
        ],
        [
            'en' => 'I confirm all co-authors have read and agreed on the current version of this manuscript.',
            'ar' => 'أؤكد أن جميع المؤلفين المشاركين قد قرأوا ووافقوا على النسخة الحالية من هذه المخطوطة.'
        ],
        [
            'en' => 'By submitting this manuscript to Science Publishing Group, I agree that if accepted, it will be published as open access, distributed under the terms of the Creative Commons Attribution 4.0 License (http://creativecommons.org/licenses/by/4.0/).',
            'ar' => 'من خلال تقديم هذه المخطوطة إلى مجموعة النشر العلمي، أوافق على أنه إذا تم قبولها، فسيتم نشرها بإتاحة مفتوحة وفقًا لشروط رخصة المشاع الإبداعي النسبة 4.0 (http://creativecommons.org/licenses/by/4.0/).'
        ],
        ];

        foreach ($titles as $title) {
            DB::table('declarations')->insert([
                'title'        => $title['en'],
                'arabic_title' => $title['ar'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
