<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BiayaPendaftaran
 *
 * @property int $id
 * @property int $id_prodi
 * @property int $tahun_ajaran
 * @property int $jumlah
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class BiayaPendaftaran extends Model
{
	use SoftDeletes;
	protected $table = 'biaya_pendaftaran';

	protected $casts = [
		'id_prodi' => 'int',
		'tahun_ajaran' => 'int',
		'jumlah' => 'int'
	];

	protected $fillable = [
		'id_prodi',
		'tahun_ajaran',
		'jumlah',
		'rpl',
		'gelombang',
		'registrasi',
		'tahap_awal',
		'upp_smk',
		'upp',
	];
}
