@php
    $desc = get_translate(get_option('payment_'. $id .'_desc'));
@endphp
@if(!empty($desc))
    {!! $desc !!}
@endif