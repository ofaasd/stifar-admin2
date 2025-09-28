<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganSkripsi extends Model
{
    use HasFactory;
    protected $table = 'bimbingan_skripsi';

    protected $guarded = ['id'];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }

    public function berkas()
    {
        return $this->hasMany(BerkasBimbingan::class, 'id_bimbingan');
    }
}
