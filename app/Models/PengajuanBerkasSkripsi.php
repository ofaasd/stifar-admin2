<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBerkasSkripsi extends Model
{
    use HasFactory;

    protected $table = 'berkas_pengajuan_skripsi';

    protected $fillable = [
        'id_master' ,'nama_file','kategori','status',
        'created_at', 'updated_at'
    ];
}
