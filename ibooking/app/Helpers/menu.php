<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/7/20
 * Time: 16:06
 */

if (!function_exists('render_menu_tree')) {
    function render_menu_tree($menu_items, $detph = 1)
    {
        if ($detph > 1) {
            echo '<ol>';
        }
        foreach ($menu_items as $k => $v) {
            ?>
            <li id="gmz-mn-<?php echo esc_attr($v->item_id); ?>" data-type="<?php echo esc_attr($v->type); ?>"
                data-post_id="<?php echo esc_attr($v->post_id); ?>"
                data-post_title="<?php echo esc_attr($v->post_title); ?>">
                <div class="item type-<?php echo esc_attr($v->type); ?>">
                    <div class="item-header d-flex align-items-center justify-content-between">
					<span class="name">
						<?php echo esc_attr($v->name); ?>
					</span>
                        <span class="gmz-delete-menu-item ml-3">
						<?php echo get_icon('icon_system_delete') ?>
					</span>
                    </div>
                    <div class="item-content-wrapper">
                        <div class="item-content">
                            <div class="form-group name">
                                <label><?php echo __('Menu name') ?></label>
                                <input type="text" class="form-control form-control-sm menu_name"
                                       value="<?php echo esc_attr($v->name); ?>">
                            </div>
                            <div class="form-group url">
                                <label><?php echo __('Menu URL') ?></label>
                                <input type="text" class="form-control form-control-sm menu_url"
                                       value="<?php echo esc_attr($v->url); ?>">
                            </div>

                            <div class="form-group target">
                                <div class="checkbox checkbox-success">
                                    <input <?php echo (!empty($v->target_blank)) ? 'checked' : ''; ?>
                                            type="checkbox" class="menu_target" value="1"
                                            id="target-checkbox<?php echo esc_attr($k . $v->item_id); ?>">
                                    <label for="target-checkbox<?php echo esc_attr($k . $v->item_id); ?>"><?php echo __('Open link in a new tab') ?></label>
                                </div>
                            </div>

                            <div class="menu-info">
                                <?php if ($v->type == 'custom') { ?>
                                    <p class="menu-type"><?php echo __('Type:') ?><?php echo ucwords(str_replace('_', ' ', __($v->type))); ?></p>
                                <?php } else { ?>
                                    <p class="menu-origin-link"><?php echo ucwords(str_replace('_', ' ', __($v->type))); ?>
                                        :
                                        <a href="<?php echo esc_attr($v->url); ?>"><?php echo esc_attr($v->post_title); ?></a>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($v->children)) {
                    render_menu_tree($v->children, 2);
                }
                ?>
            </li>
            <?php
        }
        if ($detph > 1) {
            echo '</ol>';
        }
    }
}

if (!function_exists('flatten_menu_data')) {
    function flatten_menu_data($elements, $parentId = '')
    {
        $branch = array();
        if (!$elements->isEmpty()) {
            foreach ($elements as $element) {
                if ($element->parent_id == $parentId && $element->item_id != '') {
                    $children = flatten_menu_data($elements, $element->item_id);
                    if ($children) {
                        $element->children = $children;
                    }
                    $branch[] = $element;
                }
            }
        }

        return $branch;
    }
}

if (!function_exists('has_nav_primary')) {
    function has_nav_primary()
    {
        $menu = new \App\Models\Menu();

        return $menu->hasMenuLocation();
    }
}

if (!function_exists('has_nav')) {
    function has_nav($menu_id)
    {
        $nav = get_menu_by_id($menu_id);
        if ($nav) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('get_nav_by_id')) {
    function get_nav_by_id($menu_id, $classes = '')
    {
        if (!empty($menu_id)) {
            $menuItems = get_menu_items_by_menu_id($menu_id);
            get_normal_menu($menuItems, 1, false, $classes);
        }
    }
}

if (!function_exists('get_normal_menu')) {
    function get_normal_menu($menu_items, $detph = 1, $is_submenu = false, $classes = '')
    {
        if ($is_submenu) {
            echo '<ul role="menu" class="sub-menu">';
        } else {
            $start_ul = (empty($classes)) ? '<ul class="menu">' : '<ul class="menu ' . $classes . '">';
            echo $start_ul;
        }

        $current_url = url()->current();
        $current_url = rtrim($current_url, '/');

        foreach ($menu_items as $k => $v) {
            $item_link = rtrim($v->url, '/');
            $class_current = '';
            if ($current_url == $item_link) {
                $class_current = ' current-menu-item';
                if (isset($v->children)) {
                    $class_current .= ' current-menu-parent';
                }
            }
            $class = "menu-item menu-item-" . $v->item_id . ' ' . $class_current;
            if (isset($v->children)) {
                $class = "menu-item-has-children" . $class_current;
            }

            $target = '';
            if (isset($v->target_blank) && !empty($v->target_blank)) {
                $target = 'target="_blank"';
            }
            ?>
            <li class="<?php echo esc_attr($class); ?>">
                <a href="<?php echo esc_attr($v->url); ?>" <?php echo $target; ?>><?php echo esc_attr($v->name); ?></a>
                <?php
                if (isset($v->children)) {
                    get_normal_menu($v->children, 2, true);
                }
                ?>
            </li>
            <?php
        }
        echo '</ul>';
    }
}

if (!function_exists('get_main_menu')) {
    function get_main_menu($menu_items, $detph = 1, $is_submenu = false)
    {
        if ($is_submenu) {
            echo '<ul role="menu" class="sub-menu">';
        } else {
            echo '<ul id="menu-primary-1" class="main-menu">';
        }

        $current_url = url()->current();
        $current_url = rtrim($current_url, '/');

        foreach ($menu_items as $k => $v) {
            $item_link = rtrim($v->url, '/');
            $class_current = '';
            if ($current_url == $item_link) {
                $class_current = ' current-menu-item';
                if (isset($v->children)) {
                    $class_current .= ' current-menu-parent';
                }
            }
            $class = "menu-item menu-item-" . $v->item_id . ' ' . $class_current;
            if (isset($v->children)) {
                $class = "menu-item-has-children" . $class_current;
            }

            $target = '';
            if (isset($v->target_blank) && !empty($v->target_blank)) {
                $target = 'target="_blank"';
            }
            ?>
            <li class="<?php echo esc_attr($class); ?>">
                <a href="<?php echo esc_attr($v->url); ?>" <?php echo $target; ?>><?php echo esc_attr($v->name); ?></a>
                <?php
                if (isset($v->children)) {
                    get_main_menu($v->children, 2, true);
                }
                ?>
            </li>
            <?php
        }
        echo '</ul>';
    }
}

if (!function_exists('get_main_mobile_menu')) {
    function get_main_mobile_menu($a)
    {
        echo 'Mobile Menu';
    }
}

if (!function_exists('get_nav')) {
    function get_nav($data)
    {
        $default = [
            'location' => '',
            'walker' => 'normal'
        ];

        $data = gmz_parse_args($data, $default);

        $menu = new \App\Models\Menu();
        $menuObject = $menu->getMenuByLocation($data['location']);
        if (!empty($menuObject)) {
            $menuItems = get_menu_items_by_menu_id($menuObject->menu_id);
            switch ($data['walker']) {
                case 'main':
                    get_main_menu($menuItems);
                    break;
                case 'main-mobile':
                    get_main_mobile_menu($menuItems);
                    break;
                default:
                    get_normal_menu($menuItems);
                    break;
            }
        } else {
            echo 'No menus.';
        }
    }
}

if (!function_exists('get_menu_items_by_menu_id')) {
    function get_menu_items_by_menu_id($menu_id)
    {
        $menu_structure = new \App\Models\MenuStructure();
        $data = $menu_structure->getByMenuId($menu_id);
        $data = flatten_menu_data($data);

        return $data;
    }
}

if (!function_exists('get_list_menus')) {
    function get_list_menus()
    {
        $menu = new \App\Models\Menu();

        return $menu->getAllMenus();
    }
}

if (!function_exists('get_menu_by_id')) {
    function get_menu_by_id($menu_id)
    {
        return \App\Repositories\MenuRepository::inst()->getMenuByID($menu_id);
    }
}

if (!function_exists('get_navigation')) {
    function get_navigation()
    {
        $listMenus = get_list_menus();
        if (!empty($listMenus) && is_object($listMenus)) {
            $return = [];
            foreach ($listMenus as $menu) {
                $return[$menu->menu_id] = $menu->menu_title;
            }

            return $return;
        } else {
            return [];
        }
    }
}

