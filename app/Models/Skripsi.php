<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    use HasFactory;

    protected $table = 'skripsi';

    protected $fillable = [
        'nim', 'judul', 'abstrak', 'kata_kunci', 'status',
        'tanggal_pengajuan', 'tanggal_persetujuan', 'created_at', 'updated_at'
    ];

    public function pembimbing()
    {
        return $this->hasMany(PembimbingSkripsi::class);
    }

    public function bimbingan()
    {
        return $this->hasMany(BimbinganSkripsi::class);
    }

    public function berkas()
    {
        return $this->hasMany(BerkasSkripsi::class);
    }

    public function sidang()
    {
        return $this->hasOne(SidangSkripsi::class);
    }
}
