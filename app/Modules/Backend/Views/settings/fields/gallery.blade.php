@php
    if(empty($value))
        $value = $std;

    $value_temp = $value;
    if(!empty($value)){
        $value = explode(',', $value);
    }
    admin_enqueue_scripts('jquery-ui');
    admin_enqueue_styles('jquery-ui');
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <div class="media-wrapper @if(!empty($value)) has-media @endif">
        @if(empty($value))
        <div class="thumbnail" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}" data-multi="true">
            <span class="add-icon">+</span>
            @if(!empty($media_url))
                <img src="{{$media_url}}" />
            @endif
        </div>
        @else
            @foreach($value as $key => $val)
                @php
                    $media_url = get_attachment_url($val, [150, 150]);
                @endphp
                <div class="thumbnail @if($key > 0) appended @endif" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}" data-multi="true" data-id="{{$val}}">
                    <span class="add-icon">+</span>
                    @if(!empty($media_url))
                        <img src="{{$media_url}}" />
                    @endif
                </div>
            @endforeach
        @endif
        <div class="action d-flex align-items-center">
            <a href="javascript:void(0)" class="text-success" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}" data-multi="true">{{__('Add image')}}</a>
            <a href="javascript:void(0)" class="ml-3 text-danger btn-remove d-none">{{__('Remove')}}</a>
        </div>
        <input type="hidden" name="{{$id}}" class="form-control" id="gmz-field-{{$id}}" value="{{$value_temp}}"/>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif