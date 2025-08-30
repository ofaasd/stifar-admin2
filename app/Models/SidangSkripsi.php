<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidangSkripsi extends Model
{
    use HasFactory;
     protected $table = 'sidang';

    protected $fillable = [
        'skripsi_id', 'gelombang_id', 'tanggal', 'waktu_mulai',
        'waktu_selesai', 'ruangan', 'status',
        'created_at', 'updated_at'
    ];

    public function skripsi()
    {
        return $this->belongsTo(MasterSkripsi::class);
    }

    public function gelombang()
    {
        return $this->belongsTo(GelombangSidangSkripsi::class, 'gelombang_id');
    }

    public function penguji()
    {
        return $this->hasMany(PengujiSkripsi::class, 'sidang_id');
    }
    

    public function penilaian()
    {
        return $this->hasMany(PenilaianSkripsi::class);
    }

    public function hasil()
    {
        return $this->hasOne(HasilSidangSkripsi::class);
    }
}
