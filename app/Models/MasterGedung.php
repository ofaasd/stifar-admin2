<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterGedung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_gedung';
    protected $fillable = ['nama', 'kode'];
}
