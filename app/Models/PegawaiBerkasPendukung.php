<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PegawaiBerkasPendukung
 * 
 * @property int $id
 * @property int $id_pegawai
 * @property string|null $ktp
 * @property string|null $kk
 *
 * @package App\Models
 */
class PegawaiBerkasPendukung extends Model
{
	protected $table = 'pegawai_berkas_pendukung';
	public $timestamps = false;

	protected $guarded = ['id'];
}
