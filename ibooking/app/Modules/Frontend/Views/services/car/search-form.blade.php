@php
    $has_advanced = true;
   if(isset($advanced) && !$advanced){
       $has_advanced = false;
   }

   enqueue_styles([
       'mapbox-gl',
       'mapbox-gl-geocoder'
    ]);
    enqueue_scripts([
       'mapbox-gl',
       'mapbox-gl-geocoder'
    ]);

   if($has_advanced){
       $price_range = get_price_range('car');
       $car_types = get_terms('name','car-type');
       $car_features = get_terms('name','car-feature');
       $car_equipments = get_terms('name','car-equipment');
       $extension_range = get_range_extension();

       enqueue_styles([
          'icon.rangeSlider'
       ]);

        enqueue_scripts([
          'icon.rangeSlider'
       ]);
   }

   $address = request()->get('address', '');
   $lat = request()->get('lat', '');
   $lng = request()->get('lng', '');
   $checkIn = request()->get('checkIn', '');
   $checkOut = request()->get('checkOut', '');
   $checkInOut = request()->get('checkInOut', '');
@endphp
<form id="search-car" method="GET" class="search-form car" action="{{url('car-search')}}">
    <div class="search-form__basic">
        <div class="search-form__address">
            <i class="fal fa-car-building"></i>
            <div class="form-control h-100 border-0" data-plugin="mapbox-geocoder" data-value="{{$address}}"
                 data-placeholder="{{__('Location')}}" data-lang="{{get_current_language()}}">
            </div>
            <div class="map d-none"></div>
            <input type="hidden" name="lat" value="{{$lat}}">
            <input type="hidden" name="lng" value="{{$lng}}">
            <input type="hidden" name="address" value="{{$address}}">
        </div>
        <input type="text" class="input-hidden check-in-out-field align-self-end"
               name="checkInOut" value="{{$checkInOut}}">
        <div class="search-form__from">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkIn))
                    {{date(get_date_format(), strtotime($checkIn))}}
                @else
                    {{__('Pick-up')}}
                @endif
            </span>
        </div>
        <div class="search-form__to">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-out-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkOut))
                    {{date(get_date_format(), strtotime($checkOut))}}
                @else
                    {{__('Return')}}
                @endif
            </span>
        </div>
        <input type="text" class="input-hidden check-in-field"
               name="checkIn" value="{{$checkIn}}">
        <input type="text" class="input-hidden check-out-field"
               name="checkOut" value="{{$checkOut}}">
        @if($has_advanced)
            <button class="btn search-form__more" type="button"><i class="fal fa-search-plus"></i></button>
        @endif
        <button class="btn btn-primary search-form__search" type="submit"><i class="fal fa-search"></i>{{__('Search')}}
        </button>
    </div>

    @if($has_advanced)
        <div class="search-form__advanced bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-form__label">{{__('Price')}}</div>
                        <input type="text" class="price-range-slider" name="price_range" value=""
                               data-min="{{$price_range['min']}}"
                               data-max="{{$price_range['max']}}"
                               data-form="{{$price_range['from']}}"
                               data-to="{{$price_range['to']}}"
                               data-prefix="{{$extension_range['prefix']}}"
                               data-postfix="{{$extension_range['postfix']}}"
                        />

                    </div>
                    @if(!empty($car_types))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Types')}}</div>
                            @foreach($car_types as $key => $type)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="car_types[]" value="{{$key}}"><span>{{get_translate($type)}}</span></label>
                            @endforeach
                            <input type="hidden" name="car_type" value=""/>
                        </div>
                    @endif

                    @if(!empty($car_features))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Features')}}</div>
                            @foreach($car_features as $key => $value)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="car_features[]" value="{{$key}}"><span>{{get_translate($value)}}</span></label>
                            @endforeach
                            <input type="hidden" name="car_feature" value=""/>
                        </div>
                    @endif

                    @if(!empty($car_equipments))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Equipments')}}</div>
                            @foreach($car_equipments as $key => $value)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="car_equipments[]" value="{{$key}}"><span>{{get_translate($value)}}</span></label>
                            @endforeach
                            <input type="hidden" name="car_equipment" value=""/>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif
</form>