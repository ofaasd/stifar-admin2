<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetGedungBangunan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aset_gedung_bangunan';
    protected $fillable = [
        'kode_tanah',
        'id_lantai',
        'kode',
        'nama',
        'luas',
    ];
}
