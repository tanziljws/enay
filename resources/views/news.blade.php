@extends('layouts.app')

@section('title', 'Berita - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Berita Sekolah</h1>
    
    <!-- News List -->
    <div id="news-container">
        @if($news->count() > 0)
            @foreach($news as $item)
                <div class="card news-card mb-4" style="border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}?v={{ time() }}" class="img-fluid rounded-start h-100" style="object-fit: cover; height: 200px;" alt="{{ $item->title }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center h-100" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <span class="badge bg-{{ $item->category == 'academic' ? 'primary' : ($item->category == 'sports' ? 'success' : ($item->category == 'events' ? 'warning' : ($item->category == 'announcements' ? 'info' : 'secondary'))) }} mb-2">
                                    {{ ucfirst($item->category == 'academic' ? 'Akademik' : ($item->category == 'sports' ? 'Olahraga' : ($item->category == 'events' ? 'Acara' : ($item->category == 'announcements' ? 'Pengumuman' : 'Umum')))) }}
                                </span>
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text">{{ Str::limit(strip_tags($item->content), 200) }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $item->author }} | 
                                        <i class="fas fa-calendar me-1"></i>{{ $item->published_at->format('d M Y') }}
                                    </small>
                                </p>
                                <a href="/news/{{ $item->id }}" class="btn btn-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">Tidak ada berita yang ditemukan.</div>
        @endif
    </div>
    
    <!-- Pagination -->
    <nav aria-label="News pagination" class="mt-4">
        <div class="d-flex justify-content-center">
            {{ $news->links() }}
        </div>
    </nav>
</div>
@endsection

@section('scripts')
<style>
.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.news-card .card-body {
    padding: 20px;
}

.news-card .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2c3e50;
}

.news-card .card-text {
    color: #6c757d;
    line-height: 1.5;
}

.news-card .badge {
    font-size: 0.75rem;
    padding: 5px 10px;
}
</style>
@endsection
