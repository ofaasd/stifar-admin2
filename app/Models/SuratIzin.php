<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratIzin
 *
 * @property int $id
 * @property int $id_dosen
 * @property Carbon $tgl_surat
 * @property string $perihal
 * @property string $keterangan
 * @property string|null $file_surat
 * @property int $dilihat
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $izin_mgr_sdm
 * @property int $izin_ka_jenjang
 * @property int|null $id_kategori
 *
 * @package App\Models
 */
class SuratIzin extends Model
{
	protected $table = 'surat_izin';

	protected $casts = [
		'id_pegawai' => 'int',
		'dilihat' => 'int',
		'izin_mgr_sdm' => 'int',
		'izin_ka_jenjang' => 'int',
		'id_kategori' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'tgl_surat',
		'perihal',
		'keterangan',
		'dokumen',
		'dilihat',
		'izin_mgr_sdm',
		'izin_ka_jenjang',
		'id_kategori'
	];
}
