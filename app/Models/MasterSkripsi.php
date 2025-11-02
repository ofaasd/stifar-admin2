<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSkripsi extends Model
{
    use HasFactory;

    protected $table = 'master_skripsi';
    protected $guarded = ['id'];

    public function sidang()
    {
        return $this->hasMany(SidangSkripsi::class);
    }
}
