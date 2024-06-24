<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JabatanFungsional
 * 
 * @property int $id
 * @property string $jabatan
 *
 * @package App\Models
 */
class JabatanFungsional extends Model
{
	protected $table = 'jabatan_fungsional';
	public $timestamps = false;

	protected $fillable = [
		'jabatan'
	];
}
