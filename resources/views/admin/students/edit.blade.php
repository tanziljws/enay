@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Edit Siswa</h3>
    <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">NIS</label>
                <input type="text" name="student_number" value="{{ $student->student_number }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ $student->name }}" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kelas</label>
                <input type="number" name="class_room_id" value="{{ $student->class_room_id }}" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" @selected($student->status==='active')>Aktif</option>
                    <option value="inactive" @selected($student->status==='inactive')>Tidak Aktif</option>
                    <option value="graduated" @selected($student->status==='graduated')>Lulus</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto (opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                @if($student->photo)
                    <small class="text-muted d-block mt-1">Saat ini:</small>
                    <img src="/{{ $student->photo }}" alt="" style="max-height:120px">
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
    <p class="text-muted mt-3">Pastikan sudah menjalankan: <code>php artisan storage:link</code> agar foto tampil.</p>
</div>
@endsection


