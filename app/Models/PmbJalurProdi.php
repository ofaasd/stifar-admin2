<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PmbJalurProdi
 *
 * @property int $id
 * @property int $id_jalur
 * @property int $id_program_studi
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property string|null $keterangan
 *
 * @property PmbJalur $pmb_jalur
 * @property ProgramStudi $program_studi
 *
 * @package App\Models
 */
class PmbJalurProdi extends Model
{
	use SoftDeletes;
	protected $table = 'pmb_jalur_prodi';


	protected $fillable = [
		'id_jalur',
		'id_program_studi',
		'keterangan'
	];

	public function pmb_jalur()
	{
		return $this->belongsTo(PmbJalur::class, 'id_jalur');
	}

	public function program_studi()
	{
		return $this->belongsTo(ProgramStudi::class, 'id_program_studi');
	}
}
