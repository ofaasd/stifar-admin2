<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiModelArsip extends Model
{
    use HasFactory;
    protected $table = 'absensi_model_arsip';
    protected $fillable = [
        'id_jadwal',
        'id_pertemuan',
        'id_mhs',
        'type',
    ];
}
