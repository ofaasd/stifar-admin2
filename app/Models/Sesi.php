<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $fillable = [
        'kode_sesi', 
        'id_ruang',
        'id_waktu',
        'status'
    ];
}
