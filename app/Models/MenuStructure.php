<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuStructure extends Model
{
    protected $table = 'gmz_menu_structure';

    protected $fillable = [
        'item_id',
        'parent_id',
        'depth',
        'left',
        'right',
        'name',
        'type',
        'post_id',
        'post_title',
        'url',
        'class',
        'menu_id',
        'menu_lang',
        'target_blank'
    ];

    public function getByMenuId($menu_id)
    {
        $menus = $this->query()->where('menu_id', $menu_id)->where('menu_lang', get_current_language())->orderBy('item_id', 'ASC')->get();
        return $menus;
    }
}
