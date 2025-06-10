<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBerkasSkripsi extends Model
{
    use HasFactory;
    protected $table = 'kategori_berkas_skripsi';

    protected $fillable = [
        'nama', 'deskripsi','kategori',
        'created_at', 'updated_at'
    ];

    public function berkas()
    {
        return $this->belongsTo(BerkasSkripsi::class);
    }
}
