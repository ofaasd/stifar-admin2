<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PmbPesertum
 * 
 * @property int $id
 * @property int $nopen
 * @property string $password
 * @property string|null $nisn
 * @property string|null $nim
 * @property string|null $gelombang
 * @property string|null $noktp
 * @property string $nama
 * @property bool|null $jk
 * @property int|null $agama
 * @property string|null $nama_ibu
 * @property string|null $nama_ayah
 * @property string|null $hp_ortu
 * @property string|null $email
 * @property string|null $alamat_semarang
 * @property int|null $tinggi_badan
 * @property int|null $berat_badan
 * @property string|null $tempat_lahir
 * @property Carbon|null $tanggal_lahir
 * @property string|null $alamat
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $kelurahan
 * @property string|null $kecamatan
 * @property string|null $kotakab
 * @property string|null $provinsi
 * @property int|null $kodepos
 * @property string $telpon
 * @property string $hp
 * @property string|null $foto_peserta
 * @property string $path_foto
 * @property string|null $ukuran_seragam
 * @property string|null $asal_sekolah
 * @property string|null $warga_negara
 * @property string|null $peringkat_pmdp
 * @property string|null $pilihan1
 * @property string|null $pilihan2
 * @property int|null $admin_input
 * @property Carbon|null $admin_input_date
 * @property bool|null $is_bayar
 * @property bool|null $is_online
 * @property bool|null $kelas
 * @property int|null $jalur_pendaftaran
 * @property bool|null $is_kerjasama
 * @property int|null $is_mou
 * @property int|null $jenis_pendaftaran
 * @property int|null $angkatan
 * @property int|null $tahun_ajaran
 * @property int|null $admin_registrasi
 * @property Carbon|null $admin_registrasi_date
 * @property string|null $info_pmb
 * @property bool|null $is_delete
 * @property bool|null $is_mundur
 * @property int $registrasi_pendaftaran
 * @property int $is_inject
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class PmbPesertum extends Model
{
	use SoftDeletes;
	protected $table = 'pmb_peserta';

	protected $casts = [
		'nopen' => 'int',
		'jk' => 'int',
		'agama' => 'int',
		'tinggi_badan' => 'int',
		'berat_badan' => 'int',
		'tanggal_lahir' => 'datetime',
		'kodepos' => 'int',
		'admin_input' => 'int',
		'admin_input_date' => 'datetime',
		'is_bayar' => 'bool',
		'is_online' => 'bool',
		'kelas' => 'bool',
		'jalur_pendaftaran' => 'int',
		'is_kerjasama' => 'bool',
		'is_mou' => 'int',
		'jenis_pendaftaran' => 'int',
		'angkatan' => 'int',
		'tahun_ajaran' => 'int',
		'admin_registrasi' => 'int',
		'admin_registrasi_date' => 'datetime',
		'is_delete' => 'bool',
		'is_mundur' => 'bool',
		'registrasi_pendaftaran' => 'int',
		'is_inject' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nopen',
		'password',
		'nisn',
		'nim',
		'gelombang',
		'noktp',
		'nama',
		'jk',
		'agama',
		'nama_ibu',
		'nama_ayah',
		'hp_ortu',
		'email',
		'alamat_semarang',
		'tinggi_badan',
		'berat_badan',
		'tempat_lahir',
		'tanggal_lahir',
		'alamat',
		'rt',
		'rw',
		'kelurahan',
		'kecamatan',
		'kotakab',
		'provinsi',
		'kodepos',
		'telpon',
		'hp',
		'foto_peserta',
		'path_foto',
		'ukuran_seragam',
		'asal_sekolah',
		'warga_negara',
		'peringkat_pmdp',
		'pilihan1',
		'pilihan2',
		'admin_input',
		'admin_input_date',
		'is_bayar',
		'is_online',
		'kelas',
		'jalur_pendaftaran',
		'is_kerjasama',
		'is_mou',
		'jenis_pendaftaran',
		'angkatan',
		'tahun_ajaran',
		'admin_registrasi',
		'admin_registrasi_date',
		'info_pmb',
		'is_delete',
		'is_mundur',
		'registrasi_pendaftaran',
		'is_inject'
	];
}
