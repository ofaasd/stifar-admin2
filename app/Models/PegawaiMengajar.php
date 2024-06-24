<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiMengajar
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $tahun
 * @property string $institusi
 * @property string $prodi
 * @property string $mata_kuliah
 * @property string|null $rombel
 * @property string|null $kelas
 * @property int|null $sks
 * @property string|null $dokumen
 * @property string|null $sk_mengajar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiMengajar extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_mengajar';

	protected $casts = [
		'id_pegawai' => 'int',
		'tahun' => 'int',
		'sks' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'tahun',
		'institusi',
		'prodi',
		'mata_kuliah',
		'rombel',
		'kelas',
		'sks',
		'dokumen',
		'sk_mengajar'
	];
}
