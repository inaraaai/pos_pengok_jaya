@extends('layouts.app')
@section('title','Kategori Barang')
@section('page-title','Kategori Barang')

@section('content')
<div class="row g-3">
  <!-- Form Tambah -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</div>
      <div class="card-body">
        <form method="POST" action="{{ route('kategori.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror"
                   value="{{ old('nama_kategori') }}" placeholder="Contoh: Makanan">
            @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Opsional">{{ old('deskripsi') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan Kategori</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-list-ul me-2"></i>Daftar Kategori ({{ $data->count() }})</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Produk</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($data as $i => $k)
            <tr>
              <td class="text-muted">{{ $i+1 }}</td>
              <td><strong>{{ $k->nama_kategori }}</strong></td>
              <td><small class="text-muted">{{ $k->deskripsi ?: '-' }}</small></td>
              <td><span class="badge bg-secondary">{{ $k->produk_count }} produk</span></td>
              <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editKategori({{ $k->id_kategori }},'{{ addslashes($k->nama_kategori) }}','{{ addslashes($k->deskripsi) }}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <form method="POST" action="{{ route('kategori.destroy', $k->id_kategori) }}" class="d-inline"
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
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" id="formEdit">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="editNama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function editKategori(id, nama, deskripsi) {
  document.getElementById('formEdit').action = `/kategori/${id}`;
  document.getElementById('editNama').value = nama;
  document.getElementById('editDeskripsi').value = deskripsi;
  new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
