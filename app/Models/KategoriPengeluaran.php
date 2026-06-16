<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model {
    protected $table      = 'kategori_pengeluaran';
    protected $primaryKey = 'id_kategori_pengeluaran';
    protected $fillable   = ['nama_kategori','deskripsi'];

    public function pengeluaran() {
        return $this->hasMany(Pengeluaran::class,'id_kategori_pengeluaran','id_kategori_pengeluaran');
    }
}
