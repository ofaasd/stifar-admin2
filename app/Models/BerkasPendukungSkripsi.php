<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPendukungSkripsi extends Model
{
    use HasFactory;
    protected $table = 'berkas_pendukung_skripsi';
    protected $fillable = ['nim','file','kategori_id','created_at','updated_at'];
}
