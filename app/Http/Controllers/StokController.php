<?php

namespace App\Http\Controllers;

use App\Models\{Produk, StokLog};
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->where('status', 'aktif');

        if ($request->filled('filter')) {
            match($request->filter) {
                'kritis'  => $query->whereRaw('stok = 0'),
                'menipis' => $query->whereRaw('stok > 0 AND stok <= stok_minimum'),
                'aman'    => $query->whereRaw('stok > stok_minimum'),
                default   => null,
            };
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) => $q2->where('nama_produk','like',"%$q%")->orWhere('kode_produk','like',"%$q%"));
        }

        $produk  = $query->orderByRaw('stok ASC')->paginate(20)->withQueryString();
        $kritis  = Produk::where('status','aktif')->whereRaw('stok = 0')->count();
        $menipis = Produk::where('status','aktif')->whereRaw('stok > 0 AND stok <= stok_minimum')->count();
        $aman    = Produk::where('status','aktif')->whereRaw('stok > stok_minimum')->count();

        return view('stok.index', compact('produk','kritis','menipis','aman'));
    }

    public function log($id)
    {
        $produk = Produk::findOrFail($id);
        $log    = StokLog::with('user')
            ->where('id_produk', $id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('stok.log', compact('produk', 'log'));
    }

    public function updateManual(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'jumlah'          => 'required|integer|min:0',
            'jenis_perubahan' => 'required|in:masuk,penyesuaian',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $stokSebelum = $produk->stok;
        $stokSesudah = $request->jenis_perubahan === 'masuk'
            ? $stokSebelum + $request->jumlah
            : $request->jumlah;

        $produk->update(['stok' => $stokSesudah]);

        StokLog::catat(
            $produk->id_produk,
            auth()->id(),
            'penyesuaian',
            abs($stokSesudah - $stokSebelum),
            $stokSebelum,
            $stokSesudah,
            $request->keterangan ?? 'Update stok manual'
        );

        return back()->with('success', "Stok {$produk->nama_produk} berhasil diperbarui: {$stokSebelum} → {$stokSesudah}.");
    }
}
