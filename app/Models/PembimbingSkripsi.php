<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingSkripsi extends Model
{
    use HasFactory;

    protected $table = 'pembimbing';

    protected $fillable = [
        'skripsi_id', 'nip', 'tanggal_penetapan',
        'created_at', 'updated_at'
    ];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }
}
