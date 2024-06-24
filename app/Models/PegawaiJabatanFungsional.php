<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiJabatanFungsional
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string $jabatan_fungsional_sekarang
 * @property string $no_sk_fungsional
 * @property Carbon $tgl_sk_fungsional
 * @property Carbon|null $tmt_sk_fungsional
 * @property string $kum
 * @property int $status
 * @property string $dokumen
 *
 * @package App\Models
 */
class PegawaiJabatanFungsional extends Model
{
	protected $table = 'pegawai_jabatan_fungsional';
	public $timestamps = false;

	protected $casts = [
		'id_pegawai' => 'int',
		'tgl_sk_fungsional' => 'datetime',
		'tmt_sk_fungsional' => 'datetime',
		'status' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'jabatan_fungsional_sekarang',
		'no_sk_fungsional',
		'tgl_sk_fungsional',
		'tmt_sk_fungsional',
		'kum',
		'status',
		'dokumen'
	];
}
