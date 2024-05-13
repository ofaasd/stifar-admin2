<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wilayah
 * 
 * @property string $id_wil
 * @property string|null $nm_wil
 * @property string|null $asal_wil
 * @property string|null $kode_bps
 * @property string|null $kode_dagri
 * @property string|null $kode_keu
 * @property string|null $id_induk_wilayah
 * @property int|null $id_level_wil
 * @property string|null $id_negara
 *
 * @package App\Models
 */
class Wilayah extends Model
{
	protected $table = 'wilayah';
	protected $primaryKey = 'id_wil';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_level_wil' => 'int'
	];

	protected $fillable = [
		'nm_wil',
		'asal_wil',
		'kode_bps',
		'kode_dagri',
		'kode_keu',
		'id_induk_wilayah',
		'id_level_wil',
		'id_negara'
	];
}
