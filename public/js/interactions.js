// Interaction Functions for News and Teachers
// Similar to gallery interactions

/**
 * Toggle Reaction (Like/Dislike)
 */
function toggleReaction(type, id, reaction) {
    fetch(`/${type}/${id}/reaction`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: reaction })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update counts
            document.querySelector(`#${type}-like-count-${id}`).textContent = data.likes;
            document.querySelector(`#${type}-dislike-count-${id}`).textContent = data.dislikes;
            
            // Update button states
            const likeBtn = document.querySelector(`#${type}-like-btn-${id}`);
            const dislikeBtn = document.querySelector(`#${type}-dislike-btn-${id}`);
            
            if (data.user_reaction === 'like') {
                likeBtn.classList.remove('btn-outline-primary');
                likeBtn.classList.add('btn-primary');
                dislikeBtn.classList.remove('btn-danger');
                dislikeBtn.classList.add('btn-outline-danger');
            } else if (data.user_reaction === 'dislike') {
                dislikeBtn.classList.remove('btn-outline-danger');
                dislikeBtn.classList.add('btn-danger');
                likeBtn.classList.remove('btn-primary');
                likeBtn.classList.add('btn-outline-primary');
            } else {
                likeBtn.classList.remove('btn-primary');
                likeBtn.classList.add('btn-outline-primary');
                dislikeBtn.classList.remove('btn-danger');
                dislikeBtn.classList.add('btn-outline-danger');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

/**
 * Toggle Comments Section
 */
function toggleComments(type, id) {
    const section = document.getElementById(`comments-section-${type}-${id}`);
    if (section.style.display === 'none') {
        section.style.display = 'block';
        loadComments(type, id);
    } else {
        section.style.display = 'none';
    }
}

/**
 * Load Comments
 */
function loadComments(type, id) {
    fetch(`/${type}/${id}/comments`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(type, id, data.comments);
                // Update comment count
                document.querySelector(`#${type}-comment-count-${id}`).textContent = data.comments.length;
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
}

/**
 * Display Comments
 */
function displayComments(type, id, comments) {
    const container = document.getElementById(`comments-list-${type}-${id}`);
    
    if (comments.length === 0) {
        container.innerHTML = '<p class="text-muted">Belum ada komentar</p>';
        return;
    }
    
    let html = '';
    comments.forEach(comment => {
        const date = new Date(comment.created_at).toLocaleString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const isOwner = comment.user_id === parseInt(document.querySelector('meta[name="user-id"]')?.content || '0');
        
        html += `
            <div class="comment-item border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${comment.user.name}</strong>
                        <small class="text-muted ms-2">${date}</small>
                    </div>
                    ${isOwner ? `
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteComment('${type}', ${comment.id}, ${id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
                <p class="mb-0 mt-2">${comment.comment}</p>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Add Comment
 */
function addComment(event, type, id) {
    event.preventDefault();
    
    const form = event.target;
    const textarea = form.querySelector('textarea');
    const comment = textarea.value.trim();
    
    if (!comment) {
        alert('Komentar tidak boleh kosong');
        return;
    }
    
    fetch(`/${type}/${id}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ comment: comment })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            textarea.value = '';
            loadComments(type, id);
            // Show success message
            showToast('Komentar berhasil ditambahkan', 'success');
        } else {
            showToast(data.message || 'Gagal menambahkan komentar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
    });
}

/**
 * Delete Comment
 */
function deleteComment(type, commentId, itemId) {
    if (!confirm('Hapus komentar ini?')) {
        return;
    }
    
    fetch(`/${type}/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadComments(type, itemId);
            showToast('Komentar berhasil dihapus', 'success');
        } else {
            showToast(data.message || 'Gagal menghapus komentar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
    });
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'info') {
    // Create toast element if not exists
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    toast.style.cssText = 'min-width: 250px; margin-bottom: 10px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
