<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Teacher::with(['classRooms', 'subjects', 'grades']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by specialization
        if ($request->has('specialization')) {
            $query->where('specialization', 'like', "%{$request->specialization}%");
        }

        // Search by name or teacher number
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('teacher_number', 'like', "%{$search}%");
            });
        }

        $teachers = $query->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'teacher_number' => 'required|string|unique:teachers,teacher_number',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:teachers,email',
                'phone' => 'nullable|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'qualification' => 'required|string|max:255',
                'specialization' => 'required|string|max:255',
                'join_date' => 'required|date',
                'photo' => 'nullable|string',
                'status' => 'nullable|in:active,inactive',
                'subjects' => 'nullable|array',
                'subjects.*' => 'exists:subjects,id'
            ]);

            $subjects = $validated['subjects'] ?? [];
            unset($validated['subjects']);

            $teacher = Teacher::create($validated);
            
            if (!empty($subjects)) {
                $teacher->subjects()->attach($subjects);
            }

            $teacher->load(['classRooms', 'subjects', 'grades']);

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'data' => $teacher
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $teacher = Teacher::with(['classRooms', 'subjects', 'grades.student', 'grades.subject'])
                          ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $teacher = Teacher::findOrFail($id);

            $validated = $request->validate([
                'teacher_number' => 'sometimes|string|unique:teachers,teacher_number,' . $id,
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:teachers,email,' . $id,
                'phone' => 'nullable|string',
                'date_of_birth' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female',
                'address' => 'sometimes|string',
                'qualification' => 'sometimes|string|max:255',
                'specialization' => 'sometimes|string|max:255',
                'join_date' => 'sometimes|date',
                'photo' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive',
                'subjects' => 'nullable|array',
                'subjects.*' => 'exists:subjects,id'
            ]);

            $subjects = $validated['subjects'] ?? null;
            unset($validated['subjects']);

            $teacher->update($validated);
            
            if ($subjects !== null) {
                $teacher->subjects()->sync($subjects);
            }

            $teacher->load(['classRooms', 'subjects', 'grades']);

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'data' => $teacher
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully'
        ]);
    }
}
