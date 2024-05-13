<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PmbPesertaTemp
 * 
 * @property int $id
 * @property string|null $COL1
 * @property string|null $COL2
 * @property string|null $COL3
 * @property string|null $COL4
 * @property string|null $COL5
 * @property string|null $COL6
 * @property string|null $COL7
 * @property string|null $COL8
 * @property string|null $COL9
 * @property string|null $COL10
 * @property string|null $COL11
 * @property string|null $COL12
 * @property string|null $COL13
 * @property string|null $COL14
 * @property string|null $COL15
 * @property string|null $COL16
 * @property string|null $COL17
 * @property string|null $COL18
 * @property string|null $COL19
 * @property string|null $COL20
 * @property string|null $COL21
 * @property string|null $COL22
 * @property string|null $COL23
 *
 * @package App\Models
 */
class PmbPesertaTemp extends Model
{
	protected $table = 'pmb_peserta_temp';
	public $timestamps = false;

	protected $fillable = [
		'COL1',
		'COL2',
		'COL3',
		'COL4',
		'COL5',
		'COL6',
		'COL7',
		'COL8',
		'COL9',
		'COL10',
		'COL11',
		'COL12',
		'COL13',
		'COL14',
		'COL15',
		'COL16',
		'COL17',
		'COL18',
		'COL19',
		'COL20',
		'COL21',
		'COL22',
		'COL23'
	];
}
