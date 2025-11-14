<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Welcome to Our School',
                'content' => '<h1>Welcome to Our School</h1><p>This is the homepage content. You can edit this in the admin panel.</p>',
                'meta_title' => 'Welcome to Our School',
                'meta_description' => 'Welcome to our school website. We provide quality education for all students.',
                'meta_keywords' => 'school, education, learning, students',
                'is_active' => true,
            ],
            [
                'slug' => 'about',
                'title' => 'About Our School',
                'content' => '<h1>About Our School</h1><p>This is the about page content. You can edit this in the admin panel.</p>',
                'meta_title' => 'About Our School',
                'meta_description' => 'Learn more about our school, our mission, and our values.',
                'meta_keywords' => 'about, school, mission, values, history',
                'is_active' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'content' => '<h1>Contact Us</h1><p>This is the contact page content. You can edit this in the admin panel.</p>{contact_form}',
                'meta_title' => 'Contact Our School',
                'meta_description' => 'Get in touch with our school for more information.',
                'meta_keywords' => 'contact, address, phone, email, location',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
