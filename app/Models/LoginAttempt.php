<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LoginAttempt
 * 
 * @property int $id
 * @property string $ip_address
 * @property Carbon|null $time
 * @property int $user_id
 *
 * @package App\Models
 */
class LoginAttempt extends Model
{
	protected $table = 'login_attempts';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'time' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'ip_address',
		'time',
		'user_id'
	];
}
