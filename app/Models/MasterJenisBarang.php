<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_jenis_barang';
    protected $fillable = ['kode', 'nama'];
}
