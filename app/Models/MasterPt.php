<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterPt
 *
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $alamat
 * @property string|null $logo
 * @property string|null $notelp
 * @property string|null $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MasterPt extends Model
{
	use SoftDeletes;
	protected $table = 'master_pt';

	protected $fillable = [
		'nama',
		'deskripsi',
		'lat',
		'lng',
		'alamat',
		'logo',
		'notelp',
		'email',
	];
}
