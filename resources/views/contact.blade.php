@extends('layouts.app')

@section('title', 'Kontak - Galeri Sekolah Enay')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Hubungi Kami</h1>
                <p class="lead">Kami siap membantu dan menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami.</p>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-phone" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Gedung Sekolah SMK Negeri 4 Kota Bogor</h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('images/gedung.png') }}" alt="Gedung Sekolah" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                        <p class="mt-3">Gedung sekolah kami yang nyaman untuk mendukung proses belajar mengajar yang optimal.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Alamat</h6>
                                    <p class="mb-0">Jl. Raya Tajur, Kp.Buntar RT.02/RW.08, Kel. Muarasari, Kec. Bogor Selatan.
Jawa Barat 16137</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-phone text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Telepon</h6>
                                    <p class="mb-0">(0251) 7547381<br>(0251) 7547381</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="mb-0">smkn4@smkn4bogor.sch.id</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-clock text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Jam Operasional</h6>
                                    <p class="mb-0">
                                        Senin - Jumat: 07:00 - 16:00<br>
                                        Sabtu: 07:00 - 12:00<br>
                                        Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Ikuti Kami</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <a href="https://www.facebook.com/smkn4kotabogor" target="_blank" class="btn btn-outline-primary btn-lg" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/smkn4kotabogor" target="_blank" class="btn btn-outline-danger btn-lg" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://wa.me/6285692728183" target="_blank" class="btn btn-outline-success btn-lg" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.tiktok.com/smkn4kotabogor" target="_blank" class="btn btn-outline-dark btn-lg" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Lokasi Sekolah</h2>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                <h5>Peta Lokasi</h5>
                                <p class="text-muted">Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Jawa Barat 16137</p>
                                <a href="https://maps.app.goo.gl/2rh25jodNgDrN8hm9?g st=aw" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>Buka di Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Pertanyaan yang Sering Diajukan</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Bagaimana cara mendaftar sebagai siswa baru?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Untuk mendaftar sebagai siswa baru, Anda dapat mengunjungi sekolah kami atau mengunduh formulir pendaftaran dari website. Persyaratan lengkap dapat dilihat di halaman pendaftaran.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Apa saja fasilitas yang tersedia di sekolah?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sekolah kami dilengkapi dengan perpustakaan, laboratorium sains dan komputer, lapangan olahraga, ruang multimedia, dan berbagai fasilitas pendukung lainnya.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Bagaimana sistem pembelajaran di sekolah?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Kami menggunakan kurikulum yang mengintegrasikan pembelajaran akademik dengan pengembangan karakter. Sistem pembelajaran didukung dengan teknologi modern dan metode yang inovatif.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Apakah ada program beasiswa?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Ya, kami menyediakan berbagai program beasiswa untuk siswa berprestasi dan siswa yang membutuhkan bantuan finansial. Informasi lengkap dapat diperoleh di bagian administrasi.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection