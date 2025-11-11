@extends('layouts.app')

@section('title', 'Detail Program Keahlian - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Program Keahlian: {{ $major->name }}</h4>
                    <div>
                        <a href="{{ route('admin.majors.edit', $major) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($major->image_url)
                                <div class="text-center mb-4">
                                    <img src="{{ $major->image_url }}" 
                                         alt="{{ $major->name }}" 
                                         class="img-fluid rounded shadow" 
                                         style="max-height: 300px; object-fit: cover;">
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                         style="height: 300px;">
                                        <div class="text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>Tidak ada gambar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Kode Program</h6>
                                    <p class="mb-3">
                                        <span class="badge bg-secondary fs-6">{{ $major->code }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Nama Singkat</h6>
                                    <p class="mb-3"><strong>{{ $major->name }}</strong></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted">Nama Lengkap</h6>
                                <p>{{ $major->full_name }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted">Deskripsi</h6>
                                <p>{{ $major->description }}</p>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Kategori</h6>
                                    <p class="mb-3">
                                        <span class="badge bg-info">{{ ucfirst($major->category) }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Jumlah Siswa</h6>
                                    <p class="mb-3">
                                        <span class="badge bg-success fs-6">{{ $major->student_count }} Siswa</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Status</h6>
                                    <p class="mb-3">
                                        @if($major->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Urutan Tampil</h6>
                                    <p class="mb-3"><strong>{{ $major->sort_order }}</strong></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Dibuat</h6>
                                    <p class="mb-3">{{ $major->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted">Terakhir Diupdate</h6>
                                    <p class="mb-3">{{ $major->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
