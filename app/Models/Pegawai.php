<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pegawai
 * 
 * @property int $id
 * @property string|null $npp
 * @property string|null $nama
 * @property string|null $usrnm
 * @property string|null $paswd
 * @property int $status
 *
 * @package App\Models
 */
class Pegawai extends Model
{
	protected $table = 'pegawai';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'npp',
		'nama',
		'usrnm',
		'paswd',
		'status'
	];
}
