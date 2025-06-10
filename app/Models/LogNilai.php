<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogNilai
 *
 * @property int $id
 * @property int $id_jadwal
 * @property int $id_tahun
 * @property string $nim
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class LogNilai extends Model
{
	use SoftDeletes;
	protected $table = 'log_nilai';

	protected $casts = [
		'id_jadwal' => 'int',
		'id_tahun' => 'int',
		'id_mhs' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_jadwal',
		'id_tahun',
		'id_mhs',
		'status'
	];
}
