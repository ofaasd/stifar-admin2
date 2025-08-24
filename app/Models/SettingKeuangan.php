<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SettingKeuangan
 * 
 * @property int $id
 * @property int $id_tahun
 * @property int $id_jenis
 * @property int $jumlah
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class SettingKeuangan extends Model
{
	use SoftDeletes;
	protected $table = 'setting_keuangan';

	protected $casts = [
		'id_tahun' => 'int',
		'id_prodi' => 'int',
		'id_jenis' => 'int',
		'jumlah' => 'int'
	];

	protected $fillable = [
		'id_tahun',
		'id_jenis',
		'id_prodi',
		'jumlah'
	];
}
