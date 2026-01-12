<?php
namespace App\Helpers;
use App\Models\Menu;

class MenuHelper {
    public static function getSidebarMenu() {
        return Menu::with(['children' => function($query) {
                        $query->with('children')->orderBy('order', 'asc');
                    }])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->get();
    }
}