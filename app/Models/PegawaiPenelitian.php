<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiPenelitian
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $nomor
 * @property string $judul
 * @property int $id_fakultas
 * @property string $jenis_penelitian
 * @property Carbon $tahun
 * @property string $sumber_dana
 * @property int $dana
 * @property string $no_surat
 * @property string $penyelenggara
 * @property string $ketua
 * @property string $anggota
 * @property string $dokumen
 * @property string $proposal
 * @property string $lap_kemajuan
 * @property string $lap_keuangan
 * @property string $lap_akhir
 *
 * @package App\Models
 */
class PegawaiPenelitian extends Model
{
	protected $table = 'pegawai_penelitian';

	protected $casts = [
		'id_pegawai' => 'int',
		'dana' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'nomor',
		'judul',
		'fakultas',
		'jenis_penelitian',
		'tahun',
		'sumber_dana',
		'dana',
		'no_surat',
		'penyelenggara',
		'ketua',
		'anggota',
		'dokumen',
		'proposal',
		'lap_kemajuan',
		'lap_keuangan',
		'lap_akhir'
	];
}
