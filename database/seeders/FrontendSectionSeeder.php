<?php

namespace Database\Seeders;

use App\Models\FrontendSection;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Null_;

class FrontendSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FrontendSection::insert([
            ['name' => 'Hero Area', 'page_title' => 'Banner page title', 'title' => 'Business Potential With
            Service Provider Laravel Script.', 'slug' => 'hero_area', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_ACTIVE, 'has_description' => STATUS_ACTIVE, 'has_image' => STATUS_ACTIVE, 'description' => 'Our service provider software is here to transform the way you handle your clients & team members, it will drive your future business to new heights.', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'Core Features', 'page_title' => 'Core Features', 'title' => 'Zaigency made the app effortless with some core pages', 'slug' => 'core_features', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_PENDING, 'has_image' => STATUS_PENDING, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'Features', 'page_title' => 'Features', 'title' => 'We Have Some amazing Features For You', 'slug' => 'features', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_ACTIVE, 'has_image' => STATUS_PENDING, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'Pricing', 'page_title' => 'Pricing', 'title' => "We Put An Affordable Pricing Plan.", 'slug' => 'pricing', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_PENDING, 'has_image' => STATUS_PENDING, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'Services', 'page_title' => 'Services', 'title' => "", 'slug' => 'services', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_PENDING, 'has_image' => STATUS_ACTIVE, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => 'Testimonials', 'page_title' => 'Testimonials', 'title' => 'See What Our client says.', 'slug' => 'testimonials_area', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_ACTIVE, 'has_image' => STATUS_PENDING, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()],

            ['name' => "Faq's", 'page_title' => "FAQ'S", 'title' => 'Frequently Asked Question.', 'slug' => 'faqs_area', 'has_page_title' => STATUS_ACTIVE, 'has_banner_image' => STATUS_PENDING, 'has_description' => STATUS_ACTIVE, 'has_image' => STATUS_PENDING, 'description' => '', 'image' => NULL, 'status' => STATUS_ACTIVE, 'banner_image' => NULL, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
