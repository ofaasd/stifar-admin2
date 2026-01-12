<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * 
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 * @property string|null $url
 * @property string|null $icon
 * @property string $permission_name
 * @property int $order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Menu|null $menu
 * @property Collection|Menu[] $menus
 *
 * @package App\Models
 */
class Menu extends Model
{
	protected $table = 'menus';

	protected $casts = [
		'parent_id' => 'int',
		'order' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'parent_id',
		'title',
		'url',
		'icon',
		'permission_name',
		'order',
		'is_active'
	];

	public function children()
	{
		return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
	}

	public function parent()
	{
		return $this->belongsTo(Menu::class, 'parent_id');
	}
}
