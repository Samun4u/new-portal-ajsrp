<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $journals = [
            ['title' => 'Advances Website', 'charges' => 85],
            ['title' => 'Advances in Applied Physiology Website', 'charges' => 85],
            ['title' => 'Advances in Applied Sciences Website', 'charges' => 85],
            ['title' => 'Advances in Biochemistry Website', 'charges' => 85],
            ['title' => 'Advances in Bioscience and Bioengineering Website', 'charges' => 135],
            ['title' => 'Advances in Materials Website', 'charges' => 85],
            ['title' => 'Advances in Networks Website', 'charges' => 60],
            ['title' => 'Advances in Sciences and Humanities Website', 'charges' => 85],
            ['title' => 'Advances in Surgical Sciences Website', 'charges' => 85],
            ['title' => 'Advances in Wireless Communications and Networks Website', 'charges' => 60],
            ['title' => 'Agriculture, Forestry and Fisheries Website', 'charges' => 135],
            ['title' => 'American Journal of Aerospace Engineering Website', 'charges' => 85],
            ['title' => 'American Journal of Agriculture and Forestry Website', 'charges' => 85],
            ['title' => 'American Journal of Applied Chemistry Website', 'charges' => 85],
            ['title' => 'American Journal of Applied Mathematics Website', 'charges' => 60],
            ['title' => 'American Journal of Applied Psychology Website', 'charges' => 85],
            ['title' => 'American Journal of Applied Scientific Research Website', 'charges' => 85],
            ['title' => 'American Journal of Applied and Industrial Chemistry Website', 'charges' => 85],
            ['title' => 'American Journal of Art and Design Website', 'charges' => 60],
            ['title' => 'American Journal of Artificial Intelligence Website', 'charges' => 85],
            ['title' => 'American Journal of Astronomy and Astrophysics Website', 'charges' => 60],
            ['title' => 'American Journal of BioScience Website', 'charges' => 85],
        ];

        foreach ($journals as $journal) {
            Journal::create([
                'title' => $journal['title'],
                'slug' => Str::slug($journal['title']),
                'charges' => $journal['charges'],
                'status' => 'active', // Set default status
                'journal_subject_id' => null, // Set appropriate subject ID
            ]);
        }
    }
}
