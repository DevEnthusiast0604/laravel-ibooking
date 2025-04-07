@php
    $booking_type = $post['booking_type'];
    $start = request()->get('check_in', date('Y-m-d'));
    $end = request()->get('check_out', date('Y-m-d'));
    $booking_form = $post['booking_form'];
@endphp
<div class="booking-form tour">
    <div class="booking-form__heading">
        <span class="price-label">{{__('Price')}}</span><span class="price-value">{{convert_price($post['adult_price'])}}</span>
        <div id="booking-form-close" class="close">+</div>
    </div>
    <div class="booking-form__content">
        @php
            $enable = true;
            if($booking_type == 'package'){
                if(strtotime($post['package_start_date']) < strtotime(date('Y-m-d'))){
                    $enable = false;
                }
            }
        @endphp
        @if(!$enable)
            <div class="alert alert-warning mb-0">{{__('This tour is not available.')}}</div>
        @else
            @if($booking_form == 'both')
                <ul class="nav nav-tabs" id="bookingTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="instant-tab" data-toggle="tab" href="#instant" role="tab" aria-controls="instant" aria-selected="true">{{__('Instant')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="enquiry-tab" data-toggle="tab" href="#enquiry" role="tab" aria-controls="enquiry" aria-selected="false">{{__('Enquiry')}}</a>
                    </li>
                </ul>
            @endif
            @if($booking_form == 'both')
                <div class="tab-content" id="mybookingTabContent">
                    @endif
                    @if($booking_form == 'both' || $booking_form == 'instant')
                        <div class="tab-pane fade show active" id="instant" role="tabpanel" aria-labelledby="instant-tab">
                            @if($booking_type == 'external_link')
                                <a href="{{esc_url($post['external_link'])}}" class="btn btn-primary btn-book-now mt-0">{{__('BOOK NOW')}}</a>
                                <small>
                                    <span class="text-danger">*</span>
                                    {{__('External link')}}
                                </small>
                            @else
                                <form class="gmz-form-action booking-form-single tour" action="{{ url('tour-add-cart') }}" method="POST" data-price-url="{{url('tour-get-real-price')}}">
                                <input type="hidden" name="post_type" value="{{GMZ_SERVICE_TOUR}}"/>
                                <input type="hidden" name="post_id" value="{{$post['id']}}"/>
                                <input type="hidden" name="post_hashing" value="{{gmz_hashing($post['id'])}}"/>
                                @include('Frontend::components.loader')
                                @if($booking_type == 'package')
                                    <div class="booking-date single">
                                        <label>{{__('Date')}}</label>
                                        <div class="booking-date__intime">
                                            <div class="render">{{esc_html(date(get_date_format(), strtotime($post['package_start_date'])))}} - {{esc_html(date(get_date_format(), strtotime($post['package_end_date'])))}}</div>
                                            <input type="hidden" name="check_in" value="{{esc_attr($post['package_start_date'])}}"/>
                                            <input type="hidden" name="check_out" value="{{esc_attr($post['package_end_date'])}}"/>
                                        </div>
                                    </div>
                                @else
                                    <div class="booking-date single" data-date-format="{{get_date_format_moment()}}" data-action="{{ url('tour-fetch-calendar-availability') }}" data-id="{{$post['id']}}" data-hashing="{{gmz_hashing($post['id'])}}" data-post_type="{{GMZ_SERVICE_TOUR}}">
                                        <label>{{__('Date')}}</label>
                                        <input type="text" class="input-hidden date-input" name="check_in_out_time" value=""/>
                                        <div class="booking-date__intime">
                                            <div class="render">{{esc_html(date(get_date_format(), strtotime($start)))}}</div>
                                            <input type="hidden" name="check_in" value="{{esc_attr($start)}}"/>
                                        </div>
                                    </div>
                                @endif

                                <div class="booking-quantity">
                                    <div class="label">
                                        {{__('Adults')}}
                                    </div>
                                    <div class="value">
                                        <select class="form-control" name="adult">
                                            @php
                                                $guests = $post['group_size'];
                                            @endphp
                                            @if($guests > 0)
                                                @for($i = 1; $i <= $guests; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="booking-quantity">
                                    <div class="label">
                                        {{__('Children')}}
                                    </div>
                                    <div class="value">
                                        <select class="form-control" name="children">
                                            @if($guests > 0)
                                                @for($i = 0; $i <= $guests; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="booking-quantity">
                                    <div class="label">
                                        {{__('Infants')}}
                                    </div>
                                    <div class="value">
                                        <select class="form-control" name="infant">
                                            @if($guests > 0)
                                                @for($i = 0; $i <= $guests; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $extras = maybe_unserialize($post['extra_services']);
                                @endphp
                                @if(!empty($extras) && $extras != '[]')
                                    <div class="booking-equipment">
                                        <div class="accordion" id="accordionEquipment">
                                            <div class="card">
                                                <div class="card-header" id="headingEquipment">
                                                    <div class="card-header-panel collapsed" data-toggle="collapse" data-target="#collapseEquipment" aria-expanded="false" aria-controls="collapseEquipment">
                                                        {{__('Extra Services')}}
                                                        <i class="fal fa-chevron-down"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseEquipment" class="collapse" aria-labelledby="headingEquipment" data-parent="#accordionEquipment">
                                                    <div class="card-body">
                                                        @foreach($extras as $key => $val)
                                                            @if($val['required'] == 'on')
                                                                <div class="item">
                                                                    <div class="name">
                                                                        <label class="checkbox-inline">
                                                                            <input type="checkbox" name="extras[]" value="{{$key}}" checked onclick="return false;"><span>{{get_translate($val['title'])}}</span>
                                                                            <small class="required">({{__('required')}})</small>
                                                                        </label>
                                                                    </div>
                                                                    <div class="price">
                                                                        {{convert_price($val['price'])}}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @foreach($extras as $key => $val)
                                                            @if($val['required'] == 'off')
                                                                <div class="item">
                                                                    <div class="name">
                                                                        <label class="checkbox-inline">
                                                                            <input type="checkbox" name="extras[]" value="{{$key}}"><span>{{get_translate($val['title'])}}</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="price">
                                                                        {{convert_price($val['price'])}}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="gmz-message"></div>
                                <button type="submit" class="btn btn-primary btn-book-now">{{__('BOOK NOW')}}</button>
                            </form>
                            @endif
                        </div>
                    @endif
                    @if($booking_form == 'both' || $booking_form == 'enquiry')
                        <div class="tab-pane fade show" id="enquiry" role="tabpanel" aria-labelledby="enquiry-tab">
                            <form class="gmz-form-action enquiry-form-single" action="{{ url('tour-send-enquiry') }}" method="POST">
                                <input type="hidden" name="post_id" value="{{$post['id']}}"/>
                                <input type="hidden" name="post_hashing" value="{{gmz_hashing($post['id'])}}"/>
                                @include('Frontend::components.loader')
                                <div class="form-group">
                                    <label for="full-name">{{__('Full Name')}}<span class="required">*</span> </label>
                                    <input type="text" name="full_name"  class="form-control gmz-validation" data-validation="required" id="full-name"/>
                                </div>
                                <div class="form-group">
                                    <label for="email">{{__('Email')}}<span class="required">*</span></label>
                                    <input type="text" name="email"  class="form-control gmz-validation" data-validation="required" id="email"/>
                                </div>
                                <div class="form-group">
                                    <label for="content">{{__('Message')}}<span class="required">*</span> </label>
                                    <textarea name="content" rows="4" class="form-control gmz-validation" data-validation="required" id="content"></textarea>
                                </div>
                                <div class="gmz-message"></div>
                                <button type="submit" class="btn btn-primary">{{__('SUBMIT REQUEST')}}</button>
                            </form>
                        </div>
                    @endif
                    @if($booking_form == 'both')
                </div>
            @endif
        @endif
    </div>
</div>