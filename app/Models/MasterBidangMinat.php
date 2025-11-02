<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBidangMinat extends Model
{
    use HasFactory;

    protected $table = 'master_bidang_minat';
    protected $guarded = ['id'];
}
