<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RefKategoriSurat
 * 
 * @property int $id
 * @property string $nama
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class RefKategoriSurat extends Model
{
	protected $table = 'ref_kategori_surat';

	protected $fillable = [
		'nama'
	];
}
