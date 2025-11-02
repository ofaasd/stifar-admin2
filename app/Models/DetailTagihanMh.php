<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailTagihanMh
 * 
 * @property int $id
 * @property int $id_tagihan
 * @property int $id_jenis
 * @property int $jumlah
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class DetailTagihanMh extends Model
{
	use SoftDeletes;
	protected $table = 'detail_tagihan_mhs';

	protected $casts = [
		'id_tagihan' => 'int',
		'id_jenis' => 'int',
		'jumlah' => 'int'
	];

	protected $fillable = [
		'id_tagihan',
		'id_jenis',
		'jumlah'
	];
}
