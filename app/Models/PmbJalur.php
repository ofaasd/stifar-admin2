<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PmbJalur
 *
 * @property int $id
 * @property string $nama
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $pilihan
 *
 * @property Collection|PmbGelombang[] $pmb_gelombangs
 * @property Collection|PmbJalurProdi[] $pmb_jalur_prodis
 *
 * @package App\Models
 */
class PmbJalur extends Model
{
	use SoftDeletes;
	protected $table = 'pmb_jalur';

	protected $fillable = [
		'nama',
		'keterangan',
		'pilihan'
	];

	public function pmb_gelombangs()
	{
		return $this->hasMany(gelombang::class, 'id_jalur');
	}

	public function pmb_jalur_prodis()
	{
		return $this->hasMany(PmbJalurProdi::class, 'id_jalur');
	}
}
