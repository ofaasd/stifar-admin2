<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class pengajar extends Model
{
    protected $fillable = [
        'id_jadwal',
        'id_dsn',
    ];

    // hasmany ke simpeg dosen
}
