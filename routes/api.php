<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendancePhotoController;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\ClassRoomController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NewsInteractionController;
use App\Http\Controllers\Api\GalleryInteractionController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    Route::get('/attendance-photos', [AttendancePhotoController::class, 'index']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/latest', [NewsController::class, 'latest']);
    Route::get('/news/{id}', [NewsController::class, 'show']);
    
    // Public information routes
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/{id}', [SubjectController::class, 'show']);
    
    Route::get('/class-rooms', [ClassRoomController::class, 'index']);
    Route::get('/class-rooms/{id}', [ClassRoomController::class, 'show']);

    // News Interactions (public)
    Route::post('/news/{id}/view', [NewsInteractionController::class, 'incrementView']);
    Route::post('/news/{id}/like', [NewsInteractionController::class, 'like']);
    Route::post('/news/{id}/dislike', [NewsInteractionController::class, 'dislike']);
    Route::post('/news/{id}/comment', [NewsInteractionController::class, 'addComment']);
    Route::get('/news/{id}/comments', [NewsInteractionController::class, 'getComments']);

    // Gallery Interactions (public)
    Route::post('/gallery/{id}/view', [GalleryInteractionController::class, 'incrementView']);
    Route::post('/gallery/{id}/like', [GalleryInteractionController::class, 'like']);
    Route::post('/gallery/{id}/dislike', [GalleryInteractionController::class, 'dislike']);
    Route::post('/gallery/{id}/comment', [GalleryInteractionController::class, 'addComment']);
    Route::get('/gallery/{id}/comments', [GalleryInteractionController::class, 'getComments']);
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Students
    Route::apiResource('students', StudentController::class);
    
    // Teachers
    Route::apiResource('teachers', TeacherController::class);
    
    // Class Rooms
    Route::apiResource('class-rooms', ClassRoomController::class);
    
    // Subjects
    Route::apiResource('subjects', SubjectController::class);
    
    // Grades
    Route::apiResource('grades', GradeController::class);
    
    // Attendance
    Route::apiResource('attendances', AttendanceController::class);
    
    // News (admin only)
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    
    // Additional routes
    Route::get('/students/{id}/grades', [StudentController::class, 'grades']);
    Route::get('/students/{id}/attendance', [StudentController::class, 'attendance']);
    Route::get('/teachers/{id}/subjects', [TeacherController::class, 'subjects']);
    Route::get('/class-rooms/{id}/students', [ClassRoomController::class, 'students']);
    Route::get('/class-rooms/{id}/attendance', [ClassRoomController::class, 'attendance']);
});

// Admin routes (additional middleware for admin access)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin specific routes can be added here
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_students' => \App\Models\Student::count(),
                'total_teachers' => \App\Models\Teacher::count(),
                'total_classes' => \App\Models\ClassRoom::count(),
                'total_subjects' => \App\Models\Subject::count(),
                'active_students' => \App\Models\Student::where('status', 'active')->count(),
                'active_teachers' => \App\Models\Teacher::where('status', 'active')->count(),
            ]
        ]);
    });
});
