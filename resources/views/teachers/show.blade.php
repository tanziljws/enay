@extends('layouts.app')

@section('title', $teacher->name . ' - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                @if($teacher->photo)
                    <img src="{{ asset('storage/' . $teacher->photo) }}?v={{ time() }}" 
                         class="card-img-top" 
                         alt="{{ $teacher->name }}" 
                         style="max-height: 500px; width: 100%; object-fit: contain; object-position: center; background-color: #f8f9fa; cursor: pointer;"
                         data-bs-toggle="modal" 
                         data-bs-target="#teacherPhotoModal">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 400px;">
                        <i class="fas fa-{{ $teacher->gender == 'male' ? 'male' : 'female' }} fa-5x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <h1 class="card-title mb-3">{{ $teacher->name }}</h1>
                    
                    <div class="mb-4">
                        @if($teacher->role)
                            <span class="badge bg-primary" style="background-color: #3d4f5d !important;">{{ ucfirst($teacher->role) }}</span>
                        @endif
                        @if($teacher->status == 'active')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                    
                    <div class="row mb-4">
                        @if($teacher->gender)
                        <div class="col-md-6 mb-2">
                            <strong>Jenis Kelamin:</strong> {{ $teacher->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                        @endif
                        @if($teacher->teacher_number)
                        <div class="col-md-6 mb-2">
                            <strong>NIP:</strong> {{ $teacher->teacher_number }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Interaction Buttons -->
                    @include('partials.interaction-buttons', [
                        'type' => 'teacher',
                        'itemId' => $teacher->id,
                        'likes' => $teacher->likes_count,
                        'dislikes' => $teacher->dislikes_count,
                        'userReaction' => $teacher->user_reaction,
                        'commentsCount' => $teacher->comments_count,
                        'hasDownload' => false
                    ])
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ url('/teachers') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Guru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
@if($teacher->photo)
<div class="modal fade" id="teacherPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $teacher->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $teacher->photo) }}" class="img-fluid" alt="{{ $teacher->name }}">
            </div>
        </div>
    </div>
</div>
@endif
@endsection
