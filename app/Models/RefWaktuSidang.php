<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefWaktuSidang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_waktu_sidang';
    protected $guarded = ['id'];
}
