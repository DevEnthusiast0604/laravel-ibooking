@foreach(['type', 'amenity'] as $tax)
@php
    $terms = get_terms('name','apartment-' . $tax);
    $label = '';
    $post_data = request()->get('apartment_' . $tax);
    if(empty($post_data)){
        $post_data = [];
    }
    if(!empty($post_data)){
        $post_data = explode(',', $post_data);
    }

    if($tax == 'type'){
        $label = __('Types');
    }elseif($tax == 'amenity'){
        $label = __('Amenities');
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
                            <label class="checkbox-inline">
                                <input type="checkbox" class="gmz-checkbox-item" name="apartment_{{$tax}}s[]" value="{{$key}}" @if(in_array($key, $post_data)) checked @endif><span>{{get_translate($term)}}</span>
                            </label>
                        </div>
                    @endforeach
                    <input type="hidden" name="apartment_{{$tax}}" value=""/>
                </div>
            @endif
        </div>
    </div>
</div>
@endforeach