<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryItem;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleryItems = [
            [
                'title' => 'Upacara Bendera Hari Senin',
                'description' => 'Upacara bendera rutin setiap hari Senin untuk menanamkan nilai-nilai kebangsaan dan kedisiplinan kepada seluruh siswa.',
                'image' => 'images/bg0.jpeg', // Using existing image
                'taken_at' => now()->subDays(1)->setTime(7, 0),
                'status' => 'published'
            ],
            [
                'title' => 'Lomba Futsal Antar Kelas',
                'description' => 'Kompetisi futsal antar kelas untuk mengembangkan semangat sportivitas dan kerja sama tim di kalangan siswa.',
                'image' => 'images/about.jpeg', // Using existing image
                'taken_at' => now()->subDays(7)->setTime(8, 0),
                'status' => 'published'
            ],
            [
                'title' => 'Pentas Seni Budaya',
                'description' => 'Pertunjukan seni budaya yang menampilkan berbagai kesenian tradisional dan modern dari siswa-siswi SMKN 4 Bogor.',
                'image' => 'images/logo.png', // Using existing image
                'taken_at' => now()->subDays(14)->setTime(19, 0),
                'status' => 'published'
            ]
        ];

        foreach ($galleryItems as $item) {
            GalleryItem::create($item);
        }
    }
}
