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
       $price_range = get_price_range('apartment');
       $apartment_types = get_terms('name','apartment-type');
       $apartment_amenity = get_terms('name','apartment-amenity');
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
   $checkInTime = request()->get('checkInTime', '');
   $checkOutTime = request()->get('checkOutTime', '');
   $checkInOutTime = request()->get('checkInOutTime', '');
   $startTime = request()->get('startTime', '');
   $endTime = request()->get('endTime', '');
   $bookingType = request()->get('bookingType', 'day');

   $adult = (int)request()->get('adult', 1);
   $children = (int)request()->get('children', 0);
   $infant = (int)request()->get('infant', 0);
@endphp
<form id="search-apartment" method="GET" class="search-form apartment" action="{{url('apartment-search')}}">
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

        <!--For time-->
        <input type="text" class="input-hidden check-in-out-time-field align-self-end" name="checkInOutTime" value="{{$checkInOutTime}}">
        <input type="text" class="input-hidden check-in-time-field"
               name="checkInTime" value="{{$checkInTime}}">
        <input type="text" class="input-hidden check-out-time-field"
               name="checkOutTime" value="{{$checkOutTime}}">
        <div class="search-form__from-time time-group @if($bookingType == 'day') d-none @endif">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-time-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkInTime))
                    {{date(get_date_format(), strtotime($checkInTime))}}
                @else
                    {{__('Date')}}
                @endif
            </span>
        </div>

        <div class="search-form__time time-group @if($bookingType == 'day') d-none @endif">
            <div class="dropdown">
                <div class="dropdown-toggle" id="dropdownTimeButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fal fa-clock"></i>
                    <span class="time-render">
                        @php
                            $time_str = __('Time');
                            if(!empty($startTime) && !empty($endTime)){
                                $startTimeTemp = $startTime;
                                $endTimeTemp = $endTime;
                                if($startTimeTemp == '12:00 AM'){
                                    $startTimeTemp = '00:00 AM';
                                }
                                if($endTimeTemp == '12:00 AM'){
                                    $endTimeTemp = '00:00 AM';
                                }
                                $time_str = $startTimeTemp . ' - ' . $endTimeTemp;
                            }
                        @endphp
                        {{$time_str}}
                    </span>
                </div>
                @php
                    $list_times = get_list_time();
                @endphp
                <div class="dropdown-menu" aria-labelledby="dropdownTimeButton">
                    <div class="item start-time d-flex align-items-center justify-content-between">
                        <div class="label">{{__('Start Time')}}</div>
                        <div class="value">
                            <select class="form-control" name="startTime">
                                @foreach($list_times as $ktime => $vtime)
                                    <option value="{{$ktime}}" {{selected($ktime, $startTime)}}>{{$vtime}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="item end-time d-flex align-items-center justify-content-between">
                        <div class="label">{{__('End Time')}}</div>
                        <div class="value">
                            <select class="form-control" name="endTime">
                                @if(!empty($startTime) && !empty($endTime))
                                    @foreach($list_times as $ktime => $vtime)
                                        <option value="{{$ktime}}" {{selected($ktime, $endTime)}}>{{$vtime}}</option>
                                    @endforeach
                                @else
                                    <option value="">---------- </option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--End for time-->

        <input type="text" class="input-hidden check-in-out-field align-self-end"
               name="checkInOut" value="{{$checkInOut}}" data-same-date="false">
        <input type="text" class="input-hidden check-in-field"
               name="checkIn" value="{{$checkIn}}">
        <input type="text" class="input-hidden check-out-field"
               name="checkOut" value="{{$checkOut}}">
        <div class="search-form__from date-group @if($bookingType == 'hour') d-none @endif">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkIn))
                    {{date(get_date_format(), strtotime($checkIn))}}
                @else
                    {{__('Check In')}}
                @endif
            </span>
        </div>
        <div class="search-form__to date-group @if($bookingType == 'hour') d-none @endif">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-out-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkOut))
                    {{date(get_date_format(), strtotime($checkOut))}}
                @else
                    {{__('Check Out')}}
                @endif
            </span>
        </div>

        <div class="search-form__guest apartment">
            <div class="dropdown">
                <div class="dropdown-toggle" id="dropdownGuestButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fal fa-users"></i>
                    <span class="guest-render">
                        @php
                            $guests = $adult + $children;
                            $str_guest = sprintf(_n(__('%s Guest'), __('%s Guests'), $guests), $guests);
                            if($infant > 0){
                                $str_guest .= sprintf(_n(__(', %s Infant'), __(', %s Infants'), $infant), $infant);
                            }
                        @endphp
                        {{$str_guest}}
                    </span>
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdownGuestButton">
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

                    <div class="item d-flex align-items-center justify-content-between">
                        <div class="label">{{__('Infants')}}</div>
                        <div class="value">
                            <select class="form-control" name="infant">
                                @for($i = 0; $i <= 20; $i++)
                                    <option value="{{$i}}" {{selected($i, $infant)}}>{{$i}}</option>
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
                    @if(!empty($apartment_types))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Types')}}</div>
                            @foreach($apartment_types as $key => $type)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="apartment_types[]" value="{{$key}}"><span>{{get_translate($type)}}</span></label>
                            @endforeach
                            <input type="hidden" name="apartment_type" value=""/>
                        </div>
                    @endif

                    @if(!empty($apartment_amenity))
                        <div class="col-md-6 gmz-checkbox-wrapper">
                            <div class="search-form__label">{{__('Amenities')}}</div>
                            @foreach($apartment_amenity as $key => $value)
                                <label class="checkbox-inline"><input type="checkbox" class="gmz-checkbox-item" name="apartment_amenities[]" value="{{$key}}"><span>{{get_translate($value)}}</span></label>
                            @endforeach
                            <input type="hidden" name="apartment_amenity" value=""/>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</form>