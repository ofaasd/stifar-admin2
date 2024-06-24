<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiKaryaIlmiah
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $judul
 * @property string $nama_majalah
 * @property string $volume
 * @property string $nomor
 * @property string $bulan
 * @property Carbon $tahun
 * @property string $link_url
 * @property string|null $dokumen
 *
 * @package App\Models
 */
class PegawaiKaryaIlmiah extends Model
{
	protected $table = 'pegawai_karya_ilmiah';

	protected $casts = [
		'id_pegawai' => 'int',
	];

	protected $fillable = [
		'id_pegawai',
		'judul',
		'nama_majalah',
		'volume',
		'nomor',
		'bulan',
		'tahun',
		'link_url',
		'dokumen'
	];
}
