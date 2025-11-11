@extends('layouts.app')

@section('title', 'Kelola Kegiatan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Kelola Kegiatan</h3>
        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">Tambah Kegiatan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal Mulai</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->title }}</td>
                    <td>
                        @switch($activity->category)
                            @case('academic')
                                <span class="badge bg-primary">Akademik</span>
                                @break
                            @case('sports')
                                <span class="badge bg-success">Olahraga</span>
                                @break
                            @case('cultural')
                                <span class="badge bg-warning">Budaya</span>
                                @break
                            @case('social')
                                <span class="badge bg-info">Sosial</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Lainnya</span>
                        @endswitch
                    </td>
                    <td>{{ $activity->start_date->format('d M Y H:i') }}</td>
                    <td>{{ $activity->location ?? '-' }}</td>
                    <td>
                        @switch($activity->status)
                            @case('published')
                                <span class="badge bg-success">Published</span>
                                @break
                            @case('draft')
                                <span class="badge bg-warning">Draft</span>
                                @break
                            @case('archived')
                                <span class="badge bg-secondary">Archived</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada kegiatan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $activities->links() }}
</div>
@endsection
