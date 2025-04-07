@php
    $booking_form = $post['booking_form'];
$external_booking = $post['external_booking'];
@endphp
<div class="booking-form beauty">
    <div class="booking-form__heading">
        <span class="price-label">{{__('Price')}}</span>
        <span class="price-value">{{convert_price($post['base_price'])}}</span>
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
                    <div class="booking-form--beauty" id="beautyBookingForm"
                     data-action="{{url('/beauty-get-booking-form')}}"
                     data-post-slug="{{request()->route('slug')}}"
                     data-base-price="{{convert_price($post['base_price'])}}">
                    @include('Frontend::components.loader')
                    <div class="select-date">
                        <label for="beautyBookingForm__date">{{__('Date')}}</label>
                        <input type="text" class="form-control" name="daterange" id="beautyBookingForm__date"
                               data-date-format="{{get_date_format_moment()}}"/>
                    </div>
                    <div class="booking-form__content"></div>
                </div>
                @endif
            </div>
        @endif

        @if($booking_form == 'both' || $booking_form == 'enquiry')
            <div class="tab-pane fade show" id="enquiry" role="tabpanel" aria-labelledby="enquiry-tab">
                <form class="gmz-form-action enquiry-form-single" action="{{ url('beauty-send-enquiry') }}" method="POST">
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