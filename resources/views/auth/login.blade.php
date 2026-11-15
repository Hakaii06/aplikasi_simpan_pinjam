@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 page-title mb-2">Selamat datang</h1>
                <p class="text-secondary mb-4">Masuk untuk mengelola data simpan pinjam.</p>
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Kata sandi</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Masuk</button>
                </form>
                <p class="text-center text-secondary mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
