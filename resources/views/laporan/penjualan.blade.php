@extends('layouts.app')
@section('title','Laporan Penjualan')
@section('page-title','Laporan Penjualan')

@section('content')
<div class="card mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tanggal Mulai</label>
        <input type="date" name="tgl_mulai" class="form-control" value="{{ $tglMulai }}">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tanggal Akhir</label>
        <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Tampilkan</button>
        <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn btn-outline-danger" title="Export PDF"><i class="bi bi-file-pdf"></i></a>
        <a href="{{ route('laporan.excel', request()->query()) }}" class="btn btn-outline-success" title="Export Excel"><i class="bi bi-file-excel"></i></a>
      </div>
    </form>
  </div>
</div>

<!-- Ringkasan -->
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card text-center p-3" style="border-left:4px solid #4e46e5">
      <div class="text-muted">Total Pendapatan</div>
      <div class="fw-bold fs-3 text-primary">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card text-center p-3" style="border-left:4px solid #059669">
      <div class="text-muted">Jumlah Transaksi</div>
      <div class="fw-bold fs-3 text-success">{{ $totalTransaksi }}</div>
    </div>
  </div>
</div>

<!-- Produk Terlaris -->
@if($produkTerlaris->count())
<div class="card mb-3">
  <div class="card-header"><i class="bi bi-trophy me-2"></i>Produk Terlaris</div>
  <div class="card-body p-0">
    <table class="table mb-0">
      <thead class="table-light"><tr><th>#</th><th>Produk</th><th>Total Terjual</th><th>Total Omzet</th></tr></thead>
      <tbody>
      @foreach($produkTerlaris as $i => $p)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $p->nama_produk }}</td>
        <td><strong>{{ $p->total_terjual }}</strong></td>
        <td>Rp {{ number_format($p->total_omzet,0,',','.') }}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

<!-- Detail Transaksi -->
<div class="card">
  <div class="card-header"><i class="bi bi-list-ul me-2"></i>Detail Transaksi</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>No Transaksi</th><th>Kasir</th><th>Item</th><th>Total</th><th>Waktu</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($transaksi as $t)
        <tr>
          <td><code>{{ $t->no_transaksi }}</code></td>
          <td>{{ $t->user->nama_lengkap ?? '-' }}</td>
          <td>{{ $t->details->count() }} item</td>
          <td class="fw-bold">Rp {{ number_format($t->total_harga,0,',','.') }}</td>
          <td><small>{{ $t->tanggal_transaksi->format('d/m/Y H:i') }}</small></td>
          <td><a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn btn-xs btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
