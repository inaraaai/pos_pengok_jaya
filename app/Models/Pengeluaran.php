<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model {
    protected $table      = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    protected $fillable   = ['id_user','id_kategori_pengeluaran','nominal','keterangan','tanggal'];
    protected $casts      = ['tanggal' => 'date'];

    public function user()     { return $this->belongsTo(User::class,'id_user','id_user'); }
    public function kategori() { return $this->belongsTo(KategoriPengeluaran::class,'id_kategori_pengeluaran','id_kategori_pengeluaran'); }
}
