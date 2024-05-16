<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblKunci
 *
 * @property int $id
 * @property int $id_soal
 * @property string $kunci
 *
 * @package App\Models
 */
class TblKunci extends Model
{
	protected $table = 'tbl_kunci';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_soal' => 'int'
	];

	protected $fillable = [
		'id_soal',
		'kunci'
	];
}
