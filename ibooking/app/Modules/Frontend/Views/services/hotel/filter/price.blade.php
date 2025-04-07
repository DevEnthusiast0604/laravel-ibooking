@php
    enqueue_styles([
       'icon.rangeSlider'
    ]);

     enqueue_scripts([
       'icon.rangeSlider'
    ]);

    $price_range = get_price_range(GMZ_SERVICE_HOTEL);
    $extension_range = get_range_extension();
@endphp
<div class="filter-item price">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fal fa-usd-circle"></i> {{__('Price')}}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <input type="text" name="price_range" value=""
                   data-min="{{$price_range['min']}}"
                   data-max="{{$price_range['max']}}"
                   data-from="{{$price_range['from']}}"
                   data-to="{{$price_range['to']}}"
                   data-prefix="{{$extension_range['prefix']}}"
                   data-postfix="{{$extension_range['postfix']}}"
            />
        </div>
    </div>
</div>