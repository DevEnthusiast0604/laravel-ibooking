@php
    $gallery = $post['gallery'];
    $galleries = [];
    if(!empty($gallery)){
        $gallery = explode(',', $gallery);
        if(!empty($gallery)){
            foreach($gallery as $item){
                $url = get_attachment_url($item);
                if(!empty($url)){
                    array_push($galleries, $url);
                }
            }
        }
    }
    $video = $post['video'];
@endphp
@if(!empty($galleries))
    @php
        enqueue_styles('slick');
        enqueue_scripts('slick');
        enqueue_styles('magnific-popup');
        enqueue_scripts('magnific-popup');
    @endphp
    <section class="gallery">
        @if(!empty($video))
            @php
                $video = get_video_url($video);
            @endphp
            <a href="{{$video}}" class="video gmz-iframe-popup" data-effect="mfp-zoom-in">
                <i class="fal fa-photo-video"></i>
                <span>{{__('Video')}}</span>
            </a>
        @endif
        @if(count($galleries) < 2)
            <div class="gmz-single-image-with-lightbox">
                @foreach($galleries as $item)
                    <a href="{{$item}}">
                        <img src="{{$item}}" alt="home slider">
                    </a>
                @endforeach
            </div>
        @else
            <div class="gmz-carousel-with-lightbox" data-count="{{count($galleries)}}">
                @foreach($galleries as $item)
                    <a href="{{$item}}">
                        <img src="{{$item}}" alt="home slider">
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endif