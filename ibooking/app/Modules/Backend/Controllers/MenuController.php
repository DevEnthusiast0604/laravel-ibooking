<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\MenuService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TorMorten\Eventy\Facades\Eventy;

class MenuController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = MenuService::inst();
    }

    public function deleteMenuAction(Request $request)
    {
        $response = $this->service->deleteMenu($request);
        return response()->json($response);
    }

    public function updateMenuAction(Request $request)
    {
        $response = $this->service->updateMenu($request);
        return response()->json($response);
    }

    public function index(Request $request)
    {
        $menuLocations = Eventy::filter('gmz_menu_locations', admin_config('menu_location'));
        $currentLocation = '';

        $listMenus = $this->service->getListMenus();
        $menuID = $request->get('menu_id', 'none');

        $menuObject = [];
        if ($menuID == 'none') {
            if ($listMenus->count() > 0) {
                $menuID = $listMenus[0]->menu_id;
            }
        }

        if ($menuID != 'none') {
            $menuObject = $this->service->getMenuByID($menuID);
            if (!empty($menuObject)) {
                $currentLocation = $menuObject->menu_position;
            }
        }

        $menuStructureItems = $this->service->getMenuItemsByMenuID($menuID);
        $menuStructureItems = flatten_menu_data($menuStructureItems);

        return $this->getView($this->getFolderView('menu.index'), [
            'menuObject' => $menuObject,
            'listMenus' => $listMenus,
            'menuID' => $menuID,
            'menuStructureItems' => $menuStructureItems,
            'menuLocations' => $menuLocations,
            'currentLocation' => $currentLocation
        ]);
    }
}