<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PmbGelombang
 * 
 * @property int $id
 * @property int|null $no_gel
 * @property string|null $nama_gel
 * @property string|null $nama_gel_long
 * @property Carbon|null $tgl_mulai
 * @property Carbon|null $tgl_akhir
 * @property Carbon|null $ujian
 * @property string|null $jam_ujian
 * @property string|null $hari_ujian
 * @property Carbon|null $pengumuman
 * @property Carbon|null $reg_mulai
 * @property Carbon|null $reg_akhir
 * @property string|null $tahun
 * @property bool|null $semester
 * @property int $id_jalur
 * @property int $pmb_online
 * 
 * @property PmbJalur $pmb_jalur
 *
 * @package App\Models
 */
class PmbGelombang extends Model
{
	protected $table = 'pmb_gelombang';
	public $timestamps = false;

	protected $casts = [
		'no_gel' => 'int',
		'tgl_mulai' => 'datetime',
		'tgl_akhir' => 'datetime',
		'ujian' => 'datetime',
		'pengumuman' => 'datetime',
		'reg_mulai' => 'datetime',
		'reg_akhir' => 'datetime',
		'semester' => 'bool',
		'id_jalur' => 'int',
		'pmb_online' => 'int'
	];

	protected $fillable = [
		'no_gel',
		'nama_gel',
		'nama_gel_long',
		'tgl_mulai',
		'tgl_akhir',
		'ujian',
		'jam_ujian',
		'hari_ujian',
		'pengumuman',
		'reg_mulai',
		'reg_akhir',
		'tahun',
		'semester',
		'id_jalur',
		'pmb_online'
	];

	public function pmb_jalur()
	{
		return $this->belongsTo(PmbJalur::class, 'id_jalur');
	}
}
