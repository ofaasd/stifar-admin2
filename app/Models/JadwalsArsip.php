<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\pengajar;

class JadwalsArsip extends Model
{
    protected $table = 'jadwals_arsip';
    protected $fillable = [
        'kode_jadwal',
        'id_tahun',
        'id_mk',
        'hari',
        'id_sesi',
        'id_ruang',
        'kel',
        'kuota',
        'status',
        'tp'
    ];

    public function pengajar(){
        return $this->hasMany(Pengajar::class, 'id_jadwal', 'id');
    }
}
