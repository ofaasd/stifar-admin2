<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterPtAtribut
 * 
 * @property int $id
 * @property int $id_pt
 * @property string $nama
 * @property string|null $file
 * @property string|null $url
 * @property Carbon|null $created_at
 * @property Carbon|null $update_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MasterPtAtribut extends Model
{
	use SoftDeletes;
	protected $table = 'master_pt_atribut';
	public $timestamps = false;

	protected $casts = [
		'id_pt' => 'int',
		'update_at' => 'datetime'
	];

	protected $fillable = [
		'id_pt',
		'nama',
		'file',
		'url',
		'update_at'
	];
}
