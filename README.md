# Sistem Informasi Kasir (POS) — Toko Pengok Jaya

Aplikasi Point of Sale berbasis web untuk toko kelontong, dibangun dengan Laravel 11, MySQL, dan Blade + Bootstrap 5.

## Teknologi

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8.0
- **Frontend:** Blade Templates + Bootstrap 5 (Responsive)
- **Auth:** Session-based authentication (Laravel native)
- **Arsitektur:** MVC (Model–View–Controller)

## Fitur

- Login & Logout (role Admin / Kasir)
- Kelola Data Barang (CRUD + auto kode produk)
- Kelola Kategori Barang
- Kelola Pengguna (Admin only, toggle status aktif/nonaktif)
- Transaksi Penjualan (kasir, keranjang real-time, cetak struk)
- Monitoring Stok (indikator warna aman/menipis/habis)
- Update Stok Manual + Log Stok otomatis
- Kelola Pengeluaran Operasional + Kategori Pengeluaran
- Laporan Penjualan, Pengeluaran, dan Laba Rugi
- Dashboard statistik (penjualan hari ini, grafik 7 hari, produk terlaris, notifikasi stok kritis)

---

## Cara Instalasi (Local — XAMPP / Laragon / Native PHP)

### 1. Persiapan
Pastikan sudah terinstall:
- PHP >= 8.2 (dengan ekstensi: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath)
- Composer
- MySQL / MariaDB
- Node.js (opsional, hanya jika ingin compile asset tambahan — proyek ini sudah memakai CDN Bootstrap sehingga tidak wajib)

### 2. Install dependency PHP
```bash
cd pos-toko-pengok-jaya
composer install
```

### 3. Konfigurasi environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuaikan kredensial database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_toko_pengok_jaya
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat database
```sql
CREATE DATABASE pos_toko_pengok_jaya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan migration + seeder
```bash
php artisan migrate --seed
```

Perintah ini akan membuat seluruh tabel (roles, users, kategori, produk, transaksi, detail_transaksi, kategori_pengeluaran, pengeluaran, stok_log, sessions, cache) dan mengisi data awal (akun Admin/Kasir, contoh kategori & produk).

### 6. Jalankan server
```bash
php artisan serve
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## Akun Default (dari Seeder)

| Role  | Username | Password   |
|-------|----------|------------|
| Admin | admin    | admin123   |
| Kasir | kasir1   | kasir123   |

Ubah password ini setelah login pertama kali melalui menu Kelola Pengguna.

---

## Struktur Folder Penting

```
app/
  Models/              → Eloquent models (User, Role, Produk, Kategori, Transaksi, dll)
  Http/
    Controllers/       → Seluruh controller (Auth, Dashboard, Produk, Transaksi, dll)
    Middleware/        → CheckRole.php (middleware otorisasi role)
database/
  migrations/          → Skema seluruh tabel database
  seeders/             → Data awal (DatabaseSeeder.php)
resources/
  views/
    layouts/app.blade.php   → Layout utama (sidebar + topbar)
    auth/login.blade.php    → Halaman login
    dashboard/admin.blade.php
    kategori/ produk/ users/ transaksi/ stok/ pengeluaran/ laporan/ struk/
routes/
  web.php              → Seluruh route aplikasi
```

---

## Catatan Fitur Export PDF/Excel

Fungsi export pada Laporan saat ini berupa *dummy function* (sesuai permintaan awal) yang menampilkan notifikasi instruksi instalasi. Untuk mengaktifkan export sungguhan:

```bash
composer require barryvdh/laravel-dompdf      # untuk export PDF
composer require maatwebsite/excel            # untuk export Excel
```

Lalu sesuaikan method `exportPdf()` dan `exportExcel()` di `app/Http/Controllers/LaporanController.php`.

---

## Alur Penggunaan Singkat

1. **Login** sebagai Admin → kelola Kategori dan Produk terlebih dahulu.
2. **Login** sebagai Kasir → buka menu Transaksi, cari produk, tambah ke keranjang, input pembayaran, proses transaksi, cetak struk.
3. **Admin** dapat memantau stok di menu Monitoring Stok, mencatat Pengeluaran, dan melihat Laporan Penjualan/Laba Rugi di dashboard.

Selamat menggunakan Sistem Informasi Kasir Toko Pengok Jaya!
