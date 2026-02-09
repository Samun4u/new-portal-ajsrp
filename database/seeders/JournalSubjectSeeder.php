<?php

namespace Database\Seeders;

use App\Models\JournalSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JournalSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            'Life Sciences, Agriculture & Food',
            'Materials Science',
            'Earth, Energy & Environment',
            'Economics & Management',
            'Chemistry',
            'Mathematics & Physics',
            'Architecture & Civil Engineering',
            'Humanities & Social Sciences',
            'Medicine & Health',
            'Electrical & Computer Science',
            'Education',
            'Multidisciplinary',
        ];

        foreach ($subjects as $subject) {
            JournalSubject::create([
                'name' => $subject,
                'slug' => Str::slug($subject),
                'status' => "active", 
            ]);
        }
    }
}
