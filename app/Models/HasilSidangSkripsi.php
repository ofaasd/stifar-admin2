<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSidangSkripsi extends Model
{
    use HasFactory;

    protected $table = 'hasil_sidang_skripsi';

    protected $fillable = [
        'sidang_id', 'nilai_akhir', 'nilai_huruf', 'status_kelulusan',
        'catatan', 'revisi', 'batas_revisi',
        'created_at', 'updated_at'
    ];

    public function sidang()
    {
        return $this->belongsTo(SidangSkripsi::class);
    }
}
