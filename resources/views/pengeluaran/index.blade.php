@extends('layouts.app')
@section('title','Kelola Pengeluaran')
@section('page-title','Kelola Pengeluaran')

@section('content')
<div class="row g-3">
  <!-- Form tambah -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Catat Pengeluaran</div>
      <div class="card-body">
        <form method="POST" action="{{ route('pengeluaran.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select name="id_kategori_pengeluaran" class="form-select @error('id_kategori_pengeluaran') is-invalid @enderror" required>
              <option value="">Pilih Kategori</option>
              @foreach($kategori as $k)
              <option value="{{ $k->id_kategori_pengeluaran }}" {{ old('id_kategori_pengeluaran') == $k->id_kategori_pengeluaran ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
              @endforeach
            </select>
            @error('id_kategori_pengeluaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror"
                   value="{{ old('nominal') }}" placeholder="0" min="1" required>
            @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional">{{ old('keterangan') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-body text-center">
        <div class="text-muted" style="font-size:12px">Total Periode Ini</div>
        <div class="fw-bold text-danger fs-4">Rp {{ number_format($total,0,',','.') }}</div>
        <a href="{{ route('pengeluaran.kategori') }}" class="btn btn-sm btn-outline-secondary mt-2">
          <i class="bi bi-tags me-1"></i>Kelola Kategori
        </a>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <form method="GET" class="d-flex gap-2 flex-wrap">
          <select name="bulan" class="form-select form-select-sm" style="width:auto">
            @for($m=1;$m<=12;$m++)
            <option value="{{ $m }}" {{ request('bulan',$currentMonth??date('n')) == $m ? 'selected' : '' }}>
              {{ date('F', mktime(0,0,0,$m,1)) }}
            </option>
            @endfor
          </select>
          <select name="tahun" class="form-select form-select-sm" style="width:auto">
            @for($y=date('Y');$y>=date('Y')-3;$y--)
            <option value="{{ $y }}" {{ request('tahun',date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
          <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-filter"></i></button>
          <a href="{{ route('pengeluaran.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </form>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>Tanggal</th><th>Kategori</th><th>Nominal</th><th>Keterangan</th><th>Oleh</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($data as $p)
            <tr>
              <td><small>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</small></td>
              <td><span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span></td>
              <td class="fw-bold text-danger">Rp {{ number_format($p->nominal,0,',','.') }}</td>
              <td><small class="text-muted">{{ $p->keterangan ?: '-' }}</small></td>
              <td><small>{{ $p->user->nama_lengkap ?? '-' }}</small></td>
              <td>
                <button class="btn btn-sm btn-outline-primary"
                        onclick="editPengeluaran({{ $p->id_pengeluaran }},{{ $p->id_kategori_pengeluaran }},'{{ $p->nominal }}','{{ $p->tanggal }}','{{ addslashes($p->keterangan) }}')">
                  <i class="bi bi-pencil"></i>
                </button>
                <form method="POST" action="{{ route('pengeluaran.destroy', $p->id_pengeluaran) }}" class="d-inline"
                      onsubmit="return confirm('Hapus pengeluaran ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pengeluaran pada periode ini.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($data->hasPages())
      <div class="card-footer">{{ $data->links() }}</div>
      @endif
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditPng" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Pengeluaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" id="formEditPng">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="id_kategori_pengeluaran" id="eKatPng" class="form-select">
              @foreach($kategori as $k)
              <option value="{{ $k->id_kategori_pengeluaran }}">{{ $k->nama_kategori }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Nominal (Rp)</label>
            <input type="number" name="nominal" id="eNominal" class="form-control" min="1" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Tanggal</label>
            <input type="date" name="tanggal" id="eTanggal" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Keterangan</label>
            <textarea name="keterangan" id="eKetPng" class="form-control" rows="2"></textarea>
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
function editPengeluaran(id, katId, nominal, tanggal, ket) {
  document.getElementById('formEditPng').action = `/pengeluaran/${id}`;
  document.getElementById('eKatPng').value = katId;
  document.getElementById('eNominal').value = nominal;
  document.getElementById('eTanggal').value = tanggal;
  document.getElementById('eKetPng').value = ket;
  new bootstrap.Modal(document.getElementById('modalEditPng')).show();
}
</script>
@endpush
