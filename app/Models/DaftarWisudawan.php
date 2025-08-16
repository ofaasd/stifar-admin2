<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarWisudawan extends Model
{
    use HasFactory;

    protected $table = 'tb_daftar_wisudawan';
    protected $guarded = ['id'];
}
