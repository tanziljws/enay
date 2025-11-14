@extends('admin.layouts.app')

@section('title', 'Kelola Komentar - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Kelola Komentar</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $type == 'all' ? 'active' : '' }}" href="{{ route('admin.comments.index', ['type' => 'all']) }}">
                <i class="fas fa-list me-1"></i>Semua ({{ \App\Models\Comment::count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type == 'gallery' ? 'active' : '' }}" href="{{ route('admin.comments.index', ['type' => 'gallery']) }}">
                <i class="fas fa-image me-1"></i>Galeri ({{ \App\Models\Comment::where('commentable_type', 'App\Models\GalleryItem')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type == 'news' ? 'active' : '' }}" href="{{ route('admin.comments.index', ['type' => 'news']) }}">
                <i class="fas fa-newspaper me-1"></i>Berita ({{ \App\Models\Comment::where('commentable_type', 'App\Models\News')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type == 'teacher' ? 'active' : '' }}" href="{{ route('admin.comments.index', ['type' => 'teacher']) }}">
                <i class="fas fa-chalkboard-teacher me-1"></i>Guru ({{ \App\Models\Comment::where('commentable_type', 'App\Models\Teacher')->count() }})
            </a>
        </li>
    </ul>

    <!-- Bulk Actions -->
    <form id="bulkDeleteForm" action="{{ route('admin.comments.bulk-delete') }}" method="POST">
        @csrf
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-comments me-2"></i>Daftar Komentar</span>
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus komentar yang dipilih?')">
                    <i class="fas fa-trash me-1"></i>Hapus Terpilih
                </button>
            </div>
            <div class="card-body p-0">
                @if($comments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>User</th>
                                    <th>Komentar</th>
                                    <th>Pada</th>
                                    <th>Tanggal</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comments as $comment)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" class="form-check-input comment-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($comment->user && $comment->user->profile_photo)
                                                <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                            @endif
                                            <span>{{ $comment->user ? $comment->user->name : 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 400px;">
                                            {{ Str::limit($comment->comment, 100) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($comment->commentable_type == 'App\Models\GalleryItem')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-image me-1"></i>Galeri
                                            </span>
                                        @elseif($comment->commentable_type == 'App\Models\News')
                                            <span class="badge bg-info">
                                                <i class="fas fa-newspaper me-1"></i>Berita
                                            </span>
                                        @elseif($comment->commentable_type == 'App\Models\Teacher')
                                            <span class="badge bg-success">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>Guru
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            {{ $comment->commentable ? Str::limit($comment->commentable->title ?? $comment->commentable->name ?? 'N/A', 30) : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $comment->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Hapus komentar ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada komentar</p>
                    </div>
                @endif
            </div>
            @if($comments->hasPages())
            <div class="card-footer">
                {{ $comments->links() }}
            </div>
            @endif
        </div>
    </form>
</div>

<script>
// Select all checkboxes
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Update select all when individual checkboxes change
document.querySelectorAll('.comment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.comment-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.comment-checkbox:checked');
        document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
    });
});
</script>
@endsection
