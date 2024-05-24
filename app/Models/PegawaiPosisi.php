<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiPosisi
 * 
 * @property int $id
 * @property int $id_jenis_pegawai
 * @property string $nama
 * @property string $kode
 *
 * @package App\Models
 */
class PegawaiPosisi extends Model
{
	protected $table = 'pegawai_posisi';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_jenis_pegawai' => 'int'
	];

	protected $fillable = [
		'id_jenis_pegawai',
		'nama',
		'kode'
	];
}
