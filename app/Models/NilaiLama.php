<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NilaiLama
 * 
 * @property int $id
 * @property string $kode_mk
 * @property string $nim
 * @property float $uts
 * @property float $uas
 * @property float $tugas
 * @property float $nilai
 * @property float $nilai_huruf
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class NilaiLama extends Model
{
	use SoftDeletes;
	protected $table = 'nilai_lama';

	protected $casts = [
		'uts' => 'float',
		'uas' => 'float',
		'tugas' => 'float',
		'nilai' => 'float',
		'nilai_huruf' => 'float'
	];

	protected $fillable = [
		'kode_mk',
		'nim',
		'uts',
		'uas',
		'tugas',
		'nilai',
		'nilai_huruf'
	];
}
