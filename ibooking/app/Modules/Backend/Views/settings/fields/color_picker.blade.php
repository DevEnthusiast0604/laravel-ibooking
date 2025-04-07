@php
    admin_enqueue_styles('gmz-spectrum');
    admin_enqueue_scripts('gmz-spectrum');
    if(empty($value)){
        $value = $std;
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label><br>
    <input type="text" name="{{$id}}" class="form-control gmz-color-picker" id="gmz-field-{{$id}}" value="{{$value}}"/>
</div>