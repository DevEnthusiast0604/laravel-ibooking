@extends('Backend::layouts.master')

@section('title', __('Import SVG Icon'))

@section('content')
    <div class="layout-top-spacing">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h6>{{__('Import SVG Icon')}}</h6>
                    </div>
                    <div class="widget-content widget-content-area pl-2 pr-2">
                        <div class="gmz-import-font-wrapper">
                            <form action="{{ dashboard_url('import-font') }}" method="post" class="text-center form-file-action" enctype="multipart/form-data">
                                @include('Backend::components.loader')
                                <div class="import-font-inner needsclick mt-2 mb-2">
                                    <i data-feather="upload-cloud" class="icon-no"></i>
                                    <i data-feather="check-circle" class="icon-yes"></i>
                                    <h3 data-text-origin="{{__('Drop SVG files or .zip file here or click to select file upload.')}}" data-text-uploaded="{{__('Your files has been selected')}}">{{__('Drop SVG files or .zip file here or click to select file upload.')}}</h3>
                                    <input type="file" name="fonts[]" accept=".zip,.svg" multiple/>
                                </div>
                                <div class="form-message"></div>
                                <button class="btn btn-success w-100">{{__('Upload Your Icon')}}</button>
                            </form>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <p class="mb-0">{!! __('<a target="_blank" href="https://docs.booteam.co/ibooking/dashboard/icons">How to upload your SVG icon.</a> Please refer our document for more details.') !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h6>{{__('Icons')}}</h6>
                    </div>
                    <div class="widget-content widget-content-area pl-2 pr-2">
                        <div class="gmz-list-font-wrapper">
                            @include('Backend::components.loader')

                            <div class="icon-tab mb-3">
                                @php
                                    $type = request()->get('type', '');
                                    if(!in_array($type, ['', 'system'])){
                                        $type = '';
                                    }
                                @endphp
                                <a href="{{dashboard_url('import-font')}}" class="@if($type == '') text-primary font-weight-bold @endif mr-3">{{__('Custom Icon')}}</a>
                                <a href="{{dashboard_url('import-font?type=system')}}" class="@if($type == 'system') text-primary font-weight-bold @endif ">{{__('System Icon')}}</a>
                            </div>

                            <input id="gmz-filter-icon" type="text" class="form-control" placeholder="{{__('Typing to search icon...')}}"/>
                            <div class="gmz-list-font-inner">
                                @php
                                    $all_fonts = [];
                                    if($type == 'system'){
                                        include public_path('fonts/system-fonts.php');
                                        $all_fonts = $system_fonts;
                                    }else{
                                        include public_path('fonts/fonts.php');
                                        $all_fonts = $fonts;
                                    }
                                @endphp
                                @if(isset($all_fonts) && !empty($all_fonts))
                                    @foreach($all_fonts as $key => $icon)
                                        <div class="icon-wrapper">
                                            @php
                                              echo '<i class="gmz-icon">' . $icon . '</i>';
                                            @endphp
                                            <span>{{$key}}</span>
                                            <p class="icon-copy" data-name="{{$key}}" data-coppied-text="{{__('Coppied')}}" data-origin-text="{{__('Copy to clipboard')}}">{{__('Copy to clipboard')}}</p>
                                            @if($type !== 'system')
                                            <p class="icon-delete" data-name="{{$key}}" data-action="{{dashboard_url('delete-font-icon')}}">+</p>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop