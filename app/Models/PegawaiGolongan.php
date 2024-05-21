<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiGolongan
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama
 * @property string $no_pengantar
 * @property string $no_sk
 * @property Carbon $tanggal_sk
 * @property Carbon $tmt
 * @property string $status
 *
 * @package App\Models
 */
class PegawaiGolongan extends Model
{
	protected $table = 'pegawai_golongan';
	public $timestamps = false;

	protected $casts = [
		'id_pegawai' => 'int',
		'tanggal_sk' => 'datetime',
		'tmt' => 'datetime'
	];

	protected $fillable = [
		'id_pegawai',
		'nama',
		'no_pengantar',
		'no_sk',
		'tanggal_sk',
		'tmt',
		'status'
	];
}
