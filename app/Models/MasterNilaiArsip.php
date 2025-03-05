<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterNilaiArsip extends Model
{
    use HasFactory;
    protected $table = 'master_nilai_arsip';
    protected $fillable = [
        'id_jadwal',
        'id_tahun',
        'id_mhs',
        'ntugas',
        'nuts',
        'nuas',
        'nakhir',
        'nhuruf',
        'ndosen',
        'is_krs',
        'publish_tugas',
        'publish_uts',
        'publish_uas',
        'validasi_tugas',
        'validasi_uts',
        'nim',
        'validasi_uas'
    ];
}
