@php
    if(empty($value)){
        $value = $std;
    }
@endphp
@php
    admin_enqueue_styles('flatpickr');
    admin_enqueue_scripts('flatpickr');
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <input type="text"
           name="{{$id}}"
           data-plugin="date-picker"
           class="form-control @if(!empty($validation)) gmz-validation @endif"
           id="gmz-field-{{$id}}"
           value="{{$value}}"
           data-validation="{{ $validation }}"/>
</div>
@if($break)
    <div class="w-100"></div> @endif