<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JamKerjaDetail
 * 
 * @property int $id
 * @property int $id_jam_kerja
 * @property int $id_pegawai
 * @property string $email
 * @property string $nama
 * @property Carbon|null $jam_senin_mulai
 * @property Carbon|null $jam_senin_selesai
 * @property string|null $jumlah_senin
 * @property Carbon|null $jam_selasa_mulai
 * @property Carbon|null $jam_selasa_selesai
 * @property string|null $jumlah_selasa
 * @property Carbon|null $jam_rabu_mulai
 * @property Carbon|null $jam_rabu_selesai
 * @property string|null $jumlah_rabu
 * @property Carbon|null $jam_kamis_mulai
 * @property Carbon|null $jam_kamis_selesai
 * @property string|null $jumlah_kamis
 * @property Carbon|null $jam_jumat_mulai
 * @property Carbon|null $jam_jumat_selesai
 * @property string|null $jumlah_jumat
 * @property Carbon|null $jam_sabtu_mulai
 * @property Carbon|null $jam_sabtu_selesai
 * @property string|null $jumlah_sabtu
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $status
 * @property Carbon $jam_minggu_mulai
 * @property Carbon $jam_minggu_selesai
 * @property string $jumlah_minggu
 *
 * @package App\Models
 */
class JamKerjaDetail extends Model
{
	protected $table = 'jam_kerja_detail';

	protected $casts = [
		'id_jam_kerja' => 'int',
		'id_pegawai' => 'int',
		'jam_senin_mulai' => 'datetime',
		'jam_senin_selesai' => 'datetime',
		'jam_selasa_mulai' => 'datetime',
		'jam_selasa_selesai' => 'datetime',
		'jam_rabu_mulai' => 'datetime',
		'jam_rabu_selesai' => 'datetime',
		'jam_kamis_mulai' => 'datetime',
		'jam_kamis_selesai' => 'datetime',
		'jam_jumat_mulai' => 'datetime',
		'jam_jumat_selesai' => 'datetime',
		'jam_sabtu_mulai' => 'datetime',
		'jam_sabtu_selesai' => 'datetime',
		'status' => 'int',
		'jam_minggu_mulai' => 'datetime',
		'jam_minggu_selesai' => 'datetime'
	];

	protected $fillable = [
		'id_jam_kerja',
		'id_pegawai',
		'email',
		'nama',
		'jam_senin_mulai',
		'jam_senin_selesai',
		'jumlah_senin',
		'jam_selasa_mulai',
		'jam_selasa_selesai',
		'jumlah_selasa',
		'jam_rabu_mulai',
		'jam_rabu_selesai',
		'jumlah_rabu',
		'jam_kamis_mulai',
		'jam_kamis_selesai',
		'jumlah_kamis',
		'jam_jumat_mulai',
		'jam_jumat_selesai',
		'jumlah_jumat',
		'jam_sabtu_mulai',
		'jam_sabtu_selesai',
		'jumlah_sabtu',
		'status',
		'jam_minggu_mulai',
		'jam_minggu_selesai',
		'jumlah_minggu'
	];
}
