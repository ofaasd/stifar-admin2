<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakKuliahArsip extends Model
{
    use HasFactory;
    protected $table = 'kontrak_kuliah_models_arsip';
    protected $fillable = [
        'id_jadwal',
        'tugas',
        'uts',
        'uas',
    ];
}
