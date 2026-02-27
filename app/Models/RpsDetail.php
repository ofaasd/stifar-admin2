<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RpsDetail
 * 
 * @property int $id
 * @property int $mata_kuliah_id
 * @property int $tahun_ajaran_id
 * @property string $file_rps
 * @property Carbon|null $rps_log
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MataKuliah $mata_kuliah
 * @property TahunAjaran $tahun_ajaran
 *
 * @package App\Models
 */
class RpsDetail extends Model
{
	protected $table = 'rps_details';

	protected $casts = [
		'mata_kuliah_id' => 'int',
		'tahun_ajaran_id' => 'int',
		'rps_log' => 'datetime'
	];

	protected $fillable = [
		'mata_kuliah_id',
		'tahun_ajaran_id',
		'file_rps',
		'rps_log'
	];

	public function mata_kuliah()
	{
		return $this->belongsTo(MataKuliah::class);
	}

	public function tahun_ajaran()
	{
		return $this->belongsTo(TahunAjaran::class);
	}
}
