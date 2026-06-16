<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->increments('id_kategori_pengeluaran');
            $table->string('nama_kategori', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->increments('id_pengeluaran');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_kategori_pengeluaran');
            $table->decimal('nominal', 12, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_kategori_pengeluaran')
                  ->references('id_kategori_pengeluaran')->on('kategori_pengeluaran')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_user');
            $table->index('id_kategori_pengeluaran');
            $table->index('tanggal');
        });

        Schema::create('stok_log', function (Blueprint $table) {
            $table->increments('id_stok_log');
            $table->unsignedInteger('id_produk');
            $table->unsignedInteger('id_user');
            $table->enum('jenis_perubahan', ['masuk', 'keluar', 'penyesuaian']);
            $table->integer('jumlah_perubahan');
            $table->unsignedInteger('stok_sebelum');
            $table->unsignedInteger('stok_sesudah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_user')->references('id_user')->on('users')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_produk');
            $table->index('id_user');
            $table->index('jenis_perubahan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_log');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('kategori_pengeluaran');
    }
};
