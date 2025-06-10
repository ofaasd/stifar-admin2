<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoordinatorSkripsi extends Model
{
    use HasFactory;
    protected $table  = 'koordinator_skripsi';
    protected $fillable = ['id','nip','id_progdi','created_at','updated_at','deleted_at'];
}
