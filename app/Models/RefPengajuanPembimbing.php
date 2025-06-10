<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPengajuanPembimbing extends Model
{
    use HasFactory;

    protected $table = 'ref_pengajuan_pembimbing';
    protected $fillable = ['id','nim','nip','created_at'];
}
