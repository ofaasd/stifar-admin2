<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiKegiatanLuar
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama_isntansi
 * @property string $sebagai
 * @property string $durasi
 * @property string|null $surat_tugas
 * @property string|null $bukti_kegiatan
 * @property string|null $dokumen_pendukung
 * @property string|null $link
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PegawaiKegiatanLuar extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_kegiatan_luar';

	protected $casts = [
		'id_pegawai' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'nama_instansi',
		'sebagai',
		'durasi',
		'surat_tugas',
		'bukti_kegiatan',
		'dokumen_pendukung',
		'link',
		'tanggal'
	];
}
