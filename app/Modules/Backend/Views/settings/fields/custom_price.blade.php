<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/4/20
 * Time: 16:43
 */

admin_enqueue_styles('fullcalendar');
admin_enqueue_styles('flatpickr');
admin_enqueue_styles('gmz-calendar');
admin_enqueue_scripts('gmz-date');
admin_enqueue_scripts('fullcalendar');
admin_enqueue_scripts('gmz-calendar-init');
admin_enqueue_scripts('flatpickr');

$gmz_params = [
	'locale' => \Illuminate\Support\Facades\App::getLocale(),
    'timezone' => \Illuminate\Support\Facades\Config::get('app.timezone'),
    'text' => [
    	'refresh' => __('Refresh'),
        'today' => __('Today'),
        'price' => __('Price'),
        'unavailable' => __('Unavailable'),
        'booked' => __('Booked'),
        'onBooking' => __('On Booking'),
        'number' => __('Number'),
    ]
];
if($post_type == 'tour'){
    $gmz_params['text']['adultPrice'] = __('A');
    $gmz_params['text']['childrenPrice'] = __('C');
    $gmz_params['text']['infantPrice'] = __('I');
    $gmz_params['text']['groupSize'] = __('G');
}
$agentService = '';
if($post_type == 'agent'){
    $route = \Illuminate\Support\Facades\Route::current();
    if($route->hasParameter('service')){
        $agentService = $route->parameter('service');
    }
}
?>
<div class="calendar-wrapper col-12" data-post-id="{{$post_id}}" data-post-type="{{$post_type}}" data-params="{{json_encode($gmz_params)}}" data-action="{{dashboard_url('get-availability')}}" data-agent-service="{{$agentService}}">
    <div class="col-right">
        <div class="calendar-content">

        </div>
        <div class="overlay">
            <span class="spinner is-active"></span>
        </div>
        @if($post_type == 'tour')
            <ul class="mt-4 pl-0 ml-0">
            <li><b>A:</b> {{__('Adult Price')}}</li>
            <li><b>C:</b> {{__('Children Price')}}</li>
            <li><b>I:</b> {{__('Infant Price')}}</li>
            <li><b>G:</b> {{__('Group Size')}}</li>
            </ul>
        @endif
    </div>
    <div class="col-left">
        <div class="calendar-form">
            <div class="form-group">
                <label for="calendar_check_in"><strong>{{__('Check In')}}</strong></label>
                <input readonly="readonly" type="text" class="date-picker form-control" data-plugin="date-picker" name="calendar_check_in" id="calendar_check_in" placeholder="{{__('Check In')}}">
            </div>
            <div class="form-group">
                <label for="calendar_check_out"><strong>{{__('Check Out')}}</strong></label>
                <input readonly="readonly" type="text" class="date-picker form-control" data-plugin="date-picker" name="calendar_check_out" id="calendar_check_out" placeholder="{{__('Check Out')}}">
            </div>
             @if(!in_array($post_type, ['agent', 'tour']))
            <div class="form-group">
                <label for="calendar_price"><strong>{{__('Price')}}</strong></label>
                <input type="text" name="calendar_price" id="calendar_price" class="form-control" placeholder="{{__('Price')}}">
            </div>
            @endif
            @if($post_type == 'room')
                <input type="hidden" name="calendar_hotel_id" id="calendar_hotel_id" value="{{$serviceData['hotel_id']}}" class="form-control">
                <input type="hidden" name="calendar_hotel_hashing" id="calendar_hotel_hashing" value="{{gmz_hashing($serviceData['hotel_id'])}}" class="form-control">
            @endif
            @if($post_type == 'tour')
                <div class="form-group">
                    <label for="calendar_adult_price"><strong>{{__('Adult Price')}}</strong></label>
                    <input type="text" name="calendar_adult_price" id="calendar_adult_price" class="form-control" value="{{$serviceData['adult_price']}}">
                </div>
                <div class="form-group">
                    <label for="calendar_children_price"><strong>{{__('Children Price')}}</strong></label>
                    <input type="text" name="calendar_children_price" id="calendar_children_price" class="form-control" value="{{$serviceData['children_price']}}">
                </div>
                <div class="form-group">
                    <label for="calendar_infant_price"><strong>{{__('Infant Price')}}</strong></label>
                    <input type="text" name="calendar_infant_price" id="calendar_infant_price" class="form-control" value="{{$serviceData['infant_price']}}">
                </div>
                <div class="form-group">
                    <label for="calendar_group_size"><strong>{{__('Group Size')}}</strong></label>
                    <input type="number" min="1" max="100" step="1" name="calendar_group_size" id="calendar_group_size" class="form-control" value="{{$serviceData['group_size']}}">
                </div>
            @endif

            @action('gmz_custom_price_field', $post_id, $post_type)
            
            <div class="form-group">
                <label for="calendar_status"><strong>{{__('Status')}}</strong></label>
                <select name="calendar_status" id="calendar_status" class="form-control">
                    @if($post_type != 'agent')
                    <option value="available">{{__('Available')}}</option>
                    @endif
                    <option value="unavailable">{{__('Unavailble')}}</option>
                    <option value="remove">{{__('Remove from calendar')}}</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-message">
                </div>
            </div>
            <div class="form-group" >
                <input type="hidden" name="calendar_post_id" value="{{$post_id}}">
                <input type="hidden" name="calendar_post_type" value="{{$post_type}}">
                @if($post_type == 'agent')
                    <input type="hidden" name="calendar_agent_service" value="{{$agentService}}" />
                @endif
                <input type="submit" id="calendar_submit" class="btn btn-success" name="calendar_submit" value="{{__('Set Availability')}}" data-action="{{dashboard_url('add-availability')}}">
            </div>
        </div>
        <p style="margin-top: 25px;"><i>{{__('You can select and drag dates to set a range of date')}}</i></p>
        @if($post_type != 'agent')
        <div class="box-caption">
            <ul>
                <li><span class="is-custom"></span>{{__('Custom price')}}</li>
                @if(in_array($post_type, ['room', 'tour', 'car']))
                <li><span class="is-base"></span>{{__('On Booking')}}</li>
                @endif
                @if($post_type != 'beauty')
                    <li><span class="is-booked"></span>{{__('Booked')}}</li>
                @endif
                <li><span class="is-unavailable"></span>{{__('Unavailable')}}</li>
            </ul>
        </div>
        @endif
    </div>
</div>
