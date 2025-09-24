<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MahasiswaBerkasPendukung
 * 
 * @property int $id
 * @property string|null $nim
 * @property string|null $kk
 * @property string|null $ktp
 * @property string|null $akte
 * @property string|null $ijazah_depan
 * @property string|null $ijazah_belakang
 * @property string|null $foto_sistem
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MahasiswaBerkasPendukung extends Model
{
	use SoftDeletes;
	protected $table = 'mahasiswa_berkas_pendukung';

	protected $fillable = [
		'nim',
		'id_ta',
		'kk',
		'ktp',
		'akte',
		'ijazah_depan',
		'ijazah_belakang',
		'foto_sistem'
	];
}
