<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BerkasPendukungMahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mahasiswa_berkas_pendukung';    
    protected $guarded = ['id'];
}
