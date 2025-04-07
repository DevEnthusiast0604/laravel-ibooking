@extends('Frontend::layouts.master')

@section('title', __('Author Page'))
@section('class_body', 'author-page')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="author-sidebar">
                    @php
                        $userName = get_user_name($data->id);
                    @endphp
                    <div class="author-section1">
                        <div class="avatar">
                            <img alt="author avatar" src="<?php echo get_user_avatar( $data->id, [100, 100]) ?>">
                        </div>
                        <p class="name">{{$userName}}</p>
                        <p class="role {{get_user_role($data->id)}}">{{get_user_role($data->id, 'name')}}</p>
                        <p class="register-date">{{sprintf(__('Member since %s'), date('M d, Y', strtotime($data->created_at)))}}</p>
                    </div>
                    <div class="author-section2">
                        <p class="email"><i class="fal fa-envelope"></i>{{$data->email}}</p>
                        @if($data->phone)
                            <p class="phone"><i class="fal fa-phone"></i>{{$data->phone}}</p>
                        @endif
                        @if($data->address)
                            <p class="address"><i class="fal fa-map-marker-alt"></i>{{$data->address}}</p>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-lg-9">
                <div class="author-content">
                    <div class="head">
                        <h3 class="name">{{sprintf(__('Hi, I\'m %s'), $userName)}}</h3>
                        @if($data->description)
                            <p class="description">{!! nl2br($data->description) !!}</p>
                        @endif
                    </div>
                    @if(!empty($services))
                    @php
                        enqueue_scripts('match-height');
                    @endphp
                    <div class="services">
                        <div class="service-tabs">
                            @foreach($services as $k => $v)
                                <a href="{{route('author.view', ['id' => $data->id, 'service' => $k])}}" class="item {{$serviceActive == $k ? 'active' : ''}}">{{__($v)}}</a>
                            @endforeach
                        </div>
                        <div class="service-items">
                            @include('Frontend::user.services.' . $serviceActive)
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

