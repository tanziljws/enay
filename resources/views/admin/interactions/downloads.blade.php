@extends('admin.layouts.app')

@section('title', 'History Download')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-download"></i> History Download
        </h1>
        <a href="{{ route('admin.interactions.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Downloads Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 text-white" style="background-color: #3d4f5d;">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-file-download"></i> Semua Download
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Jenis</th>
                            <th>File</th>
                            <th>Waktu Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downloads as $download)
                            <tr>
                                <td>
                                    <i class="fas fa-user"></i> {{ $download->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $download->user->email }}</small>
                                </td>
                                <td>
                                    @if($download->downloadable_type === 'App\Models\GalleryItem')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-images"></i> Galeri
                                        </span>
                                    @elseif($download->downloadable_type === 'App\Models\News')
                                        <span class="badge bg-success">
                                            <i class="fas fa-newspaper"></i> Berita
                                        </span>
                                    @elseif($download->downloadable_type === 'App\Models\Teacher')
                                        <span class="badge bg-info">
                                            <i class="fas fa-chalkboard-teacher"></i> Guru
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $download->file_path }}</small>
                                </td>
                                <td>
                                    {{ $download->created_at->format('d M Y H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $download->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada download</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $downloads->links() }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Downloads Galeri
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $downloads->where('downloadable_type', 'App\Models\GalleryItem')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Total Downloads Berita
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $downloads->where('downloadable_type', 'App\Models\News')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Total Downloads Guru
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $downloads->where('downloadable_type', 'App\Models\Teacher')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
