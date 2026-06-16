<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table      = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_role', 'nama_lengkap', 'username', 'password', 'status',
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_user', 'id_user');
    }

    public function stokLog()
    {
        return $this->hasMany(StokLog::class, 'id_user', 'id_user');
    }

    public function isAdmin(): bool
    {
        return $this->role->nama_role === 'Admin';
    }

    public function isKasir(): bool
    {
        return $this->role->nama_role === 'Kasir';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
