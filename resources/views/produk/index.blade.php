@extends('layouts.app')
@section('title','Data Produk')
@section('page-title','Data Produk')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <span><i class="bi bi-box-seam me-2"></i>Daftar Produk</span>
    <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle me-1"></i>Tambah Produk
    </a>
  </div>
  <div class="card-body border-bottom">
    <form method="GET" class="row g-2">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / kode produk..." value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="kategori" class="form-select form-select-sm">
          <option value="">Semua Kategori</option>
          @foreach($kategori as $k)
          <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
          <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
      </div>
      <div class="col-md-1">
        <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th>Harga Jual</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($produk as $p)
        <tr>
          <td><code>{{ $p->kode_produk }}</code></td>
          <td>
            <strong>{{ $p->nama_produk }}</strong><br>
            <small class="text-muted">{{ $p->satuan }}</small>
          </td>
          <td><span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span></td>
          <td>Rp {{ number_format($p->harga_jual,0,',','.') }}</td>
          <td>
            <span class="fw-bold {{ $p->stok == 0 ? 'text-danger' : ($p->stok <= $p->stok_minimum ? 'text-warning' : 'text-success') }}">
              {{ $p->stok }}
            </span>
            <small class="text-muted">/ min {{ $p->stok_minimum }}</small>
          </td>
          <td>
            @if($p->status === 'aktif')
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif</span>
            @endif
          </td>
          <td>
            <a href="{{ route('produk.edit', $p->id_produk) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('produk.destroy', $p->id_produk) }}" class="d-inline"
                  onsubmit="return confirm('Hapus produk {{ addslashes($p->nama_produk) }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-4"></i><br>Tidak ada produk ditemukan.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($produk->hasPages())
  <div class="card-footer">{{ $produk->links() }}</div>
  @endif
</div>
@endsection
