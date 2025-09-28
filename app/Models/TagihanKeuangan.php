<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TagihanKeuangan
 *
 * @property int $id
 * @property int $id_tahun
 * @property int $total
 * @property int $total_bayar
 * @property int $status
 * @property string $nim
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TagihanKeuangan extends Model
{
	use SoftDeletes;
	protected $table = 'tagihan_keuangan';

	protected $casts = [
		'id_tahun' => 'int',
		'total' => 'int',
		'total_bayar' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_tahun',
		'total',
		'total_bayar',
        'angkatan',
		'status',
		'is_pubish',
        'batas_waktu',
		'id_prodi',
		'nim',
		'tipe_bayar',
	];
}
