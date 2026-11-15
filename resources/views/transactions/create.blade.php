@extends('layouts.app')

@section('title', 'Tambah transaksi')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="mb-4"><h1 class="h2 page-title">Tambah transaksi</h1><a href="{{ route('transaksi.index') }}">← Kembali ke transaksi</a></div>
    <div class="card"><div class="card-body p-4">
        @if ($members->isEmpty())
            <div class="alert alert-warning mb-0">Tambahkan anggota terlebih dahulu sebelum membuat transaksi.</div>
            <a class="btn btn-primary mt-3" href="{{ route('anggota.create') }}">Tambah anggota</a>
        @else
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                @include('transactions.form')
                <button class="btn btn-primary" type="submit">Simpan transaksi</button>
            </form>
        @endif
    </div></div>
</div></div>
@endsection
