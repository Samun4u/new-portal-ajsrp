<?php

namespace Database\Seeders;

use App\Models\ArticleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Article type seeder
        $types = [
            ['name' => 'Research Article', 'arabic_name' => 'مقالة بحثية'],
            ['name' => 'Review Article', 'arabic_name' => 'مقالة مراجعة'],
            ['name' => 'Case Report', 'arabic_name' => 'تقرير حالة'],
            ['name' => 'Editorial', 'arabic_name' => 'افتتاحية'],
            ['name' => 'Letter', 'arabic_name' => 'رسالة'],
            ['name' => 'Report', 'arabic_name' => 'تقرير'],
            ['name' => 'Commentary', 'arabic_name' => 'تعليق توضيحي'],
            ['name' => 'Communication', 'arabic_name' => 'مراسلة'],
            ['name' => 'Methodology Article', 'arabic_name' => 'مقالة منهجية'],
            ['name' => 'Research/Technical Note', 'arabic_name' => 'ملاحظة بحثية/فنية'],
        ];

        foreach ($types as $type) {
            ArticleType::firstOrCreate(
                ['name' => $type['name']], 
                ['arabic_name' => $type['arabic_name']]
            );
        }

    }
}
