<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiRepository
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama_file
 * @property Carbon $tanggal
 * @property string $dokumen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiRepository extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_repository';

	protected $casts = [
		'id_pegawai' => 'int',
	];

	protected $fillable = [
		'id_pegawai',
		'nama_file',
		'tanggal',
		'dokumen'
	];
}
