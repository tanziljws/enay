<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Student::with(['classRoom', 'grades', 'attendances']);

        // Filter by class room
        if ($request->has('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or student number
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_number' => 'required|string|unique:students,student_number',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'phone' => 'nullable|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'parent_name' => 'required|string|max:255',
                'parent_phone' => 'required|string',
                'class_room_id' => 'required|exists:class_rooms,id',
                'photo' => 'nullable|string',
                'status' => 'nullable|in:active,inactive,graduated'
            ]);

            $student = Student::create($validated);
            $student->load(['classRoom', 'grades', 'attendances']);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student
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
        $student = Student::with(['classRoom', 'grades.subject', 'grades.teacher', 'attendances'])
                          ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $student = Student::findOrFail($id);

            $validated = $request->validate([
                'student_number' => 'sometimes|string|unique:students,student_number,' . $id,
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'nullable|string',
                'date_of_birth' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female',
                'address' => 'sometimes|string',
                'parent_name' => 'sometimes|string|max:255',
                'parent_phone' => 'sometimes|string',
                'class_room_id' => 'sometimes|exists:class_rooms,id',
                'photo' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive,graduated'
            ]);

            $student->update($validated);
            $student->load(['classRoom', 'grades', 'attendances']);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student
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
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }
}
