<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kematians', function (Blueprint $table) {
            $table->id();
            $table->string('no_akta');
            $table->string('nama_petugas');
            $table->date('tanggal_daftar');
            $table->string('nama_Almarhum');
            $table->date('tanggal_kematian');
            $table->date('tahun_terbit');
            $table->string('tempat_kematian');
            $table->string('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kematians');
    }
};
