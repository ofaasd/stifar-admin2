<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PmbPesertaOnline
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $nisn
 * @property string|null $nim
 * @property string|null $gelombang
 * @property string|null $noktp
 * @property string $nama
 * @property bool $jk
 * @property int $agama
 * @property string $nama_ibu
 * @property string $nama_ayah
 * @property string|null $hp_ortu
 * @property string|null $alamat_semarang
 * @property int $tinggi_badan
 * @property int $berat_badan
 * @property string $tempat_lahir
 * @property Carbon $tanggal_lahir
 * @property string $alamat
 * @property string $rt
 * @property string $rw
 * @property string $kelurahan
 * @property string $kecamatan
 * @property string $kotakab
 * @property string $provinsi
 * @property int $kodepos
 * @property string|null $telpon
 * @property string $hp
 * @property string|null $file_pendukung
 * @property string|null $ukuran_seragam
 * @property string $asal_sekolah
 * @property string $warga_negara
 * @property string|null $peringkat_pmdp
 * @property string|null $pilihan1
 * @property string|null $pilihan2
 * @property int $admin_input
 * @property Carbon $admin_input_date
 * @property bool|null $is_bayar
 * @property bool|null $is_online
 * @property bool|null $kelas
 * @property int $jalur_pendaftaran
 * @property int|null $jenis_pendaftaran
 * @property int $angkatan
 * @property int $tahun_ajaran
 * @property int|null $admin_registrasi
 * @property Carbon|null $admin_registrasi_date
 * @property string|null $info_pmb
 * @property bool|null $is_delete
 * @property bool|null $is_mundur
 * @property float $ipk
 * @property float $toefl
 *
 * @package App\Models
 */
class PmbPesertaOnline extends Model
{
	protected $table = 'pmb_peserta_online';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
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
		'jenis_pendaftaran' => 'int',
		'angkatan' => 'int',
		'tahun_ajaran' => 'int',
		'admin_registrasi' => 'int',
		'admin_registrasi_date' => 'datetime',
		'is_delete' => 'bool',
		'is_mundur' => 'bool',
		'ipk' => 'float',
		'toefl' => 'float'
	];

	protected $fillable = [
		'user_id',
		'nopen',
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
		'file_pendukung',
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
		'jenis_pendaftaran',
		'angkatan',
		'tahun_ajaran',
		'admin_registrasi',
		'admin_registrasi_date',
		'info_pmb',
		'is_delete',
		'is_mundur',
		'ipk',
		'toefl',
        'is_verifikasi',
        'no_refrensi',
	];
}
