<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiPengabdian
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama_tempat
 * @property Carbon $tahun
 * @property string|null $tempat
 * @property string|null $link_url
 * @property string|null $dokumen
 * @property int|null $ketua
 * @property string|null $proposal
 * @property Carbon|null $created_at
 * @property Carbon|null $update_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiPengabdian extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_pengabdian';

	protected $casts = [
		'id_pegawai' => 'int',
		'update_at' => 'datetime'
	];

	protected $fillable = [
		'id_pegawai',
		'nama_tempat',
		'tahun',
		'tempat',
		'link_url',
		'dokumen',
		'ketua',
		'proposal',
		'update_at'
	];
}
