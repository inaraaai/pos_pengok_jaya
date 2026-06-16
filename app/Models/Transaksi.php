<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user', 'no_transaksi', 'total_harga',
        'jumlah_bayar', 'kembalian', 'tanggal_transaksi',
    ];

    protected $casts = ['tanggal_transaksi' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public static function generateNomor(): string
    {
        $prefix = 'TRX-' . now()->format('Ymd') . '-';
        $count  = self::whereDate('tanggal_transaksi', today())->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
