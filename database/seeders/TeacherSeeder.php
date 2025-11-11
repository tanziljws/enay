<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            // PPLG Teachers
            [
                'teacher_number' => 'T001',
                'name' => 'Dr. Ahmad Wijaya, S.Kom, M.Kom',
                'email' => 'ahmad.wijaya@smknegenay.sch.id',
                'phone' => '081234567890',
                'date_of_birth' => '1975-05-15',
                'gender' => 'male',
                'address' => 'Jl. Pendidikan No. 123, Jakarta',
                'qualification' => 'S.Kom, M.Kom, Dr.',
                'specialization' => 'Pemrograman Web & Mobile',
                'major' => 'PPLG',
                'join_date' => '2015-08-01',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T002',
                'name' => 'Siti Nurhaliza, S.Kom',
                'email' => 'siti.nurhaliza@smknegenay.sch.id',
                'phone' => '081234567891',
                'date_of_birth' => '1980-03-22',
                'gender' => 'female',
                'address' => 'Jl. Teknologi No. 456, Jakarta',
                'qualification' => 'S.Kom',
                'specialization' => 'Database & Sistem Informasi',
                'major' => 'PPLG',
                'join_date' => '2018-01-15',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T003',
                'name' => 'Budi Santoso, S.T, M.T',
                'email' => 'budi.santoso@smknegenay.sch.id',
                'phone' => '081234567892',
                'date_of_birth' => '1978-11-10',
                'gender' => 'male',
                'address' => 'Jl. Komputer No. 789, Jakarta',
                'qualification' => 'S.T, M.T',
                'specialization' => 'Jaringan Komputer & Keamanan',
                'major' => 'PPLG',
                'join_date' => '2012-07-01',
                'status' => 'active',
                'photo' => null
            ],
            // TJKT Teachers
            [
                'teacher_number' => 'T004',
                'name' => 'Ir. Muhammad Rizki, M.T',
                'email' => 'muhammad.rizki@smknegenay.sch.id',
                'phone' => '081234567893',
                'date_of_birth' => '1970-08-05',
                'gender' => 'male',
                'address' => 'Jl. Telekomunikasi No. 321, Jakarta',
                'qualification' => 'Ir., M.T',
                'specialization' => 'Jaringan & Telekomunikasi',
                'major' => 'TJKT',
                'join_date' => '2010-03-01',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T005',
                'name' => 'Dewi Kartika, S.T, M.T',
                'email' => 'dewi.kartika@smknegenay.sch.id',
                'phone' => '081234567894',
                'date_of_birth' => '1985-12-18',
                'gender' => 'female',
                'address' => 'Jl. Elektronika No. 654, Jakarta',
                'qualification' => 'S.T, M.T',
                'specialization' => 'Sistem Komputer & Mikrokontroler',
                'major' => 'TJKT',
                'join_date' => '2016-08-15',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T006',
                'name' => 'Agus Prasetyo, S.T',
                'email' => 'agus.prasetyo@smknegenay.sch.id',
                'phone' => '081234567895',
                'date_of_birth' => '1990-07-25',
                'gender' => 'male',
                'address' => 'Jl. Jaringan No. 987, Jakarta',
                'qualification' => 'S.T',
                'specialization' => 'Administrasi Jaringan',
                'major' => 'TJKT',
                'join_date' => '2020-01-10',
                'status' => 'active',
                'photo' => null
            ],
            // TO Teachers
            [
                'teacher_number' => 'T007',
                'name' => 'Prof. Dr. Sri Wahyuni, M.Pd',
                'email' => 'sri.wahyuni@smknegenay.sch.id',
                'phone' => '081234567896',
                'date_of_birth' => '1965-04-12',
                'gender' => 'female',
                'address' => 'Jl. Otomotif No. 147, Jakarta',
                'qualification' => 'Prof. Dr., M.Pd',
                'specialization' => 'Teknologi Otomotif & Mesin',
                'major' => 'TO',
                'join_date' => '2008-01-01',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T008',
                'name' => 'Hendra Kurniawan, S.T, M.T',
                'email' => 'hendra.kurniawan@smknegenay.sch.id',
                'phone' => '081234567897',
                'date_of_birth' => '1982-09-30',
                'gender' => 'male',
                'address' => 'Jl. Motor No. 258, Jakarta',
                'qualification' => 'S.T, M.T',
                'specialization' => 'Motor & Sistem Kelistrikan',
                'major' => 'TO',
                'join_date' => '2014-06-01',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T009',
                'name' => 'Rina Sari, S.T',
                'email' => 'rina.sari@smknegenay.sch.id',
                'phone' => '081234567898',
                'date_of_birth' => '1988-01-14',
                'gender' => 'female',
                'address' => 'Jl. Body No. 369, Jakarta',
                'qualification' => 'S.T',
                'specialization' => 'Body & Paint Otomotif',
                'major' => 'TO',
                'join_date' => '2019-03-01',
                'status' => 'active',
                'photo' => null
            ],
            // TP Teachers
            [
                'teacher_number' => 'T010',
                'name' => 'Dr. Indra Gunawan, S.T, M.T',
                'email' => 'indra.gunawan@smknegenay.sch.id',
                'phone' => '081234567899',
                'date_of_birth' => '1972-06-08',
                'gender' => 'male',
                'address' => 'Jl. Pangan No. 741, Jakarta',
                'qualification' => 'Dr., S.T, M.T',
                'specialization' => 'Teknologi Pangan & Pengolahan',
                'major' => 'TP',
                'join_date' => '2011-08-01',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T011',
                'name' => 'Maya Sari, S.T, M.T',
                'email' => 'maya.sari@smknegenay.sch.id',
                'phone' => '081234567900',
                'date_of_birth' => '1983-10-20',
                'gender' => 'female',
                'address' => 'Jl. Nutrisi No. 852, Jakarta',
                'qualification' => 'S.T, M.T',
                'specialization' => 'Keamanan Pangan & Nutrisi',
                'major' => 'TP',
                'join_date' => '2017-01-15',
                'status' => 'active',
                'photo' => null
            ],
            [
                'teacher_number' => 'T012',
                'name' => 'Rudi Hartono, S.T',
                'email' => 'rudi.hartono@smknegenay.sch.id',
                'phone' => '081234567901',
                'date_of_birth' => '1992-02-28',
                'gender' => 'male',
                'address' => 'Jl. Pengolahan No. 963, Jakarta',
                'qualification' => 'S.T',
                'specialization' => 'Pengolahan & Pengawetan Pangan',
                'major' => 'TP',
                'join_date' => '2021-08-01',
                'status' => 'active',
                'photo' => null
            ]
        ];

        foreach ($teachers as $teacher) {
            \DB::table('teachers')->insert(array_merge($teacher, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}