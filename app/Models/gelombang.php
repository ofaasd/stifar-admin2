<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PmbJalur;

class gelombang extends Model
{
    protected $table = 'pmb_gelombang';
	public $timestamps = false;
    protected $fillable = [
        'no_gel',
        'nama_gel',
        'nama_gel_long',
        'tgl_mulai',
        'tgl_akhir',
        'ujian',
        'jam_ujian',
        'hari_ujian',
        'pengumuman',
        'reg_mulai',
        'reg_akhir',
        'tahun',
        'semester',
        'jenis',
        'pmb_online',
        'id_jalur',
        'ta_awal',
		'ta_akhir',
    ];
    public function jalur() : BelongsTo
    {
      return $this->belongsTo(PmbJalur::class, 'id_jalur','id');
    }
}
