<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class gelombang extends Model
{
    protected $fillable = [
        'no_gel', 
        'nama_gel', 
        'nama_gel_long', 
        'tgl_mulai', 
        'tgl_akhir', 
        'ujian', 
        'jam_ujian', 
        'hari_ujian', 
        'pengumuman', 
        'reg_mulai', 
        'reg_akhir', 
        'tahun', 
        'semester', 
        'jenis', 
        'pmb_online'
    ];
}
