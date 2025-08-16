<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbGelombangWisuda extends Model
{
    use HasFactory;

    protected $table = 'tb_gelombang_wisuda';
    protected $guarded = ['id'];
}
