<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiPekerjaan
 *
 * @property int $id
 * @property string $posisi
 * @property string $perusahaan
 * @property Carbon $tahun_masuk
 * @property Carbon $tahun_keluar
 * @property int $id_pegawai
 * @property int $sekarang
 *
 * @package App\Models
 */
class PegawaiPekerjaan extends Model
{
	protected $table = 'pegawai_pekerjaan';

	protected $casts = [
		'id_pegawai' => 'int',
	];

	protected $fillable = [
		'posisi',
		'perusahaan',
		'tahun_masuk',
		'tahun_keluar',
		'id_pegawai',
		'sekarang'
	];
}
