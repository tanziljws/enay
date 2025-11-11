@extends('layouts.app')

@section('title', 'Beranda - Website SMK NEGERI 4 BOGOR')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang di SMK NEGERI 4 KOTA BOGOR</h1>
                <p class="lead mb-4">Membangun generasi yang cerdas, berkarakter, dan berprestasi untuk masa depan yang gemilang.</p>
                <div class="d-flex gap-3">
                    <a href="{{ url('/about') }}" class="btn btn-light btn-lg">Pelajari Lebih Lanjut</a>
                    <a href="{{ url('/news') }}" class="btn btn-outline-light btn-lg">Lihat Berita</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row" id="stats-container">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number" id="total-students">-</div>
                    <h5>Total Siswa</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number" id="total-teachers">-</div>
                    <h5>Total Guru</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number" id="total-classes">-</div>
                    <h5>Total Kelas</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="stats-number" id="total-subjects">-</div>
                    <h5>Total Mata Pelajaran</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Berita Terbaru</h2>
                <div id="latest-news">
                    @if($latestNews->count() > 0)
                        @foreach($latestNews as $item)
                            <div class="card news-card mb-4">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}?v={{ time() }}" class="img-fluid rounded-start" alt="{{ $item->title }}" style="height: 200px; object-fit: cover; width: 100%;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100" style="height: 200px;">
                                                <i class="fas fa-newspaper fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <span class="badge bg-{{ $item->category == 'academic' ? 'primary' : ($item->category == 'sports' ? 'success' : ($item->category == 'events' ? 'warning' : ($item->category == 'announcements' ? 'info' : 'secondary'))) }} mb-2">
                                                {{ ucfirst($item->category == 'academic' ? 'Akademik' : ($item->category == 'sports' ? 'Olahraga' : ($item->category == 'events' ? 'Acara' : ($item->category == 'announcements' ? 'Pengumuman' : 'Umum')))) }}
                                            </span>
                                            <h5 class="card-title">{{ $item->title }}</h5>
                                            <p class="card-text">{{ Str::limit(strip_tags($item->content), 200) }}</p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $item->author }} | 
                                                    <i class="fas fa-calendar me-1"></i>{{ $item->published_at->format('d M Y') }}
                                                </small>
                                            </p>
                                            <a href="/news/{{ $item->id }}" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada berita tersedia.</p>
                    @endif
                </div>
                <div class="text-center mt-4">
                    <a href="{{ url('/news') }}" class="btn btn-primary">Lihat Semua Berita</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Fitur Unggulan</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Manajemen Siswa</h5>
                        <p class="card-text">Sistem manajemen siswa yang terintegrasi untuk memantau perkembangan akademik dan non-akademik.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Manajemen Guru</h5>
                        <p class="card-text">Platform untuk mengelola data guru, jadwal mengajar, dan evaluasi kinerja.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Laporan Akademik</h5>
                        <p class="card-text">Sistem pelaporan yang komprehensif untuk memantau prestasi dan kehadiran siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load stats
    loadStats();
});

function loadStats() {
    $.get(API_BASE_URL + '/admin/dashboard/stats')
        .done(function(response) {
            if (response.success) {
                $('#total-students').text(response.data.total_students);
                $('#total-teachers').text(response.data.total_teachers);
                $('#total-classes').text(response.data.total_classes);
                $('#total-subjects').text(response.data.total_subjects);
            }
        })
        .fail(function() {
            // Fallback values if API is not accessible
            $('#total-students').text('0');
            $('#total-teachers').text('0');
            $('#total-classes').text('0');
            $('#total-subjects').text('0');
        });
}
</script>
@endsection
