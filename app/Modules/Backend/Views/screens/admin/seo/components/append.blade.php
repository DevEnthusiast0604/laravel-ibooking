@if(is_seo_enable())
    @php
        $options = get_seo_content_config('single_' . $post_type);
    @endphp
    @if(isset($options['seo_enable']) && $options['seo_enable'] == 'on')
        @php
            $page_id = $post_type;
            $data_temp = [
                'seo_title' => isset($serviceData['seo']['seo_title']) ? $serviceData['seo']['seo_title'] : '',
                'meta_description' => isset($serviceData['seo']['meta_description']) ? $serviceData['seo']['meta_description'] : '',
                'seo_image_facebook' => isset($serviceData['seo']['seo_image_facebook']) ? $serviceData['seo']['seo_image_facebook'] : '',
                'seo_title_facebook' => isset($serviceData['seo']['seo_title_facebook']) ? $serviceData['seo']['seo_title_facebook'] : '',
                'meta_description_facebook' => isset($serviceData['seo']['meta_description_facebook']) ? $serviceData['seo']['meta_description_facebook'] : '',
                'seo_image_twitter' => isset($serviceData['seo']['seo_image_twitter']) ? $serviceData['seo']['seo_image_twitter'] : '',
                'seo_title_twitter' => isset($serviceData['seo']['seo_title_twitter']) ? $serviceData['seo']['seo_title_twitter'] : '',
                'meta_description_twitter' => isset($serviceData['seo']['meta_description_twitter']) ? $serviceData['seo']['meta_description_twitter'] : '',
            ];
        @endphp
        <div class="statbox widget box box-shadow mt-3">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{__('SEO Options')}}</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area pl-2 pr-2">
                @include('Backend::screens.admin.seo.components.variable', ['is_content_type' => true, 'variable_type' => 'content'])
                
                @php
                    $seo_fields = admin_config('content', 'seo');
                @endphp
                <form class="gmz-form-action form-translation" action="{{dashboard_url('seo-single-save-settings')}}" method="POST" data-loader="body" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="{{$serviceData['id']}}" />
                    <input type="hidden" name="post_id_hashing" value="{{gmz_hashing($serviceData['id'])}}" />
                    <input type="hidden" name="post_type" value="{{$post_type}}" />
                    <input type="hidden" name="post_type_hashing" value="{{gmz_hashing($post_type)}}" />
                    @include('Backend::components.loader')
                    <ul class="nav nav-tabs mt-3" id="seo-{{$post_type}}-tabs" role="tablist">
                        <li class="nav-item mb-0">
                            <a class="nav-link active" id="seo-{{$post_type}}-tab" data-toggle="tab" href="#seo-{{$post_type}}" role="tab" aria-controls="seo-{{$post_type}}" aria-selected="true"><i class="fas fa-cubes"></i> {{__('General')}}</a>
                        </li>
                        <li class="nav-item mb-0">
                            <a class="nav-link" id="seo-facebook-{{$post_type}}-tab" data-toggle="tab" href="#seo-facebook-{{$post_type}}" role="tab" aria-controls="seo-facebook-{{$post_type}}" aria-selected="false"><i class="fab fa-facebook"></i> {{__('Facebook')}}</a>
                        </li>
                        <li class="nav-item mb-0">
                            <a class="nav-link" id="seo-twitter-{{$post_type}}-tab" data-toggle="tab" href="#seo-twitter-{{$post_type}}" role="tab" aria-controls="seo-twitter-{{$post_type}}" aria-selected="false"><i class="fab fa-twitter"></i> {{__('Twitter')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="seo-tabsContent">
                        <div class="tab-pane fade show active" id="seo-{{$post_type}}" role="tabpanel" aria-labelledby="seo-{{$post_type}}-tab">
                            <div class="mt-4">
                                @php $fields = $seo_fields['fields']['general']; @endphp
                                @include('Backend::settings.seo')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="seo-facebook-{{$post_type}}" role="tabpanel" aria-labelledby="seo-facebook-{{$post_type}}-tab">
                            <div class="mt-4">
                                @php $fields = $seo_fields['fields']['facebook']; @endphp
                                @include('Backend::settings.seo')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="seo-twitter-{{$post_type}}" role="tabpanel" aria-labelledby="seo-twitter-{{$post_type}}-tab">
                            <div class="mt-4">
                                @php $fields = $seo_fields['fields']['twitter']; @endphp
                                @include('Backend::settings.seo')
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </form>
            </div>
        </div>
    @endif
@endif