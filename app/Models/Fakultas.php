<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = [
        'kode_fak', 
        'nama_fak', 
        'tgl_berdiri', 
        'no_sk', 
        'status'
    ];
}
