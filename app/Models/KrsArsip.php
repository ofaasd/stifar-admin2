<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbsensiModel;

class KrsArsip extends Model
{
    protected $table = 'krs_arsip';
    protected $fillable = [
        'id_jadwal', 'id_tahun', 'id_mhs', 'is_publish'
    ];

    public function kehadiran(){
        return $this->hasMany(AbsensiModel::class, 'id_mhs', 'id_mhs');
    }
}
