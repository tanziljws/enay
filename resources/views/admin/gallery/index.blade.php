@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Galeri Kegiatan</h3>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Tambah Foto</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($items as $item)
            <div class="col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $item->title }}</h6>
                        <small class="text-muted">{{ $item->taken_at ? $item->taken_at->format('d M Y') : '' }}</small>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="ms-auto" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">Belum ada foto.</div></div>
        @endforelse
    </div>

    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection


