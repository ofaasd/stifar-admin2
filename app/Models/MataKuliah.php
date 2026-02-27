<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class MataKuliah extends Model
{
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'nama_matkul_eng',
        'kel_mk',
        'tp',
        'ruang_teori',
        'ruang_praktek',
        'sks_teori',
        'id_bidang_minat',
        'sks_praktek',
        'semester',
        'status_mk',
        'rumpun',
        'status',
        'prasyarat1',
        'prasyarat2',
        'prasyarat3'
    ];

    public function rps_details()
    {
        // Relasi One-to-Many untuk melihat history
        return $this->hasMany(RpsDetail::class, 'mata_kuliah_id');
    }

    public function rps_sekarang()
    {
        return $this->hasOne(RpsDetail::class, 'mata_kuliah_id')
                    ->latestOfMany()
                    ->whereHas('tahun_ajaran', function($query) {
                        $query->where('status', 'Aktif');
                    });
    }
}
