@extends('admin.layouts.app')

@section('title', 'Dashboard Interaksi User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Interaksi User</h1>
        <div>
            <span class="badge bg-primary">Total Users: {{ $stats['total_users'] }}</span>
            <span class="badge bg-primary">User Baru Minggu Ini: {{ $weeklyStats['new_users'] }}</span>
        </div>
    </div>
    
    <!-- Weekly Report Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-calendar-week"></i> Laporan Minggu Ini (7 Hari Terakhir)
            </h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-2">
                    <h5 class="text-primary">{{ $weeklyStats['gallery_likes'] }}</h5>
                    <small>Galeri Likes</small>
                </div>
                <div class="col-md-2">
                    <h5 class="text-secondary">{{ $weeklyStats['gallery_dislikes'] }}</h5>
                    <small>Galeri Dislikes</small>
                </div>
                <div class="col-md-2">
                    <h5 class="text-primary">{{ $weeklyStats['gallery_comments'] }}</h5>
                    <small>Galeri Comments</small>
                </div>
                <div class="col-md-2">
                    <h5 class="text-primary">{{ $weeklyStats['news_likes'] + $weeklyStats['teacher_likes'] }}</h5>
                    <small>Total Likes Lain</small>
                </div>
                <div class="col-md-2">
                    <h5 class="text-primary">{{ $weeklyStats['downloads'] }}</h5>
                    <small>Downloads</small>
                </div>
                <div class="col-md-2">
                    <h5 class="text-primary">{{ $weeklyStats['new_users'] }}</h5>
                    <small>User Baru</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards (Total) -->
    <div class="row">
        <!-- Gallery Stats -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Galeri
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-thumbs-up text-primary"></i> {{ $stats['total_gallery_likes'] }} Likes
                                <br>
                                <i class="fas fa-thumbs-down text-secondary"></i> {{ $stats['total_gallery_dislikes'] }} Dislikes
                                <br>
                                <i class="fas fa-comment text-primary"></i> {{ $stats['total_gallery_comments'] }} Comments
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- News Stats -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Berita
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-thumbs-up text-primary"></i> {{ $stats['total_news_likes'] }} Likes
                                <br>
                                <i class="fas fa-thumbs-down text-secondary"></i> {{ $stats['total_news_dislikes'] }} Dislikes
                                <br>
                                <i class="fas fa-comment text-primary"></i> {{ $stats['total_news_comments'] }} Comments
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teacher Stats -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Guru
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-thumbs-up text-primary"></i> {{ $stats['total_teacher_likes'] }} Likes
                                <br>
                                <i class="fas fa-thumbs-down text-secondary"></i> {{ $stats['total_teacher_dislikes'] }} Dislikes
                                <br>
                                <i class="fas fa-comment text-primary"></i> {{ $stats['total_teacher_comments'] }} Comments
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Downloads -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Downloads
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-download text-primary"></i> {{ $stats['total_downloads'] }} Files Downloaded
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-download fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.interactions.gallery') }}" class="btn btn-primary btn-block w-100">
                <i class="fas fa-images"></i> Lihat Interaksi Galeri
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.interactions.news') }}" class="btn btn-primary btn-block w-100">
                <i class="fas fa-newspaper"></i> Lihat Interaksi Berita
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.interactions.teachers') }}" class="btn btn-primary btn-block w-100">
                <i class="fas fa-chalkboard-teacher"></i> Lihat Interaksi Guru
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.interactions.downloads') }}" class="btn btn-primary btn-block w-100">
                <i class="fas fa-download"></i> Lihat History Download
            </a>
        </div>
    </div>
    
    <!-- Recent Comments -->
    <div class="row">
        <!-- Gallery Comments -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Komentar Galeri Terbaru</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentGalleryComments as $comment)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>{{ $comment->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $comment->commentable->title ?? 'N/A' }}</small>
                            <p class="mb-1">{{ $comment->comment }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- News Comments -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Komentar Berita Terbaru</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentNewsComments as $comment)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>{{ $comment->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $comment->commentable->title ?? 'N/A' }}</small>
                            <p class="mb-1">{{ $comment->comment }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Teacher Comments -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Komentar Guru Terbaru</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentTeacherComments as $comment)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>{{ $comment->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $comment->commentable->name ?? 'N/A' }}</small>
                            <p class="mb-1">{{ $comment->comment }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
