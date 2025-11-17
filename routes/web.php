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
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    $latestNews = \App\Models\News::where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->orderBy('created_at', 'desc')
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
        ->orderBy('created_at', 'desc')
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
        ->orderBy('taken_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(12);
        
    // Sync storage to public for Windows
    if (app()->environment('local')) {
        $source = storage_path('app/public/gallery');
        $destination = public_path('storage/gallery');
        
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        if (file_exists($source)) {
            foreach (glob($source . '/*') as $file) {
                $destFile = $destination . '/' . basename($file);
                if (!file_exists($destFile)) {
                    copy($file, $destFile);
                }
            }
        }
    }
    
    return view('galeri', compact('items'));
});

// CAPTCHA routes for download modal
Route::get('/captcha/generate', [CaptchaController::class, 'generate'])->name('captcha.generate');
Route::post('/captcha/verify', [CaptchaController::class, 'verify'])->name('captcha.verify');

// Teachers routes
Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');

// Authentication Routes
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserAuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserAuthController::class, 'updateProfile'])->name('profile.update');
});

// Admin Authentication
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // News management
    Route::resource('news', NewsAdminController::class);
    
    // Gallery management
    // Custom routes must come BEFORE the resource so /admin/gallery/statistics
    // tidak tertangkap sebagai gallery/{gallery} (show)
    Route::get('/gallery/statistics', [GalleryAdminController::class, 'statistics'])->name('gallery.statistics');
    Route::get('/gallery/print-report', [GalleryAdminController::class, 'printReport'])->name('gallery.print-report');
    Route::resource('gallery', GalleryAdminController::class);
    
    // Student management
    Route::resource('students', StudentAdminController::class);
    
    // Teacher management
    Route::resource('teachers', TeacherAdminController::class)->names([
        'index' => 'teachers.admin.index',
        'create' => 'teachers.admin.create',
        'store' => 'teachers.admin.store',
        'show' => 'teachers.admin.show',
        'edit' => 'teachers.admin.edit',
        'update' => 'teachers.admin.update',
        'destroy' => 'teachers.admin.destroy',
    ]);
    
    // Major management
    Route::resource('majors', MajorAdminController::class);
    
    // Activity management
    Route::resource('activities', ActivityAdminController::class);
    
    // Comments management
    Route::get('/comments', [\App\Http\Controllers\Admin\CommentManagementController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{id}', [\App\Http\Controllers\Admin\CommentManagementController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/bulk-delete', [\App\Http\Controllers\Admin\CommentManagementController::class, 'bulkDelete'])->name('comments.bulk-delete');
        
    // Interactions dashboard & detail pages
    Route::get('/interactions', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'dashboard'])->name('interactions.dashboard');
    Route::get('/interactions/gallery', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'galleryInteractions'])->name('interactions.gallery');
    Route::get('/interactions/news', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'newsInteractions'])->name('interactions.news');
    Route::get('/interactions/teachers', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'teacherInteractions'])->name('interactions.teachers');
    Route::get('/interactions/downloads', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'downloads'])->name('interactions.downloads');
    Route::delete('/interactions/gallery/comment/{id}', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'deleteGalleryComment'])->name('interactions.gallery.comment.delete');
    Route::delete('/interactions/teacher/comment/{id}', [\App\Http\Controllers\Admin\InteractionAdminController::class, 'deleteTeacherComment'])->name('interactions.teacher.comment.delete');
    
    // Pages management
    Route::get('/pages', [\App\Http\Controllers\Admin\PageAdminController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [\App\Http\Controllers\Admin\PageAdminController::class, 'create'])->name('pages.create');
    Route::post('/pages', [\App\Http\Controllers\Admin\PageAdminController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [\App\Http\Controllers\Admin\PageAdminController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [\App\Http\Controllers\Admin\PageAdminController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [\App\Http\Controllers\Admin\PageAdminController::class, 'destroy'])->name('pages.destroy');
    
    // Home page
    Route::get('/pages/home', [\App\Http\Controllers\Admin\PageAdminController::class, 'editHome'])->name('pages.home.edit');
    Route::put('/pages/home', [\App\Http\Controllers\Admin\PageAdminController::class, 'updateHome'])->name('pages.home.update');
    
    // About page
    Route::get('/pages/about', [\App\Http\Controllers\Admin\PageAdminController::class, 'editAbout'])->name('pages.about.edit');
    Route::put('/pages/about', [\App\Http\Controllers\Admin\PageAdminController::class, 'updateAbout'])->name('pages.about.update');
    
    // Contact page
    Route::get('/pages/contact', [\App\Http\Controllers\Admin\PageAdminController::class, 'editContact'])->name('pages.contact.edit');
    Route::put('/pages/contact', [\App\Http\Controllers\Admin\PageAdminController::class, 'updateContact'])->name('pages.contact.update');
});

// User interaction routes (like, comment, download)
Route::middleware('auth')->group(function () {
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