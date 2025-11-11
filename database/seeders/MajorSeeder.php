<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            [
                'code' => 'PPLG',
                'name' => 'PPLG',
                'full_name' => 'Pengembangan Perangkat Lunak dan Gim',
                'description' => 'Program keahlian yang fokus pada pengembangan aplikasi perangkat lunak, game development, dan teknologi informasi modern.',
                'image' => 'images/pplg.jpg',
                'category' => 'technology',
                'student_count' => 120,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'code' => 'TJKT',
                'name' => 'TJKT',
                'full_name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'description' => 'Program keahlian yang mempelajari instalasi, konfigurasi, dan maintenance jaringan komputer serta sistem telekomunikasi.',
                'image' => 'images/tjkt.jpg',
                'category' => 'network',
                'student_count' => 95,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'code' => 'TP',
                'name' => 'TP',
                'full_name' => 'Teknik Pengelasan',
                'description' => 'Program keahlian yang mempelajari teknik pengelasan berbagai jenis logam, fabrikasi, dan konstruksi metal.',
                'image' => 'images/tp.jpg',
                'category' => 'other',
                'student_count' => 85,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'code' => 'TO',
                'name' => 'TO',
                'full_name' => 'Teknik Otomotif',
                'description' => 'Program keahlian yang mempelajari perawatan, perbaikan, dan modifikasi kendaraan bermotor.',
                'image' => 'images/to.jpg',
                'category' => 'other',
                'student_count' => 110,
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }
    }
}
