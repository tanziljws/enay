@extends('layouts.app')

@section('title', 'Register - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Daftar Akun</h2>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="mb-3">
                            <label class="form-label">Verifikasi CAPTCHA</label>
                            <div class="captcha-box" onclick="refreshRegisterCaptcha()" style="cursor: pointer; user-select: none;">
                                <div class="captcha-text" id="registerCaptchaText">
                                    Loading...
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-sync-alt"></i> Klik untuk refresh
                            </small>
                            
                            <input type="text" 
                                   class="form-control mt-2 @error('captcha') is-invalid @enderror" 
                                   id="registerCaptchaInput"
                                   name="captcha"
                                   placeholder="KETIK KODE DI ATAS"
                                   maxlength="6"
                                   style="text-transform: uppercase; font-size: 1.1rem; letter-spacing: 2px;"
                                   required>
                            <small class="text-muted">Tidak case-sensitive</small>
                            @error('captcha')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
    }
    
    .btn-primary {
        background-color: #2c3e50;
        border-color: #2c3e50;
    }
    
    .btn-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
    }
    
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

<script>
// Colors for CAPTCHA characters
const captchaColors = ['#3d4f5d', '#2c3e50', '#34495e', '#5d6d7e', '#566573', '#4a5568'];

// Refresh CAPTCHA
async function refreshRegisterCaptcha() {
    const captchaText = document.getElementById('registerCaptchaText');
    captchaText.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route("captcha.generate") }}?' + Date.now());
        const data = await response.json();
        
        // Display CAPTCHA with colorful characters
        let html = '';
        data.chars.forEach((char, index) => {
            const color = captchaColors[index % captchaColors.length];
            const rotation = (Math.random() - 0.5) * 20;
            const yOffset = Math.random() * 10 - 5;
            html += `<span style="color: ${color}; transform: rotate(${rotation}deg) translateY(${yOffset}px); display: inline-block; margin: 0 3px;">${char}</span>`;
        });
        captchaText.innerHTML = html;
        
        document.getElementById('registerCaptchaInput').value = '';
    } catch (error) {
        console.error('Error loading CAPTCHA:', error);
        captchaText.innerHTML = 'Error loading CAPTCHA';
    }
}

// Auto-uppercase input
document.addEventListener('DOMContentLoaded', function() {
    const captchaInput = document.getElementById('registerCaptchaInput');
    if (captchaInput) {
        captchaInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Load CAPTCHA on page load
    refreshRegisterCaptcha();
});
</script>
@endsection
