<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsItems = [
            [
                'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2025/2026',
                'content' => 'SMK Negeri 4 Kota Bogor membuka pendaftaran siswa baru untuk tahun ajaran 2025/2026. Pendaftaran dibuka mulai tanggal 1 Januari 2025 hingga 31 Maret 2025. Program keahlian yang tersedia meliputi PPLG, TJKT, TP, dan TO.',
                'author' => 'Admin SMKN 4 Bogor',
                'category' => 'announcements',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'image' => 'images/bg0.jpeg'
            ],
            [
                'title' => 'Lomba Futsal Antar Kelas Berhasil Diselenggarakan',
                'content' => 'Lomba futsal antar kelas yang diselenggarakan pada tanggal 15 September 2025 berhasil diselenggarakan dengan meriah. Kelas XII PPLG 1 berhasil menjadi juara pertama setelah mengalahkan kelas XII TJKT 2 dengan skor 3-1.',
                'author' => 'Tim Olahraga SMKN 4',
                'category' => 'sports',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'image' => 'images/about.jpeg'
            ],
            [
                'title' => 'Workshop Teknologi Terkini untuk Siswa PPLG',
                'content' => 'Dalam rangka meningkatkan kompetensi siswa program keahlian PPLG, sekolah mengadakan workshop teknologi terkini yang menghadirkan praktisi dari industri teknologi. Workshop ini membahas perkembangan terbaru dalam pengembangan aplikasi mobile dan web.',
                'author' => 'Jurusan PPLG',
                'category' => 'academic',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'image' => 'images/logo.png'
            ],
            [
                'title' => 'Pentas Seni Budaya SMKN 4 Bogor',
                'content' => 'Pentas seni budaya tahunan SMKN 4 Bogor akan diselenggarakan pada tanggal 25 September 2025 di Aula Utama sekolah. Acara ini akan menampilkan berbagai kesenian tradisional dan modern dari siswa-siswi SMKN 4 Bogor.',
                'author' => 'Ekstrakurikuler Seni',
                'category' => 'events',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'image' => 'images/bg0.jpeg'
            ]
        ];

        foreach ($newsItems as $news) {
            News::create($news);
        }
    }
}
