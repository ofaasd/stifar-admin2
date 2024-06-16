<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PegawaiBiodatum
 *
 * @property int $id
 * @property int|null $id_pegawai
 * @property int $id_posisi
 * @property int $id_posisi_detail
 * @property int|null $id_progdi
 * @property string|null $ktp
 * @property Carbon|null $expired_ktp
 * @property string $npp
 * @property string $nidn
 * @property string|null $nip_pns
 * @property string $nama_lengkap
 * @property string|null $gelar_depan
 * @property string|null $gelar_belakang
 * @property string|null $tempat_lahir
 * @property Carbon|null $tanggal_lahir
 * @property string|null $agama
 * @property int|null $jenis_kelamin
 * @property string|null $alamat
 * @property string|null $notelp
 * @property string|null $nohp
 * @property string|null $nama_pasangan
 * @property int|null $jumlah_anak
 * @property string|null $pekerjaan_pasangan
 * @property string|null $email1
 * @property string|null $email2
 * @property string|null $blog
 * @property string|null $citation
 * @property string|null $status_pegawai
 * @property string|null $no_ktp
 * @property string|null $no_kk
 * @property Carbon|null $tgl_lahir_pasangan
 * @property string $path_foto
 * @property string|null $foto
 * @property string|null $no_bpjs_kesehatan
 * @property string|null $no_bpjs_ketenagakerjaan
 * @property int $status_nikah
 * @property int $homebase
 * @property string|null $golongan_darah
 * @property string|null $provinsi
 * @property string|null $kotakab
 * @property string|null $kecamatan
 * @property string|null $kelurahan
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class PegawaiBiodatum extends Model
{
	use SoftDeletes;
	protected $table = 'pegawai_biodata';

	protected $casts = [
		'id_pegawai' => 'int',
		'id_posisi_pegawai' => 'int',
		'id_progdi' => 'int',
		'expired_ktp' => 'datetime',
		'tanggal_lahir' => 'datetime',
		'jumlah_anak' => 'int',
		'tgl_lahir_pasangan' => 'datetime',
		'status_nikah' => 'int',
		'homebase' => 'int'
	];

	protected $fillable = [
		'id_pegawai',
		'id_posisi_pegawai',
		'id_progdi',
		'ktp',
		'expired_ktp',
		'npp',
		'nidn',
		'nip_pns',
		'nama_lengkap',
		'gelar_depan',
		'gelar_belakang',
		'tempat_lahir',
		'tanggal_lahir',
		'agama',
		'jenis_kelamin',
		'alamat',
		'notelp',
		'nohp',
		'nama_pasangan',
		'jumlah_anak',
		'pekerjaan_pasangan',
		'email1',
		'email2',
		'blog',
		'citation',
		'status_pegawai',
		'no_ktp',
		'no_kk',
		'tgl_lahir_pasangan',
		'path_foto',
		'foto',
		'no_bpjs_kesehatan',
		'no_bpjs_ketenagakerjaan',
		'status_nikah',
		'homebase',
		'golongan_darah',
		'provinsi',
		'kotakab',
		'kecamatan',
		'kelurahan',
        'user_id'
	];
}
