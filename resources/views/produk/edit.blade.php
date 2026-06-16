@extends('layouts.app')
@section('title','Edit Produk')
@section('page-title','Edit Produk')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Edit Produk: {{ $produk->nama_produk }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('produk.update', $produk->id_produk) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Kode Produk</label>
              <input type="text" class="form-control bg-light" value="{{ $produk->kode_produk }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
              <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ (old('id_kategori',$produk->id_kategori)) == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
              </select>
              @error('id_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
              <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                     value="{{ old('nama_produk',$produk->nama_produk) }}" required>
              @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Satuan</label>
              <input type="text" name="satuan" class="form-control" value="{{ old('satuan',$produk->satuan) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Harga Beli (Rp)</label>
              <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli',$produk->harga_beli) }}" min="0" step="100" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Harga Jual (Rp)</label>
              <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual',$produk->harga_jual) }}" min="0" step="100" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Stok Saat Ini</label>
              <input type="number" name="stok" class="form-control" value="{{ old('stok',$produk->stok) }}" min="0" required>
              <small class="text-muted">Perubahan stok akan dicatat ke log.</small>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Stok Minimum</label>
              <input type="number" name="stok_minimum" class="form-control" value="{{ old('stok_minimum',$produk->stok_minimum) }}" min="0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select" required>
                <option value="aktif" {{ old('status',$produk->status)=='aktif'?'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ old('status',$produk->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
