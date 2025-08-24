<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterSlide
 *
 * @property int $id
 * @property string $gambar
 * @property string $caption
 * @property string $link
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class JenisKeuangan extends Model
{
    use SoftDeletes;
	protected $table = 'jenis_keuangan';

	protected $fillable = [
		'nama',
	];
}
