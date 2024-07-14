<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $fillable = [
        'id_jadwal', 'id_tahun', 'id_mhs', 'is_publish'
    ];
}
