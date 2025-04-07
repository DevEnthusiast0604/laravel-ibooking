@extends('Frontend::layouts.master')

@section('title', __('Contact Us'))
@section('class_body', 'page contact-us')

@php
    enqueue_styles('mapbox-gl');
    enqueue_styles('mapbox-gl-geocoder');
    enqueue_scripts('mapbox-gl');
    enqueue_scripts('mapbox-gl-geocoder');
@endphp

@section('content')
    @php
        the_breadcrumb([], 'page', [
            'title' => __('Contact Us')
        ]);
        $feature_image = get_option('contact_feature_image');
        $feature_image = get_attachment_url($feature_image);
    @endphp
   <section class="partner-form">
       @if(!empty($feature_image))
           <img src="{{$feature_image}}" alt="contact-us" class="contact-feature-image"/>
       @endif
       <div class="container">
           <div class="row">
               <div class="col-lg-5 partner-form__left">
                   <div class="become-form">
                       <h2 class="title">{{__('Contact Us')}}</h2>
                       <form class="gmz-form-action" action="{{url('contact-us')}}" method="POST">
                            @include('Frontend::components.loader')
                           <div class="row">
                               <div class="form-group col-lg-6">
                                   <label for="full-name">{{__('Full Name')}}<span class="required">*</span> </label>
                                   <input type="text" name="full_name"  class="form-control gmz-validation" data-validation="required" id="full-name"/>
                               </div>
                               <div class="form-group col-lg-6">
                                   <label for="email">{{__('Email')}}</label>
                                   <input type="text" name="email"  class="form-control gmz-validation" data-validation="required" id="email"/>
                               </div>
                           </div>
                           <div class="form-group">
                               <label for="subject">{{__('Subject')}}<span class="required">*</span> </label>
                               <input type="text" name="subject"  class="form-control gmz-validation" data-validation="required" id="subject"/>
                           </div>
                           <div class="form-group">
                               <label for="content">{{__('Content')}}<span class="required">*</span> </label>
                               <textarea name="content" rows="3" class="form-control " id="content"></textarea>
                           </div>
                            <div class="gmz-message"></div>
                           <button type="submit" class="btn btn-primary">{{__('SUBMIT REQUEST')}}</button>
                       </form>
                   </div>
               </div>
               <div class="col-lg-7 partner-form__right">
                   @php
                    $heading = get_translate(get_option('contact_heading'));
                    $description = get_translate(get_option('contact_description'));
                    $address = get_translate(get_option('contact_address'));
                    $phone = get_option('contact_phone');
                    $email = get_option('contact_email');
                   @endphp
                   <div class="become-intro">
                       @if(!empty($heading))
                        <h3>{{esc_html($heading)}}</h3>
                       @endif
                       @if(!empty($description))
                        <p class="description">{{esc_html($description)}}</p>
                       @endif
                       @if(!empty($address))
                       <p class="meta">{{sprintf(__('Address: %s'), esc_html($address))}}</p>
                       @endif
                       @if(!empty($phone))
                       <p class="meta">{{sprintf(__('Phone: %s'), esc_html($phone))}}</p>
                       @endif
                       @if(!empty($email))
                       <p class="meta">{{sprintf(__('Email: %s'), esc_html($email))}}</p>
                       @endif
                   </div>
               </div>
           </div>
       </div>
   </section>
    <section class="map-single" data-lat="{{floatval(get_option('contact_map_lat'))}}" data-lng="{{floatval(get_option('contact_map_lng'))}}"></section>
@stop

