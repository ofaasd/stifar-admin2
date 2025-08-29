<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LaporPembayaran
 * 
 * @property int $id
 * @property string $nim_mahasiswa
 * @property Carbon $tanggal_bayar
 * @property string $atas_nama
 * @property string $bukti_bayar
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class LaporPembayaran extends Model
{
	use SoftDeletes;
	protected $table = 'lapor_pembayaran';

	protected $casts = [
		'tanggal_bayar' => 'datetime'
	];

	protected $fillable = [
		'nim_mahasiswa',
		'tanggal_bayar',
		'atas_nama',
		'bukti_bayar',
		'status'
	];
}
