<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbFlagging extends Model
{
    use HasFactory;

    protected $table = 'tb_flagging';
    protected $guarded = ['id'];
}
