@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 page-title mb-2">Buat akun</h1>
                <p class="text-secondary mb-4">Daftarkan akun untuk mulai mengelola koperasi.</p>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Kata sandi (minimal 8 karakter)</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Ulangi kata sandi</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Daftar</button>
                </form>
                <p class="text-center text-secondary mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
