<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkingHour
 * 
 * @property int $id
 * @property int $user_id
 * @property int $working_start
 * @property int $working_end
 * @property string $day
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class WorkingHour extends Model
{
	use SoftDeletes;
	protected $table = 'working_hour';

	protected $casts = [
		'user_id' => 'int',
		'working_start' => 'int',
		'working_end' => 'int'
	];

	protected $fillable = [
		'user_id',
		'working_start',
		'working_end',
		'day'
	];
}
