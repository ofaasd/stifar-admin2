<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiBagian
 * 
 * @property int $id
 * @property int $id_unit_kerja
 * @property string $nama_bagian
 *
 * @package App\Models
 */
class PegawaiBagian extends Model
{
	protected $table = 'pegawai_bagian';
	public $timestamps = false;

	protected $casts = [
		'id_unit_kerja' => 'int'
	];

	protected $fillable = [
		'id_unit_kerja',
		'nama_bagian'
	];
}
