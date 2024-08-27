<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
class MasterSlide extends Model
{
	protected $table = 'master_slide';
	public $timestamps = false;

	protected $fillable = [
		'gambar',
		'caption',
		'link'
	];
}
