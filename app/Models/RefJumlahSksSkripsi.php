<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJumlahSksSkripsi extends Model
{
    use HasFactory;
    protected $table = 'ref_jumlah_sks_skripsi';
    protected $fillable = ['id','jumlah_sks','id_progdi','created_at','updated_at','deleted_at'];
}
