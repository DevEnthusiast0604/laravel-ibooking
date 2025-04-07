@php
    admin_enqueue_styles('gmz-checkbox');
    if(empty($value)){
        $value = $std;
    }

    if(is_string($value) && $value !== 'all'){
        $value = json_decode($value, true);
    }

    if(!isset($choices))
        $choices = [];

    if(!isset($column))
        $column = 'col-lg-12';

    if(!is_array($choices) && strpos($choices, ':')){
        $choices_arr = explode(':', $choices);
        if(isset($choices_arr[0])){
            switch ($choices_arr[0]){
                case 'term':
                    $choices = get_terms_recursive([], get_terms($choices_arr[1], $choices_arr[2], 'full'), 0, false);
                    break;
            }
        }
    }

    $langs = $translation == false ? [""] : get_languages_field();
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>

    @if(!empty($choices))
        <div class="row">
            @foreach($choices as $key => $val)
                <div class="{{$column}} n-chk mb-2">
                    <label class="new-control new-checkbox checkbox-primary">
                        <input type="checkbox" name="{{$id}}[]" class="new-control-input" value="{{$key}}" @if($value == 'all' || (!empty($value) && in_array($key, $value))) checked @endif />
                        <span class="new-control-indicator"></span>
                        @foreach($langs as $key => $item)
                            <span class="{{get_lang_class($key, $item)}}" @if(!empty($item))
                            data-lang="{{$item}}" @endif>
                            {{ get_translate($val, $item) }}
                            </span>
                        @endforeach
                    </label>
                </div>
            @endforeach
        </div>
    @else
        <p>
        <small><i>{{__('No data')}}</i></small>
        </p>
    @endif
</div>