<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $fillable = [
        'id_jadwal',
        'capaian',
        'no_pertemuan',
        'tgl_pertemuan',
        'id_dsn'
    ];
}
