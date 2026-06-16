<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Struk - {{ $trx->no_transaksi }}</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Courier New',monospace; font-size:12px; width:300px; margin:0 auto; padding:10px; }
  .center { text-align:center; }
  .bold { font-weight:bold; }
  .line { border-top:1px dashed #000; margin:6px 0; }
  .row { display:flex; justify-content:space-between; margin:2px 0; }
  .toko-name { font-size:16px; font-weight:bold; }
  @media print { body { width:80mm; } .no-print { display:none; } }
</style>
</head>
<body>
<div class="center">
  <div class="toko-name">TOKO PENGOK JAYA</div>
  <div>Sistem Informasi Kasir (POS)</div>
  <div style="font-size:10px">{{ now()->format('d/m/Y H:i:s') }}</div>
</div>

<div class="line"></div>

<div class="row"><span>No Trx</span><span>{{ $trx->no_transaksi }}</span></div>
<div class="row"><span>Kasir</span><span>{{ $trx->user->nama_lengkap }}</span></div>

<div class="line"></div>

@foreach($trx->details as $d)
<div class="bold" style="font-size:11px">{{ $d->produk->nama_produk ?? '-' }}</div>
<div class="row">
  <span>{{ $d->jumlah }} x Rp {{ number_format($d->harga_satuan,0,',','.') }}</span>
  <span>Rp {{ number_format($d->subtotal,0,',','.') }}</span>
</div>
@endforeach

<div class="line"></div>

<div class="row bold"><span>TOTAL</span><span>Rp {{ number_format($trx->total_harga,0,',','.') }}</span></div>
<div class="row"><span>BAYAR</span><span>Rp {{ number_format($trx->jumlah_bayar,0,',','.') }}</span></div>
<div class="row bold"><span>KEMBALI</span><span>Rp {{ number_format($trx->kembalian,0,',','.') }}</span></div>

<div class="line"></div>
<div class="center" style="font-size:11px">
  <div>Terima kasih telah berbelanja!</div>
  <div>Barang yang sudah dibeli</div>
  <div>tidak dapat dikembalikan.</div>
</div>

<div class="no-print" style="text-align:center;margin-top:20px">
  <button onclick="window.print()" style="padding:8px 20px;background:#4e46e5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px">
    🖨️ Cetak Struk
  </button>
  <button onclick="window.close()" style="padding:8px 20px;background:#6b7280;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px">
    ✕ Tutup
  </button>
</div>

<script>
// Auto print on load
window.onload = () => setTimeout(() => window.print(), 500);
</script>
</body>
</html>
