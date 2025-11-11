@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="container py-5" style="max-width:520px;">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Login Admin</h3>
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk</button>
            </form>
        </div>
    </div>
    <p class="text-muted mt-3">Gunakan akun dari tabel `users`.</p>
</div>
@endsection


