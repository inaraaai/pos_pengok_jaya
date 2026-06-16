<?php

namespace App\Http\Controllers;

use App\Models\{Produk, Kategori, StokLog};
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) => $q2->where('nama_produk', 'like', "%$q%")->orWhere('kode_produk', 'like', "%$q%"));
        }
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $produk   = $query->orderBy('nama_produk')->paginate(15)->withQueryString();
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('produk.index', compact('produk', 'kategori'));
    }

    public function create()
    {
        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kodeBaru  = Produk::generateKode();
        return view('produk.create', compact('kategori', 'kodeBaru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'nama_produk'  => 'required|string|max:150',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $data                = $request->all();
        $data['kode_produk'] = Produk::generateKode();

        $produk = Produk::create($data);

        if ($produk->stok > 0) {
            StokLog::catat($produk->id_produk, auth()->id(), 'masuk', $produk->stok, 0, $produk->stok, 'Stok awal saat penambahan produk');
        }

        return redirect()->route('produk.index')->with('success', "Produk {$produk->nama_produk} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $produk   = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'nama_produk'  => 'required|string|max:150',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $stokLama = $produk->stok;
        $stokBaru = (int) $request->stok;

        $produk->update($request->only('id_kategori','nama_produk','harga_beli','harga_jual','satuan','stok','stok_minimum','status'));

        if ($stokBaru !== $stokLama) {
            $selisih = $stokBaru - $stokLama;
            $jenis   = $selisih > 0 ? 'masuk' : 'keluar';
            StokLog::catat($produk->id_produk, auth()->id(), 'penyesuaian', abs($selisih), $stokLama, $stokBaru, 'Penyesuaian stok manual');
        }

        return redirect()->route('produk.index')->with('success', "Produk {$produk->nama_produk} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->hasTransaksi()) {
            return back()->with('error', "Produk tidak dapat dihapus karena memiliki riwayat transaksi. Nonaktifkan produk ini.");
        }

        $nama = $produk->nama_produk;
        $produk->delete();

        return redirect()->route('produk.index')->with('success', "Produk {$nama} berhasil dihapus.");
    }

    public function cari(Request $request)
    {
        $keyword = $request->get('q', '');
        $produk  = Produk::where('status', 'aktif')
            ->where(fn($q) => $q->where('nama_produk', 'like', "%$keyword%")->orWhere('kode_produk', 'like', "%$keyword%"))
            ->with('kategori')
            ->limit(10)
            ->get(['id_produk','kode_produk','nama_produk','harga_jual','stok','satuan']);

        return response()->json($produk);
    }
}
