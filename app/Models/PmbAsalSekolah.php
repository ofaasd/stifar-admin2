<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PmbAsalSekolah
 * 
 * @property int $id
 * @property int $id_peserta
 * @property string|null $asal_sekolah
 * @property string|null $jurusan
 * @property string|null $akreditasi
 * @property string|null $alamat
 * @property string|null $provinsi_id
 * @property string|null $kota_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PmbAsalSekolah extends Model
{
	use SoftDeletes;
	protected $table = 'pmb_asal_sekolah';

	protected $casts = [
		'id_peserta' => 'int'
	];

	protected $fillable = [
		'id_peserta',
		'asal_sekolah',
		'jurusan',
		'akreditasi',
		'alamat',
		'provinsi_id',
		'kota_id'
	];
}
