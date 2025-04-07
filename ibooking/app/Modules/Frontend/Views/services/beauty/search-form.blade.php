@php
    $has_advanced = true;
    if(isset($advanced) && !$advanced){
        $has_advanced = false;
    }

    enqueue_styles([
        'mapbox-gl',
        'mapbox-gl-geocoder',
        'select2'
     ]);
     enqueue_scripts([
        'mapbox-gl',
        'mapbox-gl-geocoder',
        'select2'
     ]);

    $address = request()->get('address', '');
    $lat = request()->get('lat', '');
    $lng = request()->get('lng', '');
    $checkInTime = request()->get('checkIn', '');
    $checkInOutTime = request()->get('checkInOutTime', '');
    $serviceInput = request()->get('service', '');
@endphp
<form id="search-beauty" method="GET" class="search-form beauty" action="{{url('beauty-search')}}">
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
        <input type="text" class="input-hidden check-in-out-time-field align-self-end" name="checkInOut"
               value="{{$checkInOutTime}}" disabled>
        <input type="text" class="input-hidden check-in-time-field"
               name="checkIn" value="{{$checkInTime}}">
        <div class="search-form__from-time time-group">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-time-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkInTime))
                    {{date(get_date_format(), strtotime($checkInTime))}}
                @else
                    {{__('Date')}}
                @endif
            </span>
        </div>
        <!--End for time-->

        <div class="search-form__select search-form__select--beauty">
           <i class="fal fa-spa"></i>
           <?php $services = get_terms('name', 'beauty-services');?>

            <select class="gmz-select-2 gmz-select-2--beauty-service d-none" name="service" id="beauty-service">
                <option value="-1">{{__("All Services")}}</option>
                @if($services)
                    @foreach($services as $id => $service)
                        <option value="{{$id}}" @if($id == $serviceInput) selected @endif>{{get_translate($service)}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <button class="btn btn-primary search-form__search" type="submit"><i class="fal fa-search"></i>{{__('Search')}}
        </button>
    </div>
</form>