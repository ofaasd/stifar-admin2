<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GelombangYudisium extends Model
{
    use HasFactory;

    protected $table = 'gelombang_yudisium';

    protected $guarded = ['id'];

    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranYudisium::class);
    }
}
