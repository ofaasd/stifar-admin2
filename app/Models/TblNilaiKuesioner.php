<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblNilaiKuesioner
 * 
 * @property int $id
 * @property string $nim
 * @property int $id_kuesioner
 * @property int $id_jadwal
 * @property int $id_dosen
 * @property int $id_ta
 * @property int $nilai
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TblNilaiKuesioner extends Model
{
	use SoftDeletes;
	protected $table = 'tbl_nilai_kuesioner';

	protected $casts = [
		'id_kuesioner' => 'int',
		'id_jadwal' => 'int',
		'id_dosen' => 'int',
		'id_ta' => 'int',
		'nilai' => 'int'
	];

	protected $fillable = [
		'nim',
		'id_kuesioner',
		'id_jadwal',
		'id_dosen',
		'id_ta',
		'nilai'
	];
}
