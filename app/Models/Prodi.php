<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'program_studi';
    protected $fillable = [
        'kode_prodi',
        'kode_nim',
        'jenjang',
        'nama_prodi',
        'tgl_pendirian',
        'no_sk_pendirian'
    ];
}
