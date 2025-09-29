<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengujiSkripsi extends Model
{
    use HasFactory;

    protected $table = 'penguji';

    protected $guarded = ['id'];

    public function sidang()
    {
        return $this->belongsTo(SidangSkripsi::class);
    }
}
