@php
    admin_enqueue_styles([
      'bootstrap-select',
     ]);
    admin_enqueue_scripts([
       'bootstrap-select',
    ]);

    if(empty($value)){
        $value = $std;
    }

    if(!isset($choices)){
        $choices = [];
    }

    $ext_class = '';
    if(!empty($choices) && !is_array($choices)){
        $choices_arr = explode(':', $choices);
        if(isset($choices_arr[0])){
            switch ($choices_arr[0]){
                case 'term':
                    $dataTerm = get_terms($choices_arr[1], $choices_arr[2], 'full');
                    $choices = get_terms_recursive([], $dataTerm);

                    if(isset($choices_arr[3]) && $choices_arr[3]){
                        $choices = array('0' => __('---No parent---')) + $choices;
                    }
                    if(isset($choices_arr[4]) && $choices_arr[4] == 'ex'){
                        $choices = array('0' => __('---No parent---')) + $choices;
                        if(isset($choices[$choices_arr[5]])){
                            unset($choices[$choices_arr[5]]);
                        }
                    }
                    break;
            }
        }
    }

    if ($dataTerm->isEmpty()){
       $dataTerm = [];
    }else{
       $dataTerm = $dataTerm->toArray();
    }

@endphp
<div class="gmz-field form-group {{$ext_class}} {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper"
     @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <select name="{{$id}}" class="form-control" id="gmz-field-{{$id}}">
        @if($no_option)
            <option value="">{{__('Select a value')}}</option>
        @endif
        @if(!empty($choices))
            @foreach($choices as $key => $val)
                <option value="{{$key}}" @if($value == $key) selected @endif>{{get_translate($val)}}</option>
            @endforeach
        @endif
    </select>
    <div class="gmz-field-{{$type}}__metadata">
        @foreach($dataTerm as $v)
            <div class="__term-meta" style="display: none" data-term-id="{{$v['id']}}">
                <span>
                    {{__('Adddress')}}:
                    <i>{{get_translate($v['term_description'])}}</i>
                </span>
                <a href="{{dashboard_url('edit-term/'.$v['id'].'/beauty-branch')}}"><i class="fas fa-pen small"></i></a>
            </div>
        @endforeach
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif