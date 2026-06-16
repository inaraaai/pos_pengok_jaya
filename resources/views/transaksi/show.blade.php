@extends('layouts.app')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-receipt me-2"></i>Detail Transaksi</span>
        <div class="d-flex gap-2">
          <a href="{{ route('transaksi.struk', $trx->id_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer me-1"></i>Cetak Struk</a>
          <a href="{{ route('transaksi.riwayat') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
        </div>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-6"><strong>No Transaksi:</strong><br><code>{{ $trx->no_transaksi }}</code></div>
          <div class="col-6 text-end"><strong>Kasir:</strong><br>{{ $trx->user->nama_lengkap }}</div>
          <div class="col-12 mt-2"><strong>Waktu:</strong> {{ $trx->tanggal_transaksi->format('d M Y, H:i:s') }}</div>
        </div>
        <hr>
        <table class="table">
          <thead class="table-light"><tr><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Harga Satuan</th><th class="text-end">Subtotal</th></tr></thead>
          <tbody>
          @foreach($trx->details as $d)
          <tr>
            <td>{{ $d->produk->nama_produk ?? 'Produk Dihapus' }}</td>
            <td class="text-center">{{ $d->jumlah }}</td>
            <td class="text-end">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
            <td class="text-end fw-bold">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
          </tr>
          @endforeach
          </tbody>
          <tfoot class="table-light">
            <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold text-primary">Rp {{ number_format($trx->total_harga,0,',','.') }}</td></tr>
            <tr><td colspan="3" class="text-end">Dibayar</td><td class="text-end">Rp {{ number_format($trx->jumlah_bayar,0,',','.') }}</td></tr>
            <tr><td colspan="3" class="text-end">Kembalian</td><td class="text-end text-success fw-bold">Rp {{ number_format($trx->kembalian,0,',','.') }}</td></tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
