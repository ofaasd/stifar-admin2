<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblPilihanSoal
 *
 * @property int $id
 * @property int $id_soal
 * @property string $huruf
 * @property string $pilihan
 *
 * @package App\Models
 */
class TblPilihanSoal extends Model
{
	protected $table = 'tbl_pilihan_soal';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_soal' => 'int'
	];

	protected $fillable = [
		'id_soal',
		'huruf',
		'pilihan'
	];
}
