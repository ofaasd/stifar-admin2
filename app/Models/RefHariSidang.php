<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefHariSidang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_hari_sidang';
    protected $guarded = ['id'];
}
