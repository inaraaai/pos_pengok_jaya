@extends('layouts.app')
@section('title','Laporan Pengeluaran')
@section('page-title','Laporan Pengeluaran')

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
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card text-center p-3" style="border-left:4px solid #dc2626">
      <div class="text-muted">Total Pengeluaran</div>
      <div class="fw-bold fs-3 text-danger">Rp {{ number_format($total,0,',','.') }}</div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-3">
      <div class="fw-semibold mb-2">Per Kategori</div>
      @foreach($perKategori as $kat => $jml)
      <div class="d-flex justify-content-between mb-1">
        <span style="font-size:13px">{{ $kat }}</span>
        <span class="text-danger fw-semibold" style="font-size:13px">Rp {{ number_format($jml,0,',','.') }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light"><tr><th>Tanggal</th><th>Kategori</th><th>Nominal</th><th>Keterangan</th><th>Dicatat Oleh</th></tr></thead>
      <tbody>
      @forelse($data as $p)
      <tr>
        <td><small>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</small></td>
        <td><span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span></td>
        <td class="fw-bold text-danger">Rp {{ number_format($p->nominal,0,',','.') }}</td>
        <td><small>{{ $p->keterangan ?: '-' }}</small></td>
        <td><small>{{ $p->user->nama_lengkap ?? '-' }}</small></td>
      </tr>
      @empty
      <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pengeluaran.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
