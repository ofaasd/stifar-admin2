<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JamKerjaMaster
 * 
 * @property int $id
 * @property string $judul
 * @property Carbon $mulai
 * @property Carbon $selesai
 * @property int $status
 *
 * @package App\Models
 */
class JamKerjaMaster extends Model
{
	protected $table = 'jam_kerja_master';
	public $timestamps = false;

	protected $casts = [
		'mulai' => 'datetime',
		'selesai' => 'datetime',
		'status' => 'int'
	];

	protected $fillable = [
		'judul',
		'mulai',
		'selesai',
		'status'
	];
}
