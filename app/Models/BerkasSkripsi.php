<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasSkripsi extends Model
{
    use HasFactory;

    protected $table = 'berkas_skripsi';

    protected $fillable = [
        'skripsi_id', 'nim', 'jenis', 'nama_file', 'deskripsi',
        'status', 'catatan_verifikasi', 'verifikasi_oleh',
        'tanggal_upload', 'tanggal_verifikasi','kategori_id',
        'created_at', 'updated_at'
    ];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }
 
    public function kategori()
    {
        return $this->hasMany(KategoriBerkasSkripsi::class, 'id_bimbingan');
    }
}
