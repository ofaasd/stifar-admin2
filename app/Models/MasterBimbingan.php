<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBimbingan extends Model
{
    use HasFactory;

    protected $table = 'master_bimbingan_skripsi';
    protected $fillable = ['id_pembimbing', 'judul', 'file', 'kategori','status'];
    
}
