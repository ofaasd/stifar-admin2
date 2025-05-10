<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblSoalKuesioner
 *
 * @property int $id
 * @property int $id_ta
 * @property string $no_soal
 * @property string $soal
 * @property int $category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TblSoalKuesioner extends Model
{
	use SoftDeletes;
	protected $table = 'tbl_soal_kuesioner';

	protected $casts = [
		'id_ta' => 'int',
		'category' => 'int'
	];

	protected $fillable = [
		'id_ta',
		'no_soal',
		'soal',
		'category',
        'tipe_soal'
	];
}
