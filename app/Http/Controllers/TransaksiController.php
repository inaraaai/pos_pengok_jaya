<?php

namespace App\Http\Controllers;

use App\Models\{Transaksi, DetailTransaksi, Produk, StokLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('user')
            ->orderByDesc('tanggal_transaksi')
            ->paginate(20);

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function cariProduk(Request $request)
    {
        $q = $request->get('q', '');
        $produk = Produk::where('status', 'aktif')
            ->where(fn($query) => $query
                ->where('nama_produk', 'like', "%$q%")
                ->orWhere('kode_produk', 'like', "%$q%"))
            ->select('id_produk', 'kode_produk', 'nama_produk', 'harga_jual', 'stok', 'satuan')
            ->limit(10)
            ->get();

        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'        => 'required|array|min:1',
            'items.*.id'   => 'required|exists:produk,id_produk',
            'items.*.qty'  => 'required|integer|min:1',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $itemsValid = [];

            foreach ($request->items as $item) {
                $produk = Produk::lockForUpdate()->findOrFail($item['id']);

                if (!$produk->isStokCukup($item['qty'])) {
                    DB::rollBack();
                    return response()->json([
                        'error' => "Stok {$produk->nama_produk} tidak cukup. Tersedia: {$produk->stok} {$produk->satuan}."
                    ], 422);
                }

                $subtotal        = $produk->harga_jual * $item['qty'];
                $total          += $subtotal;
                $itemsValid[]    = [
                    'produk'    => $produk,
                    'qty'       => $item['qty'],
                    'harga'     => $produk->harga_jual,
                    'subtotal'  => $subtotal,
                ];
            }

            if ($request->jumlah_bayar < $total) {
                DB::rollBack();
                return response()->json(['error' => 'Jumlah pembayaran kurang dari total harga.'], 422);
            }

            $trx = Transaksi::create([
                'id_user'           => auth()->id(),
                'no_transaksi'      => Transaksi::generateNomor(),
                'total_harga'       => $total,
                'jumlah_bayar'      => $request->jumlah_bayar,
                'kembalian'         => $request->jumlah_bayar - $total,
                'tanggal_transaksi' => now(),
            ]);

            foreach ($itemsValid as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $trx->id_transaksi,
                    'id_produk'    => $item['produk']->id_produk,
                    'jumlah'       => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['subtotal'],
                ]);

                $stokSebelum = $item['produk']->stok;
                $stokSesudah = $stokSebelum - $item['qty'];

                $item['produk']->decrement('stok', $item['qty']);

                StokLog::catat(
                    $item['produk']->id_produk,
                    auth()->id(),
                    'keluar',
                    $item['qty'],
                    $stokSebelum,
                    $stokSesudah,
                    "Transaksi #{$trx->no_transaksi}"
                );
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'id_transaksi'  => $trx->id_transaksi,
                'no_transaksi'  => $trx->no_transaksi,
                'total'         => $total,
                'kembalian'     => $trx->kembalian,
                'struk_url'     => route('transaksi.struk', $trx->id_transaksi),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $trx = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        return view('transaksi.show', compact('trx'));
    }

    public function struk($id)
    {
        $trx = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        return view('struk.index', compact('trx'));
    }
}
