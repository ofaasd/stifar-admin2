<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PmbTum
 * 
 * @property int $id
 * @property int $kode
 * @property int $awal
 * @property int $akhir
 * @property int $is_active
 *
 * @package App\Models
 */
class PmbTum extends Model
{
	protected $table = 'pmb_ta';
	public $timestamps = false;

	protected $casts = [
		'kode' => 'int',
		'awal' => 'int',
		'akhir' => 'int',
		'is_active' => 'int'
	];

	protected $fillable = [
		'kode',
		'awal',
		'akhir',
		'is_active'
	];
}
