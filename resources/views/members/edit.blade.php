@extends('layouts.app')

@section('title', 'Ubah anggota')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
    <div class="mb-4"><h1 class="h2 page-title">Ubah anggota</h1><a href="{{ route('anggota.index') }}">← Kembali ke anggota</a></div>
    <div class="card"><div class="card-body p-4">
        <form method="POST" action="{{ route('anggota.update', $member) }}">
            @csrf @method('PUT')
            @include('members.form')
            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
        </form>
    </div></div>
</div></div>
@endsection
