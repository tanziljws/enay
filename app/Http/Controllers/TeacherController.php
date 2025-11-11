<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        // Handle both old 'major' parameter and new 'category' parameter
        $selectedCategory = $request->get('category', 'guru');
        
        // If old 'major' parameter is used, redirect to show all teachers
        if ($request->has('major')) {
            $selectedCategory = 'guru'; // Default to guru when major is used
        }
        
        // Validate category parameter
        if (!in_array($selectedCategory, ['guru', 'staff'])) {
            $selectedCategory = 'guru';
        }
        
        // Get teachers from database based on category
        try {
            $query = Teacher::where('status', 'active');
            
            // Filter by role
            if ($selectedCategory == 'guru') {
                $query->where('role', 'guru');
            } elseif ($selectedCategory == 'staff') {
                $query->where('role', 'staff');
            }
            
            $teachers = $query->orderBy('name')->get();
            
            // Ensure $teachers is never null
            if ($teachers === null) {
                $teachers = collect([]);
            }
        } catch (\Exception $e) {
            // If any error occurs, return empty collection
            $teachers = collect([]);
            \Log::error('Error fetching teachers: ' . $e->getMessage());
        }
        
        return view('teachers', compact('teachers', 'selectedCategory'));
    }
    
    private function getSampleTeachers($major)
    {
        $teachers = [
            'PPLG' => [
                [
                    'id' => 1,
                    'name' => 'Dr. Ahmad Wijaya, S.Kom, M.Kom',
                    'teacher_number' => 'T001',
                    'email' => 'ahmad.wijaya@smknegenay.sch.id',
                    'phone' => '081234567890',
                    'gender' => 'male',
                    'specialization' => 'Pemrograman Web & Mobile',
                    'join_date' => '2015-08-01',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 2,
                    'name' => 'Siti Nurhaliza, S.Kom',
                    'teacher_number' => 'T002',
                    'email' => 'siti.nurhaliza@smknegenay.sch.id',
                    'phone' => '081234567891',
                    'gender' => 'female',
                    'specialization' => 'Database & Sistem Informasi',
                    'join_date' => '2018-01-15',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 3,
                    'name' => 'Budi Santoso, S.T, M.T',
                    'teacher_number' => 'T003',
                    'email' => 'budi.santoso@smknegenay.sch.id',
                    'phone' => '081234567892',
                    'gender' => 'male',
                    'specialization' => 'Jaringan Komputer & Keamanan',
                    'join_date' => '2012-07-01',
                    'status' => 'active',
                    'photo' => null
                ]
            ],
            'TJKT' => [
                [
                    'id' => 4,
                    'name' => 'Ir. Muhammad Rizki, M.T',
                    'teacher_number' => 'T004',
                    'email' => 'muhammad.rizki@smknegenay.sch.id',
                    'phone' => '081234567893',
                    'gender' => 'male',
                    'specialization' => 'Jaringan & Telekomunikasi',
                    'join_date' => '2010-03-01',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 5,
                    'name' => 'Dewi Kartika, S.T, M.T',
                    'teacher_number' => 'T005',
                    'email' => 'dewi.kartika@smknegenay.sch.id',
                    'phone' => '081234567894',
                    'gender' => 'female',
                    'specialization' => 'Sistem Komputer & Mikrokontroler',
                    'join_date' => '2016-08-15',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 6,
                    'name' => 'Agus Prasetyo, S.T',
                    'teacher_number' => 'T006',
                    'email' => 'agus.prasetyo@smknegenay.sch.id',
                    'phone' => '081234567895',
                    'gender' => 'male',
                    'specialization' => 'Administrasi Jaringan',
                    'join_date' => '2020-01-10',
                    'status' => 'active',
                    'photo' => null
                ]
            ],
            'TO' => [
                [
                    'id' => 7,
                    'name' => 'Prof. Dr. Sri Wahyuni, M.Pd',
                    'teacher_number' => 'T007',
                    'email' => 'sri.wahyuni@smknegenay.sch.id',
                    'phone' => '081234567896',
                    'gender' => 'female',
                    'specialization' => 'Teknologi Otomotif & Mesin',
                    'join_date' => '2008-01-01',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 8,
                    'name' => 'Hendra Kurniawan, S.T, M.T',
                    'teacher_number' => 'T008',
                    'email' => 'hendra.kurniawan@smknegenay.sch.id',
                    'phone' => '081234567897',
                    'gender' => 'male',
                    'specialization' => 'Motor & Sistem Kelistrikan',
                    'join_date' => '2014-06-01',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 9,
                    'name' => 'Rina Sari, S.T',
                    'teacher_number' => 'T009',
                    'email' => 'rina.sari@smknegenay.sch.id',
                    'phone' => '081234567898',
                    'gender' => 'female',
                    'specialization' => 'Body & Paint Otomotif',
                    'join_date' => '2019-03-01',
                    'status' => 'active',
                    'photo' => null
                ]
            ],
            'TP' => [
                [
                    'id' => 10,
                    'name' => 'Dr. Indra Gunawan, S.T, M.T',
                    'teacher_number' => 'T010',
                    'email' => 'indra.gunawan@smknegenay.sch.id',
                    'phone' => '081234567899',
                    'gender' => 'male',
                    'specialization' => 'Teknologi Pangan & Pengolahan',
                    'join_date' => '2011-08-01',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 11,
                    'name' => 'Maya Sari, S.T, M.T',
                    'teacher_number' => 'T011',
                    'email' => 'maya.sari@smknegenay.sch.id',
                    'phone' => '081234567900',
                    'gender' => 'female',
                    'specialization' => 'Keamanan Pangan & Nutrisi',
                    'join_date' => '2017-01-15',
                    'status' => 'active',
                    'photo' => null
                ],
                [
                    'id' => 12,
                    'name' => 'Rudi Hartono, S.T',
                    'teacher_number' => 'T012',
                    'email' => 'rudi.hartono@smknegenay.sch.id',
                    'phone' => '081234567901',
                    'gender' => 'male',
                    'specialization' => 'Pengolahan & Pengawetan Pangan',
                    'join_date' => '2021-08-01',
                    'status' => 'active',
                    'photo' => null
                ]
            ]
        ];
        
        return $teachers[$major] ?? [];
    }
    
    public function show($id)
    {
        $teacher = Teacher::with(['reactions', 'comments.user'])
            ->withCount(['reactions as likes_count' => function($query) {
                $query->where('type', 'like');
            }])
            ->withCount(['reactions as dislikes_count' => function($query) {
                $query->where('type', 'dislike');
            }])
            ->withCount('comments')
            ->findOrFail($id);
        
        // Get user's reaction if authenticated
        if (auth()->check()) {
            $userReaction = $teacher->reactions()
                ->where('user_id', auth()->id())
                ->first();
            $teacher->user_reaction = $userReaction ? $userReaction->type : null;
        } else {
            $teacher->user_reaction = null;
        }
        
        return view('teachers.show', compact('teacher'));
    }
}