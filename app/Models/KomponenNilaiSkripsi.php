<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenNilaiSkripsi extends Model
{
    use HasFactory;

    protected $table = 'komponen_nilai_skripsi';

    protected $fillable = [
        'nama', 'kategori', 'bobot', 'deskripsi',
        'created_at', 'updated_at'
    ];

    public function penilaian()
    {
        return $this->hasMany(PenilaianSkripsi::class, 'komponen_id');
    }
}
