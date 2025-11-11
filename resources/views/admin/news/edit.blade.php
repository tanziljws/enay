@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <h3 class="mb-3">Edit Berita</h3>
    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" value="{{ old('title', $news->title) }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Konten</label>
            <textarea name="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $news->content) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Penulis</label>
                <input type="text" name="author" value="{{ old('author', $news->author) }}" class="form-control @error('author') is-invalid @enderror" required>
                @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                    @foreach(['academic'=>'Akademik','sports'=>'Olahraga','events'=>'Acara','announcements'=>'Pengumuman','general'=>'Umum'] as $val=>$label)
                        <option value="{{ $val }}" @selected(old('category', $news->category)===$val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(['draft'=>'Draft','published'=>'Published','archived'=>'Archived'] as $val=>$label)
                        <option value="{{ $val }}" @selected(old('status', $news->status)===$val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @if($news->image)
                    <small class="text-muted">Gambar saat ini:</small>
                    <div><img src="{{ asset('storage/' . $news->image) }}" alt="" style="max-height:120px"></div>
                @endif
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Publish (opsional)</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}" class="form-control @error('published_at') is-invalid @enderror">
                @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection


