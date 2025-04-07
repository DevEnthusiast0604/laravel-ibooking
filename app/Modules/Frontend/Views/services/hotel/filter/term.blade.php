@foreach(['type', 'facilities', 'services'] as $tax)
@php
    if($tax == 'type'){
        $terms = get_terms('name','property-' . $tax);
    }else{
        $terms = get_terms('name','hotel-' . $tax);
    }
    $label = '';
    if($tax == 'type'){
        $post_data = request()->get('property_' . $tax);
    }else{
        $post_data = request()->get('hotel_' . $tax);
    }
    if(empty($post_data)){
        $post_data = [];
    }
    if(!empty($post_data)){
        $post_data = explode(',', $post_data);
    }

    if($tax == 'type'){
        $label = __('Types');
    }elseif($tax == 'facilities'){
        $label = __('Facilities');
    }elseif($tax == 'services'){
        $label = __('Services');

}
@endphp
<div class="filter-item term">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{$label}}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @if(!empty($terms))
                <div class="row gmz-checkbox-wrapper">
                    @foreach($terms as $key => $term)
                        <div class="col-md-4 col-6">
                            <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="apartment_{{$tax}}ss[]" value="{{$key}}" @if(in_array($key, $post_data)) checked @endif><span>{{get_translate($term)}}</span></label>
                        </div>
                    @endforeach
                    @if($tax == 'type')
                        <input type="hidden" name="property_{{$tax}}" value=""/>
                    @else
                        <input type="hidden" name="hotel_{{$tax}}" value=""/>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endforeach