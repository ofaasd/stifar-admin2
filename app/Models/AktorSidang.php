<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktorSidang extends Model
{
    use HasFactory;

    protected $table = 'aktor_sidang';

    protected $guarded = ['id'];

    public function sidang()
    {
        return $this->belongsTo(SidangSkripsi::class);
    }
}
