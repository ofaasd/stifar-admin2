<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengujiSkripsi extends Model
{
    use HasFactory;

    protected $table = 'penguji';

    protected $fillable = [
        'sidang_id', 'nip', 'peran',
        'created_at', 'updated_at'
    ];

    public function sidang()
    {
        return $this->belongsTo(SidangSkripsi::class);
    }
}
