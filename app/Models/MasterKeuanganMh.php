<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MasterKeuanganMh
 * 
 * @property int $id
 * @property int $id_mahasiswa
 * @property int $id_tahun_ajaran
 * @property int $krs
 * @property int $uts
 * @property int $uas
 *
 * @package App\Models
 */
class MasterKeuanganMh extends Model
{
	protected $table = 'master_keuangan_mhs';
	public $timestamps = false;

	protected $casts = [
		'id_mahasiswa' => 'int',
		'id_tahun_ajaran' => 'int',
		'krs' => 'int',
		'uts' => 'int',
		'uas' => 'int'
	];

	protected $fillable = [
		'id_mahasiswa',
		'id_tahun_ajaran',
		'krs',
		'uts',
		'uas'
	];
}
