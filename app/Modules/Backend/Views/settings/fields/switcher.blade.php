@php
    admin_enqueue_styles('gmz-switches');
    if(empty($value))
        $value = $std;
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label>{{__($label)}}</label><br />
    <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
        <input id="gmz-field-{{$id}}" type="checkbox" @if($value == 'on') checked @endif class="for-switcher">
        <span class="slider round"></span>
        <input type="hidden" name="{{$id}}" value="{{$value}}" />
    </label>
</div>