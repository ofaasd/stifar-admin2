<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GelombangYudisium extends Model
{
    use HasFactory;

    protected $table = 'gelombang_yudisium';

    protected $fillable = [
        'periode', 'tanggal', 'tempat', 'tanggal_mulai_daftar',
        'tanggal_selesai_daftar', 'status', 'created_at', 'updated_at'
    ];

    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranYudisium::class);
    }
}
