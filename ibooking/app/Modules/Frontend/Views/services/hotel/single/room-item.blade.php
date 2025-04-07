@if(isset($error))
    <div class="alert alert-warning">
        {{$error}}
    </div>
@else
@if(!$data->isEmpty())
    @php
        $check_in = $postData['check_in'];
        $check_out = $postData['check_out'];
    @endphp
    @if(!empty($postData))
        @foreach($postData as $pk => $pv)
            @if(!is_array($pv))
            <input type="hidden" name="{{$pk}}" value="{{$pv}}" />
            @endif
        @endforeach
    @endif
    @foreach($data as $item)
        @php
            $post_title = get_translate($item['post_title']);
            $thumbnail_id = $item['thumbnail_id'];
            $thumbnail_url = get_attachment_url($thumbnail_id, [360, 240]);
            $params = [
               'post_id' => $item['id'],
               'post_hashing' => gmz_hashing($item['id'])
           ];
        @endphp
        <div class="room-item room-item--list">
            <div class="row">
                <div class="col-4">
                    <div class="room-item__thumbnail">
                        <a href="javascript:void(0)" class="gmz-open-modal" data-target="#gmz-room-detail-modal" data-action="{{url('room-detail')}}" data-params="{{ base64_encode(json_encode($params)) }}">
                            <img src="{{$thumbnail_url}}" alt="{{$post_title}}">
                        </a>
                    </div>
                </div>
                <div class="col-8">
                    <div class="room-item__details">
                        <div>
                            <h3 class="room-item__title">
                            <a href="javascript:void(0)" class="gmz-open-modal" data-target="#gmz-room-detail-modal" data-action="{{url('room-detail')}}" data-params="{{ base64_encode(json_encode($params)) }}">{{$post_title}}</a>
                        </h3>
                            <div class="room-item__meta">
                            <div class="i-meta" data-toggle="tooltip" title="{{__('Room Size')}}">
                                <span class="i-meta__icon">{!! get_icon('icon_system_size_2') !!}</span>
                                <span class="i-meta__figure">{{$item['room_footage']}} {{get_option('unit_of_measure', 'm2')}} </span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="{{__('Bedroom')}}">
                                <span class="i-meta__icon">{!! get_icon('icon_system_bed_2') !!}</span>
                                <span class="i-meta__figure">x{{$item['number_of_bed']}}</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="{{__('Adult')}}">
                                <span class="i-meta__icon">{!! get_icon('icon_system_children') !!}</span>
                                <span class="i-meta__figure">x{{$item['number_of_adult']}}</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="{{__('Children')}}">
                                <span class="i-meta__icon">{!! get_icon('icon_system_adult') !!}</span>
                                <span class="i-meta__figure">x{{$item['number_of_children']}}</span>
                            </div>
                        </div>
                        </div>
                        @if(!empty($check_in) && !empty($check_out))
                            @php
                                $number_days = gmz_date_diff(strtotime($check_in), strtotime($check_out));
                                $price = get_room_price($item, $check_in, $check_out);
                                $number_rooms = $item['number_of_room'];
                                if(in_array($item['id'], array_keys($numberRooms))){
                                    $number_rooms = $numberRooms[$item['id']];
                                }
                            @endphp
                        <div class="room-price-wrapper">
                            <div class="price">
                                <span>{{convert_price($price)}}</span>/{{sprintf(_n(__('%s night'), __('%s nights'), $number_days), $number_days)}}
                            </div>
                            <div class="number-room">
                                <select name="room[{{$item['id']}}][number]" class="form-control">
                                    <option value="0">{{0}}</option>
                                    @if(!empty($number_rooms) && $number_rooms > 0)
                                        @for($i = 1; $i <= $number_rooms; $i++)
                                            @php
                                                $price_day = convert_price($price * $i);
                                                $option_text =  $i . ' &nbsp; ('. $price_day .')';
                                            @endphp
                                            <option value="{{$i}}">
                                                {!! $option_text !!}
                                            </option>
                                        @endfor
                                    @endif
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-warning">
        {{__('No rooms available!')}}
    </div>
@endif
@endif
