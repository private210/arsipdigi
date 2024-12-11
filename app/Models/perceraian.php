<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perceraian extends Model
{
    use HasFactory;
    protected $table = 'perceraians';
    protected $fillable = [
        'no_akta',
        'nama_petugas',
        'tanggal_daftar',
        'nama_suami',
        'nama_istri',
        'nama_saksi1',
        'nama_saksi2',
        'status_perceraian',
        'tahun_terbit',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];
}
