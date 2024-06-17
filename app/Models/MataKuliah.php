<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'nama_matkul_eng',
        'kel_mk',
        'tp',
        'ruang_teori',
        'ruang_praktek',
        'sks_teori',
        'sks_praktek',
        'semester',
        'status_mk',
        'rumpun',
        'status'
    ];
}
