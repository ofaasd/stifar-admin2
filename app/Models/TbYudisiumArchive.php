<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbYudisiumArchive extends Model
{
    use HasFactory;

    protected $table = 'tb_yudisium_archive';
    protected $guarded = ['id'];
}
