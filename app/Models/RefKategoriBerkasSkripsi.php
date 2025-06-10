<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKategoriBerkasSkripsi extends Model
{
    use HasFactory;
    protected $table = 'ref_kategori_berkas_skripsi';
    protected $fillable = ['id','nama','updated_at','created_at','deleted_at'];
}
