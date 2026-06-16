<?php

namespace App\Http\Controllers;

use App\Models\{Pengeluaran, KategoriPengeluaran};
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['user','kategori']);

        if ($request->filled('bulan'))  $query->whereMonth('tanggal', $request->bulan);
        if ($request->filled('tahun'))  $query->whereYear('tanggal', $request->tahun);
        if ($request->filled('kategori')) $query->where('id_kategori_pengeluaran', $request->kategori);

        $data      = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();
        $total     = $query->sum('nominal');
        $kategori  = KategoriPengeluaran::orderBy('nama_kategori')->get();

        return view('pengeluaran.index', compact('data','total','kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori_pengeluaran' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
            'nominal'                 => 'required|numeric|min:1',
            'keterangan'              => 'nullable|string',
            'tanggal'                 => 'required|date',
        ], [
            'nominal.min'    => 'Nominal harus lebih dari 0.',
            'tanggal.required' => 'Tanggal wajib diisi.',
        ]);

        Pengeluaran::create([
            'id_user'                 => auth()->id(),
            'id_kategori_pengeluaran' => $request->id_kategori_pengeluaran,
            'nominal'                 => $request->nominal,
            'keterangan'              => $request->keterangan,
            'tanggal'                 => $request->tanggal,
        ]);

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $request->validate([
            'id_kategori_pengeluaran' => 'required|exists:kategori_pengeluaran,id_kategori_pengeluaran',
            'nominal'                 => 'required|numeric|min:1',
            'keterangan'              => 'nullable|string',
            'tanggal'                 => 'required|date',
        ]);

        $pengeluaran->update($request->only('id_kategori_pengeluaran','nominal','keterangan','tanggal'));

        return back()->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }

    // Kelola Kategori Pengeluaran
    public function kategoriIndex()
    {
        $data = KategoriPengeluaran::withCount('pengeluaran')->get();
        return view('pengeluaran.kategori', compact('data'));
    }

    public function kategoriStore(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100|unique:kategori_pengeluaran,nama_kategori']);
        KategoriPengeluaran::create($request->only('nama_kategori','deskripsi'));
        return back()->with('success', 'Kategori pengeluaran ditambahkan.');
    }

    public function kategoriDestroy($id)
    {
        $kat = KategoriPengeluaran::withCount('pengeluaran')->findOrFail($id);
        if ($kat->pengeluaran_count > 0) {
            return back()->with('error', 'Kategori masih digunakan.');
        }
        $kat->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
