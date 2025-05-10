<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuktiRegistrasi
 * 
 * @property int $id
 * @property string|null $nopen
 * @property string|null $norek_pengirim
 * @property string|null $an_pengirim
 * @property Carbon|null $tgl_tf
 * @property string|null $bukti
 * @property int|null $is_online
 * @property Carbon|null $created_at
 * @property int $verifikasi
 * @property string|null $no_refrensi
 *
 * @package App\Models
 */
class BuktiRegistrasi extends Model
{
	protected $table = 'bukti_registrasi';
	public $timestamps = false;

	protected $casts = [
		'tgl_tf' => 'datetime',
		'is_online' => 'int',
		'verifikasi' => 'int'
	];

	protected $fillable = [
		'nopen',
		'norek_pengirim',
		'an_pengirim',
		'tgl_tf',
		'bukti',
		'is_online',
		'verifikasi',
		'no_refrensi'
	];
}
