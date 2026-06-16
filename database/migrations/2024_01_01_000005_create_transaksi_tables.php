<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->unsignedInteger('id_user');
            $table->string('no_transaksi', 30)->unique();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('kembalian', 12, 2)->default(0);
            $table->datetime('tanggal_transaksi')->useCurrent();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_user');
            $table->index('tanggal_transaksi');
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_transaksi');
            $table->unsignedInteger('id_produk');
            $table->unsignedInteger('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_transaksi');
            $table->index('id_produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
    }
};
