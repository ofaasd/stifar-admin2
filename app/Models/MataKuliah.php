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
        'jumlah_sks',
        'semester',
        'tp',
        'kel_mk',
        'rumpun',
        'id_prodi',
        'status'
    ];
}
