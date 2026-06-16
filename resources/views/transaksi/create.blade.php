@extends('layouts.app')
@section('title','Kasir - Transaksi Penjualan')
@section('page-title','Transaksi Penjualan')

@push('styles')
<style>
.pos-layout { display:grid; grid-template-columns:1fr 380px; gap:16px; }
.pos-produk { min-height:calc(100vh - 180px); }
.keranjang-card { position:sticky; top:80px; }
.search-produk { border-radius:8px; padding:10px 14px; font-size:15px; }
.item-row { display:flex; align-items:center; gap:8px; padding:10px 0; border-bottom:1px solid #f3f4f6; }
.item-row:last-child { border-bottom:none; }
.qty-btn { width:28px; height:28px; border-radius:6px; border:1px solid #d1d5db; background:#f9fafb; cursor:pointer; font-size:14px; font-weight:700; }
.qty-input { width:50px; text-align:center; border:1px solid #d1d5db; border-radius:6px; padding:3px; font-size:14px; }
.total-box { background:#f0f9ff; border-radius:10px; padding:16px; }
.kembalian-box { background:#f0fdf4; border-radius:10px; padding:12px; }
@media(max-width:900px) { .pos-layout { grid-template-columns:1fr; } .keranjang-card { position:static; } }
</style>
@endpush

@section('content')
<div class="pos-layout">
  <!-- Kiri: Pencarian Produk -->
  <div class="pos-produk">
    <div class="card">
      <div class="card-header"><i class="bi bi-search me-2"></i>Cari Produk</div>
      <div class="card-body">
        <div class="position-relative">
          <input type="text" id="searchProduk" class="form-control search-produk"
                 placeholder="Ketik nama atau kode produk..." autocomplete="off">
          <div id="hasilCari" class="position-absolute w-100 bg-white border rounded-3 shadow mt-1 z-3" style="display:none;max-height:300px;overflow-y:auto;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Kanan: Keranjang -->
  <div class="keranjang-card">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-cart3 me-2"></i>Keranjang</span>
        <button class="btn btn-sm btn-outline-danger" onclick="kosongkanKeranjang()"><i class="bi bi-trash"></i> Kosongkan</button>
      </div>
      <div class="card-body p-3">
        <!-- Item list -->
        <div id="keranjangList" style="min-height:120px;max-height:320px;overflow-y:auto;">
          <div id="keranjangKosong" class="text-center text-muted py-4">
            <i class="bi bi-cart-x fs-4"></i><br>Belum ada item
          </div>
        </div>

        <div class="total-box mt-3">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Total Item</span>
            <strong id="totalItem">0</strong>
          </div>
          <div class="d-flex justify-content-between">
            <span class="fw-bold fs-6">Total Harga</span>
            <strong class="fs-5 text-primary" id="totalHarga">Rp 0</strong>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label fw-semibold">Jumlah Bayar (Rp)</label>
          <input type="number" id="jumlahBayar" class="form-control form-control-lg" placeholder="0" min="0" step="1000" oninput="hitungKembalian()">
        </div>

        <div id="kembalianBox" class="kembalian-box mt-2" style="display:none;">
          <div class="d-flex justify-content-between">
            <span>Kembalian</span>
            <strong class="text-success" id="kembalianText">Rp 0</strong>
          </div>
        </div>

        <!-- Quick cash buttons -->
        <div class="d-flex gap-1 flex-wrap mt-2" id="quickCash" style="display:none!important;"></div>

        <div class="mt-3 d-grid gap-2">
          <button class="btn btn-success btn-lg fw-bold" onclick="prosesTransaksi()" id="btnProses" disabled>
            <i class="bi bi-check-circle me-2"></i>Proses Transaksi
          </button>
          <a href="{{ route('transaksi.riwayat') }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i>Riwayat Transaksi
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sukses -->
<div class="modal fade" id="modalSukses" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="mb-3" style="font-size:60px">✅</div>
        <h4 class="fw-bold text-success">Transaksi Berhasil!</h4>
        <p class="text-muted mb-1">No: <strong id="noTrxSukses"></strong></p>
        <div class="d-flex justify-content-between bg-light rounded p-3 mb-3">
          <span>Total</span><strong id="totalSukses"></strong>
        </div>
        <div class="d-flex justify-content-between bg-success bg-opacity-10 rounded p-3 mb-4">
          <span>Kembalian</span><strong class="text-success" id="kembalianSukses"></strong>
        </div>
        <div class="d-flex gap-2 justify-content-center">
          <a id="btnStruk" href="#" target="_blank" class="btn btn-outline-primary"><i class="bi bi-printer me-1"></i>Cetak Struk</a>
          <button class="btn btn-success" onclick="transaksiSelanjutnya()"><i class="bi bi-plus-circle me-1"></i>Transaksi Baru</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const keranjang = [];
let totalHarga  = 0;

// Cari Produk
let searchTimeout;
document.getElementById('searchProduk').addEventListener('input', function() {
  clearTimeout(searchTimeout);
  const q = this.value.trim();
  if (q.length < 2) { document.getElementById('hasilCari').style.display='none'; return; }
  searchTimeout = setTimeout(() => {
    fetch('/kasir/cari-produk', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    q: q
  })
})
.then(r => r.json())
    .then(data => {
      const box = document.getElementById('hasilCari');
      if (!data.length) { box.innerHTML='<div class="p-3 text-muted text-center">Produk tidak ditemukan.</div>'; box.style.display='block'; return; }
      box.innerHTML = data.map(p => `
        <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom hasil-item"
             style="cursor:pointer" onclick="tambahKeKeranjang(${p.id_produk},'${p.nama_produk.replace(/'/g,"\\'")}',${p.harga_jual},${p.stok},'${p.satuan}')">
          <div>
            <div class="fw-semibold" style="font-size:14px">${p.nama_produk}</div>
            <div style="font-size:12px;color:#6b7280">${p.kode_produk} &bull; ${p.satuan}</div>
          </div>
          <div class="text-end">
            <div class="fw-bold text-primary">Rp ${p.harga_jual.toLocaleString('id-ID')}</div>
            <div style="font-size:12px;color:${p.stok===0?'#dc2626':'#059669'}">${p.stok} stok</div>
          </div>
        </div>`).join('');
      box.style.display='block';
    });
  }, 300);
});

document.addEventListener('click', e => {
  if (!e.target.closest('#hasilCari') && !e.target.closest('#searchProduk')) {
    document.getElementById('hasilCari').style.display='none';
  }
});

function tambahKeKeranjang(id, nama, harga, stok, satuan) {
  document.getElementById('searchProduk').value = '';
  document.getElementById('hasilCari').style.display='none';

  const idx = keranjang.findIndex(i => i.id === id);
  if (idx > -1) {
    if (keranjang[idx].qty >= stok) { alert(`Stok ${nama} tidak cukup (tersedia: ${stok} ${satuan})`); return; }
    keranjang[idx].qty++;
  } else {
    if (stok === 0) { alert(`Stok ${nama} habis!`); return; }
    keranjang.push({ id, nama, harga, stok, satuan, qty: 1 });
  }
  renderKeranjang();
}

function renderKeranjang() {
  const list = document.getElementById('keranjangList');
  const kosong = document.getElementById('keranjangKosong');
  const btnProses = document.getElementById('btnProses');

  if (!keranjang.length) {
    list.innerHTML = '';
    list.appendChild(document.getElementById('keranjangKosong') || (() => { const d=document.createElement('div'); d.id='keranjangKosong'; d.className='text-center text-muted py-4'; d.innerHTML='<i class="bi bi-cart-x fs-4"></i><br>Belum ada item'; return d; })());
    document.getElementById('totalHarga').textContent = 'Rp 0';
    document.getElementById('totalItem').textContent = '0';
    document.getElementById('kembalianBox').style.display='none';
    btnProses.disabled = true;
    totalHarga = 0;
    return;
  }

  totalHarga = keranjang.reduce((s, i) => s + i.harga * i.qty, 0);
  const totalQty = keranjang.reduce((s, i) => s + i.qty, 0);

  list.innerHTML = keranjang.map((item, idx) => `
    <div class="item-row">
      <div class="flex-grow-1">
        <div style="font-size:13px;font-weight:600">${item.nama}</div>
        <div style="font-size:12px;color:#6b7280">Rp ${item.harga.toLocaleString('id-ID')} / ${item.satuan}</div>
        <div style="font-size:12px;font-weight:700;color:#4e46e5">Rp ${(item.harga*item.qty).toLocaleString('id-ID')}</div>
      </div>
      <div class="d-flex align-items-center gap-1">
        <button class="qty-btn" onclick="ubahQty(${idx},-1)">−</button>
        <input type="number" class="qty-input" value="${item.qty}" min="1" max="${item.stok}"
               onchange="setQty(${idx}, this.value)">
        <button class="qty-btn" onclick="ubahQty(${idx},1)">+</button>
        <button class="qty-btn text-danger border-0 bg-transparent" onclick="hapusItem(${idx})"><i class="bi bi-x"></i></button>
      </div>
    </div>`).join('');

  document.getElementById('totalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
  document.getElementById('totalItem').textContent = totalQty;
  btnProses.disabled = false;
  hitungKembalian();
}

function ubahQty(idx, delta) {
  keranjang[idx].qty = Math.max(1, Math.min(keranjang[idx].stok, keranjang[idx].qty + delta));
  renderKeranjang();
}

function setQty(idx, val) {
  val = parseInt(val) || 1;
  keranjang[idx].qty = Math.max(1, Math.min(keranjang[idx].stok, val));
  renderKeranjang();
}

function hapusItem(idx) {
  keranjang.splice(idx, 1);
  renderKeranjang();
}

function kosongkanKeranjang() {
  if (!keranjang.length || confirm('Kosongkan keranjang?')) {
    keranjang.length = 0;
    renderKeranjang();
    document.getElementById('jumlahBayar').value = '';
  }
}

function hitungKembalian() {
  const bayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
  const box   = document.getElementById('kembalianBox');
  if (bayar > 0 && totalHarga > 0) {
    const kem = bayar - totalHarga;
    document.getElementById('kembalianText').textContent = (kem >= 0 ? '' : '-') + 'Rp ' + Math.abs(kem).toLocaleString('id-ID');
    document.getElementById('kembalianText').className = kem >= 0 ? 'fw-bold text-success' : 'fw-bold text-danger';
    box.style.display='block';
  } else { box.style.display='none'; }
}

function prosesTransaksi() {
  if (!keranjang.length) return;
  const bayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
  if (bayar < totalHarga) { alert('Jumlah bayar kurang dari total harga!'); document.getElementById('jumlahBayar').focus(); return; }

  document.getElementById('btnProses').disabled = true;
  document.getElementById('btnProses').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

  fetch('/kasir/proses', {
    method: 'POST',
    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' },
    body: JSON.stringify({ items: keranjang.map(i=>({id:i.id, qty:i.qty})), jumlah_bayar: bayar })
  })
  .then(r => r.json())
  .then(data => {
    document.getElementById('btnProses').disabled = false;
    document.getElementById('btnProses').innerHTML = '<i class="bi bi-check-circle me-2"></i>Proses Transaksi';

    if (data.error) { alert(data.error); return; }

    document.getElementById('noTrxSukses').textContent = data.no_transaksi;
    document.getElementById('totalSukses').textContent  = 'Rp ' + data.total.toLocaleString('id-ID');
    document.getElementById('kembalianSukses').textContent = 'Rp ' + data.kembalian.toLocaleString('id-ID');
    document.getElementById('btnStruk').href = data.struk_url;

    new bootstrap.Modal(document.getElementById('modalSukses')).show();
  })
  .catch(() => {
    document.getElementById('btnProses').disabled = false;
    document.getElementById('btnProses').innerHTML = '<i class="bi bi-check-circle me-2"></i>Proses Transaksi';
    alert('Terjadi kesalahan koneksi. Coba lagi.');
  });
}

function transaksiSelanjutnya() {
  bootstrap.Modal.getInstance(document.getElementById('modalSukses')).hide();
  keranjang.length = 0;
  renderKeranjang();
  document.getElementById('jumlahBayar').value = '';
}
</script>
@endpush
