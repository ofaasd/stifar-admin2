<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbJadwalAbsensi
 *
 * @property int $id
 * @property int $id_pegawai
 * @property Carbon $jam_masuk
 * @property Carbon $jam_keluar
 * @property string $id_fingerprint
 * @property int $id_ta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TbJadwalAbsensi extends Model
{
	use SoftDeletes;
	protected $table = 'tb_jadwal_absensi';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'id_pegawai' => 'int',
		'id_ta' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'jam_masuk',
		'jam_keluar',
		'id_fingerprint',
		'id_ta'
	];
}
