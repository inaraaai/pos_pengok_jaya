@extends('layouts.app')
@section('title','Log Stok')
@section('page-title','Riwayat Stok')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <i class="bi bi-clock-history me-2"></i>
      Log Stok: <strong>{{ $produk->nama_produk }}</strong>
      <span class="badge bg-secondary ms-2">{{ $produk->kode_produk }}</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span>Stok saat ini: <strong class="{{ $produk->stok == 0 ? 'text-danger' : ($produk->stok <= $produk->stok_minimum ? 'text-warning' : 'text-success') }}">{{ $produk->stok }} {{ $produk->satuan }}</strong></span>
      <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Waktu</th><th>Jenis</th><th>Perubahan</th><th>Sebelum</th><th>Sesudah</th><th>Oleh</th><th>Keterangan</th></tr>
        </thead>
        <tbody>
        @forelse($log as $l)
        <tr>
          <td><small>{{ $l->created_at->format('d/m/Y H:i') }}</small></td>
          <td>
            @if($l->jenis_perubahan === 'masuk')
              <span class="badge bg-success">Masuk</span>
            @elseif($l->jenis_perubahan === 'keluar')
              <span class="badge bg-danger">Keluar</span>
            @else
              <span class="badge bg-warning text-dark">Penyesuaian</span>
            @endif
          </td>
          <td>
            <span class="{{ $l->jenis_perubahan === 'keluar' ? 'text-danger' : 'text-success' }} fw-bold">
              {{ $l->jenis_perubahan === 'keluar' ? '-' : '+' }}{{ $l->jumlah_perubahan }}
            </span>
          </td>
          <td>{{ $l->stok_sebelum }}</td>
          <td><strong>{{ $l->stok_sesudah }}</strong></td>
          <td><small>{{ $l->user->nama_lengkap ?? '-' }}</small></td>
          <td><small class="text-muted">{{ $l->keterangan ?: '-' }}</small></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat perubahan stok.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($log->hasPages())
  <div class="card-footer">{{ $log->links() }}</div>
  @endif
</div>
@endsection
