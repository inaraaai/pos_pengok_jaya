@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card bg-indigo d-flex justify-content-between align-items-center">
      <div>
        <div class="label">Penjualan Hari Ini</div>
        <div class="value">Rp {{ number_format($penjualanHariIni,0,',','.') }}</div>
        <div class="label mt-1">{{ $transaksiHariIni }} transaksi</div>
      </div>
      <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card bg-emerald d-flex justify-content-between align-items-center">
      <div>
        <div class="label">Total Produk Aktif</div>
        <div class="value">{{ $totalProduk }}</div>
        <div class="label mt-1">produk terdaftar</div>
      </div>
      <div class="icon"><i class="bi bi-box-seam"></i></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card bg-rose d-flex justify-content-between align-items-center">
      <div>
        <div class="label">Stok Kritis</div>
        <div class="value">{{ $stokKritis }}</div>
        <div class="label mt-1">produk perlu restok</div>
      </div>
      <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card bg-amber-g d-flex justify-content-between align-items-center">
      <div>
        <div class="label">Pengeluaran Hari Ini</div>
        <div class="value">Rp {{ number_format($pengeluaranHariIni,0,',','.') }}</div>
        <div class="label mt-1">total pengeluaran</div>
      </div>
      <div class="icon"><i class="bi bi-wallet2"></i></div>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Grafik 7 hari -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bar-chart me-2"></i>Penjualan 7 Hari Terakhir</span>
        <a href="{{ route('laporan.penjualan') }}" class="btn btn-sm btn-outline-primary">Lihat Laporan</a>
      </div>
      <div class="card-body">
        <canvas id="grafikPenjualan" height="120"></canvas>
      </div>
    </div>
  </div>

  <!-- Produk terlaris -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-trophy me-2"></i>Terlaris Hari Ini</div>
      <div class="card-body p-0">
        @forelse($produkTerlaris as $p)
        <div class="d-flex align-items-center px-3 py-2 border-bottom">
          <div class="me-auto">
            <div style="font-size:13px;font-weight:600">{{ $p->nama_produk }}</div>
          </div>
          <span class="badge bg-primary rounded-pill">{{ $p->total_terjual }} terjual</span>
        </div>
        @empty
        <div class="text-center text-muted py-4" style="font-size:13px"><i class="bi bi-inbox"></i><br>Belum ada transaksi hari ini</div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Stok kritis -->
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-exclamation-triangle text-danger me-2"></i>Produk Stok Kritis</span>
        <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @if($produkStokKritis->isEmpty())
        <div class="text-center text-muted py-4"><i class="bi bi-check-circle text-success fs-4"></i><br>Semua stok dalam kondisi aman.</div>
        @else
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($produkStokKritis as $p)
            <tr>
              <td><strong>{{ $p->nama_produk }}</strong><br><small class="text-muted">{{ $p->kode_produk }}</small></td>
              <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
              <td><strong class="{{ $p->stok == 0 ? 'text-danger' : 'text-warning' }}">{{ $p->stok }}</strong> {{ $p->satuan }}</td>
              <td>{{ $p->stok_minimum }} {{ $p->satuan }}</td>
              <td>
                @if($p->stok == 0)
                  <span class="badge badge-kritis">Habis</span>
                @else
                  <span class="badge badge-menipis">Kritis</span>
                @endif
              </td>
              <td><a href="{{ route('stok.log', $p->id_produk) }}" class="btn btn-sm btn-outline-secondary">Log</a></td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($grafik->pluck('tgl'));
const values = @json($grafik->pluck('total'));

new Chart(document.getElementById('grafikPenjualan'), {
  type: 'bar',
  data: {
    labels: labels.map(d => new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'short'})),
    datasets: [{
      label: 'Total Penjualan (Rp)',
      data: values,
      backgroundColor: 'rgba(78,70,229,.7)',
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }, grid: { color: '#f3f4f6' } },
      x: { grid: { display: false } }
    }
  }
});
</script>
@endpush
