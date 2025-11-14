@extends('layouts.app')

@section('title', 'Statistik Galeri')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Statistik Galeri</h3>
        <div>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Kembali ke Galeri</a>
            <a href="{{ route('admin.gallery.print-report') }}" class="btn btn-primary" target="_blank">Cetak Laporan</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Foto</h5>
                    <h2>{{ $totalItems }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Dilihat</h5>
                    <h2>{{ $totalViews }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Disukai</h5>
                    <h2>{{ $totalLikes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Total Komentar</h5>
                    <h2>{{ $totalComments }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Viewed Items -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Foto Paling Banyak Dilihat</h5>
        </div>
        <div class="card-body">
            @if($topViewed->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Judul</th>
                                <th>Dilihat</th>
                                <th>Disukai</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topViewed as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-fluid" style="max-height: 100px; width: auto;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px; width: 100px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->views_count }}</td>
                                <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                                <td>{{ $item->approved_comments_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Belum ada data.</p>
            @endif
        </div>
    </div>

    <!-- Top Liked Items -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Foto Paling Banyak Disukai</h5>
        </div>
        <div class="card-body">
            @if($topLiked->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Judul</th>
                                <th>Dilihat</th>
                                <th>Disukai</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topLiked as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-fluid" style="max-height: 100px; width: auto;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px; width: 100px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->views_count }}</td>
                                <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                                <td>{{ $item->approved_comments_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Belum ada data.</p>
            @endif
        </div>
    </div>

    <!-- All Gallery Items -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Semua Foto Galeri</h5>
        </div>
        <div class="card-body">
            @if($galleryItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Judul</th>
                                <th>Tanggal Diambil</th>
                                <th>Status</th>
                                <th>Dilihat</th>
                                <th>Disukai</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galleryItems as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-fluid" style="max-height: 100px; width: auto;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px; width: 100px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->taken_at ? $item->taken_at->format('d M Y') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ $item->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $item->views_count }}</td>
                                <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                                <td>{{ $item->approved_comments_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Belum ada foto.</p>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .btn {
        display: none !important;
    }
    
    .card-header, .card-body {
        border: 1px solid #000 !important;
    }
    
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    
    th {
        background-color: #f2f2f2;
    }
}
</style>
@endsection