<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class KontrakKuliahModel extends Model
{
    protected $fillable = [
        'id_jadwal', 'tugas', 'uts', 'uas'
    ];
}
