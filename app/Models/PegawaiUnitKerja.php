<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiUnitKerja
 * 
 * @property int $id
 * @property string $unit_kerja
 *
 * @package App\Models
 */
class PegawaiUnitKerja extends Model
{
	protected $table = 'pegawai_unit_kerja';
	public $timestamps = false;

	protected $fillable = [
		'unit_kerja'
	];
}
