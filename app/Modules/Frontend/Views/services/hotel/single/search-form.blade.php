@php
   $checkIn = request()->get('checkIn', '');
   $checkOut = request()->get('checkOut', '');
   $checkInOut = request()->get('checkInOut', '');

   $number_room = (int)request()->get('number_room', 1);
   $adult = (int)request()->get('adult', 1);
   $children = (int)request()->get('children', 0);

    $min_day_before_booking = $post['min_day_booking'];
    $min_date = date('Y-m-d');
    if(!empty($min_day_before_booking) && is_numeric($min_day_before_booking)){
        $min_date = date('Y-m-d', strtotime('+ ' . $min_day_before_booking . ' days', strtotime($min_date)));
    }
@endphp
<form id="search-room" method="POST" class="search-form room" action="{{url('room-search')}}">
    <input type="hidden" name="hotel_id" value="{{$post['id']}}" />
    <div class="search-form__basic">
        <input type="text" class="input-hidden check-in-out-field align-self-end"
               name="checkInOut" value="{{$checkInOut}}" data-same-date="false" data-date-group="true" data-min-date="{{$min_date}}">
        <input type="hidden" class="input-hidden check-in-field"
               name="checkIn" value="{{$checkIn}}">
        <input type="hidden" class="input-hidden check-out-field"
               name="checkOut" value="{{$checkOut}}">
        <div class="search-form__from date-group">
            <i class="fal fa-calendar-alt"></i>
            <span class="check-in-render" data-date-format="{{get_date_format_moment()}}">
                @if(!empty($checkIn) && !empty($checkOut))
                    {{date(get_date_format(), strtotime($checkIn))}} - {{date(get_date_format(), strtotime($checkOut))}}
                @else
                    {{__('Check In-Out')}}
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
        <button class="btn btn-primary search-form__search" type="submit">{{__('CHECK AVAILABILITY')}}
        </button>
    </div>
</form>