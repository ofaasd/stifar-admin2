<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiOrganisasi
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama_organisasi
 * @property string $jabatan
 * @property Carbon $tahun
 * @property Carbon $tahun_keluar
 * @property int $sekarang
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiOrganisasi extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_organisasi';

	protected $casts = [
		'id_pegawai' => 'int',
		'sekarang' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'nama_organisasi',
		'jabatan',
		'tahun',
		'tahun_keluar',
		'sekarang'
	];
}
