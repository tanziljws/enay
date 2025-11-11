# 🎯 Implementasi Lengkap Fitur Interaksi untuk Berita & Guru

## ✅ File yang Sudah Dibuat Sebelumnya:
1. `resources/views/partials/interaction-buttons.blade.php` ✅
2. `resources/views/partials/download-news-modal.blade.php` ✅
3. `resources/views/partials/download-teacher-modal.blade.php` ✅
4. `public/js/interactions.js` ✅

## 📝 Langkah Implementasi:

### STEP 1: Buat Controller untuk Detail Berita

Buat file: `app/Http/Controllers/NewsController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show($id)
    {
        $news = News::with(['reactions', 'comments.user'])
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
            $userReaction = $news->reactions()
                ->where('user_id', auth()->id())
                ->first();
            $news->user_reaction = $userReaction ? $userReaction->type : null;
        } else {
            $news->user_reaction = null;
        }
        
        // Get related news
        $relatedNews = News::where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();
        
        return view('news.show', compact('news', 'relatedNews'));
    }
}
```

### STEP 2: Buat View Detail Berita

Buat file: `resources/views/news/show.blade.php`

```blade
@extends('layouts.app')

@section('title', $news->title . ' - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <article class="card">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}?v={{ time() }}" 
                         class="card-img-top" 
                         alt="{{ $news->title }}" 
                         style="height: 400px; object-fit: cover; cursor: pointer;"
                         data-bs-toggle="modal" 
                         data-bs-target="#newsImageModal">
                @endif
                
                <div class="card-body p-4">
                    <span class="badge bg-primary mb-3">{{ ucfirst($news->category) }}</span>
                    <h1 class="card-title mb-3">{{ $news->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-4 text-muted">
                        <i class="fas fa-user me-2"></i>
                        <span class="me-4">{{ $news->author }}</span>
                        <i class="fas fa-calendar me-2"></i>
                        <span>{{ $news->published_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="card-text mb-4">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                    
                    <!-- Interaction Buttons -->
                    @include('partials.interaction-buttons', [
                        'type' => 'news',
                        'itemId' => $news->id,
                        'likes' => $news->likes_count,
                        'dislikes' => $news->dislikes_count,
                        'userReaction' => $news->user_reaction,
                        'commentsCount' => $news->comments_count,
                        'hasDownload' => $news->image ? true : false
                    ])
                </div>
            </article>
            
            <div class="mt-4">
                <a href="{{ url('/news') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Berita
                </a>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Berita Terkait</h5>
                </div>
                <div class="card-body">
                    @forelse($relatedNews as $item)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;" 
                                         alt="{{ $item->title }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-newspaper text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="{{ route('news.show', $item->id) }}" class="text-decoration-none">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $item->published_at->format('d M Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada berita terkait</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
@if($news->image)
<div class="modal fade" id="newsImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $news->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid" alt="{{ $news->title }}">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Download Modal -->
@auth
@if($news->image)
    @include('partials.download-news-modal', ['newsId' => $news->id])
@endif
@endauth
@endsection
```

### STEP 3: Buat Controller untuk Detail Guru

Buat file: `app/Http/Controllers/TeacherController.php` (update yang sudah ada)

Tambahkan method `show`:

```php
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
```

### STEP 4: Buat View Detail Guru

Buat file: `resources/views/teachers/show.blade.php`

```blade
@extends('layouts.app')

@section('title', $teacher->name . ' - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                @if($teacher->photo)
                    <img src="{{ asset('storage/' . $teacher->photo) }}?v={{ time() }}" 
                         class="card-img-top" 
                         alt="{{ $teacher->name }}" 
                         style="height: 400px; object-fit: cover; cursor: pointer;"
                         data-bs-toggle="modal" 
                         data-bs-target="#teacherPhotoModal">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 400px;">
                        <i class="fas fa-{{ $teacher->gender == 'male' ? 'male' : 'female' }} fa-5x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <h1 class="card-title mb-3">{{ $teacher->name }}</h1>
                    
                    <div class="mb-4">
                        @if($teacher->role)
                            <span class="badge bg-primary">{{ ucfirst($teacher->role) }}</span>
                        @endif
                        @if($teacher->status == 'active')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                    
                    <div class="row mb-4">
                        @if($teacher->gender)
                        <div class="col-md-6 mb-2">
                            <strong>Jenis Kelamin:</strong> {{ $teacher->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Interaction Buttons -->
                    @include('partials.interaction-buttons', [
                        'type' => 'teacher',
                        'itemId' => $teacher->id,
                        'likes' => $teacher->likes_count,
                        'dislikes' => $teacher->dislikes_count,
                        'userReaction' => $teacher->user_reaction,
                        'commentsCount' => $teacher->comments_count,
                        'hasDownload' => $teacher->photo ? true : false
                    ])
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ url('/teachers') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Guru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
@if($teacher->photo)
<div class="modal fade" id="teacherPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $teacher->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $teacher->photo) }}" class="img-fluid" alt="{{ $teacher->name }}">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Download Modal -->
@auth
@if($teacher->photo)
    @include('partials.download-teacher-modal', ['teacherId' => $teacher->id])
@endif
@endauth
@endsection
```

### STEP 5: Update Routes

Edit file: `routes/web.php`

Tambahkan routes:

```php
// News detail
Route::get('/news/{id}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Teacher detail (jika belum ada)
Route::get('/teachers/{id}', [App\Http\Controllers\TeacherController::class, 'show'])->name('teachers.show');
```

### STEP 6: Update Card di Halaman List

Update link di halaman list berita dan guru agar mengarah ke detail page.

**Untuk Berita** (`resources/views/news.blade.php`):
```blade
<a href="{{ route('news.show', $news->id) }}" class="text-decoration-none">
    <!-- Card content -->
</a>
```

**Untuk Guru** (`resources/views/teachers.blade.php`):
```blade
<a href="{{ route('teachers.show', $teacher->id) }}" class="text-decoration-none">
    <!-- Card content -->
</a>
```

## ✅ Hasil Akhir:

### Fitur yang Tersedia:

#### Berita:
- ✅ Like/Dislike dengan counter
- ✅ Comment dengan form dan list
- ✅ Download gambar dengan CAPTCHA
- ✅ Klik gambar untuk zoom
- ✅ Berita terkait di sidebar

#### Guru:
- ✅ Like/Dislike dengan counter
- ✅ Comment dengan form dan list
- ✅ Download foto dengan CAPTCHA
- ✅ Klik foto untuk zoom
- ✅ Info lengkap guru

## 🎯 Testing:

1. Buat controller files
2. Buat view files
3. Update routes
4. Clear cache: `php artisan optimize:clear`
5. Test di browser

---

**Semua fitur interaksi sama persis seperti di Galeri!** ✅
