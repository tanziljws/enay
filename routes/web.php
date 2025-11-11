<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\GalleryAdminController;
use App\Models\GalleryItem;
use App\Http\Controllers\Admin\StudentAdminController;
use App\Http\Controllers\Admin\TeacherAdminController;
use App\Http\Controllers\Admin\AttendancePhotoAdminController;
use App\Http\Controllers\Admin\MajorAdminController;
use App\Http\Controllers\Admin\ActivityAdminController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserInteractionController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    $latestNews = \App\Models\News::where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->limit(3)
        ->get();
    return view('home', compact('latestNews'));
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/news', function () {
    $news = \App\Models\News::where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->paginate(10);
    return view('news', compact('news'));
});

Route::get('/news/{id}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

Route::get('/contact', function () {
    return view('contact');
});

// Contact form submission
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/jurusan', function () {
    $majors = \App\Models\Major::active()->ordered()->get();
    return view('jurusan', compact('majors'));
});

Route::get('/galeri', function () {
    $items = \App\Models\GalleryItem::where('status','published')
        ->orderByDesc('taken_at')
        ->orderByDesc('created_at')
        ->paginate(12);
    
    // Load reactions and comments count for each item
    foreach ($items as $item) {
        $item->likes_count = \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count();
        $item->dislikes_count = \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'dislike')->count();
        $item->comments_count = \App\Models\GalleryUserComment::where('gallery_item_id', $item->id)->count();
        
        // Get user's reaction if authenticated
        if (auth()->check()) {
            $userReaction = \App\Models\GalleryReaction::where('gallery_item_id', $item->id)
                ->where('user_id', auth()->id())
                ->first();
            $item->user_reaction = $userReaction ? $userReaction->type : null;
        } else {
            $item->user_reaction = null;
        }
    }
    
    return view('galeri', compact('items'));
});

// Teachers routes
Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');

// Halaman kelas dinonaktifkan sesuai permintaan

// CAPTCHA Routes
Route::get('/captcha/generate', [\App\Http\Controllers\CaptchaController::class, 'generate'])->name('captcha.generate');
Route::post('/captcha/verify', [\App\Http\Controllers\CaptchaController::class, 'verify'])->name('captcha.verify');

// User Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin - User Interactions Management
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/interactions', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'dashboard'])->name('admin.interactions.dashboard');
    Route::get('/interactions/gallery', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'galleryInteractions'])->name('admin.interactions.gallery');
    Route::get('/interactions/news', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'newsInteractions'])->name('admin.interactions.news');
    Route::get('/interactions/teachers', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'teacherInteractions'])->name('admin.interactions.teachers');
    Route::get('/interactions/downloads', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'downloads'])->name('admin.interactions.downloads');
    
    // Delete comments
    Route::delete('/interactions/gallery/comment/{id}', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'deleteGalleryComment'])->name('admin.interactions.gallery.comment.delete');
    Route::delete('/interactions/news/comment/{id}', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'deleteNewsComment'])->name('admin.interactions.news.comment.delete');
    Route::delete('/interactions/teacher/comment/{id}', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'deleteTeacherComment'])->name('admin.interactions.teacher.comment.delete');
});

// User Interaction Routes (requires authentication)
Route::middleware('auth')->group(function () {
    // User Profile
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    
    // Gallery interactions
    Route::post('/gallery/{id}/reaction', [UserInteractionController::class, 'toggleGalleryReaction'])->name('gallery.reaction');
    Route::post('/gallery/{id}/comment', [UserInteractionController::class, 'addGalleryComment'])->name('gallery.comment');
    Route::get('/gallery/{id}/comments', [UserInteractionController::class, 'getGalleryComments'])->name('gallery.comments');
    Route::delete('/gallery/comment/{id}', [UserInteractionController::class, 'deleteGalleryComment'])->name('gallery.comment.delete');
    Route::get('/gallery/{id}/download', [UserInteractionController::class, 'downloadGalleryImage'])->name('gallery.download');
    
    // News interactions
    Route::post('/news/{id}/reaction', [UserInteractionController::class, 'toggleNewsReaction'])->name('news.reaction');
    Route::post('/news/{id}/comment', [UserInteractionController::class, 'addNewsComment'])->name('news.comment');
    Route::get('/news/{id}/comments', [UserInteractionController::class, 'getNewsComments'])->name('news.comments');
    Route::delete('/news/comment/{id}', [UserInteractionController::class, 'deleteNewsComment'])->name('news.comment.delete');
    Route::get('/news/{id}/download', [UserInteractionController::class, 'downloadNewsImage'])->name('news.download');
    
    // Teacher interactions
    Route::post('/teacher/{id}/reaction', [UserInteractionController::class, 'toggleTeacherReaction'])->name('teacher.reaction');
    Route::post('/teacher/{id}/comment', [UserInteractionController::class, 'addTeacherComment'])->name('teacher.comment');
    Route::get('/teacher/{id}/comments', [UserInteractionController::class, 'getTeacherComments'])->name('teacher.comments');
    Route::delete('/teacher/comment/{id}', [UserInteractionController::class, 'deleteTeacherComment'])->name('teacher.comment.delete');
});

// Admin auth
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Comment Management
    Route::get('/admin/comments', [\App\Http\Controllers\Admin\CommentManagementController::class, 'index'])->name('admin.comments.index');
    Route::delete('/admin/comments/{id}', [\App\Http\Controllers\Admin\CommentManagementController::class, 'destroy'])->name('admin.comments.destroy');
    Route::delete('/admin/comments-bulk', [\App\Http\Controllers\Admin\CommentManagementController::class, 'bulkDelete'])->name('admin.comments.bulk-delete');

    // Admin News CRUD
    Route::resource('/admin/news', NewsAdminController::class)->except(['show'])->names('admin.news');

    // Admin Gallery CRUD
    Route::resource('/admin/gallery', GalleryAdminController::class)->except(['show'])->parameters([
        'gallery' => 'gallery'
    ])->names('admin.gallery');

    // Admin Students & Teachers CRUD
    Route::resource('/admin/students', StudentAdminController::class)->except(['show'])->names('admin.students');
    Route::resource('/admin/teachers', TeacherAdminController::class)->except(['show'])->names('admin.teachers');

    // Admin Attendance Photos
    Route::resource('/admin/attendance-photos', AttendancePhotoAdminController::class)->only(['index','create','store','destroy'])->parameter('attendance-photos','attendance')->names('admin.attendance');

    // Admin Majors CRUD
    Route::resource('/admin/majors', MajorAdminController::class)->names('admin.majors');

    // Admin Activities CRUD
    Route::resource('/admin/activities', ActivityAdminController::class)->names('admin.activities');

    // Admin Comments Management
    Route::get('/admin/comments/news', [CommentAdminController::class, 'newsComments'])->name('admin.comments.news');
    Route::post('/admin/comments/news/{id}/approve', [CommentAdminController::class, 'approveNewsComment'])->name('admin.comments.news.approve');
    Route::delete('/admin/comments/news/{id}', [CommentAdminController::class, 'deleteNewsComment'])->name('admin.comments.news.delete');
    
    Route::get('/admin/comments/gallery', [CommentAdminController::class, 'galleryComments'])->name('admin.comments.gallery');
    Route::post('/admin/comments/gallery/{id}/approve', [CommentAdminController::class, 'approveGalleryComment'])->name('admin.comments.gallery.approve');
    Route::delete('/admin/comments/gallery/{id}', [CommentAdminController::class, 'deleteGalleryComment'])->name('admin.comments.gallery.delete');

    // Admin Statistics
    Route::get('/admin/statistics', [CommentAdminController::class, 'statistics'])->name('admin.statistics');
});
