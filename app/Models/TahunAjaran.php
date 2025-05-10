<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'kode_ta',
        'tgl_awal',
        'tgl_awal_kuliah',
        'tgl_akhir',
        'status',
        'krs',
        'keterangan',
        'kuesioner',
    ];
}
