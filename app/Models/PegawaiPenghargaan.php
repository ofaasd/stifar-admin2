<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiPenghargaan
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama_penghargaan
 * @property string|null $penyelenggara
 * @property Carbon|null $tanggal
 * @property string|null $file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiPenghargaan extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_penghargaan';

	protected $casts = [
		'id_pegawai' => 'int',
		'tanggal' => 'datetime'
	];

	protected $fillable = [
		'id_pegawai',
		'nama_penghargaan',
		'penyelenggara',
		'tanggal',
		'file'
	];
}
