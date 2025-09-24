<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsetTanah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aset_tanah';
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'luas',
        'tanggal_perolehan',
        'no_sertifikat',
        'status_tanah',
        'keterangan',
        'bukti_fisik'
    ];
}
