<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class KelompokMataKuliah extends Model
{
    protected $fillable = [
        'nama_kelompok',
        'nama_kelompok_eng',
        'kode'
    ];
}
