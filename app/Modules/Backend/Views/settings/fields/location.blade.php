@php
    if(empty($value)){
        $value = $std;
    }

    if(!isset($hide_real_address)){
       $hide_real_address = false;
    }

    if(!empty($value) && !is_array($value)){
       $value = json_decode($value, true);
    }

    $value = gmz_parse_args($value, [
        'address' => '',
        'city' => '',
        'state' => '',
        'postcode' => '',
        'country' => '',
        'lat' => 48.856613,
        'lng' => 2.352222,
        'zoom' => 13
    ]);
    $idName = str_replace(['[', ']'], '_', $id);

    admin_enqueue_styles('mapbox-gl');
    admin_enqueue_styles('mapbox-gl-geocoder');
    admin_enqueue_scripts('mapbox-gl');
    admin_enqueue_scripts('mapbox-gl-geocoder');
    $langs = get_languages_field();
@endphp

<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}} @if($translation_ext == true) gmz-field-has-translation @endif" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>

    @if(!$hide_real_address)
    <div class="row _gmz-row-real-address">
        <div class="col">
            <div class="form-group">
                <label for="{{ $idName }}_address_">{{__('Real Address')}}</label>
                @foreach($langs as $key => $item)
                    <input type="text" class="form-control gmz-real-address @if(!empty($validation)) gmz-validation @endif {{get_lang_class($key, $item)}}" name="{{ $idName }}[address]{{!empty($item) ? '['. $item .']' : ''}}" id="{{ $idName }}_address{{get_lang_suffix($item)}}" value="{{ get_translate($value['address'], $item) }}" @if(!empty($item)) data-lang="{{$item}}" @endif/>
                @endforeach
            </div>
        </div>
        <div class="w-100 mb-3"></div>
    </div>
    @endif

    <div class="row">
        <div class="col">
            <div class="mapbox-wrapper">
                <div class="form-group mapbox-text-search">
                    <div class="form-control" data-plugin="mapbox-geocoder" data-value="" data-placeholder="{{__('Search on the map')}}"></div>
                    <input type="text" class="input-none gmz-address" name="" id="{{ $idName }}_search_address" value="" />
                </div>

                <div id="{{ $idName }}_mapbox" class="mapbox-content"
                     data-lat="{{ (float)$value['lat'] }}" data-lng="{{ (float)$value['lng'] }}"
                     data-zoom="{{ $value['zoom'] }}"></div>
                <input type="text" class="input-none gmz-zoom" name="{{ $id }}[zoom]"
                       value="{{ $value['zoom'] }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-sm-6 col-12">
            <div class="form-group field-no-translate">
                <label for="{{ $idName }}_lat_">{{__('Latitude')}}</label>
                <input type="text" class="form-control gmz-lat" name="{{ $id }}[lat]"
                       id="{{ $idName }}_lat_" value="{{ $value['lat'] }}" readonly>
            </div>
        </div>
        <div class="col col-sm-6 col-12">
            <div class="form-group field-no-translate">
                <label for="{{ $idName }}_lng_">{{__('Longtitude')}}</label>
                <input type="text" class="form-control gmz-lng" name="{{ $id }}[lng]"
                       id="{{ $idName }}_lng_" value="{{ $value['lng'] }}" readonly>
            </div>
        </div>

        @action('gmz_after_location_field', $id, $idName, $value)
        
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif