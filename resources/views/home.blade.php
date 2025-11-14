@extends('layouts.app')

@section('title', 'Beranda - Galeri Sekolah')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Selamat Datang di<br>SMK NEGERI 4 KOTA BOGOR</h1>
                <p class="lead">Platform digital untuk dokumentasi kegiatan sekolah, berita terbaru, dan interaksi antara guru dan siswa.</p>
                <div class="mt-4">
                    <a href="{{ url('/galeri') }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-images"></i> Lihat Galeri
                    </a>
                    <a href="{{ url('/news') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-newspaper"></i> Berita Terbaru
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/hero-illustration.svg') }}" alt="Illustration" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container-fluid">
        <div class="row" id="stats-container">
            <div class="col-12">
                <div class="card stats-card">
                    <div class="row g-0">
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-5">
                            <div class="principal-photo-frame">
                                <img src="{{ asset('images/principal.jpg.png') }}" alt="Kepala Sekolah" class="img-fluid" style="width: 300px; height: 350px; object-fit: cover; border-radius: 14px;">
                            </div>
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body text-center text-md-start py-4 w-100">
                                <h2 class="card-title">Sambutan Kepala Sekolah SMK Negeri 4 Bogor</h2>
                                <p class="card-text fs-5 fst-italic mb-4">"Pendidikan adalah fondasi utama dalam membentuk karakter bangsa yang unggul, berprestasi, dan berakhlak mulia. Melalui sinergi antara guru, siswa, dan orang tua, kita wujudkan generasi emas yang siap menghadapi tantangan global."</p>
                                <p class="mb-2 fs-4"><strong>DRS. MULYAMURPRI HARTONO, M.SI</strong></p>
                                <p class="text-muted fs-5 mb-0">Kepala Sekolah</p>
                            </div>
                        </div>
                    </div>
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
                                                    <i class="fas fa-calendar me-1"></i>{{ $item->published_at ? $item->published_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}
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

<style>
    .principal-photo-frame {
        padding: 10px;
        border-radius: 22px;
        background:
            radial-gradient(circle at top left,  rgba(46, 64, 104, 0.4), transparent 55%),
            radial-gradient(circle at bottom right, rgba(46, 64, 104, 0.6), transparent 60%),
            linear-gradient(135deg, #0b1725, #1f3550); /* biru tua dengan sudut lebih pekat */
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        position: relative;
    }

    .principal-photo-frame::before {
        content: "";
        position: absolute;
        inset: 4px;
        border-radius: 18px;
        border: 2px solid #f4d08a; /* emas luar */
        pointer-events: none;
    }

    .principal-photo-frame::after {
        content: "";
        position: absolute;
        inset: 10px;
        border-radius: 14px;
        border: 2px solid #d9b765; /* emas dalam */
        pointer-events: none;
    }

    .principal-photo-frame img {
        display: block;
        width: 100%;
        height: 100%;
        border-radius: 14px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        position: relative;
        z-index: 1;
    }
</style>
@endsection

@section('scripts')
