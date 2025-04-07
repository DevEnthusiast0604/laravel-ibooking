@extends('Backend::layouts.master')

@section('title', __('Themes'))

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Themes')}}</h4>
                            <a href="{{route('theme.new')}}" class="btn btn-primary">{{__('Add New')}}</a>
                        </div>
                    </div>
                </div>

                @if(!empty($themes))
                    <div class="themes-wrapper">
                        <div class="row">
                            @foreach($themes as $k => $item)
                                @php
                                    $params = [
                                        'theme' => $item['slug'],
                                        'folder' => $item['folderName'],
                                        'currentRoute' => 'themes'
                                    ]
                                @endphp
                                <div class="col-lg-4">
                                    <div class="item">
                                        @if(isset($version[$item['slug']]) && $version[$item['slug']] != $item['Version'])
                                            <a class="btn btn-link btn-sm gmz-link-action btn-update" href="javascript:void(0);" data-action="{{dashboard_url('update-theme')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Update new version')}}</a>
                                        @endif
                                        <div class="thumbnail">
                                            <img src="{{$item['screenshot']}}" alt="screenshot" class="img-fluid"/>
                                            <a href="javascript:void(0);" data-toggle="modal"
                                               data-target="#themeModal{{$k}}" class="view-detail"><span
                                                        class="btn btn-dark" >{{__('Theme Details')}}</span></a>
                                            @if($active == $item['slug'])
                                                <p class="current-theme">{{__('Current Theme')}}</p>
                                            @endif
                                        </div>
                                        <div class="info {{$active == $item['slug'] ? 'active' : ''}}">
                                                <div class="info-group">
                                                    <p class="name">{{$item['Theme Name']}}<span class="ver">v{{$item['Version']}}</span></p>
                                                    <div class="info-action">
                                                        @if($active == $item['slug'])
                                                        <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('deactivate-theme')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Deactivate')}}</a>
                                                        @else
                                                        <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('active-theme')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Activate')}}</a>
                                                        @endif
                                                        <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-theme')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Delete')}}</a>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="themeModal{{$k}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="themeModalLabel{{$k}}">
                                                    {{$item['Theme Name']}}
                                                    @if($active == $item['slug'])
                                                        <p class="current-theme">{{__('Current Theme')}}</p>
                                                    @endif
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <i class="fal fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <img src="{{$item['screenshot']}}" alt="screenshot"
                                                             class="img-fluid"/>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class="theme-name">
                                                            {{$item['Theme Name']}}
                                                            <span class="theme-version">{{sprintf(__('Version: %s'), $item['Version'])}}</span>
                                                        </p>
                                                        <p class="theme-author">
                                                            {{__('By')}}
                                                            <a href="{{$item['Author URI']}}" target="_blank">{{$item['Author']}}</a>
                                                        </p>
                                                        <p class="theme-description">{{$item['Description']}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @if($active == $item['slug'])
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('deactivate-theme')}}" data-params="{{base64_encode(json_encode($params))}}">
                                                        {{__('Deactivate')}}
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('active-theme')}}" data-params="{{base64_encode(json_encode($params))}}">
                                                        {{__('Activate')}}
                                                    </a>
                                                @endif
                                                <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-theme')}}" data-params="{{base64_encode(json_encode($params))}}">
                                                    {{__('Delete')}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <i class="mt-2 d-block">{{__('No themes')}}</i>
                @endif
            </div>
        </div>
    </div>
@stop

