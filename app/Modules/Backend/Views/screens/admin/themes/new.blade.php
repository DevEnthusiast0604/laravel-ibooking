@extends('Backend::layouts.master')

@section('title', __('Add Themes'))

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('Add Themes')}}</h4>
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
                                        'currentRoute' => 'theme.new'
                                    ]
                                @endphp
                                <div class="col-lg-4">
                                    <div class="item">
                                        <div class="thumbnail">
                                            <img src="{{$item['screenshot']}}" alt="screenshot" class="img-fluid"/>
                                            <a href="javascript:void(0);" data-toggle="modal"
                                               data-target="#themeModal{{$k}}" class="view-detail"><span
                                                        class="btn btn-dark">{{__('Theme Details')}}</span></a>
                                        </div>
                                        <div class="info">
                                            <div class="info-group">
                                                <p class="name">{{$item['Theme Name']}}<span class="ver">v{{$item['Version']}}</span></p>
                                                <div class="info-action">
                                                    <a class="btn btn-primary btn-sm gmz-link-action btn-install-theme-{{$item['slug']}}"
                                                       href="javascript:void(0);"
                                                       data-action="{{dashboard_url('install-theme')}}"
                                                       data-params="{{base64_encode(json_encode($params))}}">{{__('Install')}}</a>
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
                                                            <a href="{{$item['Author URI']}}"
                                                               target="_blank">{{$item['Author']}}</a>
                                                        </p>
                                                        <p class="theme-description">{{$item['Description']}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a class="btn btn-primary btn-sm gmz-link-action btn-install-theme-{{$item['slug']}}"
                                                   href="javascript:void(0);"
                                                   data-action="{{dashboard_url('install-theme')}}"
                                                   data-params="{{base64_encode(json_encode($params))}}">
                                                    {{__('Install')}}
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

