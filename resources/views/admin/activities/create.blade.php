@extends('layouts.app')

@section('title', 'Tambah Kegiatan')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Tambah Kegiatan</h3>
    <form method="POST" action="{{ route('admin.activities.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Judul Kegiatan</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="academic" @selected(old('category')==='academic')>Akademik</option>
                    <option value="sports" @selected(old('category')==='sports')>Olahraga</option>
                    <option value="cultural" @selected(old('category')==='cultural')>Budaya</option>
                    <option value="social" @selected(old('category')==='social')>Sosial</option>
                    <option value="other" @selected(old('category')==='other')>Lainnya</option>
                </select>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="draft" @selected(old('status')==='draft')>Draft</option>
                    <option value="published" @selected(old('status')==='published')>Published</option>
                    <option value="archived" @selected(old('status')==='archived')>Archived</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai (opsional)</label>
                <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Lokasi (opsional)</label>
                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Penyelenggara (opsional)</label>
                <input type="text" name="organizer" class="form-control @error('organizer') is-invalid @enderror" value="{{ old('organizer') }}">
                @error('organizer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
