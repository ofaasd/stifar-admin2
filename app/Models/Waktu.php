<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Waktu extends Model
{
    protected $fillable = [
        'nama_sesi', 
        'waktu_mulai', 
        'waktu_selesai', 
        'jml_sks', 
        'status'
    ];
}
