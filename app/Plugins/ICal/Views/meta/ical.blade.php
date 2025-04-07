@php
$iCalUrl = url($post_type . '/' . $id . '/' . 'ical.ics');
@endphp
<div class="gmz-field form-group col-12 mt-2 gmz-field-ical" id="gmz-field-ical_export_url-wrapper">
    <label for="gmz-field-ical_export_url">{{__('Export iCal URL')}}</label>
    <input type="text" name="ical_export_url" class="form-control" id="gmz-field-ical_export_url" value="{{$iCalUrl}}">
    <button type="button" class="btn btn-dark" data-text="{{__('Copied')}}">{{__('Copy')}}</button>
</div>