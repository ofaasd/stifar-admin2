<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPembimbing extends Model
{
    use HasFactory;

    protected $table = 'ref_pembimbing_skripsi';
    protected $fillable = ['nip', 'kuota'];
}
