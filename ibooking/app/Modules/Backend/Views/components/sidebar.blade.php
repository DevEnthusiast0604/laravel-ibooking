@php
    if(is_admin()){
        $menu_name = 'admin_menu';
    }elseif(is_partner()){
        $menu_name = 'partner_menu';
    }else{
        $menu_name = 'customer_menu';
    }
    $menus = admin_config($menu_name);
    $menus = Eventy::filter('gmz_dashboard_sidebar_menu', $menus);
    $prefix = admin_config('prefix');
    $currentScreen = Request::route()->getName();
    $current_params = \Illuminate\Support\Facades\Route::current()->parameters();
    foreach ($current_params as $key => $param) {
       if ($key == 'service'){
          $currentScreen = $param . '/' . $currentScreen ;
       }else if($key !== 'page' && $key !== 'id') {
          $currentScreen .= '/' . $param;
       }elseif($key == 'type'){
            $currentScreen = $currentScreen . '/' . $param;
       }
    }
@endphp
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @foreach($menus as $item)
                @php
                    if (isset($item['service'])) {
                        if (!is_enable_service($item['service'])) {
                            continue;
                        }
                    }
                    if (isset($item['services'])) {
                        $all_services = count($item['services']);
                        $count_services = 0;
                        foreach ($item['services'] as $sitem) {
                            if (!is_enable_service($sitem)) {
                                $count_services += 1;
                            }
                        }
                        if ($all_services == $count_services) {
                            continue;
                        }
                    }
                @endphp
                @if($item['type'] === 'heading')
                    <li class="menu menu-heading">
                        <div class="heading">
                            {!! get_icon('icon_system_minus') !!}
                            <span>{{__($item['label'])}}</span>
                        </div>
                    </li>
                @endif
                    @if($item['type'] === 'item')
                        @php
                            $url = $item['screen'];
                            if($item['screen'] == 'dashboard'){
                                $url = '';
                            }
                        @endphp
                        <li class="menu @if($currentScreen == $item['screen']) active @endif">
                            <a href="{{dashboard_url($url)}}" aria-expanded="false" class="dropdown-toggle">
                                <div class="d-flex align-items-center">
                                    <i class="fal {{$item['icon']}}"></i>
                                    <span>{{__($item['label'])}}</span>
                                </div>
                            </a>
                        </li>
                    @endif

                    @if($item['type'] === 'parent')
                        <li class="menu @if(in_array($currentScreen, $item['screen'])) active @endif">
                            <a href="#{{$item['id']}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <div class="d-flex align-items-center">
                                    <i class="fal {{$item['icon']}}"></i>
                                    <span>{{__($item['label'])}}</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            @if(isset($item['child']) && !empty($item['child']))
                            <ul class="collapse submenu list-unstyled @if(in_array($currentScreen, $item['screen'])) show @endif" id="{{$item['id']}}" data-parent="#accordionExample">
                                @foreach($item['child'] as $child)
                                <li class="@if($currentScreen == $child['screen']) active @endif @if($child['type'] == 'hidden') d-none @endif">
                                    <a href="{{dashboard_url($child['screen'])}}"> {{__($child['label'])}} </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endif
            @endforeach
        </ul>
    </nav>
</div>