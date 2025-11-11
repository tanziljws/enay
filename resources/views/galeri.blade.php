@extends('layouts.app')

@section('title', 'Galeri Kegiatan - Galeri Sekolah Enay')

@section('head')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@if(config('recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
@endsection

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Galeri Kegiatan</h1>
    <p class="text-muted">Dokumentasi kegiatan dan event sekolah.</p>

    <div class="row" id="gallery-grid">
        @forelse($items as $item)
        <div class="col-6 col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $item->image) }}?v={{ time() }}" 
                     class="card-img-top gallery-image-clickable" 
                     alt="{{ $item->title }}" 
                     style="height: 250px; object-fit: cover; cursor: pointer;" 
                     data-bs-toggle="modal" 
                     data-bs-target="#imageModal{{ $item->id }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'; console.error('Failed to load image:', this.src);"
                     onload="console.log('Image loaded successfully:', this.src);">
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $item->title }}</h6>
                    <small class="text-muted d-block mb-2">{{ $item->taken_at ? $item->taken_at->format('d M Y') : '' }}</small>
                    
                    @if($item->description)
                        <p class="card-text small text-muted mb-2">{{ Str::limit($item->description, 100) }}</p>
                    @endif
                    
                    <!-- Interaction Buttons -->
                    @include('partials.interaction-buttons', [
                        'type' => 'gallery',
                        'itemId' => $item->id,
                        'likes' => $item->likes_count,
                        'dislikes' => $item->dislikes_count,
                        'userReaction' => $item->user_reaction,
                        'commentsCount' => $item->comments_count,
                        'hasDownload' => true
                    ])
                </div>
            </div>
        </div>
        
        <!-- Image Detail Modal -->
        <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $item->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <img src="{{ asset('storage/' . $item->image) }}?v={{ time() }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $item->title }}">
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <small class="text-muted">{{ $item->taken_at ? $item->taken_at->format('d M Y') : '' }}</small>
                                    @if($item->description)
                                        <p class="mt-2">{{ $item->description }}</p>
                                    @endif
                                </div>
                                
                                <hr>
                                
                                <h6 class="mb-3">
                                    <i class="fas fa-comments"></i> Komentar ({{ $item->comments_count }})
                                </h6>
                                
                                <div class="comments-modal-list" id="commentsModalList{{ $item->id }}" style="max-height: 300px; overflow-y: auto;">
                                    <div class="text-center">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @auth
                                <div class="mt-3">
                                    <form class="modal-comment-form" data-item-id="{{ $item->id }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tulis komentar..." required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @else
                                <div class="alert alert-info mt-3">
                                    <a href="{{ route('login') }}">Login</a> untuk memberikan komentar
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Download CAPTCHA Modal -->
        @auth
            @include('partials.download-captcha-modal', [
                'type' => 'gallery',
                'itemId' => $item->id
            ])
        @endauth
        @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada foto galeri.</div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $items->links() }}
    </div>
</div>

<script>
// Force reload images to ensure latest photos are displayed
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[src*="storage/"]');
    images.forEach(function(img) {
        const originalSrc = img.src;
        img.src = originalSrc.split('?')[0] + '?v=' + Date.now();
    });
    
    // Setup modal event listeners
    setupModalComments();
});

// Clear browser cache
if ('caches' in window) {
    caches.keys().then(function(names) {
        for (let name of names) {
            caches.delete(name);
        }
    });
}

// Setup modal comments functionality
function setupModalComments() {
    const modals = document.querySelectorAll('[id^="imageModal"]');
    
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const itemId = this.id.replace('imageModal', '');
            loadModalComments(itemId);
        });
    });
    
    // Handle comment form submission in modal
    document.querySelectorAll('.modal-comment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const itemId = this.dataset.itemId;
            const input = this.querySelector('input');
            const comment = input.value.trim();
            
            if (!comment) return;
            
            try {
                const response = await fetch(`/gallery/${itemId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ comment })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    input.value = '';
                    loadModalComments(itemId);
                    
                    // Update comment count in card
                    const commentCount = document.querySelector(`[data-id="${itemId}"] .comments-count`);
                    if (commentCount) {
                        commentCount.textContent = parseInt(commentCount.textContent) + 1;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal mengirim komentar');
            }
        });
    });
}

// Load comments in modal
async function loadModalComments(itemId) {
    const container = document.getElementById(`commentsModalList${itemId}`);
    
    try {
        const response = await fetch(`/gallery/${itemId}/comments`);
        const data = await response.json();
        
        if (data.success) {
            if (data.comments.length === 0) {
                container.innerHTML = '<p class="text-muted text-center small">Belum ada komentar</p>';
            } else {
                container.innerHTML = data.comments.map(comment => `
                    <div class="comment-item-modal mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong class="d-block" style="color: #3d4f5d;">${comment.user_name}</strong>
                                <small class="text-muted">${comment.created_at}</small>
                                <p class="mb-0 mt-1">${comment.comment}</p>
                            </div>
                            ${comment.can_delete ? `
                                <button class="btn btn-sm btn-outline-danger delete-modal-comment" 
                                        data-comment-id="${comment.id}" 
                                        data-item-id="${itemId}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `).join('');
                
                // Add delete event listeners
                container.querySelectorAll('.delete-modal-comment').forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteModalComment(this.dataset.commentId, this.dataset.itemId);
                    });
                });
            }
        }
    } catch (error) {
        console.error('Error:', error);
        container.innerHTML = '<p class="text-danger text-center small">Gagal memuat komentar</p>';
    }
}

// Delete comment from modal
async function deleteModalComment(commentId, itemId) {
    if (!confirm('Hapus komentar ini?')) return;
    
    try {
        const response = await fetch(`/gallery/comment/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadModalComments(itemId);
            
            // Update comment count in card
            const commentCount = document.querySelector(`[data-id="${itemId}"] .comments-count`);
            if (commentCount) {
                commentCount.textContent = Math.max(0, parseInt(commentCount.textContent) - 1);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus komentar');
    }
}
</script>

<style>
.gallery-image-clickable:hover {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.3s ease;
}

.comment-item-modal:last-child {
    border-bottom: none !important;
}
</style>
@endsection



