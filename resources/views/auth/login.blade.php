<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — POS Toko Pengok Jaya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background:linear-gradient(135deg,#1e1b4b 0%,#4e46e5 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
  .login-card { background:#fff; border-radius:16px; padding:40px 36px; width:100%; max-width:400px; box-shadow:0 20px 60px rgba(0,0,0,.3); }
  .login-logo { width:64px; height:64px; background:linear-gradient(135deg,#4e46e5,#7c3aed); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
  .login-logo i { font-size:32px; color:#fff; }
  .form-control:focus { border-color:#4e46e5; box-shadow:0 0 0 .25rem rgba(78,70,229,.15); }
  .btn-login { background:linear-gradient(135deg,#4e46e5,#7c3aed); border:none; padding:12px; font-weight:600; letter-spacing:.5px; }
  .btn-login:hover { background:linear-gradient(135deg,#4338ca,#6d28d9); }
</style>
</head>
<body>
<div class="login-card">
  <div class="text-center mb-4">
    <div class="login-logo"><i class="bi bi-shop"></i></div>
    <h4 class="fw-bold mb-1" style="color:#1e1b4b">Toko Pengok Jaya</h4>
    <p class="text-muted mb-0" style="font-size:14px">Sistem Informasi Kasir (POS)</p>
  </div>

  @if(session('success'))
  <div class="alert alert-success py-2"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label fw-semibold">Username</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person"></i></span>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username') }}" placeholder="Masukkan username" autofocus>
        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <button type="submit" class="btn btn-login btn-primary w-100 text-white">
      <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
    </button>
  </form>

  <div class="text-center mt-4 text-muted" style="font-size:12px">
    <i class="bi bi-shield-lock me-1"></i>Sistem terlindungi. Hanya pengguna terdaftar yang dapat masuk.
  </div>
</div>
</body>
</html>
