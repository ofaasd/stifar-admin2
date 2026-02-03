<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetKendaraan extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'aset_kendaraan';
    protected $fillable = [
        'kode',
        'kode_jenis_kendaraan',
        'kode_merek_kendaraan',
        'id_penanggung_jawab',
        'nama',
        'nomor_polisi',
        'tanggal_perolehan',
        'harga_perolehan',
        'harga_penyusutan',
        'nomor_rangka',
        'bahan_bakar',
        'transmisi',
        'kapasitas_mesin',
        'pemeriksaan_terakhir',
        'tanggal_pajak',
        'foto',
    ];
}
