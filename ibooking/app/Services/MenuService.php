<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use App\Repositories\MenuStructureRepository;
use Illuminate\Http\Request;

class MenuService extends AbstractService
{
    private static $_inst;
    protected $repository;

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }

    public function __construct()
    {
        $this->repository = MenuRepository::inst();
    }

    public function deleteMenu(Request $request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);

        $menu_id = isset($params['menuID']) ? $params['menuID'] : '';
        $menu_hashing = isset($params['menuHashing']) ? $params['menuHashing'] : '';

        if (!gmz_compare_hashing($menu_id, $menu_hashing)) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This menu is invalid')
            ];
        }

        $menuObject = $this->repository->find($menu_id);

        if (!empty($menuObject) && is_object($menuObject)) {
            $deleted = $this->repository->delete($menu_id);
            $menuStructureRepo = MenuStructureRepository::inst();
            $menuStructureRepo->deleteByWhere([
                'menu_id' => $menu_id
            ]);

            if ($deleted) {
                return [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('This Menu is deleted'),
                    'redirect' => dashboard_url('menu')
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not delete this menu')
                ];
            }
        } else {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This Menu is invalid')
            ];
        }
    }

    public function updateMenu(Request $request)
    {
        $menu_id = $request->post('menu_id');
        $menu_name = $request->post('menu_name');
        $menu_location = $request->post('menu_location');
        $menu_structure = $request->post('menu_structure');

        if (!empty($menu_name)) {
            $menu_structure = json_decode($menu_structure);
            if (!empty($menu_location)) {
                $this->repository->updateByWhere(
                    ['menu_position' => $menu_location],
                    ['menu_position' => '']
                );
            }
            if (empty($menu_id)) {
                $new_menu = $this->repository->create([
                    'menu_title' => $menu_name,
                    'menu_position' => $menu_location,
                    'created_at' => time()
                ]);
                if (!empty($new_menu)) {
                    $this->_updateMenuStructure($new_menu, $menu_structure);
                }

                if ($new_menu) {
                    return [
                        'status' => 1,
                        'title' => __('System Alert'),
                        'message' => __('Created menu successfully'),
                        'redirect' => dashboard_url('menu?menu_id=' . $new_menu)
                    ];
                }
            } else {
                $this->repository->update($menu_id, [
                    'menu_title' => $menu_name,
                    'menu_position' => $menu_location,
                ]);

                if (!empty($menu_structure)) {
                    $this->_updateMenuStructure($menu_id, $menu_structure);
                }

                return [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated menu successfully'),
                    'redirect' => dashboard_url('menu?menu_id=' . $menu_id)
                ];
            }
        } else {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Please create new menu before doing it'),
                'redirect' => dashboard_url('menu?menu_id=' . $menu_id)
            ];
        }

        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Data is invalid'),
            'redirect' => dashboard_url('menu?menu_id=' . $menu_id)
        ];
    }

    public function _createMenu(Request $request)
    {
        $menu_name = $request->post('menu_name');
        $menu_location = $request->post('menu_location');
        $menu_structure = $request->post('menu_structure');
        $data = [
            'menu_title' => $menu_name,
            'menu_position' => $menu_location,
            'created_at' => time()
        ];
        $new_menu = $this->repository->create($data);
        if (!empty($new_menu)) {
            $menu_structure = json_decode($menu_structure);
            $this->_updateMenuStructure($new_menu, $menu_structure);
            return $new_menu;
        }
        return false;
    }

    private function _updateMenuStructure($menu_id, $menu_structure)
    {
        $menuStructureRepo = MenuStructureRepository::inst();
        $menuStructureRepo->resetMenuStructure($menu_id);
        if (!empty($menu_structure)) {
            foreach ($menu_structure as $k => $v) {
                $data = [
                    'item_id' => $v->item_id,
                    'parent_id' => $v->parent_id,
                    'depth' => $v->depth,
                    'left' => $v->left,
                    'right' => $v->right,
                    'name' => isset($v->name) ? $v->name : '',
                    'type' => isset($v->type) ? $v->type : '',
                    'post_id' => isset($v->post_id) ? $v->post_id : '',
                    'post_title' => isset($v->post_title) ? $v->post_title : '',
                    'url' => isset($v->url) ? $v->url : '',
                    'class' => isset($v->class) ? $v->class : '',
                    'menu_id' => $menu_id,
                    'menu_lang' => get_current_language(),
                    'target_blank' => isset($v->target_blank) ? $v->target_blank : 0,
                    'created_at' => time()
                ];

                $menuStructureRepo->create($data);
            }
        }
    }

    public function getMenuItemsByMenuID($menu_id)
    {
        $menuStructureRepo = MenuStructureRepository::inst();
        return $menuStructureRepo->getStructureByMenuID($menu_id);
    }

    public function getMenuByID($menu_id)
    {
        return $this->repository->find($menu_id);
    }

    public function getListMenus()
    {
        return $this->repository->all();
    }
}