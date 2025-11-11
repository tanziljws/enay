<!-- Download Modal for News -->
<div class="modal fade" id="downloadModalnews{{ $newsId }}" tabindex="-1">
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
                        
                        <div class="captcha-box" onclick="refreshNewsCaptcha{{ $newsId }}()" style="cursor: pointer; user-select: none;">
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
const captchaColorsNews{{ $newsId }} = ['#3d4f5d', '#2c3e50', '#34495e', '#5d6d7e', '#566573', '#4a5568'];

async function refreshNewsCaptcha{{ $newsId }}() {
    const captchaText = document.getElementById('newsCaptchaText{{ $newsId }}');
    captchaText.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route("captcha.generate") }}?' + Date.now());
        const data = await response.json();
        
        let html = '';
        data.chars.forEach((char, index) => {
            const color = captchaColorsNews{{ $newsId }}[index % captchaColorsNews{{ $newsId }}.length];
            const rotation = (Math.random() - 0.5) * 20;
            const yOffset = Math.random() * 10 - 5;
            html += `<span style="color: ${color}; transform: rotate(${rotation}deg) translateY(${yOffset}px); display: inline-block; margin: 0 3px;">${char}</span>`;
        });
        captchaText.innerHTML = html;
        
        document.getElementById('newsCaptchaInput{{ $newsId }}').value = '';
        document.getElementById('captchaErrorNews{{ $newsId }}').classList.add('d-none');
    } catch (error) {
        console.error('Error loading CAPTCHA:', error);
        captchaText.innerHTML = 'Error loading CAPTCHA';
    }
}

document.getElementById('newsCaptchaInput{{ $newsId }}').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    const downloadBtn = document.getElementById('downloadBtnNews{{ $newsId }}');
    downloadBtn.disabled = this.value.length < 6;
});

document.getElementById('downloadFormNews{{ $newsId }}').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const captchaInput = document.getElementById('newsCaptchaInput{{ $newsId }}').value;
    const errorDiv = document.getElementById('captchaErrorNews{{ $newsId }}');
    const errorMsg = document.getElementById('captchaErrorMessageNews{{ $newsId }}');
    const downloadBtn = document.getElementById('downloadBtnNews{{ $newsId }}');
    
    if (!captchaInput || captchaInput.length < 6) {
        errorDiv.classList.remove('d-none');
        errorMsg.textContent = 'Silakan masukkan kode CAPTCHA';
        return false;
    }
    
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
            errorMsg.textContent = data.message || 'Kode CAPTCHA salah';
            refreshNewsCaptcha{{ $newsId }}();
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i>Download';
        }
    } catch (error) {
        console.error('Error:', error);
        errorDiv.classList.remove('d-none');
        errorMsg.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        downloadBtn.disabled = false;
        downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i>Download';
    }
});

document.getElementById('downloadModalnews{{ $newsId }}').addEventListener('hidden.bs.modal', function () {
    document.getElementById('newsCaptchaInput{{ $newsId }}').value = '';
    document.getElementById('downloadBtnNews{{ $newsId }}').disabled = true;
    document.getElementById('downloadBtnNews{{ $newsId }}').innerHTML = '<i class="fas fa-download me-1"></i>Download';
    document.getElementById('captchaErrorNews{{ $newsId }}').classList.add('d-none');
    refreshNewsCaptcha{{ $newsId }}();
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('downloadModalnews{{ $newsId }}');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            console.log('News modal opened, loading CAPTCHA...');
            setTimeout(() => {
                refreshNewsCaptcha{{ $newsId }}();
            }, 300);
        });
    }
});
</script>

<style>
.captcha-box {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border: 3px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.captcha-box:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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

.captcha-text span {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}
</style>
