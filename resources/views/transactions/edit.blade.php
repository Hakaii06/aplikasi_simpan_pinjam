@extends('layouts.app')

@section('title', 'Ubah transaksi')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="mb-4"><h1 class="h2 page-title">Ubah transaksi</h1><a href="{{ route('transaksi.index') }}">← Kembali ke transaksi</a></div>
    <div class="card"><div class="card-body p-4">
        <form method="POST" action="{{ route('transaksi.update', $transaction) }}">
            @csrf @method('PUT')
            @include('transactions.form')
            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
        </form>
    </div></div>
</div></div>
@endsection
