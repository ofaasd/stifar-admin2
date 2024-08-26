<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserGuest
 *
 * @property int $id
 * @property string $nama
 * @property string $email
 * @property Carbon $tgl_lahir
 * @property string $password
 * @property string|null $no_pendaftaran
 *
 * @package App\Models
 */
class UserGuest extends Model
{
	protected $table = 'user_guest';
	public $timestamps = false;

	protected $casts = [
		'tgl_lahir' => 'date'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nama',
		'email',
		'tgl_lahir',
		'password',
		'no_pendaftaran'
	];
}
