<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BankDataVa
 * 
 * @property int $id
 * @property string $no_va
 * @property string|null $keterangan
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class BankDataVa extends Model
{
	use SoftDeletes;
	protected $table = 'bank_data_va';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'no_va',
		'keterangan',
		'status',
		'nopen',
	];
}
