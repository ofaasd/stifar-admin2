<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertemuanArsip extends Model
{
    use HasFactory;
    protected $table = 'pertemuan_arsip';
    protected $fillable = [
        'id_jadwal',
        'capaian',
        'no_pertemuan',
        'tgl_pertemuan',
        'id_dsn'
    ];
}
