<?php

namespace App\Http\Controllers;

use App\Models\{Transaksi, Produk, Pengeluaran, User};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $today = today();

        $penjualanHariIni = Transaksi::whereDate('tanggal_transaksi', $today)->sum('total_harga');
        $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', $today)->count();
        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal', $today)->sum('nominal');

        $totalProduk     = Produk::where('status', 'aktif')->count();
        $stokKritis      = Produk::where('status', 'aktif')->whereRaw('stok <= stok_minimum')->count();
        $totalUser       = User::count();

        // Penjualan 7 hari terakhir
        $grafik = Transaksi::selectRaw('DATE(tanggal_transaksi) as tgl, SUM(total_harga) as total')
            ->where('tanggal_transaksi', '>=', now()->subDays(6))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        // Produk terlaris hari ini
        $produkTerlaris = DB::table('detail_transaksi as dt')
            ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->join('produk as p', 'dt.id_produk', '=', 'p.id_produk')
            ->whereDate('t.tanggal_transaksi', $today)
            ->selectRaw('p.nama_produk, SUM(dt.jumlah) as total_terjual')
            ->groupBy('p.id_produk', 'p.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $produkStokKritis = Produk::where('status', 'aktif')
            ->whereRaw('stok <= stok_minimum')
            ->with('kategori')
            ->orderBy('stok')
            ->limit(8)
            ->get();

        return view('dashboard.admin', compact(
            'penjualanHariIni', 'transaksiHariIni', 'pengeluaranHariIni',
            'totalProduk', 'stokKritis', 'totalUser',
            'grafik', 'produkTerlaris', 'produkStokKritis'
        ));
    }
}
