@extends('layouts.app')
@section('title','Kategori Pengeluaran')
@section('page-title','Kategori Pengeluaran')

@section('content')
<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</div>
      <div class="card-body">
        <form method="POST" action="{{ route('pengeluaran.kategori.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror"
                   value="{{ old('nama_kategori') }}" placeholder="Contoh: Operasional">
            @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
      </div>
    </div>
    <div class="mt-3">
      <a href="{{ route('pengeluaran.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-left me-1"></i>Kembali ke Pengeluaran</a>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Daftar Kategori Pengeluaran</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="table-light"><tr><th>#</th><th>Nama</th><th>Deskripsi</th><th>Jumlah</th><th>Aksi</th></tr></thead>
          <tbody>
          @forelse($data as $i => $k)
          <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $k->nama_kategori }}</strong></td>
            <td><small class="text-muted">{{ $k->deskripsi ?: '-' }}</small></td>
            <td><span class="badge bg-secondary">{{ $k->pengeluaran_count }}</span></td>
            <td>
              <form method="POST" action="{{ route('pengeluaran.kategori.destroy', $k->id_kategori_pengeluaran) }}" class="d-inline"
                    onsubmit="return confirm('Hapus kategori ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
