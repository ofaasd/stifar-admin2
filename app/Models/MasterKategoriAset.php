<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKategoriAset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_kategori_aset';
    protected $fillable = ['kode', 'nama'];
}
