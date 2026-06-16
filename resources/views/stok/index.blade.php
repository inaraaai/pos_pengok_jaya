@extends('layouts.app')
@section('title','Monitoring Stok')
@section('page-title','Monitoring Stok')

@section('content')
<!-- Stat badges -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card text-center p-3" style="border-left:4px solid #dc2626">
      <div class="fw-bold text-danger fs-3">{{ $kritis }}</div>
      <div class="text-muted">Stok Habis / Kritis</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center p-3" style="border-left:4px solid #d97706">
      <div class="fw-bold text-warning fs-3">{{ $menipis }}</div>
      <div class="text-muted">Stok Menipis</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center p-3" style="border-left:4px solid #059669">
      <div class="fw-bold text-success fs-3">{{ $aman }}</div>
      <div class="text-muted">Stok Aman</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <form method="GET" class="row g-2 align-items-center">
      <div class="col-md-5">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari produk..." value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="filter" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          <option value="kritis" {{ request('filter')=='kritis'?'selected':'' }}>Habis/Kritis</option>
          <option value="menipis" {{ request('filter')=='menipis'?'selected':'' }}>Menipis</option>
          <option value="aman" {{ request('filter')=='aman'?'selected':'' }}>Aman</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-sm btn-outline-primary w-100" type="submit"><i class="bi bi-filter me-1"></i>Filter</button>
      </div>
      <div class="col-md-2">
        <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
      </div>
    </form>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($produk as $p)
        <tr class="{{ $p->stok == 0 ? 'table-danger' : ($p->stok <= $p->stok_minimum ? 'table-warning' : '') }}">
          <td>
            <strong>{{ $p->nama_produk }}</strong><br>
            <small class="text-muted"><code>{{ $p->kode_produk }}</code></small>
          </td>
          <td><span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span></td>
          <td>
            <strong class="{{ $p->stok == 0 ? 'text-danger' : ($p->stok <= $p->stok_minimum ? 'text-warning' : 'text-success') }}">
              {{ $p->stok }}
            </strong> {{ $p->satuan }}
          </td>
          <td>{{ $p->stok_minimum }} {{ $p->satuan }}</td>
          <td>
            @if($p->stok == 0)
              <span class="badge badge-kritis">Habis</span>
            @elseif($p->stok <= $p->stok_minimum)
              <span class="badge badge-menipis">Kritis</span>
            @else
              <span class="badge badge-aman">Aman</span>
            @endif
          </td>
          <td>
            <button class="btn btn-sm btn-outline-primary"
                    onclick="bukaUpdateStok({{ $p->id_produk }},'{{ addslashes($p->nama_produk) }}',{{ $p->stok }})">
              <i class="bi bi-pencil-square me-1"></i>Update
            </button>
            <a href="{{ route('stok.log', $p->id_produk) }}" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-clock-history"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada data stok.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($produk->hasPages())
  <div class="card-footer">{{ $produk->links() }}</div>
  @endif
</div>

<!-- Modal Update Stok -->
<div class="modal fade" id="modalUpdateStok" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Update Stok Manual</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" id="formUpdateStok">
        @csrf
        <div class="modal-body">
          <p>Produk: <strong id="stokProdukNama"></strong></p>
          <p>Stok saat ini: <strong id="stokSaatIni"></strong></p>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Perubahan</label>
            <select name="jenis_perubahan" id="jenisPerubahan" class="form-select" onchange="updateLabelJumlah()">
              <option value="masuk">Tambah Stok (Masuk)</option>
              <option value="penyesuaian">Sesuaikan Stok (Set ke nilai tertentu)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" id="labelJumlah">Jumlah Tambahan</label>
            <input type="number" name="jumlah" class="form-control" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Alasan perubahan stok">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function bukaUpdateStok(id, nama, stok) {
  document.getElementById('formUpdateStok').action = `/stok/${id}/update`;
  document.getElementById('stokProdukNama').textContent = nama;
  document.getElementById('stokSaatIni').textContent = stok;
  updateLabelJumlah();
  new bootstrap.Modal(document.getElementById('modalUpdateStok')).show();
}

function updateLabelJumlah() {
  const jenis = document.getElementById('jenisPerubahan').value;
  document.getElementById('labelJumlah').textContent = jenis === 'masuk' ? 'Jumlah Tambahan' : 'Sesuaikan Stok Ke';
}
</script>
@endpush
