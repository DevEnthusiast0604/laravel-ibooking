<div class="modal-header">
    <h5 class="modal-title">{{__('ROOM DETAILS')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6">
            @php
                $gallery = $data['gallery'];
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
            @endphp
            <section class="gallery">
                <div class="room-gallery fotorama" id="room-detail-gallery" data-allowfullscreen="true" data-nav="thumbs">
                    @foreach($galleries as $item)
                        <img src="{{$item}}" alt="room slider">
                    @endforeach
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <h4 class="title">{{get_translate($data['post_title'])}}</h4>
            <div class="base-price">
                {!! sprintf(__('Base price: %s'), '<span>' . convert_price($data['base_price']) . '</span>') !!}
            </div>

            <div class="room-meta">
                <div class="i-meta" data-toggle="tooltip" title="{{__('Room Size')}}">
                    <span class="i-meta__icon">{!! get_icon('icon_system_size_2') !!}</span>
                    <span class="i-meta__figure">{{$data['room_footage']}} {{get_option('unit_of_measure', 'm2')}} </span>
                </div>
                <div class="i-meta" data-toggle="tooltip" title="{{__('Bedroom')}}">
                    <span class="i-meta__icon">{!! get_icon('icon_system_bed_2') !!}</span>
                    <span class="i-meta__figure">x{{$data['number_of_bed']}}</span>
                </div>
                <div class="i-meta" data-toggle="tooltip" title="{{__('Adult')}}">
                    <span class="i-meta__icon">{!! get_icon('icon_system_children') !!}</span>
                    <span class="i-meta__figure">x{{$data['number_of_adult']}}</span>
                </div>
                <div class="i-meta" data-toggle="tooltip" title="{{__('Children')}}">
                    <span class="i-meta__icon">{!! get_icon('icon_system_adult') !!}</span>
                    <span class="i-meta__figure">x{{$data['number_of_children']}}</span>
                </div>
            </div>

            @php
                $facilities = $data['room_facilities'];
            @endphp

            @if(!empty($facilities))
                @php
                    $facilities = explode(',', $facilities);
                @endphp
                <div class="room-facilities">
                    <div class="fac-title">
                        {{__('Facilities')}}
                    </div>
                    <div class="row">
                        @foreach($facilities as $k => $v)
                            @php
                                $faci = get_term('id', $v);
                            @endphp
                            @if($faci)
                            <div class="col-md-6 item">
                                @if(!empty($faci->term_icon))
                                    @if(strpos($faci->term_icon, ' fa-'))
                                        <i class="{{$faci->term_icon}} term-icon"></i>
                                    @else
                                        {!! get_icon($faci->term_icon) !!}
                                    @endif
                                @endif
                                <span>{{get_translate($faci->term_title)}}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @php
                $post_content = get_translate($data['post_content']);
            @endphp
            @if(!empty($post_content))
                <div class="room-desc">
                    {!! balance_tags($post_content) !!}
                </div>
            @endif
        </div>
    </div>
</div>