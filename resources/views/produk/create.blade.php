@extends('layouts.app')
@section('title','Tambah Produk')
@section('page-title','Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Form Tambah Produk</div>
      <div class="card-body">
        <form method="POST" action="{{ route('produk.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Kode Produk</label>
              <input type="text" class="form-control bg-light" value="{{ $kodeBaru }}" readonly>
              <small class="text-muted">Kode dibuat otomatis oleh sistem.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
              <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
              </select>
              @error('id_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
              <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                     value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk" required>
              @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
              <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                     value="{{ old('satuan','pcs') }}" placeholder="pcs / kg / liter" required>
              @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
              <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror"
                     value="{{ old('harga_beli',0) }}" min="0" step="100" required>
              @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
              <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
                     value="{{ old('harga_jual',0) }}" min="0" step="100" required>
              @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Stok Awal</label>
              <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                     value="{{ old('stok',0) }}" min="0" required>
              @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Stok Minimum</label>
              <input type="number" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror"
                     value="{{ old('stok_minimum',5) }}" min="0" required>
              @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select" required>
                <option value="aktif" {{ old('status','aktif')=='aktif'?'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Produk</button>
            <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
