<div class="meta">
    @php
        $property_type = $post['property_type'];
        $checkin_time = $post['checkin_time'];
        $checkout_time = $post['checkout_time'];
        $min_day_booking = $post['min_day_booking'];
        $min_day_stay = $post['min_day_stay'];
    @endphp
    <ul>
        @if(!empty($property_type))
            @php
                $property_type = get_term('id', $property_type);
            @endphp
            @if($property_type)
                <li>
                    <span class="value">{{get_translate($property_type->term_title)}}</span>
                    <span class="label">{{__('Type')}}</span>
                </li>
            @endif
        @endif

        @if(!empty($checkin_time))
            <li>
                <span class="value">{{$checkin_time}}</span>
                <span class="label">{{__('Checkin')}}</span>
            </li>
        @endif

        @if(!empty($checkout_time))
            <li>
                <span class="value">{{$checkout_time}}</span>
                <span class="label">{{__('Checkout')}}</span>
            </li>
        @endif

        @if(!empty($min_day_booking))
            <li>
                <span class="value">{{sprintf(_n(__('%s day'), __('%s days'), $min_day_booking), $min_day_booking)}}</span>
                <span class="label">{{__('M.D.B.B')}}<span class="text-danger">*</span></span>
            </li>
        @endif

        @if(!empty($min_day_stay))
            <li>
                <span class="value">{{sprintf(_n(__('%s day'), __('%s days'), $min_day_stay), $min_day_stay)}}</span>
                <span class="label">{{__('M.D Stay')}}<span class="text-danger">**</span></span>
            </li>
        @endif

    </ul>
    @if(!empty($min_day_booking))
    <div><small><span class="text-danger">*</span> {{__('Min day before booking')}}</small></div>
    @endif
    @if(!empty($min_day_stay))
    <div><small><span class="text-danger">**</span> {{__('Min day stay')}}</small></div>
    @endif
</div>