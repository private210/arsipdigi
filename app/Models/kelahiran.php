<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelahiran extends Model
{
    use HasFactory;
    protected $table = 'kelahirans';
    protected $fillable = [
        'no_akta',
        'nama_petugas',
        'no_register',
        'tanggal_daftar',
        'nama_anak',
        'nama_ayah',
        'nama_ibu',
        'tanggal_lahir_anak',
        'tahun_terbit',
        'tempat_lahir_anak',
        'jenis_kelamin_anak',
        'status_nikah_orangtua',
        'alamat',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];

    
}
