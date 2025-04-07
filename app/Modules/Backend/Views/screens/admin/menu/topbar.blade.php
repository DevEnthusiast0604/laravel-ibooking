<div class="gmz-select-menu mb-4">
    <form action="{{ current_url() }}" method="GET">
        <div class="form-inline form-xs">
            <div class="w-100 form-group d-flex align-items-center justify-content-between">
                <div class="d-flex">
                    <label for="gmz-menu-selection" class="mr-2">{{__('MENU')}}</label>
                    <select id="gmz-menu-selection" name="menu_id" class="form-control min-w-100" data-plugin="customselect">
                        <?php
                        if(empty($menuObject) || count($listMenus) <= 0){
                        ?>
                        <option value="" <?php echo ($menuID == 'none') ? 'selected' : ''; ?>>{{__('--- Select ---')}}</option>
                        <?php
                        }
                        if(count($listMenus) > 0){
                            foreach ($listMenus as $key => $value) {
                                $selected = '';
                                if($value->menu_id == $menuID){
                                    $selected = 'selected';
                                }
                                echo '<option value="'. esc_attr($value->menu_id) .'" '. $selected .'>'. esc_html($value->menu_title) .'</option>';
                            }
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-success btn-sm ml-sm-2 mt-2 mt-sm-0">{{__('Select')}}</button>
                </div>
                <a href="{{ url('dashboard/menu?menu_id=0') }}" class="ml-2">{{__('Create new Menu')}}</a>
            </div>
        </div>
    </form>
</div>
