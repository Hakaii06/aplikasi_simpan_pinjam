@extends('layouts.app')

@section('title', 'Anggota')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 page-title mb-1">Data anggota</h1><p class="text-secondary mb-0">Kelola anggota yang terdaftar.</p></div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('reports.members') }}">Cetak PDF</a>
        <a class="btn btn-primary" href="{{ route('anggota.create') }}">+ Tambah anggota</a>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>No. Anggota</th><th>Nama</th><th>Alamat</th><th>Telepon</th><th>Transaksi</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($members as $member)
                <tr>
                    <td>{{ $member->member_number }}</td><td>{{ $member->name }}</td><td>{{ $member->address }}</td><td>{{ $member->phone }}</td><td>{{ $member->transactions_count }}</td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('anggota.edit', $member) }}">Ubah</a>
                        <form class="d-inline" method="POST" action="{{ route('anggota.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini beserta transaksinya?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="text-center text-secondary py-4" colspan="6">Belum ada data anggota.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($members->hasPages()) <div class="card-body">{{ $members->links() }}</div> @endif
</div>
@endsection
