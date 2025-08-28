<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDaftarWisudawanArchive extends Model
{
    use HasFactory;

    protected $table = 'tb_daftar_wisudawan_archive';
    protected $guarded = ['id'];
}
