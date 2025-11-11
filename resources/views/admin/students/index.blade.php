@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Siswa</h3>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Tambah Siswa</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td>{{ $s->student_number }}</td>
                    <td>{{ $s->name }}</td>
                    <td>{{ optional($s->classRoom)->name }}</td>
                    <td><span class="badge bg-secondary">{{ $s->status }}</span></td>
                    <td>
                        <a href="{{ route('admin.students.edit',$s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.students.destroy',$s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $students->links() }}
</div>
@endsection


