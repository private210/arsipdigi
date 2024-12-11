<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kematian extends Model
{ 
    use HasFactory;
    protected $table = 'kematians';
    protected $fillable = [
        'no_akta',
        'nama_petugas',
        'tanggal_daftar',
        'nama_Almarhum',
        'tanggal_kematian',
        'tahun_terbit',
        'tempat_kematian',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];
}
