<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudulSkripsi extends Model
{
    use HasFactory;

    protected $table = 'judul_skripsi';
    protected $fillable = ['nim','judul','translate','abstrak'];
}
