<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'POS') — Toko Pengok Jaya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  :root { --pos-primary:#4e46e5; --pos-sidebar:#1e1b4b; --pos-sidebar-active:#4e46e5; }
  body { background:#f5f5f5; font-family:'Segoe UI',sans-serif; }
  .sidebar {
    width:240px;
    height:100vh;
    background:var(--pos-sidebar);
    position:fixed;
    top:0;
    left:0;
    z-index:1000;
    overflow-y:scroll;
    overflow-x:hidden;
}
  .sidebar .brand { padding:20px 16px 12px; border-bottom:1px solid #3730a3; }
  .sidebar .brand h6 { color:#c7d2fe; font-size:11px; text-transform:uppercase; letter-spacing:1px; margin:0; }
  .sidebar .brand h5 { color:#fff; font-weight:700; margin:2px 0 0; }
  .sidebar .nav-link { color:#a5b4fc; padding:9px 16px; border-radius:6px; margin:1px 8px; font-size:14px; display:flex; align-items:center; gap:10px; }
  .sidebar .nav-link:hover, .sidebar .nav-link.active { background:var(--pos-sidebar-active); color:#fff; }
  .sidebar .nav-section { color:#6366f1; font-size:10px; text-transform:uppercase; letter-spacing:1px; padding:14px 16px 4px; }
  .sidebar .nav-link i { font-size:16px; width:20px; }
  .main-content { margin-left:240px; }
  .topbar { background:#fff; border-bottom:1px solid #e5e7eb; padding:12px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; }
  .topbar h5 { margin:0; font-weight:600; color:#1e1b4b; }
  .content-area { padding:24px; }
  .card { border:none; box-shadow:0 1px 3px rgba(0,0,0,.1); border-radius:10px; }
  .card-header { background:#fff; border-bottom:1px solid #f3f4f6; padding:14px 20px; font-weight:600; color:#374151; }
  .stat-card { border-radius:12px; padding:20px; color:#fff; }
  .stat-card .label { font-size:12px; opacity:.85; }
  .stat-card .value { font-size:28px; font-weight:700; }
  .stat-card .icon { font-size:36px; opacity:.3; }
  .bg-indigo { background:linear-gradient(135deg,#4e46e5,#7c3aed); }
  .bg-emerald { background:linear-gradient(135deg,#059669,#10b981); }
  .bg-amber-g { background:linear-gradient(135deg,#d97706,#f59e0b); }
  .bg-rose { background:linear-gradient(135deg,#dc2626,#f43f5e); }
  .badge-aman    { background:#d1fae5; color:#065f46; }
  .badge-menipis { background:#fef3c7; color:#92400e; }
  .badge-kritis  { background:#fee2e2; color:#991b1b; }
  .badge-habis   { background:#f3f4f6; color:#6b7280; }
  .table th { font-size:12px; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; font-weight:600; }
  .btn-primary { background:var(--pos-primary); border-color:var(--pos-primary); }
  .btn-primary:hover { background:#4338ca; border-color:#4338ca; }
  @media(max-width:768px) { .sidebar{display:none;} .main-content{margin-left:0;} }
</style>
@stack('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="brand">
    <h6>Point of Sale</h6>
    <h5><i class="bi bi-shop"></i> Pengok Jaya</h5>
  </div>
  <nav class="py-2">
    @if(auth()->user()->isAdmin())
    <div class="nav-section">Utama</div>
    <a href="{{ route('dashboard.admin') }}" class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
      <i class="bi bi-grid"></i> Dashboard
    </a>

    <div class="nav-section">Katalog</div>
    <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
      <i class="bi bi-tag"></i> Kategori
    </a>
    <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
      <i class="bi bi-box-seam"></i> Produk
    </a>

    <div class="nav-section">Operasional</div>
    <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
      <i class="bi bi-receipt"></i> Kasir / Transaksi
    </a>
    <a href="{{ route('transaksi.riwayat') }}" class="nav-link {{ request()->routeIs('transaksi.riwayat') ? 'active' : '' }}">
      <i class="bi bi-clock-history"></i> Riwayat Transaksi
    </a>
    <a href="{{ route('stok.index') }}" class="nav-link {{ request()->routeIs('stok.*') ? 'active' : '' }}">
      <i class="bi bi-bar-chart"></i> Monitoring Stok
    </a>
    <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
      <i class="bi bi-wallet2"></i> Pengeluaran
    </a>

    <div class="nav-section">Laporan</div>
    <a href="{{ route('laporan.penjualan') }}" class="nav-link {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
      <i class="bi bi-graph-up"></i> Laporan Penjualan
    </a>
    <a href="{{ route('laporan.pengeluaran') }}" class="nav-link {{ request()->routeIs('laporan.pengeluaran') ? 'active' : '' }}">
      <i class="bi bi-journal-minus"></i> Laporan Pengeluaran
    </a>
    <a href="{{ route('laporan.labarugi') }}" class="nav-link {{ request()->routeIs('laporan.labarugi') ? 'active' : '' }}">
      <i class="bi bi-calculator"></i> Laba Rugi
    </a>

    <div class="nav-section">Pengaturan</div>
    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Kelola Pengguna
    </a>
    @else
    <div class="nav-section">Kasir</div>
    <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
      <i class="bi bi-receipt"></i> Transaksi
    </a>
    @endif

    <div class="nav-section">Akun</div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-link border-0 w-100 text-start" style="background:none;">
        <i class="bi bi-box-arrow-left"></i> Logout
      </button>
    </form>
  </nav>
</div>

<!-- Main -->
<div class="main-content">
  <div class="topbar">
    <h5>@yield('page-title', 'Dashboard')</h5>

    <div class="d-flex align-items-center gap-3">

      @php $kritis = \App\Models\Produk::where('status','aktif')->whereRaw('stok <= stok_minimum')->count(); @endphp

      @if($kritis > 0)
      <a href="{{ route('stok.index') }}" class="btn btn-sm btn-warning">
        <i class="bi bi-exclamation-triangle"></i> {{ $kritis }} stok kritis
      </a>
      @endif

      <div class="text-end">
        <div class="fw-semibold" style="font-size:14px">{{ auth()->user()->nama_lengkap }}</div>
        <div class="text-muted" style="font-size:11px">{{ auth()->user()->role->nama_role }}</div>
      </div>
    </div>
  </div>

  <div class="content-area">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-info-circle me-2"></i>{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
