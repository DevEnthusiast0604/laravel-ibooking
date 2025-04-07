@php
    render_flag_option();
    $data = [];
    $data['term_object'] = [];
    $seo_fields = admin_config('page', 'seo');
    $i = 0;
    $variable_type = 'page';
@endphp
@include('Backend::components.loader')
@include('Backend::screens.admin.seo.components.variable')
@if($seo_fields['items'])
    @foreach($seo_fields['items'] as $key => $page)
        @php
            $page_id = $page['id'];
            $options = get_opt('seo_page_' . $page_id, '');
            $seo_enable = isset($options['seo_enable']) ? $options['seo_enable'] : 'off';
            $data_temp = [
                'seo_title' => isset($options['seo_title']) ? $options['seo_title'] : '',
                'meta_description' => isset($options['meta_description']) ? $options['meta_description'] : '',
                'seo_image_facebook' => isset($options['seo_image_facebook']) ? $options['seo_image_facebook'] : '',
                'seo_title_facebook' => isset($options['seo_title_facebook']) ? $options['seo_title_facebook'] : '',
                'meta_description_facebook' => isset($options['meta_description_facebook']) ? $options['meta_description_facebook'] : '',
                'seo_image_twitter' => isset($options['seo_image_twitter']) ? $options['seo_image_twitter'] : '',
                'seo_title_twitter' => isset($options['seo_title_twitter']) ? $options['seo_title_twitter'] : '',
                'meta_description_twitter' => isset($options['meta_description_twitter']) ? $options['meta_description_twitter'] : '',
            ];
        @endphp
        <div id="toggleAccordion{{$page['id']}}" class="mt-4">
            <div class="card">
                <div class="card-header" id="headingSeparator{{$page['id']}}">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="{{$i !== 0 ? 'collapsed' : ''}}" data-toggle="collapse" data-target="#defaultAccordionSeparator{{$page['id']}}" aria-expanded="{{$i == 0 ? 'true' : 'false'}}" aria-controls="defaultAccordionSeparator{{$page['id']}}">
                <span class="item-title">
                   {{$page['label']}}
                </span>
                            <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg></div>
                        </div>
                    </section>
                </div>

                <div id="defaultAccordionSeparator{{$page['id']}}" class="collapse {{$i == 0 ? 'show' : ''}}" aria-labelledby="headingSeparator{{$page['id']}}" data-parent="#toggleAccordion{{$page['id']}}">
                    <div class="card-body">

                        {{--Enable Option--}}
                        @php
                            admin_enqueue_styles('gmz-switches');
                        @endphp
                        <div class="gmz-field gmz-field-switcher">
                            <label>{{__('SEO Enable')}}</label><br>
                            <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
                                <input id="gmz-field-tax_included" type="checkbox" class="for-switcher" {{$seo_enable == 'on' ? 'checked' : ''}}>
                                <span class="slider round"></span>
                                <input type="hidden" name="seo_enable_{{$page['id']}}" value="{{$seo_enable}}">
                            </label>
                        </div>

                        <ul class="nav nav-tabs mt-3" id="seo-{{$page['id']}}-tabs" role="tablist">
                            <li class="nav-item mb-0">
                                <a class="nav-link active" id="seo-{{$page['id']}}-tab" data-toggle="tab" href="#seo-{{$page['id']}}" role="tab" aria-controls="seo-{{$page['id']}}" aria-selected="true"><i class="fas fa-cubes"></i> {{__('General')}}</a>
                            </li>
                            <li class="nav-item mb-0">
                                <a class="nav-link" id="seo-facebook-{{$page['id']}}-tab" data-toggle="tab" href="#seo-facebook-{{$page['id']}}" role="tab" aria-controls="seo-facebook-{{$page['id']}}" aria-selected="false"><i class="fab fa-facebook"></i> {{__('Facebook')}}</a>
                            </li>
                            <li class="nav-item mb-0">
                                <a class="nav-link" id="seo-twitter-{{$page['id']}}-tab" data-toggle="tab" href="#seo-twitter-{{$page['id']}}" role="tab" aria-controls="seo-twitter-{{$page['id']}}" aria-selected="false"><i class="fab fa-twitter"></i> {{__('Twitter')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content mb-4" id="seo-tabsContent">
                            <div class="tab-pane fade show active" id="seo-{{$page['id']}}" role="tabpanel" aria-labelledby="seo-{{$page['id']}}-tab">
                                <div class="mt-4">
                                    @php $fields = $seo_fields['fields']['general']; @endphp
                                    @include('Backend::settings.seo')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="seo-facebook-{{$page['id']}}" role="tabpanel" aria-labelledby="seo-facebook-{{$page['id']}}-tab">
                                <div class="mt-4">
                                    @php $fields = $seo_fields['fields']['facebook']; @endphp
                                    @include('Backend::settings.seo')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="seo-twitter-{{$page['id']}}" role="tabpanel" aria-labelledby="seo-twitter-{{$page['id']}}-tab">
                                <div class="mt-4">
                                    @php $fields = $seo_fields['fields']['twitter']; @endphp
                                    @include('Backend::settings.seo')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php $i++;  @endphp
    @endforeach
@endif