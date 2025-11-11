@extends('layouts.app')

@section('title', 'Tambah Foto Absen')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Tambah Foto Absen</h3>
    <form method="POST" action="{{ route('admin.attendance.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Level</label>
                <input type="text" name="level" class="form-control" placeholder="Ketik: X / XI / XII" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jurusan</label>
                <input type="text" name="major" class="form-control" placeholder="Ketik: PPLG / TJKT / TO / TP" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kelas (Rombel)</label>
                <select name="class_room_id" class="form-select" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
        <p class="text-muted mt-3">Pastikan sudah menjalankan: <code>php artisan storage:link</code> agar gambar tampil.</p>
    </form>
</div>
@endsection


