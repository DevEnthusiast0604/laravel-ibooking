@php
    $copy_right = get_option('footer_copyright');
@endphp
<div class="footer-wrapper">
    <div class="footer-section f-section-1">
        <p class="">
            @if(!empty($copy_right))
                {{ get_translate($copy_right) }}
            @else
                ©{{date('Y')}} iBooking - All rights reserved.
            @endif
        </p>
    </div>
</div>

@include('Backend::components.modal')
