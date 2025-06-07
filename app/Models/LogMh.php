<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogMh
 * 
 * @property int $id
 * @property int $id_mhs
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class LogMh extends Model
{
	use SoftDeletes;
	protected $table = 'log_mhs';

	protected $casts = [
		'id_mhs' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_mhs',
		'status'
	];
}
