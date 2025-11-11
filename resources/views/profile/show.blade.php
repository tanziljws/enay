@extends('layouts.app')

@section('title', 'Profil Saya - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #3d4f5d; color: white;">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil Saya</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Photo -->
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-photo-container">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}?v={{ time() }}" 
                                         class="rounded-circle img-thumbnail" 
                                         alt="{{ $user->name }}"
                                         style="width: 200px; height: 200px; object-fit: cover;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="rounded-circle bg-light align-items-center justify-content-center mx-auto" 
                                         style="width: 200px; height: 200px; border: 3px solid #dee2e6; display: none;">
                                        <i class="fas fa-user fa-5x text-muted"></i>
                                    </div>
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 200px; height: 200px; border: 3px solid #dee2e6;">
                                        <i class="fas fa-user fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm" style="background-color: #3d4f5d; border-color: #3d4f5d;">
                                    <i class="fas fa-edit me-1"></i>Edit Profil
                                </a>
                            </div>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $user->name }}</h3>
                            
                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0"><i class="fas fa-envelope me-2" style="color: #3d4f5d;"></i>{{ $user->email }}</p>
                            </div>
                            
                            @if($user->phone)
                            <div class="mb-3">
                                <label class="text-muted small">Telepon</label>
                                <p class="mb-0"><i class="fas fa-phone me-2" style="color: #3d4f5d;"></i>{{ $user->phone }}</p>
                            </div>
                            @endif
                            
                            @if($user->bio)
                            <div class="mb-3">
                                <label class="text-muted small">Bio</label>
                                <p class="mb-0"><i class="fas fa-info-circle me-2" style="color: #3d4f5d;"></i>{{ $user->bio }}</p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="text-muted small">Bergabung Sejak</label>
                                <p class="mb-0"><i class="fas fa-calendar me-2" style="color: #3d4f5d;"></i>{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Activity Stats -->
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2" style="color: #3d4f5d;"></i>Aktivitas Saya</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-thumbs-up fa-2x mb-2" style="color: #3d4f5d;"></i>
                                    <h4 class="mb-0">{{ $user->reactions()->where('type', 'like')->count() }}</h4>
                                    <small class="text-muted">Likes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-comment fa-2x mb-2" style="color: #3d4f5d;"></i>
                                    <h4 class="mb-0">{{ $user->comments()->count() }}</h4>
                                    <small class="text-muted">Komentar</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-download fa-2x mb-2" style="color: #3d4f5d;"></i>
                                    <h4 class="mb-0">{{ $user->downloads()->count() }}</h4>
                                    <small class="text-muted">Downloads</small>
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
