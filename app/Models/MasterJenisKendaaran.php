<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterJenisKendaaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_jenis_kendaraan';
    protected $fillable = ['kode', 'nama'];
}
