@extends('layouts.app')

@section('title', 'Jurusan - Galeri Sekolah Enay')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Daftar jurusan/Program Keahlian di SMK NEGERI 4 KOTA BOGOR</h1>
    <p class="text-muted">Program keahlian yang tersedia di sekolah kami.</p>

    <div class="row" id="majors-grid">
        @forelse($majors as $major)
        <div class="col-12 col-md-6 mb-4">
            <div class="card h-100">
                @if($major->image)
                    <img src="{{ asset('storage/' . $major->image) }}?v={{ time() }}" 
                         class="card-img-top" 
                         alt="{{ $major->name }}" 
                         style="height: 350px; object-fit: cover;" 
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'; console.log('Image load error for: {{ $major->name }}');">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 350px;">
                        <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $major->full_name }}</h6>
                    <p class="card-text text-muted mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                        {{ $major->description }}
                    </p>
                    <small class="text-muted">{{ $major->student_count }} Siswa</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada program keahlian.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
/* Style untuk kartu program keahlian seperti galeri */
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.card-text {
    color: #6c757d;
    line-height: 1.4;
    margin-bottom: 12px;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 12px;
}

.card-img-top {
    transition: transform 0.3s ease;
    filter: none !important;
    -webkit-filter: none !important;
    width: 100% !important;
    height: 350px !important;
    object-fit: cover !important;
    display: block !important;
}

.card:hover .card-img-top {
    transform: scale(1.02);
}

.card-body {
    padding: 15px;
}

.card-text {
    font-size: 0.85rem;
    line-height: 1.3;
    color: #6c757d;
    margin-bottom: 8px;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

/* Ensure all images display with normal colors and correct size */
.card img,
.card-img-top,
.major-card img,
.major-card .card-img-top {
    filter: none !important;
    -webkit-filter: none !important;
    image-rendering: auto !important;
    -webkit-image-rendering: auto !important;
    width: 100% !important;
    height: 350px !important;
    object-fit: cover !important;
    display: block !important;
    max-height: 350px !important;
    min-height: 350px !important;
}

/* Force image size - highest priority */
#majors-grid .card img,
#majors-grid .card-img-top,
#majors-grid img[style*="height"] {
    height: 350px !important;
    max-height: 350px !important;
    min-height: 350px !important;
    width: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

/* Grid layout sama dengan galeri */
#majors-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

#majors-grid .col-12 {
    flex: 0 0 100%;
}

@media (min-width: 768px) {
    #majors-grid .col-md-6 {
        flex: 0 0 calc(50% - 0.5rem);
    }
}
</style>
@endsection

@section('scripts')
<script>
// Force reload untuk memastikan perubahan terlihat
if (performance.navigation.type === 1) {
    // Page was refreshed
    console.log('Page was refreshed');
} else {
    // Page was loaded normally
    console.log('Page was loaded normally');
}

// Clear any cached data
if ('caches' in window) {
    caches.keys().then(function(names) {
        for (let name of names) {
            caches.delete(name);
        }
    });
}

// Force reload images to ensure latest photos are displayed
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[src*="storage/"]');
    images.forEach(function(img) {
        const originalSrc = img.src;
        img.src = originalSrc.split('?')[0] + '?v=' + Date.now();
        
        // Force image size
        img.style.height = '350px';
        img.style.maxHeight = '350px';
        img.style.minHeight = '350px';
        img.style.width = '100%';
        img.style.objectFit = 'cover';
        img.style.display = 'block';
        
        // Ensure image displays with normal colors
        img.style.filter = 'none';
        img.style.webkitFilter = 'none';
        img.style.imageRendering = 'auto';
        img.style.webkitImageRendering = 'auto';
    });
    
    // Force all card images to have correct size
    const allCardImages = document.querySelectorAll('#majors-grid .card img, #majors-grid .card-img-top');
    allCardImages.forEach(function(img) {
        img.style.height = '350px';
        img.style.maxHeight = '350px';
        img.style.minHeight = '350px';
        img.style.width = '100%';
        img.style.objectFit = 'cover';
        img.style.display = 'block';
    });
});

// Grid layout animation seperti galeri
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('majors-grid');
    if (grid) {
        // Add animation class
        grid.style.opacity = '0';
        grid.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            grid.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            grid.style.opacity = '1';
            grid.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
@endsection

