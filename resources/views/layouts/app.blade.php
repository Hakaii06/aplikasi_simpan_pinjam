<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SimpanPinjam') - SimpanPinjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #172b4d;
            --muted: #718096;
            --brand: #176b68;
            --brand-dark: #124e55;
            --surface: #fff;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f3f8f7 0%, #f7f8fc 55%, #fdf7ef 100%);
            color: var(--ink);
        }
        .navbar {
            background: linear-gradient(110deg, #123f50, #176b68 65%, #258078);
            box-shadow: 0 8px 24px rgba(18, 63, 80, .16);
        }
        .navbar-brand, .navbar .nav-link { color: #fff; }
        .navbar-brand { letter-spacing: .02em; }
        .navbar .nav-link { opacity: .84; transition: opacity .2s ease, color .2s ease; }
        .navbar .nav-link:hover { color: #f6d58a; opacity: 1; }
        .navbar .btn-outline-light { border-color: rgba(255, 255, 255, .55); }
        .navbar .btn-outline-light:hover { color: var(--brand-dark); }
        .card {
            overflow: hidden;
            border: 1px solid rgba(31, 74, 81, .08);
            border-radius: 1rem;
            background: var(--surface);
            box-shadow: 0 8px 28px rgba(31, 61, 77, .07);
        }
        .page-title { color: var(--ink); font-weight: 750; letter-spacing: -.025em; }
        .text-secondary { color: var(--muted) !important; }
        .btn { border-radius: .65rem; font-weight: 600; transition: transform .18s ease, box-shadow .18s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            border-color: var(--brand);
            background: linear-gradient(120deg, var(--brand), #258078);
            box-shadow: 0 4px 12px rgba(23, 107, 104, .16);
        }
        .btn-primary:hover, .btn-primary:focus { border-color: var(--brand-dark); background: var(--brand-dark); }
        .btn-outline-primary { border-color: #3c9186; color: var(--brand); }
        .btn-outline-primary:hover { border-color: var(--brand); background: var(--brand); }
        .form-control, .form-select { border-color: #dce5e8; border-radius: .65rem; }
        .form-control:focus, .form-select:focus {
            border-color: #55a69b;
            box-shadow: 0 0 0 .22rem rgba(37, 128, 120, .13);
        }
        .table { --bs-table-hover-bg: #f4faf8; }
        .table thead th {
            background: #edf5f3;
            color: #426568;
            font-size: .78rem;
            letter-spacing: .055em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .table td, .table th { padding: .9rem 1rem; }
        .table tbody tr:last-child > * { border-bottom: 0; }
        .metric-card { position: relative; }
        .metric-card::before { position: absolute; inset: 0 auto 0 0; width: 5px; content: ""; }
        .metric-card .card-body { padding: 1.4rem; }
        .metric-card .metric-label { font-size: .9rem; font-weight: 600; }
        .metric-card .display-6 { margin-top: .35rem; color: var(--ink); }
        .metric-members::before { background: #258078; }
        .metric-savings::before { background: #d49a37; }
        .metric-loans::before { background: #7180c5; }
        .alert { border: 0; border-radius: .8rem; }
        a:not(.btn):not(.navbar-brand) { color: #176b68; }
        a:not(.btn):not(.navbar-brand):hover { color: #124e55; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ auth()->check() ? route('dashboard') : route('login') }}">SimpanPinjam</a>
        @auth
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ route('dashboard') }}">Ringkasan</a>
                    <a class="nav-link" href="{{ route('anggota.index') }}">Anggota</a>
                    <a class="nav-link" href="{{ route('transaksi.index') }}">Transaksi</a>
                </div>
                <div class="d-flex align-items-center gap-3 text-white">
                    <span class="small">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-light" type="submit">Keluar</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
<main class="container py-4 py-lg-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
