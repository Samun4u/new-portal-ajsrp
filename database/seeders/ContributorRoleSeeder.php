<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContributorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['en' => 'Conceptualization', 'ar' => 'التصور'],
            ['en' => 'Investigation', 'ar' => 'التحقيق'],
            ['en' => 'Software', 'ar' => 'البرمجيات'],
            ['en' => 'Writing – original draft', 'ar' => 'الكتابة - المسودة الأصلية'],
            ['en' => 'Data curation', 'ar' => 'تنظيم البيانات'],
            ['en' => 'Methodology', 'ar' => 'المنهجية'],
            ['en' => 'Supervision', 'ar' => 'الإشراف'],
            ['en' => 'Writing – review & editing', 'ar' => 'الكتابة - المراجعة والتحرير'],
            ['en' => 'Formal Analysis', 'ar' => 'التحليل الرسمي'],
            ['en' => 'Project administration', 'ar' => 'إدارة المشروع'],
            ['en' => 'Validation', 'ar' => 'التحقق'],
            ['en' => 'Funding acquisition', 'ar' => 'الحصول على التمويل'],
            ['en' => 'Resources', 'ar' => 'الموارد'],
            ['en' => 'Visualization', 'ar' => 'التصور المرئي'],
        ];

        foreach ($roles as $role) {
            DB::table('contributor_roles')->insert([
                'role_name'   => $role['en'],
                'arabic_name' => $role['ar'],
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
