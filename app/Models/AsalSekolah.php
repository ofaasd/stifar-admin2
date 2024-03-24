<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AsalSekolah extends Model
{
    protected $fillable = [
        'npsn', 
        'nss', 
        'jenis', 
        'nama', 
        'alamat', 
        'telepon', 
        'email', 
        'status', 
        'daerah', 
        'propinsi', 
        'prov_id'
    ];
}
