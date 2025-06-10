<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganSkripsi extends Model
{
    use HasFactory;
    protected $table = 'bimbingan_skripsi';

    protected $fillable = [
        'skripsi_id', 'nip', 'tanggal_waktu', 'tempat', 'topik',
        'status', 'catatan_mahasiswa', 'catatan_dosen', 'metode',
        'created_at', 'updated_at'
    ];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }

    public function berkas()
    {
        return $this->hasMany(BerkasBimbingan::class, 'id_bimbingan');
    }
}
