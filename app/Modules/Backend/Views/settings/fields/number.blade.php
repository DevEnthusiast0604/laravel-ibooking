@php
    if(empty($value)){
        $value = $std;
    }

    if(!isset($min_max_step) || count($min_max_step) != 3){
        $min_max_step = [0, 50, 1];
    }

@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <input
            @if($min_max_step[0] != -1) min="{{$min_max_step[0]}}" @endif
            @if($min_max_step[1] != -1) max="{{$min_max_step[1]}}" @endif
            @if($min_max_step[2] != -1) step="{{$min_max_step[2]}}" @endif
            type="number"
            name="{{$id}}"
            class="form-control"
            id="gmz-field-{{$id}}"
            value="{{$value}}"/>
</div>