{{-- 
    Interaction Buttons Component
    Props:
    - $type: 'gallery', 'news', or 'teacher'
    - $itemId: ID of the item
    - $likes: Number of likes
    - $dislikes: Number of dislikes
    - $userReaction: Current user's reaction ('like', 'dislike', or null)
    - $commentsCount: Number of comments
    - $hasDownload: Whether download button should be shown (default: true)
--}}

@php
    $likes = $likes ?? 0;
    $dislikes = $dislikes ?? 0;
    $userReaction = $userReaction ?? null;
    $commentsCount = $commentsCount ?? 0;
    $hasDownload = $hasDownload ?? true;
@endphp

<div class="interaction-buttons" data-type="{{ $type }}" data-id="{{ $itemId }}">
    <div class="d-flex gap-2 align-items-center flex-wrap">
        @auth
            <!-- Like Button -->
            <button class="btn btn-sm {{ $userReaction === 'like' ? 'btn-primary' : 'btn-outline-primary' }} reaction-btn" 
                    data-reaction="like">
                <i class="fas fa-thumbs-up"></i>
                <span class="likes-count">{{ $likes }}</span>
            </button>

            <!-- Dislike Button -->
            <button class="btn btn-sm {{ $userReaction === 'dislike' ? 'btn-danger' : 'btn-outline-danger' }} reaction-btn" 
                    data-reaction="dislike">
                <i class="fas fa-thumbs-down"></i>
                <span class="dislikes-count">{{ $dislikes }}</span>
            </button>

            <!-- Comment Button -->
            <button class="btn btn-sm btn-outline-secondary comment-toggle-btn">
                <i class="fas fa-comment"></i>
                <span class="comments-count">{{ $commentsCount }}</span>
            </button>

            <!-- Download Button -->
            @if($hasDownload)
                <button type="button" class="btn btn-sm btn-outline-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#downloadModal{{ $type }}{{ $itemId }}"
                        style="border-color: #3d4f5d; color: #3d4f5d;">
                    <i class="fas fa-download"></i> Download
                </button>
            @endif
        @else
            <!-- Show counts and login prompt for guests -->
            <span class="badge bg-primary" style="background-color: #3d4f5d !important;">
                <i class="fas fa-thumbs-up"></i> {{ $likes }}
            </span>
            <span class="badge bg-danger">
                <i class="fas fa-thumbs-down"></i> {{ $dislikes }}
            </span>
            <span class="badge bg-secondary">
                <i class="fas fa-comment"></i> {{ $commentsCount }}
            </span>
            
            <!-- Login Alert -->
            <div class="alert alert-info mt-3 mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Ingin berinteraksi?</h6>
                        <p class="mb-2 small">Silakan login atau daftar untuk dapat:</p>
                        <ul class="mb-2 small">
                            <li><i class="fas fa-thumbs-up text-primary"></i> Like & Dislike</li>
                            <li><i class="fas fa-comment text-secondary"></i> Berkomentar</li>
                            <li><i class="fas fa-download text-success"></i> Download konten</li>
                        </ul>
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary" style="background-color: #3d4f5d; border-color: #3d4f5d;">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary" style="border-color: #3d4f5d; color: #3d4f5d;">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <!-- Comments Section -->
    @auth
        <div class="comments-section mt-3" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Komentar</h6>
                    
                    <!-- Comment Form -->
                    <form class="comment-form mb-3">
                        <div class="input-group">
                            <textarea class="form-control comment-input" 
                                      placeholder="Tulis komentar..." 
                                      rows="2" 
                                      maxlength="1000" 
                                      required></textarea>
                            <button class="btn btn-primary submit-comment-btn" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="comments-list">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>

<style>
    .interaction-buttons .btn {
        border-radius: 20px;
        font-size: 0.875rem;
    }

    /* Navy blue color for active like button */
    .interaction-buttons .btn-primary {
        background-color: #3d4f5d !important;
        border-color: #3d4f5d !important;
        color: white !important;
    }

    .interaction-buttons .btn-primary:hover {
        background-color: #2c3e50 !important;
        border-color: #2c3e50 !important;
    }

    .interaction-buttons .btn-outline-primary {
        color: #3d4f5d !important;
        border-color: #3d4f5d !important;
    }

    .interaction-buttons .btn-outline-primary:hover {
        background-color: #3d4f5d !important;
        border-color: #3d4f5d !important;
        color: white !important;
    }

    .comment-item {
        border-bottom: 1px solid #e9ecef;
        padding: 10px 0;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-user {
        font-weight: 600;
        color: #2c3e50;
    }

    .comment-time {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .comment-text {
        margin-top: 5px;
        color: #495057;
    }

    .delete-comment-btn {
        font-size: 0.75rem;
        padding: 2px 8px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.interaction-buttons[data-type="{{ $type }}"][data-id="{{ $itemId }}"]');
    if (!container) return;

    const type = container.dataset.type;
    const itemId = container.dataset.id;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Reaction buttons
    container.querySelectorAll('.reaction-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const reaction = this.dataset.reaction;
            
            try {
                const response = await fetch(`/${type}/${itemId}/reaction`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ type: reaction })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update counts
                    container.querySelector('.likes-count').textContent = data.likes;
                    container.querySelector('.dislikes-count').textContent = data.dislikes;
                    
                    // Update button states
                    const likeBtn = container.querySelector('[data-reaction="like"]');
                    const dislikeBtn = container.querySelector('[data-reaction="dislike"]');
                    
                    likeBtn.classList.remove('btn-primary');
                    likeBtn.classList.add('btn-outline-primary');
                    dislikeBtn.classList.remove('btn-danger');
                    dislikeBtn.classList.add('btn-outline-danger');
                    
                    if (data.userReaction === 'like') {
                        likeBtn.classList.remove('btn-outline-primary');
                        likeBtn.classList.add('btn-primary');
                    } else if (data.userReaction === 'dislike') {
                        dislikeBtn.classList.remove('btn-outline-danger');
                        dislikeBtn.classList.add('btn-danger');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });

    // Comment toggle
    const commentToggleBtn = container.querySelector('.comment-toggle-btn');
    const commentsSection = container.querySelector('.comments-section');
    
    if (commentToggleBtn && commentsSection) {
        commentToggleBtn.addEventListener('click', function() {
            if (commentsSection.style.display === 'none') {
                commentsSection.style.display = 'block';
                loadComments();
            } else {
                commentsSection.style.display = 'none';
            }
        });
    }

    // Load comments
    async function loadComments() {
        const commentsList = container.querySelector('.comments-list');
        
        try {
            const response = await fetch(`/${type}/${itemId}/comments`);
            const data = await response.json();
            
            if (data.success) {
                if (data.comments.length === 0) {
                    commentsList.innerHTML = '<p class="text-muted text-center">Belum ada komentar</p>';
                } else {
                    commentsList.innerHTML = data.comments.map(comment => `
                        <div class="comment-item" data-comment-id="${comment.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="comment-user">${comment.user_name}</span>
                                    <span class="comment-time ms-2">${comment.created_at}</span>
                                    <p class="comment-text mb-0">${comment.comment}</p>
                                </div>
                                ${comment.can_delete ? `
                                    <button class="btn btn-sm btn-outline-danger delete-comment-btn" data-comment-id="${comment.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `).join('');
                    
                    // Add delete event listeners
                    commentsList.querySelectorAll('.delete-comment-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            deleteComment(this.dataset.commentId);
                        });
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            commentsList.innerHTML = '<p class="text-danger text-center">Gagal memuat komentar</p>';
        }
    }

    // Submit comment
    const commentForm = container.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const commentInput = this.querySelector('.comment-input');
            const submitBtn = this.querySelector('.submit-comment-btn');
            const comment = commentInput.value.trim();
            
            if (!comment) return;
            
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`/${type}/${itemId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ comment })
                });

                const data = await response.json();
                
                if (data.success) {
                    commentInput.value = '';
                    loadComments();
                    
                    // Update comment count from API response
                    if (data.commentsCount !== undefined) {
                        container.querySelector('.comments-count').textContent = data.commentsCount;
                    } else {
                        // Fallback: increment count
                        const count = parseInt(container.querySelector('.comments-count').textContent) + 1;
                        container.querySelector('.comments-count').textContent = count;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal mengirim komentar. Silakan coba lagi.');
            } finally {
                submitBtn.disabled = false;
            }
        });
    }

    // Delete comment
    async function deleteComment(commentId) {
        if (!confirm('Hapus komentar ini?')) return;
        
        try {
            const response = await fetch(`/${type}/comment/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();
            
            if (data.success) {
                loadComments();
                
                // Update comment count from API response
                if (data.commentsCount !== undefined) {
                    container.querySelector('.comments-count').textContent = data.commentsCount;
                } else {
                    // Fallback: decrement count
                    const count = Math.max(0, parseInt(container.querySelector('.comments-count').textContent) - 1);
                    container.querySelector('.comments-count').textContent = count;
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal menghapus komentar. Silakan coba lagi.');
        }
    }
});
</script>
