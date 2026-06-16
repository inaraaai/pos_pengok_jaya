<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StokLog extends Model {
    protected $table      = 'stok_log';
    protected $primaryKey = 'id_stok_log';
    protected $fillable   = ['id_produk','id_user','jenis_perubahan','jumlah_perubahan','stok_sebelum','stok_sesudah','keterangan'];

    public function produk() { return $this->belongsTo(Produk::class,'id_produk','id_produk'); }
    public function user()   { return $this->belongsTo(User::class,'id_user','id_user'); }

    public static function catat(int $produkId, int $userId, string $jenis, int $jumlah, int $sebelum, int $sesudah, string $ket = null): self {
        return self::create([
            'id_produk'        => $produkId,
            'id_user'          => $userId,
            'jenis_perubahan'  => $jenis,
            'jumlah_perubahan' => $jumlah,
            'stok_sebelum'     => $sebelum,
            'stok_sesudah'     => $sesudah,
            'keterangan'       => $ket,
        ]);
    }
}
