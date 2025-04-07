@php
    admin_enqueue_styles('gmz-checkbox');
    admin_enqueue_styles('select2');
    admin_enqueue_scripts('select2');
        if(empty($value))
            $value = $std;

    $awe_json = 'html/assets/vendor/font-awesome-5/';
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif
data-json-icon="{{asset($awe_json . 'icons.json')}}" data-json-category="{{asset($awe_json . 'categories.json')}}">
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <input type="text" name="{{$id}}" class="form-control input-icon" id="gmz-field-{{$id}}" value="{{$value}}" autocomplete="off" readonly/>
    <div class="icon-display">
        <span class="icon-remove"  @if(empty($value)) style="display: none" @endif>+</span>
        <span data-text="{{__('Add icon')}}">
            @if(empty($value))
                {{__('Add icon')}}
            @else
                @if(strpos($value, ' fa-'))
                    <i class="{{$value}}"></i>
                @else
                    {!! get_icon($value) !!}
                @endif
            @endif
        </span>
    </div>
    <div class="icon-overlay"></div>
    <div class="icon-picker-box">
        @include('Backend::components.loader')
        <div class="heading">
            <div class="d-flex align-items-center">
            {{__('ICON LIBRARY')}}
                <span class="svg-font">
                    <span class="b-font active">{{__('FONT ICON')}}</span>
                    <span class="b-svg">{{__('SVG ICON')}}</span>
                </span>
            </div>
            <span class="icon-close"><i class="fal fa-times"></i></span>
        </div>
        <div class="col-wrapper icon-type-font">
            <div class="col-left">
                <div class="col-heading">
                    {{__('ICON TYPE')}}
                </div>
                <ul>
                    <li>
                        <label class="new-control new-checkbox checkbox-primary">
                            <input type="checkbox" name="icon_type[]" class="new-control-input" value="solid">
                            <span class="new-control-indicator"></span>
                            {{__('Solid')}}
                        </label>
                    </li>
                    <li>
                        <label class="new-control new-checkbox checkbox-primary">
                            <input type="checkbox" name="icon_type[]" class="new-control-input" value="regular">
                            <span class="new-control-indicator"></span>
                            {{__('Regular')}}
                        </label>
                    </li>
                    <li>
                        <label class="new-control new-checkbox checkbox-primary">
                            <input type="checkbox" name="icon_type[]" class="new-control-input" value="light">
                            <span class="new-control-indicator"></span>
                            {{__('Light')}}
                        </label>
                    </li>
                    <li>
                        <label class="new-control new-checkbox checkbox-primary">
                            <input type="checkbox" name="icon_type[]" class="new-control-input" value="brands">
                            <span class="new-control-indicator"></span>
                            {{__('Brands')}}
                        </label>
                    </li>
                </ul>
            </div>
            <div class="col-right">

                <div class="icons-wrapper">
                    <div class="icon-search">
                        <input type="text" name="icon_search" placeholder="{{__('Search icon')}}" class="form-control" autocomplete="off"/>
                    </div>
                    <div class="render">
                        {{__('Icon data')}}
                    </div>
                    <div class="icon-pagination">
                        <div class="icon-prev-page">
                            <i class="fal fa-angle-left"></i>
                            {{__('Prev')}}
                        </div>
                        <div class="icon-next-page">
                            {{__('Next')}}
                            <i class="fal fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-wrapper icon-type-svg d-none">
            <div class="col-left upload-box">
                <div class="gmz-import-font-wrapper">
                    @include('Backend::components.loader')
                    <div class="import-font-inner needsclick mt-2 mb-2">
                        <i class="fal fa-cloud-upload-alt icon-no"></i>
                        <i class="fal fa-check-circle icon-yes"></i>
                        <h3 data-text-origin="{{__('Drop SVG files or .zip file here or click to select file upload.')}}" data-text-uploaded="{{__('Your files has been selected')}}">{{__('Drop SVG/.zip file or click to select file upload.')}}</h3>
                        <input type="file" name="fonts[]" accept=".zip,.svg" multiple/>
                    </div>
                    <div class="form-message"></div>
                    <button class="btn btn-success w-100" data-action="{{ dashboard_url('import-font') }}">{{__('Upload')}}</button>
                </div>
                <div class="alert alert-warning mt-2 icon-question">
                    <i class="fal fa-question-circle"></i> {!! __('<a target="_blank" href="https://docs.booteam.co/ibooking/dashboard/icons">How to upload your SVG icon.</a>') !!}
                </div>
            </div>
            <div class="col-right">
                <div class="col-heading">
                    {{__('ICONS SVG')}}
                </div>
                <div class="gmz-list-font-wrapper">
                    @include('Backend::components.loader')
                    <input id="gmz-filter-icon" type="text" class="form-control" placeholder="{{__('Typing to search icon...')}}"/>
                    <div class="gmz-list-font-inner">
                        @php
                            include public_path('fonts/fonts.php');
                        @endphp
                        @if(isset($fonts) && !empty($fonts))
                            @foreach($fonts as $key => $icon)
                                <div class="icon-wrapper" data-name="{{$key}}">
                                    @php
                                        echo '<i class="gmz-icon">' . $icon . '</i>';
                                    @endphp
                                    <p class="icon-delete" data-name="{{$key}}" data-action="{{dashboard_url('delete-font-icon')}}">+</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>