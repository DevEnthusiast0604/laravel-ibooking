<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/7/2020
 * Time: 10:24 PM
 */
?>
@if(is_enable_service('space'))
    <div class="gmz-add-menu-box overflow-hidden">
        <h5 class="title d-flex align-items-center justify-content-between">{{__('Space')}} {!! get_icon('icon_system_arrow_down') !!}</h5>
        <div class="menu-content-wrapper">
            <div class="content">
				<?php
				$posts = get_posts(['post_type' => 'space']);
				if(!$posts->isEmpty()){
				foreach ($posts as $k => $item){
				    $post_title = get_translate($item->post_title);
				?>
                <div class="checkbox  checkbox-success mb-2">
                    <input type="checkbox"
                           class="gmz-add-menu-item"
                           name="menu_item[]"
                           value="{{ $item->id }}"
                           data-id="{{ $item->id  }}"
                           data-url="{{ get_space_permalink($item->post_slug)  }}"
                           data-type="space"
                           data-name="{{ $post_title }}"
                           id="menu_item_space_{{ $item->id  }}"/>
                    <label for="menu_item_space_{{ $item->id  }}">{{ $post_title }}</label>
                </div>
				<?php
				}
				}else {
					echo __('No space found');
				}
				?>
            </div>
            @if(!$posts->isEmpty())
                <a href="javascript:void(0);" class="btn btn-success btn-sm mt-2 right gmz-btn-add-menu-item">{{__('Add to menu')}}</a>
            @endif
        </div>
    </div>
@endif

