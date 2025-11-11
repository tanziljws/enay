@extends('layouts.app')

@section('title', 'Edit Foto Galeri')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Edit Foto</h3>
    <form method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            <div class="mt-2">
                <small class="text-muted">Saat ini:</small>
                <div><img src="{{ asset('storage/' . $item->image) }}" alt="" style="max-height:120px"></div>
            </div>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Kegiatan (opsional)</label>
                <input type="datetime-local" name="taken_at" value="{{ old('taken_at', optional($item->taken_at)->format('Y-m-d\TH:i')) }}" class="form-control @error('taken_at') is-invalid @enderror">
                @error('taken_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="published" @selected(old('status',$item->status)==='published')>Published</option>
                    <option value="draft" @selected(old('status',$item->status)==='draft')>Draft</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection


