<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tagihan
 * 
 * @property int $id
 * @property string $nim
 * @property string|null $gelombang
 * @property int $total_bayar
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Tagihan extends Model
{
	use SoftDeletes;
	protected $table = 'tagihan';

	protected $casts = [
		'total_bayar' => 'int'
	];

	protected $fillable = [
		'nim',
		'gelombang',
		'total_bayar'
	];
}
