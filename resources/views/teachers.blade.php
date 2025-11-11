@extends('layouts.app')

@section('title', 'Data Guru & Staff- Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4" style="color:#2c3e50">
                Data 
                @if($selectedCategory == 'guru')
                    Guru
                @elseif($selectedCategory == 'staff')
                    Staff
                @else
                    Guru & Staff
                @endif
            </h1>
            
            <!-- Category Filter Section -->
            <div class="card mb-4" style="border: 1px solid #e9ecef; border-radius: 10px;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pilih Kategori</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('teachers.index', ['category' => 'guru']) }}" 
                               class="btn w-100 {{ $selectedCategory == 'guru' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Guru
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('teachers.index', ['category' => 'staff']) }}" 
                               class="btn w-100 {{ $selectedCategory == 'staff' ? 'btn-success' : 'btn-outline-success' }}">
                                <i class="fas fa-users me-2"></i>Staff
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Teachers List -->
            <div class="row" id="teachers-grid">
                        @forelse($teachers as $teacher)
                        <div class="col-12 col-md-4 mb-4">
                            <a href="{{ route('teachers.show', $teacher->id) }}" class="text-decoration-none">
                                <div class="card h-100 teacher-card">
                                    <div class="card-body text-center py-4">
                                        <!-- Profile Photo Circle -->
                                        <div class="mb-3">
                                            @if($teacher->photo)
                                                <img src="{{ asset('storage/' . $teacher->photo) }}?v={{ time() }}" 
                                                     class="rounded-circle" 
                                                     alt="{{ $teacher->name }}" 
                                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #3d4f5d; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="rounded-circle bg-light d-none align-items-center justify-content-center mx-auto" 
                                                     style="width: 100px; height: 100px; border: 3px solid #3d4f5d;">
                                                    <i class="{{ $teacher->gender == 'male' ? 'fas fa-male' : 'fas fa-female' }} fa-2x text-muted"></i>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                                     style="width: 100px; height: 100px; border: 3px solid #3d4f5d;">
                                                    <i class="{{ $teacher->gender == 'male' ? 'fas fa-male' : 'fas fa-female' }} fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Teacher Info -->
                                        <h6 class="card-title mb-1 fw-bold" style="color: #2c3e50;">{{ $teacher->name }}</h6>
                                        <small class="text-muted d-block mb-2">{{ $teacher->teacher_number }}</small>
                                        @if($teacher->role)
                                            <span class="badge" style="background-color: #3d4f5d;">{{ ucfirst($teacher->role) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                @if($selectedCategory == 'guru')
                                    Tidak ada data guru.
                                @elseif($selectedCategory == 'staff')
                                    Tidak ada data staff.
                                @else
                                    Tidak ada data.
                                @endif
                            </div>
                        </div>
                        @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Style untuk kartu guru */
.teacher-card {
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.teacher-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(61, 79, 93, 0.15);
    border-color: #3d4f5d;
}

.teacher-card img.rounded-circle {
    transition: transform 0.3s ease;
}

.teacher-card:hover img.rounded-circle {
    transform: scale(1.05);
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
    padding: 4px 12px;
    border-radius: 12px;
}

.card-img-top {
    transition: transform 0.3s ease;
    filter: none !important;
    -webkit-filter: none !important;
    width: 100% !important;
    height: 250px !important;
    object-fit: cover !important;
    display: block !important;
}

.card:hover .card-img-top {
    transform: scale(1.02);
}

.card-body {
    padding: 15px;
}

/* Ensure all images display with normal colors and correct size */
.card img,
.card-img-top,
.teacher-card img,
.teacher-card .card-img-top {
    filter: none !important;
    -webkit-filter: none !important;
    image-rendering: auto !important;
    -webkit-image-rendering: auto !important;
    width: 100% !important;
    height: 250px !important;
    object-fit: cover !important;
    display: block !important;
    max-height: 250px !important;
    min-height: 250px !important;
}

/* Force image size - highest priority */
#teachers-grid .card img,
#teachers-grid .card-img-top,
#teachers-grid img[style*="height"] {
    height: 250px !important;
    max-height: 250px !important;
    min-height: 250px !important;
    width: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

/* Hide any unwanted images */
.card img:not(.card-img-top) {
    display: none !important;
}

/* Grid layout - 3 kolom per baris */
#teachers-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

/* Mobile: 1 kolom */
#teachers-grid .col-12 {
    flex: 0 0 100%;
}

/* Tablet ke atas: 3 kolom */
@media (min-width: 768px) {
    #teachers-grid .col-md-4 {
        flex: 0 0 calc(33.333% - 1rem);
        max-width: calc(33.333% - 1rem);
    }
}
</style>

<script>
// Force reload images to ensure latest photos are displayed
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[src*="storage/"]');
    images.forEach(function(img) {
        const originalSrc = img.src;
        img.src = originalSrc.split('?')[0] + '?v=' + Date.now();
        
        // Ensure image displays with normal colors and correct size
        img.style.filter = 'none';
        img.style.webkitFilter = 'none';
        img.style.imageRendering = 'auto';
        img.style.webkitImageRendering = 'auto';
        img.style.width = '100%';
        img.style.height = '250px';
        img.style.objectFit = 'cover';
        img.style.display = 'block';
    });
    
    // Force all card images to have correct size
    const allCardImages = document.querySelectorAll('#teachers-grid .card img, #teachers-grid .card-img-top');
    allCardImages.forEach(function(img) {
        img.style.height = '250px';
        img.style.maxHeight = '250px';
        img.style.minHeight = '250px';
        img.style.width = '100%';
        img.style.objectFit = 'cover';
        img.style.display = 'block';
    });
    
    // Hide any unwanted images that might appear
    const allImages = document.querySelectorAll('.card img');
    allImages.forEach(function(img) {
        if (!img.classList.contains('card-img-top')) {
            img.style.display = 'none';
        }
    });
});

// Grid layout animation seperti galeri
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('teachers-grid');
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

