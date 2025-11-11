<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Upacara Bendera Hari Senin',
                'description' => 'Upacara bendera rutin setiap hari Senin untuk menanamkan nilai-nilai kebangsaan dan kedisiplinan kepada seluruh siswa.',
                'category' => 'academic',
                'status' => 'published',
                'start_date' => now()->addDays(1)->setTime(7, 0),
                'end_date' => now()->addDays(1)->setTime(8, 0),
                'location' => 'Lapangan Upacara SMKN 4 Bogor',
                'organizer' => 'OSIS SMKN 4 Bogor'
            ],
            [
                'title' => 'Lomba Futsal Antar Kelas',
                'description' => 'Kompetisi futsal antar kelas untuk mengembangkan semangat sportivitas dan kerja sama tim di kalangan siswa.',
                'category' => 'sports',
                'status' => 'published',
                'start_date' => now()->addDays(7)->setTime(8, 0),
                'end_date' => now()->addDays(7)->setTime(16, 0),
                'location' => 'Lapangan Futsal SMKN 4 Bogor',
                'organizer' => 'Ekstrakurikuler Olahraga'
            ],
            [
                'title' => 'Pentas Seni Budaya',
                'description' => 'Pertunjukan seni budaya yang menampilkan berbagai kesenian tradisional dan modern dari siswa-siswi SMKN 4 Bogor.',
                'category' => 'cultural',
                'status' => 'published',
                'start_date' => now()->addDays(14)->setTime(19, 0),
                'end_date' => now()->addDays(14)->setTime(22, 0),
                'location' => 'Aula Utama SMKN 4 Bogor',
                'organizer' => 'Ekstrakurikuler Seni'
            ]
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
