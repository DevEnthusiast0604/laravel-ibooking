<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/10/20
 * Time: 16:21
 */
$logo = get_logo();
$site_name = get_translate(get_option('site_name', 'iBooking'));
$is_logged = is_user_login();
?>
@include('Frontend::components.loader', ['page' => true])
@include('Frontend::components.login')
<header class="site-header">
    <div class="toggle-menu">
        <i class="fas fa-bars"></i>
    </div>
    <div class="site-branding">
        <h1>
            <a href="{{url('/')}}">
                @if(!empty($logo))
                    <img src="{{$logo}}" alt="{{$site_name}}" height="39px"/>
                @else
                    {{get_translate($site_name)}}
                @endif
            </a>
        </h1>
    </div>
    <div class="site-navigation">
        <div class="menu-overlay"></div>
        @php
            if (has_nav_primary()) {
                get_nav([
                    'location' => 'primary',
                    'walker' => 'main'
                ]);
            }
        @endphp
    </div>
    <div class="user-navigation">
        <ul>
            @if($is_logged)
                @php
                    $current_user_id = get_current_user_id();
                    $data_notification = GMZ_Notification::inst()->getLatestNotificationByUser($current_user_id, 'to');
                    $args = [
                        'user_id' => $current_user_id,
                        'user_hashing' => gmz_hashing($current_user_id)
                    ];
                @endphp
                <li id="gmz-dropdown-notification" class="dropdown notifications"
                    data-action="{{ url('update-check-notification') }}"
                    data-params="{{ base64_encode(json_encode($args)) }}">
                    <a class="dropdown-toggle"
                       data-toggle="dropdown"
                       href="#"
                       role="button"
                       aria-haspopup="false"
                       aria-expanded="false">
                        <i class="fal fa-bell"></i>
                        @if($data_notification['total'])
                            <span class="badge">{{ $data_notification['total'] }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                        <!-- item-->
                        <div class="dropdown-item notify-title">
                            {{__('Notifications')}}
                        </div>
                        @if($data_notification['total'])
                            <div class="notify-scroll">
                                @foreach($data_notification['results'] as $notification_item)
                                    <div class="dropdown-item item">
                                        <div class="icon notify-{{ $notification_item->type }}">
                                            @if($notification_item->type == 'booking')
                                                <i class="far fa-calendar-alt"></i>
                                            @elseif($notification_item->type == 'system')
                                                <i class="far fa-shield-check"></i>
                                            @endif
                                        </div>
                                        <div class="notify-inner">
                                        <p class="details">{{ balance_tags($notification_item->title) }}</p>
                                        <p class="details-desc">{{ balance_tags($notification_item->message) }}</p>
                                        <p class="text-muted mb-0 user-msg">
                                            <small>{!! get_time_release($notification_item->created_at) !!}</small>
                                        </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">{{__('No Notifications found!')}}</p>
                    @endif
                        <a href="{{ dashboard_url('notifications') }}" class="dropdown-item text-center notify-item notify-all">
                            {{__('View All')}}
                            <i class="fi-arrow-right"></i>
                        </a>
                    </div>
                </li>
            @endif
            @if(!$is_logged)
                <li class="become-partner"><a href="{{url('become-a-partner')}}" class="btn btn-sm btn-primary">{{__('Become A Partner')}}</a></li>
                <li><a href="#gmz-login-popup" class="btn btn-sm btn-dark gmz-box-popup" data-effect="mfp-zoom-in"><i class="fal fa-sign-in pr-2"></i>{{__('Sign In')}}</a></li>
            @else
                <li class="user-logged">
                    @php
                        $avatar = get_user_avatar('', [100, 100]);
                    @endphp
                        <div class="user-info">
                            <a href="javascript:void(0);">
                                @if(!empty($avatar))
                                    <img src="{{$avatar}}" alt="avatar" />
                                @endif
                                <span>{{ get_user_name()}}</span>
                                <i class="far fa-chevron-down"></i>
                            </a>
                        </div>
                        <div class="user-dropdown">
                            <ul>
                                <li>
                                    <a href="{{url('dashboard')}}">{!! get_icon('icon_system_dashboard') !!}{{__('Dashboard')}}</a>
                                </li>
                                @if(is_admin())
                                    <li>
                                        <a href="{{dashboard_url('settings')}}">{!! get_icon('icon_system_settings') !!}{{__('Settings')}}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{dashboard_url('profile')}}">{!! get_icon('icon_system_user') !!}{{__('Your Profile')}}</a>
                                </li>
                                <li>
                                    <a href="{{dashboard_url('wishlist')}}">{!! get_icon('icon_system_heart') !!}{{__('Wishlist')}}</a>
                                </li>
                                <li class="logout">
                                    <a href="{{url('logout')}}">{!! get_icon('icon_system_logout') !!}{{__('Logout')}}</a>
                                </li>
                            </ul>
                        </div>
                </li>
            @endif
        </ul>
    </div>
</header>


