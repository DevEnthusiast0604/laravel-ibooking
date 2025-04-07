@php
    if(!isset($tag) || empty($tag)){
        $tag = 'h4';
    }
    $classes = '';
    if($id == 'payment_heading' || $id == 'email_heading'){
        $classes = 'd-flex align-items-center';
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}} mb-3 mt-3 {{$classes}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <{{$tag}} {{'class="mb-0"'}}>{{__($label)}}<{{'/' . $tag}}>
    @if($id == 'payment_heading')
        <a href="javascript:void(0);" class="btn btn-primary btn-sm ml-2 pt-1 pb-1 gmz-open-modal" data-target="#gmzPaymentModal" data-action="{{dashboard_url('get-payment-form')}}" data-params="{{base64_encode(json_encode([]))}}">{{__('Sort')}}</a>
    @endif
    @if($id == 'email_heading')
        <a href="javascript:void(0);" class="btn btn-primary btn-sm ml-2 pt-1 pb-1 gmz-open-modal" data-target="#gmzCheckingEmailModal" data-action="{{dashboard_url('get-checking-email-form')}}" data-need-saving="true" data-params="{{base64_encode(json_encode([]))}}">{{__('Checking Email')}}</a>
    @endif
</div>
<div class="w-100"></div>