@extends('admin.layouts.app')

@section('title', 'Interaksi Galeri')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-images"></i> Interaksi Galeri
        </h1>
        <a href="{{ route('admin.interactions.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Reactions Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 text-white" style="background-color: #3d4f5d;">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-thumbs-up"></i> Reactions (Like & Dislike)
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Foto Galeri</th>
                            <th>Type</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reactions as $reaction)
                            <tr>
                                <td>
                                    <i class="fas fa-user"></i> {{ $reaction->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $reaction->user->email }}</small>
                                </td>
                                <td>{{ $reaction->galleryItem->title ?? 'N/A' }}</td>
                                <td>
                                    @if($reaction->type === 'like')
                                        <span class="badge bg-success">
                                            <i class="fas fa-thumbs-up"></i> Like
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-thumbs-down"></i> Dislike
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $reaction->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada reactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reactions->links() }}
        </div>
    </div>

    <!-- Comments Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 text-white" style="background-color: #3d4f5d;">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-comments"></i> Comments
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Foto Galeri</th>
                            <th>Komentar</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>
                                    <i class="fas fa-user"></i> {{ $comment->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                </td>
                                <td>{{ $comment->galleryItem->title ?? 'N/A' }}</td>
                                <td>{{ $comment->comment }}</td>
                                <td>{{ $comment->created_at->diffForHumans() }}</td>
                                <td>
                                    <form action="{{ route('admin.interactions.gallery.comment.delete', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus komentar ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada comments</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection
