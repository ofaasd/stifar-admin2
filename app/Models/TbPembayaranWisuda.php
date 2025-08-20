<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbPembayaranWisuda extends Model
{
    use HasFactory;

    protected $table = 'tb_pembayaran_wisuda';

    protected $primaryKey = 'id';
    protected $guarded = [
        'id'
    ];
}
