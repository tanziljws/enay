<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\News;
use App\Models\GalleryItem;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create Subjects
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK', 'description' => 'Mata pelajaran matematika', 'credits' => 4, 'level' => 'SMA'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'description' => 'Mata pelajaran bahasa Indonesia', 'credits' => 3, 'level' => 'SMA'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG', 'description' => 'Mata pelajaran bahasa Inggris', 'credits' => 3, 'level' => 'SMA'],
            ['name' => 'Fisika', 'code' => 'FIS', 'description' => 'Mata pelajaran fisika', 'credits' => 3, 'level' => 'SMA'],
            ['name' => 'Kimia', 'code' => 'KIM', 'description' => 'Mata pelajaran kimia', 'credits' => 3, 'level' => 'SMA'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Create Teachers
        $teachers = [
            [
                'teacher_number' => 'T001',
                'name' => 'Dr. Ahmad Wijaya, M.Pd',
                'email' => 'ahmad.wijaya@sekolah-enay.ac.id',
                'phone' => '081234567890',
                'date_of_birth' => '1980-05-15',
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'qualification' => 'S2 Pendidikan Matematika',
                'specialization' => 'Matematika',
                'join_date' => '2010-08-01',
                'status' => 'active'
            ],
            [
                'teacher_number' => 'T002',
                'name' => 'Siti Nurhaliza, S.Pd',
                'email' => 'siti.nurhaliza@sekolah-enay.ac.id',
                'phone' => '081234567891',
                'date_of_birth' => '1985-03-20',
                'gender' => 'female',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'qualification' => 'S1 Pendidikan Bahasa Indonesia',
                'specialization' => 'Bahasa Indonesia',
                'join_date' => '2012-01-15',
                'status' => 'active'
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }

        // Create Class Rooms
        $classRooms = [
            [
                'name' => 'X IPA 1',
                'code' => 'XIPA1',
                'description' => 'Kelas X IPA 1',
                'capacity' => 30,
                'teacher_id' => 1,
                'level' => 'SMA',
                'status' => 'active'
            ],
            [
                'name' => 'X IPA 2',
                'code' => 'XIPA2',
                'description' => 'Kelas X IPA 2',
                'capacity' => 30,
                'teacher_id' => 2,
                'level' => 'SMA',
                'status' => 'active'
            ],
        ];

        foreach ($classRooms as $classRoom) {
            ClassRoom::create($classRoom);
        }

        // Create Students
        $students = [
            [
                'student_number' => 'S001',
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@student.sekolah-enay.ac.id',
                'phone' => '081234567001',
                'date_of_birth' => '2007-01-15',
                'gender' => 'male',
                'address' => 'Jl. Kebon Jeruk No. 1, Jakarta',
                'parent_name' => 'Budi Pratama',
                'parent_phone' => '081234567101',
                'class_room_id' => 1,
                'status' => 'active'
            ],
            [
                'student_number' => 'S002',
                'name' => 'Sari Indah',
                'email' => 'sari.indah@student.sekolah-enay.ac.id',
                'phone' => '081234567002',
                'date_of_birth' => '2007-03-20',
                'gender' => 'female',
                'address' => 'Jl. Kemang Raya No. 2, Jakarta',
                'parent_name' => 'Siti Indah',
                'parent_phone' => '081234567102',
                'class_room_id' => 1,
                'status' => 'active'
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        // Create News
        $news = [
            [
                'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2024/2025',
                'content' => '<p>Kami mengundang calon siswa untuk mendaftar di Galeri Sekolah Enay untuk tahun ajaran 2024/2025. Pendaftaran dibuka mulai tanggal 1 Januari 2024.</p>',
                'author' => 'Admin Sekolah',
                'category' => 'announcements',
                'status' => 'published',
                'published_at' => now()
            ],
            [
                'title' => 'Juara 1 Olimpiade Matematika Tingkat Provinsi',
                'content' => '<p>Kami bangga mengumumkan bahwa siswa kami, Andi Pratama dari kelas X IPA 1, berhasil meraih juara 1 dalam Olimpiade Matematika Tingkat Provinsi DKI Jakarta.</p>',
                'author' => 'Tim Humas',
                'category' => 'academic',
                'status' => 'published',
                'published_at' => now()->subDays(2)
            ],
        ];

        foreach ($news as $newsItem) {
            News::create($newsItem);
        }

        // Create Gallery Items
        $galleryItems = [
            [
                'title' => 'Upacara Bendera Senin',
                'description' => 'Upacara bendera rutin setiap hari Senin',
                'image' => 'images/bg0.jpeg',
                'taken_at' => now()->subDays(5),
                'status' => 'published'
            ],
            [
                'title' => 'Kegiatan Pramuka',
                'description' => 'Kegiatan ekstrakurikuler pramuka',
                'image' => 'images/pplg.jpg',
                'taken_at' => now()->subDays(3),
                'status' => 'published'
            ],
            [
                'title' => 'Praktikum Laboratorium',
                'description' => 'Kegiatan praktikum di laboratorium kimia',
                'image' => 'images/tjkt.jpg',
                'taken_at' => now()->subDays(2),
                'status' => 'published'
            ],
            [
                'title' => 'Lomba Olahraga',
                'description' => 'Lomba olahraga antar kelas',
                'image' => 'images/to.jpg',
                'taken_at' => now()->subDays(1),
                'status' => 'published'
            ],
            [
                'title' => 'Kegiatan Seni',
                'description' => 'Pertunjukan seni dan budaya',
                'image' => 'images/tp.jpg',
                'taken_at' => now(),
                'status' => 'published'
            ],
        ];

        foreach ($galleryItems as $galleryItem) {
            GalleryItem::create($galleryItem);
        }

        $this->command->info('School data seeded successfully!');
    }
}