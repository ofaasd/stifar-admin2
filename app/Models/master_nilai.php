<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class master_nilai extends Model
{
    protected $table = 'master_nilai';
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
        'validasi_uas'
    ];
}
