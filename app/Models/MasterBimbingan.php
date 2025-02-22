<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBimbingan extends Model
{
    use HasFactory;

    protected $table = 'master_bimbingan_skripsi';
    protected $fillable = ['id', 'nim', 'nip_pembimbing_1','nip_pembimbing_2','status','created_at','updated_at'];
    
}
