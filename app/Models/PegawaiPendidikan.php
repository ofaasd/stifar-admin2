<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiPendidikan
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $jenjang
 * @property string $universitas
 * @property string $jurusan
 * @property string|null $tempat
 * @property string|null $no_ijazah
 * @property Carbon|null $tanggal_ijazah
 * @property Carbon|null $tahun
 * @property string|null $ijazah
 * @property string|null $jenjang_profesi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiPendidikan extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_pendidikan';

	protected $casts = [
		'id_pegawai' => 'int',
	];

	protected $fillable = [
		'id_pegawai',
		'jenjang',
		'universitas',
		'jurusan',
		'tempat',
		'no_ijazah',
		'tanggal_ijazah',
		'tahun',
		'dokumen',
		'jenjang_profesi'
	];
}
