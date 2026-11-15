@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 page-title mb-1">Transaksi simpan pinjam</h1><p class="text-secondary mb-0">Daftar setoran, penarikan, pencairan, dan angsuran.</p></div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="{{ route('reports.transactions') }}">Cetak PDF</a>
        <a class="btn btn-primary" href="{{ route('transaksi.create', ['category' => 'simpanan']) }}">+ Simpanan</a>
        <a class="btn btn-primary" href="{{ route('transaksi.create', ['category' => 'pinjaman']) }}">+ Pinjaman</a>
    </div>
</div>
<div class="d-flex gap-2 mb-3">
    <a class="btn btn-sm {{ $category === null ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('transaksi.index') }}">Semua</a>
    <a class="btn btn-sm {{ $category === 'simpanan' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('transaksi.index', ['category' => 'simpanan']) }}">Simpanan</a>
    <a class="btn btn-sm {{ $category === 'pinjaman' ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('transaksi.index', ['category' => 'pinjaman']) }}">Pinjaman</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Tanggal</th><th>Anggota</th><th>Kategori</th><th>Jenis</th><th>Keterangan</th><th class="text-end">Jumlah</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td><td>{{ $transaction->member->name }}</td><td>{{ ucfirst($transaction->category) }}</td><td>{{ ucfirst($transaction->type) }}</td><td>{{ $transaction->description ?: '-' }}</td>
                    <td class="text-end text-nowrap">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('transaksi.edit', $transaction) }}">Ubah</a>
                        <form class="d-inline" method="POST" action="{{ route('transaksi.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="7">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($transactions->hasPages()) <div class="card-body">{{ $transactions->links() }}</div> @endif
</div>
@endsection
