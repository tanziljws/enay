@extends('layouts.app')

@section('title', 'Detail Berita - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div id="news-detail">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            
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
                <div class="card-body" id="related-news">
                    <div class="text-center">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const newsId = {{ $id }};
    loadNewsDetail(newsId);
    loadRelatedNews(newsId);
});

function loadNewsDetail(id) {
    $.get(API_BASE_URL + '/news/' + id)
        .done(function(response) {
            if (response.success) {
                displayNewsDetail(response.data);
            } else {
                $('#news-detail').html('<div class="alert alert-warning">Berita tidak ditemukan.</div>');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('API Error:', xhr, status, error);
            $('#news-detail').html('<div class="alert alert-danger">Gagal memuat berita. Error: ' + error + '</div>');
        });
}

function displayNewsDetail(news) {
    const publishedDate = new Date(news.published_at).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const categoryClass = getCategoryClass(news.category);
    
    const html = `
        <article class="card">
            ${news.image ? 
                `<img src="${APP_BASE_URL}/${news.image}?v=${Date.now()}" class="card-img-top" alt="${news.title}" style="height: 400px; object-fit: cover;">` :
                `<div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="fas fa-newspaper fa-3x text-muted"></i>
                </div>`
            }
            <div class="card-body p-4">
                <span class="badge ${categoryClass} mb-3">${getCategoryName(news.category)}</span>
                <h1 class="card-title mb-3">${news.title}</h1>
                    <i class="fas fa-user me-2"></i>
                    <span class="me-4">${news.author}</span>
                    <i class="fas fa-calendar me-2"></i>
                    <span>${publishedDate}</span>
                </div>
                <div class="card-text">
                    ${news.content.replace(/\n/g, '<br>')}
                </div>
                
                <!-- Interaction Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn btn-outline-primary btn-sm reaction-btn" data-type="like" data-id="${news.id}">
                            <i class="fas fa-thumbs-up"></i> <span class="like-count">0</span>
                        </button>
                        <button class="btn btn-outline-danger btn-sm reaction-btn" data-type="dislike" data-id="${news.id}">
                            <i class="fas fa-thumbs-down"></i> <span class="dislike-count">0</span>
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="toggleComments(${news.id})">
                            <i class="fas fa-comment"></i> <span class="comment-count">0</span> Komentar
                        </button>
                        ${news.image ? `
                        <button class="btn btn-outline-success btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#downloadModalNews${news.id}">
                            <i class="fas fa-download"></i> Download
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </article>
        
        <!-- Comments Section -->
        <div class="card mt-3" id="comments-section-${news.id}" style="display: none;">
            <div class="card-body">
                <h5 class="mb-3">Komentar</h5>
                @auth
                <form class="comment-form mb-4" data-id="${news.id}">
                    <div class="mb-2">
                        <textarea class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
                </form>
                @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}">Login</a> untuk memberikan komentar
                </div>
                @endauth
                <div class="comments-list" id="comments-list-${news.id}"></div>
            </div>
        </div>
    `;
    
    $('#news-detail').html(html);
                // Filter out current news
                const relatedNews = response.data.filter(news => news.id != {{ $id }});
                displayRelatedNews(relatedNews.slice(0, 4));
            }
        })
        .fail(function() {
            $('#related-news').html('<p class="text-muted">Tidak dapat memuat berita terkait.</p>');
        });
}

function displayRelatedNews(news) {
    let html = '';
    
    if (news.length === 0) {
        html = '<p class="text-muted">Belum ada berita terkait.</p>';
    } else {
        news.forEach(function(item) {
            html += `
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        ${item.image ? 
                            `<img src="${APP_BASE_URL}/${item.image}?v=${Date.now()}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="${item.title}">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-newspaper text-muted"></i>
                            </div>`
                        }
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">
                            <a href="/news/${item.id}" class="text-decoration-none">${item.title}</a>
                        </h6>
                        <small class="text-muted">${new Date(item.published_at).toLocaleDateString('id-ID')}</small>
                    </div>
                </div>
            `;
        });
    }
    
    $('#related-news').html(html);
}

function getCategoryClass(category) {
    const classes = {
        'academic': 'bg-primary',
        'sports': 'bg-success',
        'events': 'bg-warning',
        'announcements': 'bg-info',
        'general': 'bg-secondary'
    };
    return classes[category] || 'bg-secondary';
}

function getCategoryName(category) {
    const names = {
        'academic': 'Akademik',
        'sports': 'Olahraga',
        'events': 'Acara',
        'announcements': 'Pengumuman',
        'general': 'Umum'
    };
    return names[category] || 'Umum';
}
</script>
@endsection