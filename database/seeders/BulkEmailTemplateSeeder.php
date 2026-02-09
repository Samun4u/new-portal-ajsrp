<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulkEmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('bulk_email_templates')->insert([
            [
                'name'    => 'Test Email 1',
                'subject' => 'This is a test email subject 1',
                'body'    => '<p>Hello, this is the body of test email 1.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'    => 'Test Email 2',
                'subject' => 'This is a test email subject 2',
                'body'    => '<p>Hello, this is the body of test email 2.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'    => 'Test Email 3',
                'subject' => 'This is a test email subject 3',
                'body'    => '<p>Hello, this is the body of test email 3.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
