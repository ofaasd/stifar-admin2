<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiKompetensi
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string $bidang
 * @property string $lembaga
 * @property string|null $bukti
 * @property string|null $link
 * @property Carbon|null $tanggal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiKompetensi extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_kompetensi';

	protected $casts = [
		'id_pegawai' => 'int',
	];

	protected $fillable = [
		'id_pegawai',
		'bidang',
		'lembaga',
		'bukti',
		'link',
		'tanggal'
	];
}
