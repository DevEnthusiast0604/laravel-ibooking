<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'gmz_menu';

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'menu_title', 'menu_position'
    ];

    public function hasMenuLocation($menu_type = 'primary'){
        $menu = $this->query()->selectRaw('COUNT(*) as row_count')->where('menu_position', $menu_type)->get()->first();
        if(!empty($menu)){
            return true;
        }
        return false;
    }

    public function getMenuByLocation($location){
        $menu = $this->query()->where('menu_position', $location)->get()->first();
        return $menu;
    }

    public function getAllMenus($data = [])
    {
        $sql = $this->query()->select();
        $results = $sql->get();
        return (!empty($results) && is_object($results)) ? $results : false;
    }

    public function getById($menu_id)
    {
        $menu = $this->query()->where('menu_id', $menu_id)->get()->first();
        return $menu;
    }
}
