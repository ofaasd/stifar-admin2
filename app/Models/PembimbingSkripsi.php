<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingSkripsi extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_skripsi';

    protected $fillable = [
        'skripsi_id', 'nip', 'peran', 'tanggal_penetapan',
        'created_at', 'updated_at'
    ];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }
}
