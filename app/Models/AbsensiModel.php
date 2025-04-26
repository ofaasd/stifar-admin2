<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    protected $fillable = [
        'id_jadwal', 'id_pertemuan', 'id_mhs', 'type','note','input_by'
    ];
}
