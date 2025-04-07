@extends('Backend::layouts.master')

@section('title', __('Plugins'))

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Plugins')}}</h4>
                            <a href="{{route('plugin.new')}}" class="btn btn-primary">{{__('Add New')}}</a>
                        </div>
                    </div>
                </div>

                @if(!empty($plugins))
                    <div class="themes-wrapper plugins-wrapper">
                        <div class="row">
                            @foreach($plugins as $k => $item)
                                @php
                                    $params = [
                                        'plugin' => $item['slug'],
                                        'folder' => $item['folderName'],
                                        'currentRoute' => 'plugins'
                                    ]
                                @endphp
                                <div class="col-lg-4">
                                    <div class="item">
                                        @if(isset($version[$item['slug']]) && $version[$item['slug']] != $item['Version'])
                                            <a class="btn btn-link btn-sm gmz-link-action btn-update" href="javascript:void(0);" data-action="{{dashboard_url('update-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Update new version')}}</a>
                                        @endif
                                        <div class="thumbnail">
                                            <img src="{{$item['screenshot']}}" alt="screenshot" class="img-fluid"/>
                                            <a href="javascript:void(0);" data-toggle="modal"
                                               data-target="#pluginModal{{$k}}" class="view-detail"><span
                                                        class="btn btn-dark">{{__('Plugin Details')}}</span></a>
                                            @if(in_array($item['slug'], $actives))
                                                <p class="current-theme">{{__('Activated')}}</p>
                                            @endif
                                            <div class="info-action">
                                                @if(in_array($item['slug'], $actives))
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('deactivate-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Deactivate')}}</a>
                                                @else
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('active-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Activate')}}</a>
                                                @endif
                                                <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Delete')}}</a>
                                            </div>
                                        </div>
                                        <div class="info {{in_array($item['slug'], $actives) ? 'active' : ''}}">
                                                <div class="info-group">
                                                    <p class="name">{{$item['Plugin Name']}}<span class="ver">v{{$item['Version']}}</span></p>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="pluginModal{{$k}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="pluginModalLabel{{$k}}">
                                                    {{$item['Plugin Name']}}
                                                    @if(in_array($item['slug'], $actives))
                                                        <p class="current-theme">{{__('Activated')}}</p>
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
                                                            {{$item['Plugin Name']}}
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
                                                @if(in_array($item['slug'], $actives))
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('deactivate-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">
                                                        {{__('Deactivate')}}
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary btn-sm gmz-link-action" href="javascript:void(0);" data-action="{{dashboard_url('active-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">
                                                        {{__('Activate')}}
                                                    </a>
                                                @endif
                                                <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-plugin')}}" data-params="{{base64_encode(json_encode($params))}}">
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
                    <i class="mt-2 d-block">{{__('No plugins')}}</i>
                @endif
            </div>
        </div>
    </div>
@stop

