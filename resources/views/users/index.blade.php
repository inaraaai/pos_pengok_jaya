@extends('layouts.app')
@section('title','Kelola Pengguna')
@section('page-title','Kelola Pengguna')

@section('content')
<div class="row g-3">
  <!-- Form tambah -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-person-plus me-2"></i>Tambah Pengguna</div>
      <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                   value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap">
            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                   value="{{ old('username') }}" placeholder="Username unik">
            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 6 karakter">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="id_role" class="form-select @error('id_role') is-invalid @enderror">
              @foreach($roles as $r)
              <option value="{{ $r->id_role }}" {{ old('id_role') == $r->id_role ? 'selected' : '' }}>{{ $r->nama_role }}</option>
              @endforeach
            </select>
            @error('id_role')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Nonaktif</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Buat Akun</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Tabel user -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-people me-2"></i>Daftar Pengguna ({{ $users->count() }})</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>Nama</th><th>Username</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @foreach($users as $u)
            <tr>
              <td><strong>{{ $u->nama_lengkap }}</strong></td>
              <td><code>{{ $u->username }}</code></td>
              <td>
                <span class="badge {{ $u->role->nama_role === 'Admin' ? 'bg-primary' : 'bg-info text-dark' }}">
                  {{ $u->role->nama_role }}
                </span>
              </td>
              <td>
                <div class="form-check form-switch">
                  <input class="form-check-input toggle-status" type="checkbox" role="switch"
                         data-id="{{ $u->id_user }}"
                         {{ $u->status === 'aktif' ? 'checked' : '' }}
                         {{ $u->id_user === auth()->id() ? 'disabled' : '' }}>
                </div>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary"
                        onclick="editUser({{ $u->id_user }},'{{ addslashes($u->nama_lengkap) }}','{{ $u->username }}',{{ $u->id_role }},'{{ $u->status }}')">
                  <i class="bi bi-pencil"></i>
                </button>
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Pengguna</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" id="formEditUser">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="eNama" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" id="eUsername" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Password baru min 6 karakter">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Role</label>
            <select name="id_role" id="eRole" class="form-select">
              @foreach($roles as $r)
              <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" id="eStatus" class="form-select">
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Nonaktif</option>
            </select>
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
function editUser(id, nama, username, roleId, status) {
  document.getElementById('formEditUser').action = `/users/${id}`;
  document.getElementById('eNama').value = nama;
  document.getElementById('eUsername').value = username;
  document.getElementById('eRole').value = roleId;
  document.getElementById('eStatus').value = status;
  new bootstrap.Modal(document.getElementById('modalEditUser')).show();
}

document.querySelectorAll('.toggle-status').forEach(el => {
  el.addEventListener('change', function() {
    const id = this.dataset.id;
    fetch(`/users/${id}/toggle`, {
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
      if (d.error) { alert(d.error); this.checked = !this.checked; }
    });
  });
});
</script>
@endpush
