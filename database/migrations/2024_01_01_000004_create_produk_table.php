<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->unsignedInteger('id_kategori');
            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk', 150);
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->string('satuan', 20)->default('pcs');
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(5);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_kategori');
            $table->index('status');
            $table->index(['stok', 'stok_minimum']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
