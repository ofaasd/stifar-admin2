<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersyaratanYudisium extends Model
{
    use HasFactory;

    protected $table = 'persyaratan_yudisium';

    protected $fillable = [
        'nama', 'kategori', 'deskripsi', 'wajib',
        'created_at', 'updated_at'
    ];

    public function berkas()
    {
        return $this->hasMany(BerkasYudisium::class);
    }
}
