<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerkKendaraan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_merk_kendaraan';
    protected $fillable = ['kode', 'nama'];
}
