<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PmbNilaiTambahan
 *
 * @property int $id
 * @property int $id_peserta
 * @property string $keterangan
 * @property float $jumlah
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class PmbNilaiTambahan extends Model
{
	use SoftDeletes;
	protected $table = 'pmb_nilai_tambahan';

	protected $casts = [
		'id_peserta' => 'int',
		'nilai' => 'float'
	];

	protected $fillable = [
		'id_peserta',
		'keterangan',
		'nilai'
	];
}
