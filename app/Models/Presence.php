<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Presence
 * 
 * @property int $id
 * @property int $is_remote
 * @property int $user_id
 * @property Carbon $day
 * @property int $start
 * @property string $lat_start
 * @property string $long_start
 * @property string $ip_start
 * @property string $browser_start
 * @property string|null $isp_start
 * @property string|null $image_start
 * @property int|null $start_late
 * @property int|null $end
 * @property string|null $lat_end
 * @property string|null $long_end
 * @property string|null $ip_end
 * @property string|null $browser_end
 * @property string|null $isp_end
 * @property string|null $image_end
 * @property int|null $start_marked_by_admin
 * @property int|null $end_marked_by_system
 * @property int|null $overtime
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Presence extends Model
{
	use SoftDeletes;
	protected $table = 'presences';

	protected $casts = [
		'is_remote' => 'int',
		'user_id' => 'int',
		'day' => 'datetime',
		'start' => 'int',
		'start_late' => 'int',
		'end' => 'int',
		'start_marked_by_admin' => 'int',
		'end_marked_by_system' => 'int',
		'overtime' => 'int'
	];

	protected $fillable = [
		'is_remote',
		'user_id',
		'day',
		'start',
		'lat_start',
		'long_start',
		'ip_start',
		'browser_start',
		'isp_start',
		'image_start',
		'start_late',
		'end',
		'lat_end',
		'long_end',
		'ip_end',
		'browser_end',
		'isp_end',
		'image_end',
		'start_marked_by_admin',
		'end_marked_by_system',
		'overtime'
	];
}
