<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranYudisium extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_yudisium';

    protected $fillable = [
        'nim', 'gelombang_id', 'tanggal_daftar', 'status',
        'created_at', 'updated_at'
    ];

    public function gelombang()
    {
        return $this->belongsTo(GelombangYudisium::class, 'gelombang_id');
    }

    public function berkas()
    {
        return $this->hasMany(BerkasYudisium::class, 'pendaftaran_id');
    }
}
