<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TblJadwalUjian
 * 
 * @property int $id
 * @property int|null $id_jadwal
 * @property Carbon|null $tanggal_uts
 * @property Carbon|null $jam_mulai_uts
 * @property Carbon|null $jam_selesai_uts
 * @property int|null $id_ruang_uts
 * @property Carbon|null $tanggal_uas
 * @property Carbon|null $jam_mulai_uas
 * @property Carbon|null $jam_selesai_uas
 * @property int|null $id_ruang_uas
 * @property int|null $ta
 * @property Carbon|null $log
 *
 * @package App\Models
 */
class TblJadwalUjian extends Model
{
	protected $table = 'tbl_jadwal_ujian';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_jadwal' => 'int',
		'id_ruang_uas' => 'int',
		'ta' => 'int',
		'log' => 'datetime'
	];

	protected $fillable = [
		'id_jadwal',
		'tanggal_uts',
		'jam_mulai_uts',
		'jam_selesai_uts',
		'id_ruang_uts',
		'tanggal_uas',
		'jam_mulai_uas',
		'jam_selesai_uas',
		'id_ruang_uas',
		'ta',
		'log'
	];
}
