@if(is_user_login())
@php global $post; @endphp
<div class="admin-bar d-flex align-items-center justify-content-between">
    <div>
        <a href="{{dashboard_url('/')}}" class="item dashboard">{!! get_icon('icon_system_dashboard') !!}<span>{{__('Dashboard')}}</span></a>

    @if(is_admin())
        <div class="new-action">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fal fa-wrench"></i> <span>{{__('Quick Action')}}</span>
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{dashboard_url('settings')}}">{{__('Setting')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('menu')}}">{{__('Menu')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('all-users')}}">{{__('Users')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('language')}}">{{__('Language')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('all-media')}}">{{__('Media')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('import-font')}}">{{__('Font Icon')}}</a>
            </div>
        </div>
    @endif

    @if(!is_customer())
    <div class="new-action">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-plus"></i> <span>{{__('New')}}</span>
        </button>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @if(is_admin())
                <a class="dropdown-item" href="{{dashboard_url('new-post')}}">{{__('Post')}}</a>
                <a class="dropdown-item" href="{{dashboard_url('new-page')}}">{{__('Page')}}</a>
            @endif
                @if(is_enable_service('hotel'))
                    <a class="dropdown-item" href="{{dashboard_url('new-hotel')}}">{{__('Hotel')}}</a>
                @endif
                @if(is_enable_service('apartment'))
                    <a class="dropdown-item" href="{{dashboard_url('new-apartment')}}">{{__('Apartment')}}</a>
                @endif
                @if(is_enable_service('space'))
                    <a class="dropdown-item" href="{{dashboard_url('new-space')}}">{{__('Space')}}</a>
                @endif
                @if(is_enable_service('tour'))
                    <a class="dropdown-item" href="{{dashboard_url('new-tour')}}">{{__('Tour')}}</a>
                @endif
                @if(is_enable_service('beauty'))
                    <a class="dropdown-item" href="{{dashboard_url('new-beauty')}}">{{__('Beauty')}}</a>
                @endif
            @if(is_enable_service('car'))
            <a class="dropdown-item" href="{{dashboard_url('new-car')}}">{{__('Car')}}</a>
            @endif
            <a class="dropdown-item" href="{{dashboard_url('all-media')}}">{{__('Media')}}</a>
            @if(is_admin())
                <a class="dropdown-item" href="{{dashboard_url('import-font')}}">{{__('Font Icon')}}</a>
            @endif
        </div>
    </div>
    @endif

        @php
            $check_edit = false;
            if(isset($post['post_type'])){
                if(is_admin()){
                    $check_edit = true;
                }elseif(is_partner() && get_current_user_id() == $post['author']){
                    $check_edit = true;
                }
            }
        @endphp
    @if($check_edit)
        <a href="{{dashboard_url('edit-'. $post['post_type'] .'/' . $post['id'])}}" class="item"><i class="fal fa-pen"></i> <span>{{__('Edit')}}</span></a>
    @endif
    </div>
    <a href="{{url('logout')}}"><i class="fal fa-sign-out"></i> {{__('Logout')}}</a>
</div>
@endif
