<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPembimbing extends Model
{
    use HasFactory;
    protected $table = 'master_pembimbing_skripsi';
    protected $fillable = ['nip', 'nim', 'topik_judul','status'];
}
