<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController,
    KategoriController, ProdukController,
    UserController, TransaksiController,
    StokController, PengeluaranController,
    LaporanController
};

// ── Auth ─────────────────────────────────────────────────────
Route::get('/',      fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',[AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Authenticated ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:Admin')
        ->name('dashboard.admin');

    // ── Admin only ────────────────────────────────────────────
    Route::middleware(['role:Admin'])->group(function () {

        // Kategori Barang
        Route::get('/kategori',          [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori',         [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}',     [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}',  [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Produk
        Route::get('/produk',            [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/create',     [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk',           [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{id}/edit',  [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{id}',       [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{id}',    [ProdukController::class, 'destroy'])->name('produk.destroy');

        // User Management
        Route::get('/users',             [UserController::class, 'index'])->name('users.index');
        Route::post('/users',            [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}',        [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{id}/toggle',[UserController::class,'toggleStatus'])->name('users.toggle');

        // Monitoring Stok
        Route::get('/stok',              [StokController::class, 'index'])->name('stok.index');
        Route::get('/stok/{id}/log',     [StokController::class, 'log'])->name('stok.log');
        Route::post('/stok/{id}/update', [StokController::class, 'updateManual'])->name('stok.update');

        // Pengeluaran
        Route::get('/pengeluaran',           [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('/pengeluaran',          [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::put('/pengeluaran/{id}',      [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{id}',   [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
        Route::get('/pengeluaran/kategori',  [PengeluaranController::class, 'kategoriIndex'])->name('pengeluaran.kategori');
        Route::post('/pengeluaran/kategori', [PengeluaranController::class, 'kategoriStore'])->name('pengeluaran.kategori.store');
        Route::delete('/pengeluaran/kategori/{id}', [PengeluaranController::class, 'kategoriDestroy'])->name('pengeluaran.kategori.destroy');

        // Laporan
        Route::get('/laporan/penjualan',   [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/pengeluaran', [LaporanController::class, 'pengeluaran'])->name('laporan.pengeluaran');
        Route::get('/laporan/labarugi',    [LaporanController::class, 'labaRugi'])->name('laporan.labarugi');
        Route::get('/laporan/export-pdf',  [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/export-excel',[LaporanController::class, 'exportExcel'])->name('laporan.excel');
    });

        // Riwayat transaksi (admin & kasir)
        Route::get('/transaksi',           [TransaksiController::class, 'index'])->name('transaksi.riwayat');
        Route::get('/transaksi/{id}',      [TransaksiController::class, 'show'])->name('transaksi.show');

    // ── Kasir & Admin: Transaksi ──────────────────────────────
    Route::get('/kasir',               [TransaksiController::class, 'create'])->name('transaksi.index');
    Route::post('/kasir/cari-produk',  [TransaksiController::class, 'cariProduk'])->name('transaksi.cari');
    Route::post('/kasir/proses',       [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/kasir/struk/{id}',    [TransaksiController::class, 'struk'])->name('transaksi.struk');
});
