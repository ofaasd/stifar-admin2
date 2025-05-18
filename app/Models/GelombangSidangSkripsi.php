<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GelombangSidangSkripsi extends Model
{
    use HasFactory;
    protected $table = 'gelombang_sidang_skripsi';

    protected $fillable = [
        'nama', 'periode', 'tanggal_mulai_daftar', 'tanggal_selesai_daftar',
        'tanggal_mulai_pelaksanaan', 'tanggal_selesai_pelaksanaan',
        'created_at', 'updated_at'
    ];

    public function sidang()
    {
        return $this->hasMany(SidangSkripsi::class);
    }
}
