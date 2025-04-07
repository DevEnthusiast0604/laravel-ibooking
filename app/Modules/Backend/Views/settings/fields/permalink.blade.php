@php
    if(empty($value))
        $value = $std;

    $post_type = get_config_posttype($post_type);
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}"><i data-feather="link"></i> {{__($label)}}</label>
    <div class="permalink-wrapper d-flex align-items-center">
        <span>{{url($post_type['slug'])}}/</span>
        <input type="text" name="{{$id}}" class="form-control" id="gmz-field-{{$id}}" value="{{$value}}"/>
    </div>
</div>