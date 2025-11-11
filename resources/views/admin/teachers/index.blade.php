@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Guru</h3>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">Tambah Guru</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table align-middle" style="border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden;">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Spesialisasi</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $t)
                <tr>
                    <td>
                        @if($t->photo)
                            <img src="{{ asset('storage/' . $t->photo) }}" alt="{{ $t->name }}" 
                                 style="width:48px;height:48px;object-fit:cover;border-radius:50%;cursor:pointer" 
                                 data-bs-toggle="modal" data-bs-target="#photoModal" 
                                 data-photo-src="{{ asset('storage/' . $t->photo) }}" 
                                 data-teacher-name="{{ $t->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width:48px;height:48px;border-radius:50%">
                                <i class="{{ $t->gender==='male' ? 'fas fa-male text-primary' : 'fas fa-female text-pink' }}"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $t->teacher_number }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->specialization }}</td>
                    <td>{{ $t->major }}</td>
                    <td><span class="badge bg-secondary">{{ $t->status }}</span></td>
                    <td>
                        <a href="{{ route('admin.teachers.edit',$t) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.teachers.destroy',$t) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus guru ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $teachers->links() }}
</div>

<!-- Modal untuk menampilkan foto -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Foto Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modal-photo" src="" alt="" class="img-fluid" style="max-height: 500px; object-fit: contain;">
                <div class="mt-2">
                    <h6 id="modal-teacher-name" class="text-muted"></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoModal = document.getElementById('photoModal');
    const modalPhoto = document.getElementById('modal-photo');
    const modalTeacherName = document.getElementById('modal-teacher-name');
    
    photoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const photoSrc = button.getAttribute('data-photo-src');
        const teacherName = button.getAttribute('data-teacher-name');
        
        modalPhoto.src = photoSrc;
        modalPhoto.alt = teacherName;
        modalTeacherName.textContent = teacherName;
    });
});
</script>
@endsection


