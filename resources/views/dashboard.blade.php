@extends('layouts.app')

@section('title', 'Ringkasan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h2 page-title mb-1">Ringkasan</h1>
        <p class="text-secondary mb-0">Kelola anggota, simpanan, dan pinjaman koperasi.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('anggota.create') }}">+ Tambah anggota</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card metric-card metric-members h-100"><div class="card-body"><div class="metric-label text-secondary">Anggota</div><div class="display-6 fw-bold">{{ $memberCount }}</div></div></div></div>
    <div class="col-md-4"><div class="card metric-card metric-savings h-100"><div class="card-body"><div class="metric-label text-secondary">Transaksi simpanan</div><div class="display-6 fw-bold">{{ $savingCount }}</div></div></div></div>
    <div class="col-md-4"><div class="card metric-card metric-loans h-100"><div class="card-body"><div class="metric-label text-secondary">Transaksi pinjaman</div><div class="display-6 fw-bold">{{ $loanCount }}</div></div></div></div>
</div>
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4">
        <h2 class="h5 mb-0">Transaksi terbaru</h2>
        <a href="{{ route('transaksi.index') }}">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Tanggal</th><th>Anggota</th><th>Kategori</th><th>Jenis</th><th class="text-end">Jumlah</th></tr></thead>
            <tbody>
            @forelse ($recentTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->member->name }}</td>
                    <td>{{ ucfirst($transaction->category) }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="5">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
