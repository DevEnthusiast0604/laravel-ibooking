@php
    if(empty($value)){
        $value = $std;
    }
    $langs = $translation == false ? [""] : get_languages_field();
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}} @if($translation == true) gmz-field-has-translation @endif" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    @foreach($langs as $key => $item)
    <input type="text"
           name="{{$id}}{{get_lang_suffix($item)}}"
           class="form-control @if(!empty($validation)) gmz-validation @endif {{get_lang_class($key, $item)}}"
           id="gmz-field-{{$id}}{{get_lang_suffix($item)}}"
           value="{{ get_translate($value, $item) }}"
           data-validation="{{ $validation }}" @if(!empty($item))
           data-lang="{{$item}}" @endif/>
    @endforeach
    @if($description)
    <small>{!! balance_tags(__($description)) !!}</small>
    @endif
</div>
@if($break)
    <div class="w-100"></div> @endif