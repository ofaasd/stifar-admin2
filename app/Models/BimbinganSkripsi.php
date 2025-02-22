<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganSkripsi extends Model
{
    use HasFactory;
    protected $table = "bimbingan_skripsi_mahasiswa";
    protected $fillable = ['id','id_master_bimbingan','judul','file','kategori','status','created_at','updated_at'];
}
