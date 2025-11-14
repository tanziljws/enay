@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard Admin</h2>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-newspaper"></i> Kelola Berita
                    </h5>
                    <p class="card-text">Tambah, edit, dan hapus berita sekolah.</p>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-images"></i> Upload Galeri
                    </h5>
                    <p class="card-text">Upload foto-foto kegiatan untuk halaman galeri.</p>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-chart-bar"></i> Statistik Galeri
                    </h5>
                    <p class="card-text">Lihat statistik interaksi pengguna pada foto galeri.</p>
                    <a href="{{ route('admin.gallery.statistics') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-graduation-cap"></i> Program Keahlian
                    </h5>
                    <p class="card-text">Kelola program keahlian/jurusan sekolah.</p>
                    <a href="{{ route('admin.majors.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-chalkboard-teacher"></i> Data Guru & Staff
                    </h5>
                    <p class="card-text">Kelola data guru dan staff sekolah.</p>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-chart-line"></i> Data Interaksi User
                    </h5>
                    <p class="card-text">Lihat data like, dislike, comment, dan download dari user. Laporan per minggu tersedia.</p>
                    <a href="{{ route('admin.interactions.dashboard') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: #3d4f5d;">
                        <i class="fas fa-comments"></i> Kelola Komentar
                    </h5>
                    <p class="card-text">Moderasi dan hapus komentar yang tidak pantas dari user. Total: <strong>{{ \App\Models\Comment::count() }}</strong> komentar.</p>
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection