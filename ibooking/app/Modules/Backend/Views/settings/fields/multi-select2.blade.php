@php
    admin_enqueue_styles('select2');
    admin_enqueue_scripts('select2');

    if(empty($value)){
      $value = [];
    }
    $dataName = 'list_data_' . $choices;
@endphp

<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper"
    @if(!empty($condition)) data-condition="{{$condition}}" @endif>
    <label>{{__($label)}}</label><br/>

    @if(!empty($serviceData[$dataName]))
        <label class="gmz-multi-select2 w-100">
            <select class="form-control" data-plugin="select2" multiple="multiple" name="{{$choices}}[]">
                @foreach($serviceData[$dataName] as $key => $val)
                    <option <?php if (in_array($val, $value)) echo 'selected'; ?> value="{{$val}}">{{$key}}</option>
                @endforeach
            </select>
        </label>
    @else
        <p>
            <small><i>{{__('No data')}}</i></small>
        </p>
    @endif
</div>