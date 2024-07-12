<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class anggota_mk extends Model
{
    protected $fillable = [
        'idmk', 'id_pegawai_bio'
    ];
}
