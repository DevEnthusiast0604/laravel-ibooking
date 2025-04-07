@php
    if(empty($value)){
        $value = $std;
    }
    admin_enqueue_scripts('ace');
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
        <div id="gmz-field-{{ $id }}"
             data-value="{{ json_decode($value) }}"
             data-plugin="acejs"></div>
        <input type="hidden" name="{{$id}}" value="{{$value}}">
    @if($description)
        <small>{!! balance_tags(__($description)) !!}</small>
    @endif
</div>
@if($break)
    <div class="w-100"></div> @endif