<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookBimbingan extends Model
{
    use HasFactory;

    protected $table = 'logbook_bimbingan_skripsi';
    protected $fillable = [ 'id_bimbingan', 'keterangan', 'judul', 'nama_file','kategori','komentar','tgl_pengajuan', 'status'];
}
