<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insert([
            ['nama_role' => 'Admin',  'deskripsi' => 'Pemilik toko dengan hak akses penuh'],
            ['nama_role' => 'Kasir',  'deskripsi' => 'Staf kasir dengan hak akses transaksi'],
        ]);

        // Users
        DB::table('users')->insert([
            [
                'id_role'      => 1,
                'nama_lengkap' => 'Admin Toko',
                'username'     => 'admin',
                'password'     => Hash::make('admin123'),
                'status'       => 'aktif',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id_role'      => 2,
                'nama_lengkap' => 'Kasir Satu',
                'username'     => 'kasir1',
                'password'     => Hash::make('kasir123'),
                'status'       => 'aktif',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // Kategori Barang
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Makanan',            'deskripsi' => 'Produk makanan dan camilan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Minuman',            'deskripsi' => 'Produk minuman',             'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Rokok',              'deskripsi' => 'Produk rokok dan tembakau',  'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kebutuhan Rumah',    'deskripsi' => 'Sabun, detergen, dll',       'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Alat Tulis',         'deskripsi' => 'Perlengkapan alat tulis',    'created_at' => now(), 'updated_at' => now()],
        ]);

        // Produk
        DB::table('produk')->insert([
            ['id_kategori'=>1,'kode_produk'=>'PRD-0001','nama_produk'=>'Mie Instan Goreng','harga_beli'=>2500,'harga_jual'=>3500,'satuan'=>'pcs','stok'=>50,'stok_minimum'=>10,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>1,'kode_produk'=>'PRD-0002','nama_produk'=>'Keripik Singkong','harga_beli'=>4000,'harga_jual'=>6000,'satuan'=>'pcs','stok'=>30,'stok_minimum'=>8,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>2,'kode_produk'=>'PRD-0003','nama_produk'=>'Air Mineral 600ml','harga_beli'=>2000,'harga_jual'=>3000,'satuan'=>'pcs','stok'=>100,'stok_minimum'=>20,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>2,'kode_produk'=>'PRD-0004','nama_produk'=>'Teh Botol Sosro','harga_beli'=>4000,'harga_jual'=>5500,'satuan'=>'pcs','stok'=>60,'stok_minimum'=>15,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>3,'kode_produk'=>'PRD-0005','nama_produk'=>'Rokok Surya 12','harga_beli'=>16000,'harga_jual'=>20000,'satuan'=>'bungkus','stok'=>3,'stok_minimum'=>10,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>3,'kode_produk'=>'PRD-0006','nama_produk'=>'Rokok Gudang Garam','harga_beli'=>18000,'harga_jual'=>22000,'satuan'=>'bungkus','stok'=>0,'stok_minimum'=>10,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>4,'kode_produk'=>'PRD-0007','nama_produk'=>'Sabun Lifebuoy','harga_beli'=>3500,'harga_jual'=>5000,'satuan'=>'pcs','stok'=>25,'stok_minimum'=>5,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
            ['id_kategori'=>4,'kode_produk'=>'PRD-0008','nama_produk'=>'Detergen Rinso 800g','harga_beli'=>15000,'harga_jual'=>18000,'satuan'=>'pcs','stok'=>15,'stok_minimum'=>5,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()],
        ]);

        // Kategori Pengeluaran
        DB::table('kategori_pengeluaran')->insert([
            ['nama_kategori'=>'Pembelian Barang','deskripsi'=>'Pembelian stok dari distributor','created_at'=>now(),'updated_at'=>now()],
            ['nama_kategori'=>'Operasional','deskripsi'=>'Listrik, air, kebersihan','created_at'=>now(),'updated_at'=>now()],
            ['nama_kategori'=>'Gaji Karyawan','deskripsi'=>'Pembayaran gaji staf','created_at'=>now(),'updated_at'=>now()],
            ['nama_kategori'=>'Lain-lain','deskripsi'=>'Pengeluaran di luar kategori utama','created_at'=>now(),'updated_at'=>now()],
        ]);

        // Contoh pengeluaran
        DB::table('pengeluaran')->insert([
            ['id_user'=>1,'id_kategori_pengeluaran'=>2,'nominal'=>150000,'keterangan'=>'Bayar listrik bulan ini','tanggal'=>now()->format('Y-m-d'),'created_at'=>now(),'updated_at'=>now()],
            ['id_user'=>1,'id_kategori_pengeluaran'=>1,'nominal'=>500000,'keterangan'=>'Beli stok rokok & minuman','tanggal'=>now()->format('Y-m-d'),'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
