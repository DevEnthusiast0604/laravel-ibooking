@php
    $include = $post['tour_include'];
    $exclude = $post['tour_exclude'];
@endphp
@if(!empty($include) && !empty($exclude))
    <section class="inex">
        <h2 class="section-title">{{__('Includes/Excludes')}}</h2>
        <div class="section-content">
            <div class="row">
            @if(!empty($include))
                <div class="col-lg-6 include">
                @php
                    $includes = explode(',', $include);
                    foreach ($includes as $v){
                        $term = get_term('id', $v);
                        if($term){
                            $term_desc = get_translate($term->term_description);
                            @endphp
                            <div class="item d-flex align-items-baseline">
                                <i class="fal fa-check"></i>
                                <div>
                                    <span class="d-block">{{get_translate($term->term_title)}}</span>
                                    @if(!empty($term_desc))
                                        <small>({{ $term_desc }})</small>
                                    @endif
                                </div>
                            </div>
                            @php
                        }
                    }
                @endphp
                </div>
            @endif
                @if(!empty($exclude))
                    <div class="col-lg-6 exclude">
                        @php
                            $excludes = explode(',', $exclude);
                            foreach ($excludes as $v){
                                $term = get_term('id', $v);
                                if($term){
                                    $term_desc = get_translate($term->term_description);
                                    @endphp
                                    <div class="item d-flex align-items-baseline">
                                        <i class="fal fa-times"></i>
                                        <div>
                                            <span class="d-block">{{get_translate($term->term_title)}}</span>
                                            @if(!empty($term_desc))
                                                <small>({{ $term_desc }})</small>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                    }
                                }
                        @endphp
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif