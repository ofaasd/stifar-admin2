<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rumpun extends Model
{
    protected $fillable = [
        'nama_rumpun', 
        'status'
    ];
}
