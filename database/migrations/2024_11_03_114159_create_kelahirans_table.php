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
        Schema::create('kelahirans', function (Blueprint $table) {
            $table->id();
            $table->string('no_akta');
            $table->string('nama_petugas');
            $table->date('tanggal_daftar');
            $table->string('nama_anak');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->date('tanggal_lahir_anak');
            $table->date('tahun_terbit');
            $table->string('tempat_lahir_anak');
            $table->string('jenis_kelamin_anak');
            $table->string('status_nikah_orangtua');
            $table->string('alamat');
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
        Schema::dropIfExists('kelahirans');
    }
};
