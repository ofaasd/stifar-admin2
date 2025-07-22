<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_vendor';
    protected $fillable = ['kode', 'nama'];
}
