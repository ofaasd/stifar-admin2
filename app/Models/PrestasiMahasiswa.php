<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PrestasiMahasiswa
 * 
 * @property int $id
 * @property int $mahasiswa_id
 * @property string $nama_prestasi
 * @property int $tahun
 * @property string $tingkat
 * @property string|null $deskripsi
 * 
 * @property Mahasiswa $mahasiswa
 *
 * @package App\Models
 */
class PrestasiMahasiswa extends Model
{
	protected $table = 'prestasi_mahasiswa';
	public $timestamps = false;

	protected $casts = [
		'mahasiswa_id' => 'int',
		'tahun' => 'int'
	];

	protected $fillable = [
		'mahasiswa_id',
		'nama_prestasi',
		'tahun',
		'tingkat',
		'deskripsi'
	];

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class);
	}
}
