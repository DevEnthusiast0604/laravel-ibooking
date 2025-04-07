@php
    $desc = get_translate(get_option('payment_'. $id .'_desc'));
    $cart = Cart::inst()->getCart();
    $currency = get_option('primary_currency', 'USD');
    $total = round( (float) $cart['total'], 2 );
    $total *= 100;
    $checkoutRequest = [
        "charge" => [
            "amount" => $total,
            "currency" => $currency
        ]
    ];
    $securionpay_secret_key = get_option('payment_securionpay_secret_key');
    $signed_checkout_request = base64_encode(hash_hmac('sha256', json_encode($checkoutRequest), $securionpay_secret_key) . "|" . json_encode($checkoutRequest) );
@endphp
<div id="securionpay_checkout_request" data-checkout-request="{{json_encode($signed_checkout_request)}}" data-success-action="{{url('payment/securionpay/success')}}" data-error-action="{{url('payment/securionpay/error')}}"></div>
@if(!empty($desc))
    {!! $desc !!}
@endif
