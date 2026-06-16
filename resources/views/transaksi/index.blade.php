@extends('layouts.app')
@section('title','Riwayat Transaksi')
@section('page-title','Riwayat Transaksi')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <span><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</span>
    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus me-1"></i>Transaksi Baru</a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>No Transaksi</th><th>Kasir</th><th>Total</th><th>Bayar</th><th>Kembalian</th><th>Waktu</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($transaksi as $t)
        <tr>
          <td><code>{{ $t->no_transaksi }}</code></td>
          <td>{{ $t->user->nama_lengkap ?? '-' }}</td>
          <td class="fw-bold">Rp {{ number_format($t->total_harga,0,',','.') }}</td>
          <td>Rp {{ number_format($t->jumlah_bayar,0,',','.') }}</td>
          <td class="text-success">Rp {{ number_format($t->kembalian,0,',','.') }}</td>
          <td><small>{{ $t->tanggal_transaksi->format('d/m/Y H:i') }}</small></td>
          <td>
            <a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            <a href="{{ route('transaksi.struk', $t->id_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-4"></i><br>Belum ada transaksi.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($transaksi->hasPages())
  <div class="card-footer">{{ $transaksi->links() }}</div>
  @endif
</div>
@endsection
