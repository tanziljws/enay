@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Tambah Siswa</h3>
    <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">NIS</label>
                <input type="text" name="student_number" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kelas</label>
                <input type="number" name="class_room_id" class="form-control" placeholder="ID kelas" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                    <option value="graduated">Lulus</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto (opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
    <p class="text-muted mt-3">Pastikan sudah menjalankan: <code>php artisan storage:link</code> agar foto tampil.</p>
    <p class="text-muted">Untuk memilih kelas, gunakan ID kelas yang ada; form sederhana ini bisa kita tingkatkan menjadi dropdown nanti.</p>
</div>
@endsection


