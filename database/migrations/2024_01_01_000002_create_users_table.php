<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->unsignedTinyInteger('id_role');
            $table->string('nama_lengkap', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_role')->references('id_role')->on('roles')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
