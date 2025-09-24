<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MahasiswaTemp extends Model
{
    protected $table = 'mahasiswa_temp';
    protected $fillable = [
        'nim', 'nama', 'no_ktp', 'jk', 'agama', 'tempat_lahir', 'tgl_lahir', 'nama_ibu', 'nama_ayah', 'hp_ortu', 'alamat', 'alamat_semarang', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kokab', 'provinsi', 'telp', 'hp', 'email', 'paswd', 'status', 'foto_mhs', 'kelas', 'angkatan', 'ta', 'id_program_studi', 'id_dsn_wali','user_id', 'nopen'
    ];
}
