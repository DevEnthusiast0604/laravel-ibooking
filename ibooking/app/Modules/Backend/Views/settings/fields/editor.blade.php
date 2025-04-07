@php
    admin_enqueue_styles('gmz-quill');
    admin_enqueue_scripts('gmz-quill');
    admin_enqueue_scripts('gmz-quill-image-resize');
    if(empty($value)){
        $value = $std;
    }
    $langs = $translation == false ? [""] : get_languages_field();
    $classLangs = '';
    if(!empty($langs) && !empty($langs[0])){
        $classLangs = 'has-editor-translation';
    }
    if(isset($serviceData)){
        $classEditor = \TorMorten\Eventy\Facades\Eventy::filter('gmz_editor_field_class', '', $serviceData);
    }else{
        $classEditor = '';
    }
@endphp
@if(isset($serviceData))
    @action('gmz_before_editor_field', $serviceData)
@endif
<div class="gmz-editor-media" data-toggle="modal" data-multi="true" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}" data-name="{{$id}}"></div>
<div class="{{$classEditor}} gmz-field form-group {{$layout}} gmz-field-{{$type}} @if($translation == true) gmz-field-has-translation @endif" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition)) data-condition="{{$condition}}" @endif {{ $classLangs }}>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>

    @php
        $value = str_replace('\\', '', $value);
    @endphp

    @php
        $value = str_replace('\\', '', $value);
    @endphp

    @foreach($langs as $key => $item)
        <div style="height: 100%;" class="{{get_lang_class($key, $item)}}" @if(!empty($item)) data-lang="{{$item}}" @endif>
            <div id="gmz-field-{{$id}}{{get_lang_suffix($item)}}" class="gmz-quill-editor" name="{{$id}}{{get_lang_suffix($item)}}">
                {!! get_translate($value, $item) !!}
            </div>
            <textarea class="gmz-editor-content gmz-hidden-field" name="{{$id}}{{get_lang_suffix($item)}}">{!! get_translate($value, $item) !!}</textarea>
        </div>
    @endforeach
</div>
<div class="clearfix"></div>