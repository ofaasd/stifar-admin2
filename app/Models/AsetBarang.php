<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetBarang extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'aset_barang';
    protected $fillable = [
        'kode_ruang',
        'id_penanggung_jawab',
        'label',
        'nama',
        'elektronik',
        'kode_jenis_barang',
        'pemeriksaan_terakhir',
    ];
}
