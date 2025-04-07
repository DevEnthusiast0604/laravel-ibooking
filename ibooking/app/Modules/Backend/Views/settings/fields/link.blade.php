@php
    if(empty($value)){
        $value = $std;
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>

    <a class="gmz-infobox-link text-primary" href="{{$std}}" target="_blank">
        {{__('Visit page')}}
        <span class="ml-1 text-body font-weight-normal">{{$std}}</span>
    </a>

</div>
@if($break)
    <div class="w-100"></div>
@endif