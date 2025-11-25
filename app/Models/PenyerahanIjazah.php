<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyerahanIjazah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penyerahan_ijazah';
    protected $guarded = ['id'];
}
