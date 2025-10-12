<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenontonSidang extends Model
{
    use HasFactory;

    protected $table = 'penonton_sidang';
    protected $guarded = ['id'];
}
