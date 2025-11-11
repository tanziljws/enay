@extends('layouts.app')

@section('title', 'Tentang Kami - Galeri Sekolah Enay')

@section('styles')
<style>
    /* Custom hero background for About page */
    .hero-section {
        background: linear-gradient(135deg, rgba(44,62,80,0.7), rgba(52,152,219,0.7)), url('{{ asset('images/about.jpeg') }}') !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }
    
    /* Custom footer styling for About page - same as other pages */
    .footer {
        background-color: var(--primary-color) !important;
        color: white;
        padding: 40px 0;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-4">Tentang Kami</h1>
                <p class="lead">Kami berkomitmen untuk memberikan pendidikan terbaik dan membentuk karakter siswa yang unggul.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">Sejarah Sekolah</h2>
                        <p class="lead">SMK NEGERI 4 KOTA BOGOR didirikan pada tahun 2009, tepatnya pada tanggal 15 Juni 2009, dengan visi untuk menjadi pusat pendidikan yang unggul dan terdepan dalam membentuk generasi yang berkarakter, berprestasi dan siap bekerja.</p>
                        
                        <p>Sejak awal berdirinya, kami telah berkomitmen untuk memberikan pendidikan berkualitas tinggi yang tidak hanya fokus pada aspek akademik, tetapi juga pengembangan karakter dan keterampilan hidup siswa. Dengan fasilitas yang lengkap dan tenaga pengajar yang berpengalaman, kami terus berinovasi dalam metode pembelajaran untuk memastikan setiap siswa dapat mencapai potensi terbaiknya.</p>
                        
                        <h3 class="mt-5 mb-3">Visi</h3>
                        <div class="alert alert-primary">
                            <i class="fas fa-eye me-2"></i>
                            <strong>Menjadi sekolah unggulan yang menghasilkan lulusan berkarakter, berprestasi dan siap bekerjadi tingkat nasional dan internasional.</strong>
                        </div>
                        
                        <h3 class="mt-5 mb-3">Misi</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                Menyelenggarakan pendidikan berkualitas tinggi dengan kurikulum yang relevan dan inovatif
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                Membangun karakter siswa yang berakhlak mulia, disiplin, dan bertanggung jawab
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                Mengembangkan potensi akademik dan non-akademik siswa secara optimal
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                Menyiapkan siswa untuk menghadapi tantangan masa depan dengan bekal pengetahuan dan keterampilan yang memadai
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                Menciptakan lingkungan belajar yang kondusif, aman, dan menyenangkan
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Sekolah</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="fas fa-calendar-alt text-primary me-2"></i>Tahun Berdiri</h6>
                            <p class="mb-0">2009</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat</h6>
                            <p class="mb-0">Jl. Raya Tajur, Kp.Buntar RT.02/RW.08, Kel. Muarasari, Kec. Bogor Selatan. <br>Jawa Barat 16137</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-phone text-primary me-2"></i>Telepon</h6>
                            <p class="mb-0">(0251) 7547381</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                            <p class="mb-0">smkn4@smkn4bogor.sch.id</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-globe text-primary me-2"></i>Website</h6>
                            <p class="mb-0">smkn4bogor.sch.id</p>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Akreditasi</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-award fa-3x text-warning"></i>
                        </div>
                        <h4 class="text-warning">A</h4>
                        <p class="mb-0">Terakreditasi A oleh BAN-S/M</p>
                        <small class="text-muted">Tahun 2021</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
