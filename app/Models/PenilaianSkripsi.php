<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSkripsi extends Model
{
    use HasFactory;

     protected $table = 'penilaian_skripsi';

    protected $fillable = [
        'sidang_id', 'nip', 'komponen_id', 'nilai', 'catatan',
        'created_at', 'updated_at'
    ];

    public function sidang()
    {
        return $this->belongsTo(SidangSkripsi::class);
    }

    public function komponen()
    {
        return $this->belongsTo(KomponenNilaiSkripsi::class, 'komponen_id');
    }
}
