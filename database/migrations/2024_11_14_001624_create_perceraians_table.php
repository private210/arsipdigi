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
        Schema::create('perceraians', function (Blueprint $table) {
            $table->id();
            $table->string('no_akta');
            $table->string('nama_petugas');
            $table->date('tanggal_daftar');
            $table->string('nama_suami');
            $table->string('nama_istri');
            $table->string('nama_saksi1');
            $table->string('nama_saksi2');
            $table->string('status_perceraian');
            $table->date('tahun_terbit');
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
        Schema::dropIfExists('perceraians');
    }
};
