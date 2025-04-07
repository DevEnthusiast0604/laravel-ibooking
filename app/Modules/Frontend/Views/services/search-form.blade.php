@php
    $all_services = get_services_enabled();
    $srvc = [];
    if(in_array(GMZ_SERVICE_HOTEL, $all_services)){
        array_push($srvc, GMZ_SERVICE_HOTEL);
    }
    if(in_array(GMZ_SERVICE_APARTMENT, $all_services)){
        array_push($srvc, GMZ_SERVICE_APARTMENT);
    }
    if(in_array(GMZ_SERVICE_CAR, $all_services)){
        array_push($srvc, GMZ_SERVICE_CAR);
    }
    if(in_array(GMZ_SERVICE_SPACE, $all_services)){
        array_push($srvc, GMZ_SERVICE_SPACE);
    }
    if(in_array(GMZ_SERVICE_TOUR, $all_services)){
        array_push($srvc, GMZ_SERVICE_TOUR);
    }
    if(in_array(GMZ_SERVICE_BEAUTY, $all_services)){
        array_push($srvc, GMZ_SERVICE_BEAUTY);
    }
@endphp
@if(count($srvc) > 0)
<div class="search-form-wrapper">
    @if(count($srvc) > 1)
    <ul class="nav nav-tabs" id="searchFormTab" role="tablist">
        @if(in_array(GMZ_SERVICE_HOTEL, $srvc))
            <li class="nav-item">
                <a class="nav-link active" id="hotel-search-tab" data-toggle="tab" href="#hotel-search" role="tab" aria-controls="hotel-search" aria-selected="true"><i class="fal fa-hotel"></i> {{__('Hotel')}}</a>
            </li>
        @endif
        @if(in_array(GMZ_SERVICE_APARTMENT, $srvc))
            @php
                if(!in_array('hotel', $srvc)){
                    $apartment_active = 'active';
                }else{
                    $apartment_active = '';
                }
            @endphp
        <li class="nav-item">
            <a class="nav-link {{$apartment_active}}" id="apartment-search-tab" data-toggle="tab" href="#apartment-search" role="tab" aria-controls="apartment-search" aria-selected="true"><i class="fal fa-city"></i> {{__('Apartment')}}</a>
        </li>
        @endif
        @if(in_array(GMZ_SERVICE_CAR, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc)){
                        $car_active = 'active';
                    }else{
                        $car_active = '';
                    }
                @endphp
            <li class="nav-item">
                <a class="nav-link {{$car_active}}" id="car-search-tab" data-toggle="tab" href="#car-search" role="tab" aria-controls="car-search" aria-selected="false"><i class="fal fa-car-alt"></i> {{__('Car')}}</a>
            </li>
        @endif
            @if(in_array(GMZ_SERVICE_SPACE, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc) && !in_array('car', $srvc)){
                        $space_active = 'active';
                    }else{
                        $space_active = '';
                    }
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{$space_active}}" id="space-search-tab" data-toggle="tab" href="#space-search" role="tab" aria-controls="space-search" aria-selected="false"><i class="fal fa-building"></i> {{__('Space')}}</a>
                </li>
            @endif
            @if(in_array(GMZ_SERVICE_TOUR, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc) && !in_array('car', $srvc) && !in_array('space', $srvc)){
                        $tour_active = 'active';
                    }else{
                        $tour_active = '';
                    }
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{$tour_active}}" id="tour-search-tab" data-toggle="tab" href="#tour-search" role="tab" aria-controls="tour-search" aria-selected="false"><i class="fal fa-map-marked"></i> {{__('Tour')}}</a>
                </li>
            @endif
            @if(in_array(GMZ_SERVICE_BEAUTY, $srvc))
                <li class="nav-item">
                    <a class="nav-link" id="beauty-search-tab" data-toggle="tab" href="#beauty-search" role="tab" aria-controls="beauty-search" aria-selected="false"><i class="fal fa-spa"></i> {{__('Beauty')}}</a>
                </li>
            @endif
    </ul>
    @endif
    <div class="tab-content" id="searchFormTab">
        @if(in_array(GMZ_SERVICE_HOTEL, $srvc))
            <div class="tab-pane fade show active hotel-search-form" id="hotel-search" role="tabpanel" aria-labelledby="hotel-search-tab">
                @include('Frontend::services.hotel.search-form')
            </div>
        @endif
        @if(in_array(GMZ_SERVICE_APARTMENT, $srvc))
            @php
                if(!in_array('hotel', $srvc)){
                    $apartment_active = 'show active';
                }else{
                    $apartment_active = '';
                }
            @endphp
        <div class="tab-pane fade {{$apartment_active}} apartment-search-form" id="apartment-search" role="tabpanel" aria-labelledby="apartment-search-tab">
            @include('Frontend::services.apartment.search-form')
        </div>
        @endif
        @if(in_array(GMZ_SERVICE_CAR, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc)){
                        $car_active = 'show active';
                    }else{
                        $car_active = '';
                    }
                @endphp
        <div class="tab-pane fade {{$car_active}} car-search-form" id="car-search" role="tabpanel" aria-labelledby="car-search-tab">
            @include('Frontend::services.car.search-form')
        </div>
        @endif

            @if(in_array(GMZ_SERVICE_SPACE, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc) && !in_array('car', $srvc)){
                        $space_active = 'show active';
                    }else{
                        $space_active = '';
                    }
                @endphp
                <div class="tab-pane fade {{$space_active}} space-search-form" id="space-search" role="tabpanel" aria-labelledby="space-search-tab">
                    @include('Frontend::services.space.search-form')
                </div>
            @endif

            @if(in_array(GMZ_SERVICE_TOUR, $srvc))
                @php
                    if(!in_array('hotel', $srvc) && !in_array('apartment', $srvc) && !in_array('car', $srvc)  && !in_array('space', $srvc)){
                        $tour_active = 'show active';
                    }else{
                        $tour_active = '';
                    }
                @endphp
                <div class="tab-pane fade {{$tour_active}} tour-search-form" id="tour-search" role="tabpanel" aria-labelledby="tour-search-tab">
                    @include('Frontend::services.tour.search-form')
                </div>
            @endif

            @if(in_array(GMZ_SERVICE_BEAUTY, $srvc))
                <div class="tab-pane fade @if($srvc[0] == GMZ_SERVICE_BEAUTY)show active @endif beauty-search-form" id="beauty-search" role="tabpanel" aria-labelledby="beauty-search-tab">
                    @include('Frontend::services.beauty.search-form')
                </div>
            @endif
    </div>
</div>
@endif