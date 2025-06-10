<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasBimbingan extends Model
{
    use HasFactory;
    protected $table = 'berkas_bimbingan';

    protected $fillable = [
        'id', 'file', 'id_bimbingan',
        'created_at', 'updated_at'
    ];

    
    public function bimbingan()
    {
        return $this->belongsTo(BimbinganSkripsi::class, 'id_bimbingan');
    }
}
