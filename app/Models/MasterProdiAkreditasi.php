<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterProdiAkreditasi
 *
 * @property int $id
 * @property int $id_prodi
 * @property float $nilai
 * @property string $status
 * @property string|null $capaian
 * @property string|null $dokumen
 * @property int|null $tahun
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MasterProdiAkreditasi extends Model
{
	use SoftDeletes;
	protected $table = 'master_prodi_akreditasi';

	protected $casts = [
		'id_prodi' => 'int',
		'nilai' => 'float',
		'tahun' => 'int'
	];

	protected $fillable = [
		'id_prodi',
		'nilai',
		'status',
		'capaian',
		'dokumen',
		'tahun',
        'lembaga',
        'file'
	];
}
