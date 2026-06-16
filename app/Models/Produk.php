<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table      = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori', 'kode_produk', 'nama_produk',
        'harga_beli', 'harga_jual', 'satuan',
        'stok', 'stok_minimum', 'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function stokLog()
    {
        return $this->hasMany(StokLog::class, 'id_produk', 'id_produk');
    }

    public function isStokCukup(int $jumlah): bool
    {
        return $this->stok >= $jumlah;
    }

    public function isStokMinimum(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public function getStokStatusAttribute(): string
    {
        if ($this->stok === 0)                         return 'habis';
        if ($this->stok <= $this->stok_minimum)        return 'kritis';
        if ($this->stok <= ($this->stok_minimum * 2))  return 'menipis';
        return 'aman';
    }

    public function hasTransaksi(): bool
    {
        return $this->detailTransaksi()->count() > 0;
    }

    public static function generateKode(): string
    {
        $last = self::orderBy('id_produk', 'desc')->first();
        $next = $last ? ($last->id_produk + 1) : 1;
        return 'PRD-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
