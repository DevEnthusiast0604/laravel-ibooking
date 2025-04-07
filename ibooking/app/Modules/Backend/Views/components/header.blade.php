@php
    $logo_dashboard = get_option('logo-dashboard');
    $site_name = get_translate(get_option('site_name', 'iBooking'));
@endphp
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm">

        <ul class="navbar-nav theme-brand flex-row  text-center">
            @if(!empty($logo_dashboard))
            <li class="nav-item logo-dashboard">
                <a href="{{url('/')}}">
                    <img src="<?php echo get_attachment_url($logo_dashboard); ?>" alt="{{$site_name}}" width="100%"/>
                </a>
            </li>
            @else
                <li class="nav-item theme-text">
                    <a href="{{url('/')}}" class="nav-link">{{$site_name}}</a>
                </li>
            @endif
            <li class="nav-item toggle-sidebar">
                <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg>
                </a>
            </li>
        </ul>

        <ul class="navbar-item flex-row navbar-dropdown gmz-quick-action">
            @if(is_admin() || is_partner())
            <li class="nav-item dropdown apps-dropdown more-dropdown md-hidden">
                <div class="dropdown  custom-dropdown-icon">
                    <a class="dropdown-toggle btn" href="#" role="button" id="appSection" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-crosshair"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg><span>{{__('Quick Action')}}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="appSection">
                        @if(is_admin())
                            <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-post')}}">{{__('New Post')}}</a>
                            <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-page')}}">{{__('New Page')}}</a>
                        @endif
                        @if(!is_customer())
                                @if(is_enable_service(GMZ_SERVICE_HOTEL))
                                    <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-hotel')}}">{{__('New Hotel')}}</a>
                                @endif
                                @if(is_enable_service(GMZ_SERVICE_APARTMENT))
                                    <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-apartment')}}">{{__('New Apartment')}}</a>
                                @endif
                                    @if(is_enable_service(GMZ_SERVICE_TOUR))
                                        <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-tour')}}">{{__('New Tour')}}</a>
                                    @endif
                            @if(is_enable_service(GMZ_SERVICE_CAR))
                                    <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-car')}}">{{__('New Car')}}</a>
                            @endif
                                    @if(is_enable_service(GMZ_SERVICE_SPACE))
                                        <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('new-space')}}">{{__('New Space')}}</a>
                                    @endif
                        @endif
                        @if(is_admin())
                            <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('menu')}}">{{__('Manage Menu')}}</a>
                            <a class="dropdown-item" data-value="Todo" href="{{dashboard_url('settings')}}">{{__('Settings')}}</a>
                        @endif
                    </div>
                </div>
            </li>
            @endif
        </ul>

        <ul class="navbar-item flex-row search-ul">

        </ul>
        <ul class="navbar-item flex-row navbar-dropdown">
            @include('Backend::components.items.language')

            @php
                $current_user_id = get_current_user_id();
                $data_notification = GMZ_Notification::inst()->getLatestNotificationByUser($current_user_id, 'to');
                $args = [
                    'user_id' => $current_user_id,
                    'user_hashing' => gmz_hashing($current_user_id)
                ];
            @endphp
            <li class="nav-item dropdown notification-dropdown" id="gmz-dropdown-notification" data-action="{{ url('update-check-notification') }}"
                data-params="{{ base64_encode(json_encode($args)) }}">

                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>@if($data_notification['total'])<span class="badge badge-success"></span>@endif
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">
                        @if($data_notification['total'])
                            @foreach($data_notification['results'] as $notification_item)
                        <div class="dropdown-item">
                            <div class="media server-log">
                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class="">{{ balance_tags($notification_item->title) }}</h6>
                                        <p class="">{!! get_time_release($notification_item->created_at) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                            @endforeach
                        @else
                            <p class="text-muted mt-2">{{__('No Notifications found!')}}</p>
                        @endif
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <img src="{{get_user_avatar('', [64,64])}}" class="img-fluid mr-2" alt="avatar">
                            <div class="media-body">
                                <h5>{{get_user_name()}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <a href="{{dashboard_url('profile')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>{{__('My Profile')}}</span>
                        </a>
                    </div>
                    <div class="dropdown-item">
                        <a href="{{url('logout')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>