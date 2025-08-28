<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'mahasiswa';
    protected $fillable = [
        'nim', 'nama', 'no_ktp', 'jk', 'agama', 'tempat_lahir', 'tgl_lahir', 'nama_ibu', 'nama_ayah', 'hp_ortu', 'alamat', 'alamat_semarang', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kokab', 'provinsi', 'telp', 'hp', 'email', 'paswd', 'status', 'foto_mhs', 'kelas', 'angkatan', 'ta', 'id_program_studi', 'id_dsn_wali','user_id','nopen', 'no_pisn', 'is_yudisium', 'foto_yudisium'
    ];
}
