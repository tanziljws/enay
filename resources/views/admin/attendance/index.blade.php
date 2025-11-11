@extends('layouts.app')

@section('title', 'Foto Absen Kelas')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Foto Absen Kelas</h3>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">Tambah Foto</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row g-4">
        @forelse($photos as $p)
            <div class="col-md-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top" alt="">
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $p->level }} {{ $p->major }} {{ $p->class_room_id }}</h6>
                        <small class="text-muted">{{ $p->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="card-footer bg-white">
                        <form action="{{ route('admin.attendance.destroy',$p) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
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
    <div class="mt-3">{{ $photos->links() }}</div>
</div>
@endsection


