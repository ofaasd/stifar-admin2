<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MasterRuang
 *
 * @property int $id
 * @property string $nama_ruang
 * @property int $kapasitas
 * @property string $luas
 * @property Carbon $log_update
 *
 * @package App\Models
 */
class MasterRuang extends Model
{
	protected $table = 'master_ruang';
	public $timestamps = false;

	protected $casts = [
		'kapasitas' => 'int',
		'log_update' => 'datetime'
	];

	protected $fillable = [
		'nama_ruang',
		'kapasitas',
		'lantai_id',
		'jenis_id',
		'luas',
		'log_update'
	];
}
