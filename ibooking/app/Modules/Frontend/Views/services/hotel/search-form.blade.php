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
       $price_range = get_price_range(GMZ_SERVICE_HOTEL);
       $property_types = get_terms('name','property-type');
       $hotel_facilities = get_terms('name','hotel-facilities');
       $hotel_services = get_terms('name','hotel-services');
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

   $number_room = (int)request()->get('number_room', 1);
   $adult = (int)request()->get('adult', 1);
   $children = (int)request()->get('children', 0);
@endphp
<form id="search-hotel" method="GET" class="search-form hotel" action="{{url('hotel-search')}}">
    <div class="search-form__basic">
        <div class="search-form__address">
            <i class="fal fa-city"></i>
            <div class="form-control h-100 border-0" data-plugin="mapbox-geocoder" data-value="{{$address}}"
                 data-placeholder="{{__('Location')}}" data-lang="{{get_current_language()}}">
            </div>
            <div class="map d-none"></div>
            <input type="hidden" name="lat" value="{{$lat}}">
            <input type="hidden" name="lng" value="{{$lng}}">
            <input type="hidden" name="address" value="{{$address}}">
        </div>

        <input type="text" class="input-hidden check-in-out-field align-self-end"
               name="checkInOut" value="{{$checkInOut}}" data-same-date="false">
        <input type="text" class="input-hidden check-in-field"
               name="checkIn" value="{{$checkIn}}">
        <input type="text" class="input-hidden check-out-field"
               name="checkOut" value="{{$checkOut}}">
        <div class="search-form__from date-group">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkIn))
                    {{date(get_date_format(), strtotime($checkIn))}}
                @else
                    {{__('Check In')}}
                @endif
            </span>
        </div>
        <div class="search-form__to date-group">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-out-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkOut))
                    {{date(get_date_format(), strtotime($checkOut))}}
                @else
                    {{__('Check Out')}}
                @endif
            </span>
        </div>

        <div class="search-form__guest hotel">
            <div class="dropdown">
                <div class="dropdown-toggle" id="dropdownGuestButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fal fa-users"></i>
                    <span class="guest-render">
                        @php
                            $str_guest = sprintf(_n(__('%s Adult'), __('%s Adults'), $adult), $adult);
                            if($children > 0){
                                $str_guest .= sprintf(__(', %s Children'), $children);
                            }
                        @endphp
                        {{$str_guest}}
                    </span>
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdownGuestButton">
                    <div class="item d-flex align-items-center justify-content-between">
                        <div class="label">{{__('Rooms')}}</div>
                        <div class="value">
                            <select class="form-control" name="number_room">
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{$i}}" {{selected($i, $number_room)}}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="item d-flex align-items-center justify-content-between">
                        <div class="label">{{__('Adults')}}</div>
                        <div class="value">
                            <select class="form-control" name="adult">
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{$i}}" {{selected($i, $adult)}}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="item d-flex align-items-center justify-content-between">
                        <div class="label">{{__('Children')}}</div>
                        <div class="value">
                            <select class="form-control" name="children">
                                @for($i = 0; $i <= 20; $i++)
                                    <option value="{{$i}}" {{selected($i, $children)}}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

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
                    <div class="col-md-6 mb-2">
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
                    @if(!empty($property_types))
                        <div class="col-md-6 gmz-checkbox-wrapper mb-2">
                            <div class="search-form__label">{{__('Property Types')}}</div>
                            @foreach($property_types as $key => $type)
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="gmz-checkbox-item" name="property_types[]" value="{{$key}}">
                                    <span>{{get_translate($type)}}</span>
                                </label>
                            @endforeach
                            <input type="hidden" name="property_type" value=""/>
                        </div>
                    @endif

                    @if(!empty($hotel_facilities))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Facilities')}}</div>
                            @foreach($hotel_facilities as $key => $value)
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="gmz-checkbox-item" name="hotel_facilitiess[]" value="{{$key}}">
                                    <span>{{get_translate($value)}}</span>
                                </label>
                            @endforeach
                            <input type="hidden" name="hotel_facilities" value=""/>
                        </div>
                    @endif

                    @if(!empty($hotel_services))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Services')}}</div>
                            @foreach($hotel_services as $key => $value)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="hotel_servicess[]" value="{{$key}}"><span>{{get_translate($value)}}</span></label>
                            @endforeach
                            <input type="hidden" name="hotel_services" value=""/>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</form>