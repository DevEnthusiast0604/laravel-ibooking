@php
    if(empty($value))
        $value = $std;

    $media_url = '';
    if(!empty($value) && is_numeric($value)){
        $media_url = get_attachment_url($value, [150, 150]);
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <div class="media-wrapper @if(!empty($media_url)) has-media @endif">
        <div class="thumbnail" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}">
            <span class="add-icon">+</span>
            @if(!empty($media_url))
                <img src="{{$media_url}}" />
            @endif
        </div>
        <div class="action d-flex align-items-center">
            <a href="javascript:void(0)" class="text-success" data-toggle="modal" data-target="#gmzMediaModal" data-url="{{dashboard_url('all-media')}}">{{__('Add image')}}</a>
            <a href="javascript:void(0)" class="ml-3 text-danger btn-remove d-none">{{__('Remove')}}</a>
        </div>
        <input type="hidden" name="{{$id}}" class="form-control" id="gmz-field-{{$id}}" value="{{$value}}"/>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif