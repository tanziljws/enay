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
                    <span class="badge bg-primary mb-3" style="background-color: #3d4f5d !important;">{{ ucfirst($news->category) }}</span>
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
                <div class="card-header" style="background-color: #3d4f5d; color: white;">
                    <h5 class="mb-0">Berita Terkait</h5>
                </div>
                <div class="card-body">
                    @forelse($relatedNews as $item)
                        <div class="d-flex mb-3 pb-3 border-bottom">
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
