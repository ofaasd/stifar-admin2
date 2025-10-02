<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RekeningKoranTemp
 * 
 * @property int $id
 * @property Carbon|null $post_date
 * @property Carbon|null $eff_date
 * @property string|null $cheque_no
 * @property string|null $description
 * @property int|null $debit
 * @property int|null $credit
 * @property int|null $balance
 * @property string|null $transaction
 * @property string|null $ref_no
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deeted_at
 *
 * @package App\Models
 */
class RekeningKoranTemp extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'rekening_koran_temp';
	

	protected $casts = [
		'post_date' => 'datetime:Y-m-d',
		'eff_date' => 'datetime:Y-m-d',
		'debit' => 'int',
		'credit' => 'int',
		'balance' => 'int',
		'status' => 'int',
		'deleted_at' => 'datetime'
	];

	protected $fillable = [
		'post_date',
		'eff_date',
		'cheque_no',
		'description',
		'debit',
		'credit',
		'balance',
		'transaction',
		'ref_no',
		'status',
		'deeted_at'
	];
}
