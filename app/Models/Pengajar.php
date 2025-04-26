<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pengajar
 *
 * @property int $id
 * @property int $id_jadwal
 * @property int $id_dsn
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Pengajar extends Model
{
	protected $table = 'pengajars';

	protected $casts = [
		'id_jadwal' => 'int',
		'id_dsn' => 'int'
	];

	protected $fillable = [
		'id_jadwal',
		'id_dsn'
	];
}
