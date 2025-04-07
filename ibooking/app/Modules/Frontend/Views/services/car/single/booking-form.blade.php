@php
$start = request()->get('check_in', date('Y-m-d'));
$end = request()->get('check_out', date('Y-m-d'));
$booking_form = $post['booking_form'];
$external_booking = $post['external_booking'];
@endphp
<div class="booking-form car">
    <div class="booking-form__heading">
        <span class="price-label">{{__('Price')}}</span><span class="price-value">{{convert_price($post['base_price'])}}</span><span class="price-unit">{{__('/day')}}</span>
        <div id="booking-form-close" class="close">+</div>
    </div>
    <div class="booking-form__content">
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
               @if($external_booking == 'on')
                   <a href="{{esc_url($post['external_link'])}}" class="btn btn-primary btn-book-now mt-0">{{__('BOOK NOW')}}</a>
                   <small>
                       <span class="text-danger">*</span>
                       {{__('External link')}}
                   </small>
               @else
                    <form class="gmz-form-action booking-form-single" action="{{ url('car-add-cart') }}" method="POST" data-price-url="{{url('car-get-real-price')}}">
            <input type="hidden" name="post_type" value="car"/>
            <input type="hidden" name="post_id" value="{{$post['id']}}"/>
            <input type="hidden" name="post_hashing" value="{{gmz_hashing($post['id'])}}"/>
            @include('Frontend::components.loader')
            <div class="booking-date range" data-date-format="{{get_date_format_moment()}}" data-action="{{ url('car-fetch-calendar-availability') }}" data-id="{{$post['id']}}" data-hashing="{{gmz_hashing($post['id'])}}" data-post_type="car">
                <label>{{__('Date')}}</label>
                <input type="text" class="input-hidden date-input" name="check_in_out" value=""/>
                <div class="booking-date__in">
                    <div class="render">{{esc_html(date(get_date_format(), strtotime($start)))}}</div>
                    <input type="hidden" name="check_in" value="{{esc_attr($start)}}"/>
                </div>
                <div class="booking-date__out">
                    <div class="render">{{esc_html(date(get_date_format(), strtotime($end)))}}</div>
                    <input type="hidden" name="check_out" value="{{esc_attr($end)}}"/>
                </div>
            </div>
            <div class="booking-quantity">
                <div class="label">
                    {{__('Number')}}
                </div>
                <div class="value">
                    <select class="form-control" name="number">
                        @if($post['quantity'] > 0)
                            @for($i = 1; $i <= $post['quantity']; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        @endif
                    </select>
                </div>
            </div>
            @php
                $equipments = $post['car_equipment'];
                $equipments_custom = maybe_unserialize($post['equipments']);
            @endphp
            @if(!empty($equipments))
            <div class="booking-equipment">
                <div class="accordion" id="accordionEquipment">
                    <div class="card">
                        <div class="card-header" id="headingEquipment">
                            <div class="card-header-panel collapsed" data-toggle="collapse" data-target="#collapseEquipment" aria-expanded="false" aria-controls="collapseEquipment">
                                {{__('Equipments')}}
                                <i class="fal fa-chevron-down"></i>
                            </div>
                        </div>
                        <div id="collapseEquipment" class="collapse" aria-labelledby="headingEquipment" data-parent="#accordionEquipment">
                            <div class="card-body">
                                @foreach($equipments_custom as $key => $val)
                                    @if($val['choose'] == 'yes')
                                        @php
                                            $term = get_term('id', $key);
                                            if(empty($val['price'])){
                                                $term_price = $term->term_price;
                                            }else{
                                                $term_price = $val['price'];
                                            }
                                        @endphp
                                        @if($term)
                                        <div class="item">
                                            <div class="name">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="equipment[]" value="{{$key}}"><span>{{get_translate($term->term_title)}}</span>
                                                </label>
                                            </div>
                                            <div class="price">
                                                {{convert_price($term_price)}}
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @php
                $insurance = maybe_unserialize($post['insurance_plan']);
            @endphp
            @if(!empty($insurance) && $insurance != '[]')
                <div class="booking-insurance">
                    <div class="accordion" id="accordionInsurance">
                        <div class="card">
                            <div class="card-header" id="headingInsurance">
                                <div class="card-header-panel collapsed" data-toggle="collapse" data-target="#collapseInsurance" aria-expanded="false" aria-controls="collapseInsurance">
                                    {{__('Insurance Plan')}}
                                    <i class="fal fa-chevron-down"></i>
                                </div>
                            </div>
                            <div id="collapseInsurance" class="collapse" aria-labelledby="headingInsurance" data-parent="#accordionInsurance">
                                <div class="card-body">
                                    @foreach($insurance as $key => $val)
                                        <div class="item">
                                            <div class="name">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="insurance[]" value="{{$key}}"><span>{{get_translate($val['title'])}}</span> <i class="fal fa-info-square desc" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="{{$val['description']}} @if($val['fixed'] == 'on')<span>{{__('Fixed Price')}}</span>@endif"></i>
                                                </label>
                                            </div>
                                            <div class="price">
                                                {{convert_price($val['price'])}}
                                            </div>
                                        </div>
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
               <form class="gmz-form-action enquiry-form-single" action="{{ url('car-send-enquiry') }}" method="POST">
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
    </div>
</div>