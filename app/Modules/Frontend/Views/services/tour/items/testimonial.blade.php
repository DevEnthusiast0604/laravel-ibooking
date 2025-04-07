@php
    $testimonial = get_option('tour_testimonials');
@endphp
@if(!empty($testimonial))
<section class="testimonial py-50">
    <div class="container">
        <div class="carousel-s2">
            @foreach($testimonial as $item)
                @php
                    $name = get_translate($item['name']);
                    $content = get_translate($item['content']);
                @endphp
                <div class="testimonial-item text-white text-center">
                    <i class="fas fa-quote-left fa-3x"></i>
                    <p class="testimonial-item__comment">{{esc_html($content)}}</p>
                    <p class="testimonial-item__author">{{esc_html($name)}}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif