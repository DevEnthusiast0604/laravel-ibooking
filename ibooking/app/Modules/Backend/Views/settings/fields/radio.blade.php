@php
    if(empty($value))
        $value = $std;

    if(!isset($choices))
        $choices = [];

    if(!isset($column))
        $column = 'col-lg-12';

    if(!is_array($choices) && strpos($choices, ':')){
        $choices_arr = explode(':', $choices);
        if(isset($choices_arr[0])){
            switch ($choices_arr[0]){
                case 'term':
                    $choices = get_terms($choices_arr[1], $choices_arr[2]);
                    break;
            }
        }
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>

    @if(!empty($choices))
        <div class="row">
            @foreach($choices as $key => $val)
                <div class="{{$column}} n-chk mb-2">
                    <label class="new-control new-radio radio-classic-primary">
                        <input type="radio" name="{{$id}}" class="new-control-input" value="{{$key}}" @if($key == $value) checked @endif >
                        <span class="new-control-indicator"></span>{{$val}}
                    </label>
                </div>
            @endforeach
        </div>
    @endif
</div>