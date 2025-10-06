<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbPembayaran
 * 
 * @property int $id
 * @property string $nim
 * @property int $jumlah
 * @property string|null $keterangan
 * @property int $status
 * @property int|null $id_rekening_koran
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TbPembayaran extends Model
{
	use SoftDeletes;
	protected $table = 'tb_pembayaran';

	protected $casts = [
		'jumlah' => 'int',
		'status' => 'int',
		'id_rekening_koran' => 'int',
		'tanggal_bayar' => 'datetime:Y-m-d',
	];

	protected $fillable = [
		'nim',
		'jumlah',
		'keterangan',
		'status',
		'id_rekening_koran',
		'tanggal_bayar',
		'jenis_pembayaran'
	];
}
