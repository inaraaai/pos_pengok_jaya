<?php

namespace App\Http\Controllers;

use App\Models\{Transaksi, Pengeluaran, DetailTransaksi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $tglMulai = $request->get('tgl_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));

        $transaksi = Transaksi::with(['user','details.produk'])
            ->whereBetween('tanggal_transaksi', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59'])
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $totalPendapatan  = $transaksi->sum('total_harga');
        $totalTransaksi   = $transaksi->count();

        $produkTerlaris = DB::table('detail_transaksi as dt')
            ->join('transaksi as t', 'dt.id_transaksi','=','t.id_transaksi')
            ->join('produk as p', 'dt.id_produk','=','p.id_produk')
            ->whereBetween('t.tanggal_transaksi', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59'])
            ->selectRaw('p.nama_produk, SUM(dt.jumlah) as total_terjual, SUM(dt.subtotal) as total_omzet')
            ->groupBy('p.id_produk','p.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('laporan.penjualan', compact('transaksi','totalPendapatan','totalTransaksi','produkTerlaris','tglMulai','tglAkhir'));
    }

    public function pengeluaran(Request $request)
    {
        $tglMulai = $request->get('tgl_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tglAkhir = $request->get('tgl_akhir', now()->format('Y-m-d'));

        $data  = Pengeluaran::with(['kategori','user'])
            ->whereBetween('tanggal', [$tglMulai, $tglAkhir])
            ->orderByDesc('tanggal')
            ->get();

        $total = $data->sum('nominal');

        $perKategori = $data->groupBy('kategori.nama_kategori')
            ->map(fn($g) => $g->sum('nominal'));

        return view('laporan.pengeluaran', compact('data','total','perKategori','tglMulai','tglAkhir'));
    }

    public function labaRugi(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $pendapatan = Transaksi::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('total_harga');

        $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $labaBersih = $pendapatan - $pengeluaran;

        // HPP sederhana (harga_beli * jumlah terjual)
        $hpp = DB::table('detail_transaksi as dt')
            ->join('transaksi as t','dt.id_transaksi','=','t.id_transaksi')
            ->join('produk as p','dt.id_produk','=','p.id_produk')
            ->whereMonth('t.tanggal_transaksi', $bulan)
            ->whereYear('t.tanggal_transaksi', $tahun)
            ->selectRaw('SUM(p.harga_beli * dt.jumlah) as hpp')
            ->value('hpp') ?? 0;

        $labaKotor = $pendapatan - $hpp;

        return view('laporan.labarugi', compact('pendapatan','pengeluaran','labaBersih','labaKotor','hpp','bulan','tahun'));
    }

    public function exportPdf(Request $request)
    {
        // Dummy: redirect dengan notifikasi (implementasi nyata gunakan dompdf/snappy)
        return back()->with('info', 'Fitur export PDF membutuhkan library dompdf. Install dengan: composer require barryvdh/laravel-dompdf');
    }

    public function exportExcel(Request $request)
    {
        // Dummy: redirect dengan notifikasi (implementasi nyata gunakan maatwebsite/excel)
        return back()->with('info', 'Fitur export Excel membutuhkan library maatwebsite/excel. Install dengan: composer require maatwebsite/excel');
    }
}
