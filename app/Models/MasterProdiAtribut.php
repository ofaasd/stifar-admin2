<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterProdiAtribut
 * 
 * @property int $id
 * @property int $id_prodi
 * @property string $nama
 * @property string|null $file
 * @property string|null $url
 * @property int|null $tahun
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $keterangan
 *
 * @package App\Models
 */
class MasterProdiAtribut extends Model
{
	use SoftDeletes;
	protected $table = 'master_prodi_atribut';

	protected $casts = [
		'id_prodi' => 'int',
		'tahun' => 'int',
		'keterangan' => 'int'
	];

	protected $fillable = [
		'id_prodi',
		'nama',
		'file',
		'url',
		'tahun',
		'keterangan'
	];
}
