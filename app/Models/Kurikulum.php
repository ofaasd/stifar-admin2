<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $fillable = [
        'kode_kurikulum', 'progdi', 'thn_ajar', 'angkatan','angkatan_akhir', 'status'
    ];
}
