<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanJudulSkripsi extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_judul_skripsi';

    protected $fillable = [
        'id_master',
        'judul',
        'abstrak',
        'latar_belakang',
        'rumusan_masalah',
        'tujuan',
        'metodologi',
        'jenis_penelitian',
        'status',
    ];
}

