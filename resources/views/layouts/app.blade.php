<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Galeri Sekolah Enay')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @yield('head')
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        
        /* Ensure all images display with normal colors and correct size */
        img {
            filter: none !important;
            -webkit-filter: none !important;
            image-rendering: auto !important;
            -webkit-image-rendering: auto !important;
        }
        
        /* Force card images to have correct size */
        .card-img-top,
        #majors-grid .card img,
        #teachers-grid .card img,
        #gallery-grid .card img {
            height: 250px !important;
            max-height: 250px !important;
            min-height: 250px !important;
            width: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }
        
        /* Navbar profile photo - MUST stay small */
        .navbar-profile-photo,
        .navbar .navbar-profile-photo,
        nav .navbar-profile-photo {
            width: 35px !important;
            height: 35px !important;
            max-width: 35px !important;
            max-height: 35px !important;
            min-width: 35px !important;
            min-height: 35px !important;
            object-fit: cover !important;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link.active {
            color: #fff !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 2px;
            background-color: #fff;
            border-radius: 1px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(44,62,80,0.7), rgba(52,152,219,0.7)), url('{{ asset('images/bg0.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 80px 0;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .news-card {
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .news-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .news-card .card-body {
            padding: 20px;
        }
        
        .news-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .news-card .card-text {
            color: #6c757d;
            line-height: 1.5;
        }
        
        .news-card .badge {
            font-size: 0.75rem;
            padding: 5px 10px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 40px 0;
        }
        
        .news-card {
            margin-bottom: 20px;
        }
        
        .news-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .stats-card {
            text-align: center;
            padding: 30px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        /* Extra gutter for footer columns */
        .footer-grid { 
            --bs-gutter-x: 6rem; 
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:32px;width:auto;" class="me-2">
                K4 Website 
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('news*') ? 'active' : '' }}" href="{{ url('/news') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('jurusan') ? 'active' : '' }}" href="{{ url('/jurusan') }}">Keahlian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}" href="{{ route('teachers.index', ['major' => 'PPLG']) }}">Guru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('galeri') ? 'active' : '' }}" href="{{ url('/galeri') }}">Galeri</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Kontak</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-gauge me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-link nav-link" type="submit">
                                        <i class="fas fa-right-from-bracket me-1"></i>Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}?v={{ time() }}" 
                                             class="rounded-circle me-2 navbar-profile-photo" 
                                             alt="{{ Auth::user()->name }}"
                                             style="width: 35px !important; height: 35px !important; max-width: 35px !important; max-height: 35px !important; min-width: 35px !important; min-height: 35px !important; object-fit: cover !important; border: 2px solid #fff !important; box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                        <i class="fas fa-user-circle me-2" style="font-size: 1.8rem; display: none;"></i>
                                    @else
                                        <i class="fas fa-user-circle me-2" style="font-size: 1.8rem;"></i>
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i>Profil Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer (hidden on admin pages) -->
    @unless(request()->is('admin*'))
    <footer class="footer">
        <div class="container">
            <div class="row gx-5 footer-grid">
                <div class="col-md-4">
                    <h5>SMKN4 Website Gallery</h5>

                    <p>Akreditasi dan Sertifikasi SMKN 4 BOGOR
                    Sekolah ini telah terakreditasi A dengan Nomor SK Akreditasi 1347/BAN-SM/SK/2021 pada tanggal 8 Desember 2021.</p>
                
                </div>
                <div class="col-md-4">
                    <h5>Kontak Kami</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, Muarasari, Bogor Sel., Kota Bogor, Jawa Barat 16137, Indonesia</p>
                    <p><i class="fas fa-phone me-2"></i>(0251) 7547381</p>
                    <p><i class="fas fa-envelope me-2"></i>smkn4@smkn4bogor.sch.id</p>
                </div>
                <div class="col-md-4">
                    <h5>Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 Gallery K4. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Powered by Laravel API</p>
                </div>
            </div>
        </div>
    </footer>
    @endunless

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // API Base URL
        const API_BASE_URL = '{{ url("/api/v1") }}';
        // App Base URL (untuk membangun URL gambar yang benar saat berada di subfolder /public)
        const APP_BASE_URL = '{{ url("/") }}';
        
        // Set active navigation link
        $(document).ready(function() {
            const currentPath = window.location.pathname;
            $('.navbar-nav .nav-link').each(function() {
                const linkPath = $(this).attr('href');
                if (currentPath === linkPath || (currentPath === '/' && linkPath === '{{ url("/") }}')) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
