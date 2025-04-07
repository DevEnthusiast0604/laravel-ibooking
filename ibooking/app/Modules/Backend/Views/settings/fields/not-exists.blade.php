<div class="col-lg-6 clearfix" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <div class="alert alert-warning mb-4" role="alert">
        {{__('Field "' . $field['type'] . '" is not exists!')}}
    </div>
</div>
