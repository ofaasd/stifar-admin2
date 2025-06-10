<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogKr
 *
 * @property int $id
 * @property int $id_jadwal
 * @property int $id_mhs
 * @property int $action
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class LogKr extends Model
{
	use SoftDeletes;
	protected $table = 'log_krs';

	protected $casts = [
		'id_jadwal' => 'int',
		'id_mhs' => 'int',
		'action' => 'int'
	];

	protected $fillable = [
		'id_jadwal',
		'id_mhs',
		'id_ta',
		'action'
	];
}
