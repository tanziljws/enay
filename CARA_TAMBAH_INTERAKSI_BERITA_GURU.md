# 🎯 Cara Menambahkan Fitur Interaksi untuk Berita & Guru

## 📋 Fitur yang Akan Ditambahkan:

Untuk **Berita** dan **Guru**:
- ✅ Like
- ✅ Dislike
- ✅ Comment
- ✅ Download (untuk gambar)

Sama seperti yang sudah ada di **Galeri**.

## 🔧 Langkah-Langkah:

### 1. Tambahkan Partial untuk Download Modal Berita

Buat file: `resources/views/partials/download-news-modal.blade.php`

```blade
<!-- Download Modal for News -->
<div class="modal fade" id="downloadModalNews{{ $newsId }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download me-2"></i>Download Gambar Berita
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('news.download', $newsId) }}" method="GET" id="downloadFormNews{{ $newsId }}">
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Silakan verifikasi bahwa Anda bukan robot untuk melanjutkan download.
                    </p>
                    
                    <!-- CAPTCHA -->
                    <div class="text-center mb-3">
                        <p class="mb-2"><strong>Verifikasi CAPTCHA</strong></p>
                        
                        <div class="captcha-box" onclick="refreshNewsCapt{{$newsId}}()" style="cursor: pointer;">
                            <div class="captcha-text" id="newsCaptchaText{{ $newsId }}">
                                Loading...
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-sync-alt"></i> Klik untuk refresh
                        </small>
                        
                        <input type="text" 
                               class="form-control mt-2" 
                               id="newsCaptchaInput{{ $newsId }}"
                               name="captcha"
                               placeholder="KETIK KODE DI ATAS"
                               maxlength="6"
                               style="text-transform: uppercase; font-size: 1.1rem; letter-spacing: 2px;"
                               required>
                        <small class="text-muted">Tidak case-sensitive</small>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="captchaErrorNews{{ $newsId }}">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="captchaErrorMessageNews{{ $newsId }}"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="downloadBtnNews{{ $newsId }}" disabled>
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// CAPTCHA functions for news
async function refreshNewsCaptcha{{ $newsId }}() {
    const captchaText = document.getElementById('newsCaptchaText{{ $newsId }}');
    captchaText.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route("captcha.generate") }}?' + Date.now());
        const data = await response.json();
        
        const colors = ['#3d4f5d', '#2c3e50', '#34495e'];
        let html = '';
        data.chars.forEach((char, index) => {
            const color = colors[index % colors.length];
            const rotation = (Math.random() - 0.5) * 20;
            html += `<span style="color: ${color}; transform: rotate(${rotation}deg); display: inline-block; margin: 0 3px;">${char}</span>`;
        });
        captchaText.innerHTML = html;
        
        document.getElementById('newsCaptchaInput{{ $newsId }}').value = '';
    } catch (error) {
        captchaText.innerHTML = 'Error loading CAPTCHA';
    }
}

// Auto-uppercase
document.getElementById('newsCaptchaInput{{ $newsId }}').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    document.getElementById('downloadBtnNews{{ $newsId }}').disabled = this.value.length < 6;
});

// Form submission
document.getElementById('downloadFormNews{{ $newsId }}').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const captchaInput = document.getElementById('newsCaptchaInput{{ $newsId }}').value;
    const errorDiv = document.getElementById('captchaErrorNews{{ $newsId }}');
    const downloadBtn = document.getElementById('downloadBtnNews{{ $newsId }}');
    
    downloadBtn.disabled = true;
    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memverifikasi...';
    
    try {
        const response = await fetch('{{ route("captcha.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ captcha: captchaInput })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = this.action + '?captcha_verified=1';
        } else {
            errorDiv.classList.remove('d-none');
            document.getElementById('captchaErrorMessageNews{{ $newsId }}').textContent = 'Kode CAPTCHA salah';
            refreshNewsCaptcha{{ $newsId }}();
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i>Download';
        }
    } catch (error) {
        errorDiv.classList.remove('d-none');
        downloadBtn.disabled = false;
        downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i>Download';
    }
});

// Load CAPTCHA when modal opens
document.getElementById('downloadModalNews{{ $newsId }}').addEventListener('shown.bs.modal', function () {
    refreshNewsCaptcha{{ $newsId }}();
});
</script>

<style>
.captcha-box {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border: 3px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
}

.captcha-text {
    font-size: 2rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    letter-spacing: 8px;
    text-align: center;
    padding: 10px;
    background: white;
    border-radius: 4px;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
```

### 2. Update `news-detail.blade.php`

Tambahkan tombol interaksi setelah konten berita (sekitar baris 90-95):

```blade
<!-- Interaction Buttons -->
<div class="mt-4 pt-3 border-top">
    <div class="d-flex gap-2 align-items-center flex-wrap">
        @auth
            <button class="btn btn-outline-primary btn-sm" onclick="toggleReaction('news', {{ $news->id }}, 'like')">
                <i class="fas fa-thumbs-up"></i> 
                <span id="news-like-count-{{ $news->id }}">{{ $news->reactions()->where('type', 'like')->count() }}</span>
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="toggleReaction('news', {{ $news->id }}, 'dislike')">
                <i class="fas fa-thumbs-down"></i> 
                <span id="news-dislike-count-{{ $news->id }}">{{ $news->reactions()->where('type', 'dislike')->count() }}</span>
            </button>
            <button class="btn btn-outline-info btn-sm" onclick="toggleComments('news', {{ $news->id }})">
                <i class="fas fa-comment"></i> 
                <span id="news-comment-count-{{ $news->id }}">{{ $news->comments()->count() }}</span> Komentar
            </button>
            @if($news->image)
                <button class="btn btn-outline-success btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#downloadModalNews{{ $news->id }}">
                    <i class="fas fa-download"></i> Download
                </button>
            @endif
        @else
            <div class="alert alert-info w-100">
                <a href="{{ route('login') }}">Login</a> untuk like, comment, dan download
            </div>
        @endauth
    </div>
</div>

<!-- Comments Section -->
<div class="card mt-3" id="comments-section-news-{{ $news->id }}" style="display: none;">
    <div class="card-body">
        <h5 class="mb-3">Komentar</h5>
        @auth
        <form onsubmit="addComment(event, 'news', {{ $news->id }})">
            @csrf
            <div class="mb-2">
                <textarea class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
        </form>
        @endauth
        <div class="comments-list mt-3" id="comments-list-news-{{ $news->id }}"></div>
    </div>
</div>

@auth
@include('partials.download-news-modal', ['newsId' => $news->id])
@endauth
```

### 3. Tambahkan JavaScript Functions

Di bagian `@section('scripts')` tambahkan:

```javascript
function toggleReaction(type, id, reaction) {
    $.ajax({
        url: `/${type}/${id}/reaction`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            type: reaction
        },
        success: function(response) {
            if (response.success) {
                $(`#${type}-like-count-${id}`).text(response.likes);
                $(`#${type}-dislike-count-${id}`).text(response.dislikes);
            }
        }
    });
}

function toggleComments(type, id) {
    const section = $(`#comments-section-${type}-${id}`);
    section.toggle();
    if (section.is(':visible')) {
        loadComments(type, id);
    }
}

function loadComments(type, id) {
    $.get(`/${type}/${id}/comments`, function(response) {
        if (response.success) {
            displayComments(type, id, response.comments);
        }
    });
}

function displayComments(type, id, comments) {
    let html = '';
    comments.forEach(comment => {
        html += `
            <div class="border-bottom pb-2 mb-2">
                <strong>${comment.user.name}</strong>
                <small class="text-muted">${new Date(comment.created_at).toLocaleString('id-ID')}</small>
                <p class="mb-1">${comment.comment}</p>
            </div>
        `;
    });
    $(`#comments-list-${type}-${id}`).html(html || '<p class="text-muted">Belum ada komentar</p>');
}

function addComment(event, type, id) {
    event.preventDefault();
    const form = event.target;
    const textarea = form.querySelector('textarea');
    
    $.ajax({
        url: `/${type}/${id}/comment`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            comment: textarea.value
        },
        success: function(response) {
            if (response.success) {
                textarea.value = '';
                loadComments(type, id);
                $(`#${type}-comment-count-${id}`).text(response.total_comments);
            }
        }
    });
}
```

### 4. Ulangi untuk Guru

Buat file serupa untuk guru:
- `resources/views/partials/download-teacher-modal.blade.php`
- Update `resources/views/teachers/show.blade.php`

Gunakan pola yang sama, hanya ganti:
- `news` → `teacher`
- `newsId` → `teacherId`
- Route: `news.download` → `teacher.download`

## ✅ Checklist:

- [ ] Buat `download-news-modal.blade.php`
- [ ] Update `news-detail.blade.php` dengan tombol interaksi
- [ ] Tambahkan JavaScript functions
- [ ] Buat `download-teacher-modal.blade.php`
- [ ] Update `teachers/show.blade.php`
- [ ] Test semua fitur
- [ ] Restart server

## 🎯 Hasil Akhir:

Setelah implementasi, user bisa:
1. ✅ Like/Dislike berita & profil guru
2. ✅ Comment di berita & profil guru
3. ✅ Download gambar berita & foto guru (dengan CAPTCHA)
4. ✅ Lihat jumlah likes, dislikes, comments real-time

---

**Catatan:** Semua route dan controller sudah ada (dari galeri), hanya perlu menambahkan UI di halaman detail berita dan guru!
