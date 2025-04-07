@php
    $desc = get_translate(get_option('payment_'. $id .'_desc'));
@endphp

<div id="stripe-card-element" data-public-key="{{get_option("payment_stripe_api_key")}}">
    <!-- A Stripe Element will be inserted here. -->
</div>

<div id="card-errors" role="alert"></div>

@if(!empty($desc))
    {!! $desc !!}
@endif