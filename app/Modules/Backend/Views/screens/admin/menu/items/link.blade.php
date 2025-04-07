<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/7/2020
 * Time: 10:23 PM
 */
?>
<div class="gmz-add-menu-box overflow-hidden">
    <h5 class="title d-flex align-items-center justify-content-between">{{__('Custom Link')}} {!! get_icon('icon_system_arrow_down') !!}</h5>
    <div class="menu-content-wrapper">
        <div class="content">
            <div class="form-group">
                <label class="d-block">
                    <span class="mb-1 d-inline-block">{{__('Name')}}</span>
                    <input type="text" class="form-control form-control-sm menu-name">
                </label>
            </div>

            <div class="form-group">
                <label class="d-block">
                    <span class="mb-1 d-inline-block">{{__('URL')}}</span>
                    <input type="text" class="form-control form-control-sm menu-url">
                </label>
            </div>
        </div>
        <a href="javascript:void(0);" class="btn btn-success btn-sm mt-2 right gmz-btn-add-menu-item custom-link">{{__('Add to menu')}}</a>
    </div>
</div>
