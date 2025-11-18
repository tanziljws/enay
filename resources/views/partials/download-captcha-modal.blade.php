{{--
    Download CAPTCHA Modal
    Props:
    - $type: 'gallery', 'news', or 'teacher'
    - $itemId: ID of the item to download
--}}

<!-- Download CAPTCHA Modal -->
<div class="modal fade" id="downloadModal{{ $type }}{{ $itemId }}" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">
                    <i class="fas fa-download me-2"></i>Verifikasi Download
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($type . '.download', $itemId) }}" method="GET" id="downloadForm{{ $type }}{{ $itemId }}">
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Silakan verifikasi bahwa Anda bukan robot untuk melanjutkan download.
                    </p>
                    
                    <!-- Simple CAPTCHA -->
                    <div class="text-center mb-3">
                        <p class="mb-2"><strong>Verifikasi bahwa Anda bukan robot untuk mendownload foto ini.</strong></p>
                        
                        <!-- CAPTCHA Display -->
                        <div class="mb-3">
                            <div id="captchaDisplay{{ $type }}{{ $itemId }}" 
                                 class="captcha-box"
                                 onclick="refreshCaptcha{{ $type }}{{ $itemId }}()"
                                 style="cursor: pointer; user-select: none;">
                                <div class="captcha-text" id="captchaText{{ $type }}{{ $itemId }}">
                                    Loading...
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-sync-alt"></i> Klik untuk refresh
                            </small>
                        </div>
                        
                        <!-- CAPTCHA Input -->
                        <div class="mb-3">
                            <label for="captchaInput{{ $type }}{{ $itemId }}" class="form-label">
                                Masukkan kode CAPTCHA:
                            </label>
                            <input type="text" 
                                   class="form-control text-center" 
                                   id="captchaInput{{ $type }}{{ $itemId }}"
                                   name="captcha"
                                   placeholder="KETIK KODE DI ATAS"
                                   maxlength="6"
                                   style="text-transform: uppercase; font-size: 1.2rem; letter-spacing: 3px;"
                                   required>
                            <small class="text-muted">Tidak case-sensitive</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="captchaError{{ $type }}{{ $itemId }}">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="captchaErrorMessage{{ $type }}{{ $itemId }}"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="downloadBtn{{ $type }}{{ $itemId }}" disabled>
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Colors for CAPTCHA characters
    const captchaColors = ['#56B4E9', '#E69F00', '#009E73', '#D55E00', '#0072B2', '#CC79A7'];

    // Refresh CAPTCHA
    window.refreshCaptcha{{ $type }}{{ $itemId }} = async function() {
        const captchaText = document.getElementById('captchaText{{ $type }}{{ $itemId }}');
        if (!captchaText) {
            console.error('CAPTCHA text element not found');
            return;
        }
        
        captchaText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        
        console.log('Loading CAPTCHA from:', '{{ route("captcha.generate") }}');
        
        try {
            const captchaUrl = '{{ route("captcha.generate") }}?' + Date.now();
            const response = await fetch(captchaUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                cache: 'no-cache'
            });
            console.log('CAPTCHA Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('CAPTCHA Data:', data);
            
            if (!data.chars || data.chars.length === 0) {
                throw new Error('Invalid CAPTCHA data');
            }
            
            // Display CAPTCHA with colorful characters
            let html = '';
            data.chars.forEach((char, index) => {
                const color = captchaColors[index % captchaColors.length];
                const rotation = (Math.random() - 0.5) * 20; // Random rotation -10 to 10 degrees
                const yOffset = Math.random() * 10 - 5; // Random vertical offset
                html += `<span style="color: ${color}; transform: rotate(${rotation}deg) translateY(${yOffset}px); display: inline-block; margin: 0 3px;">${char}</span>`;
            });
            captchaText.innerHTML = html;
            
            const captchaInput = document.getElementById('captchaInput{{ $type }}{{ $itemId }}');
            if (captchaInput) captchaInput.value = '';
            
            const captchaError = document.getElementById('captchaError{{ $type }}{{ $itemId }}');
            if (captchaError) captchaError.classList.add('d-none');
        } catch (error) {
            console.error('Error loading CAPTCHA:', error);
            captchaText.innerHTML = `<small style="color: red;">Error: ${error.message}<br><a href="#" onclick="refreshCaptcha{{ $type }}{{ $itemId }}(); return false;">Klik untuk retry</a></small>`;
        }
    }

// Enable input validation
document.getElementById('captchaInput{{ $type }}{{ $itemId }}').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    const downloadBtn = document.getElementById('downloadBtn{{ $type }}{{ $itemId }}');
    downloadBtn.disabled = this.value.length < 6;
});

// Handle form submission
document.getElementById('downloadForm{{ $type }}{{ $itemId }}').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const captchaInput = document.getElementById('captchaInput{{ $type }}{{ $itemId }}').value;
    const errorDiv = document.getElementById('captchaError{{ $type }}{{ $itemId }}');
    const errorMsg = document.getElementById('captchaErrorMessage{{ $type }}{{ $itemId }}');
    const downloadBtn = document.getElementById('downloadBtn{{ $type }}{{ $itemId }}');
    
    if (!captchaInput || captchaInput.length < 6) {
        errorDiv.classList.remove('d-none');
        errorMsg.textContent = 'Silakan masukkan kode CAPTCHA';
        return false;
    }
    
    // Disable button
    downloadBtn.disabled = true;
    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memverifikasi...';
    
    try {
        // Verify CAPTCHA
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
            // CAPTCHA valid, proceed with download
            window.location.href = this.action + '?captcha_verified=1';
        } else {
            // CAPTCHA invalid
            errorDiv.classList.remove('d-none');
            errorMsg.textContent = data.message || 'Kode CAPTCHA salah';
            refreshCaptcha{{ $type }}{{ $itemId }}();
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

    // Refresh CAPTCHA when modal opens
    const modal = document.getElementById('downloadModal{{ $type }}{{ $itemId }}');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            console.log('Modal opened, loading CAPTCHA...');
            // Add delay to ensure modal is fully rendered
            setTimeout(() => {
                refreshCaptcha{{ $type }}{{ $itemId }}();
            }, 300);
        });
        
        // Reset on close
        modal.addEventListener('hidden.bs.modal', function () {
            const captchaInput = document.getElementById('captchaInput{{ $type }}{{ $itemId }}');
            if (captchaInput) captchaInput.value = '';
            
            const downloadBtn = document.getElementById('downloadBtn{{ $type }}{{ $itemId }}');
            if (downloadBtn) {
                downloadBtn.disabled = true;
                downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i>Download';
            }
            
            const captchaError = document.getElementById('captchaError{{ $type }}{{ $itemId }}');
            if (captchaError) captchaError.classList.add('d-none');
        });
        
        console.log('CAPTCHA script loaded for {{ $type }}{{ $itemId }}');
    } else {
        console.error('Modal element not found: downloadModal{{ $type }}{{ $itemId }}');
    }
});
</script>

<style>
    .captcha-box {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border: 3px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin: 15px auto;
        max-width: 300px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .captcha-box:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .captcha-text {
        font-size: 2.5rem;
        font-weight: bold;
        font-family: 'Courier New', monospace;
        letter-spacing: 8px;
        text-align: center;
        padding: 10px;
        background: white;
        border-radius: 4px;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .captcha-text span {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    
    #downloadModal{{ $type }}{{ $itemId }} .modal-body {
        min-height: 400px;
    }
</style>
