<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JabatanStruktural
 * 
 * @property int $id
 * @property int $unit_kerja_id
 * @property string $bagian
 * @property string $jabatan
 * @property int $prodi_id
 *
 * @package App\Models
 */
class JabatanStruktural extends Model
{
	protected $table = 'jabatan_struktural';
	public $timestamps = false;

	protected $casts = [
		'unit_kerja_id' => 'int',
		'prodi_id' => 'int'
	];

	protected $fillable = [
		'unit_kerja_id',
		'bagian',
		'jabatan',
		'prodi_id'
	];
}
