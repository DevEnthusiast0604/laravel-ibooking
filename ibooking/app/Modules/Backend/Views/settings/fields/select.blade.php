@php
    admin_enqueue_styles([
      'bootstrap-select',
     ]);
    admin_enqueue_scripts([
       'bootstrap-select',
    ]);

    if(empty($value)){
        $value = $std;
    }

    if(!isset($choices)){
        $choices = [];
    }

    $ext_class = '';
    $isIlangs = false;
    if(!empty($choices) && !is_array($choices)){
        $choices_arr = explode(':', $choices);
        if(isset($choices_arr[0])){
            switch ($choices_arr[0]){
                case 'page':
                    $pages = get_posts([
                     'post_type' => 'page'
                    ]);
                    $choices = [];
                    if(!$pages->isEmpty()){
                        foreach ($pages as $p){
                            $choices[$p['id']] = get_translate($p['post_title']);
                        }
                    }
                    break;
                case 'term':
                    $choices = get_terms_recursive([], get_terms($choices_arr[1], $choices_arr[2], 'full'));

                    if(isset($choices_arr[3]) && $choices_arr[3]){
                        $choices = array('0' => __('---No parent---')) + $choices;
                    }
                    if(isset($choices_arr[4]) && $choices_arr[4] == 'ex'){
                        $choices = array('0' => __('---No parent---')) + $choices;
                        if(isset($choices[$choices_arr[5]])){
                            unset($choices[$choices_arr[5]]);
                        }
                    }
                    break;
                case 'currency':
                    $choices = list_currencies(true);
                    break;
                case 'language':
                    $langs = config('locales.languages');
                    array_unshift($langs, __('Select language'));
                    $choices = $langs;
                    break;
                case 'menu':
                     $choices = get_navigation();
                   break;
                case 'time':
                    $choices = get_list_time();
                    break;
                case 'user':
                    $choices = get_users([
                        'role' => 1
                    ]);
                    break;
                case 'status':
                    $service = $choices_arr[1];
                    $need_approve = 'off';
                    if($service == GMZ_SERVICE_APARTMENT){
                        $need_approve = get_option('apartment_approve', 'off');
                    }elseif($service == GMZ_SERVICE_SPACE){
                        $need_approve = get_option('space_approve', 'off');
                    }elseif($service == GMZ_SERVICE_TOUR){
                        $need_approve = get_option('tour_approve', 'off');
                    }elseif($service == GMZ_SERVICE_CAR){
                        $need_approve = get_option('car_approve', 'off');
                    }elseif($service == GMZ_SERVICE_HOTEL){
                        $need_approve = get_option('hotel_approve', 'off');
                    }elseif($service == GMZ_SERVICE_BEAUTY){
                        $need_approve = get_option('beauty_approve', 'off');
                    }
                    if($need_approve == 'off'){
                        $choices = [
                            'publish' => __('Publish'),
                            'draft' => __('Draft')
                        ];
                        if(is_admin()){
                            $choices['pending'] = __('Pending');
                        }
                    }else{
                        $current_params = \Illuminate\Support\Facades\Route::current()->parameters();
                        $current_status = 'pending';
                        if(isset($current_params['id'])){
                            $post_object = get_post($current_params['id'], $service);
                            if($post_object){
                                $current_status = $post_object['status'];
                            }
                        }
                        if(is_admin()){
                            $choices = [
                                'publish' => __('Publish'),
                                'draft' => __('Draft'),
                                'pending' => __('Pending')
                            ];
                        }else{
                            if($current_status == 'pending'){
                                $ext_class = 'hidden';
                                $choices = [
                                    'pending' => __('Pending')
                                ];
                            }else{
                                 $choices = [
                                    'publish' => __('Publish'),
                                    'draft' => __('Draft')
                                ];
                            }
                        }
                    }
                    break;
            }
        }
    }else{
        $isIlangs = true;
    }
@endphp
<div class="gmz-field form-group {{$ext_class}} {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper"
     @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>
    <select name="{{$id}}" class="form-control" id="gmz-field-{{$id}}">
        @if($no_option)
            <option value="">{{__('Select a value')}}</option>
        @endif
        @if(!empty($choices))
            @foreach($choices as $key => $val)
                <option value="{{$key}}" @if($value == $key) selected @endif>
                    @if($isIlangs)
                        {{__($val)}}
                    @else
                        {{get_translate($val)}}
                    @endif
                </option>
            @endforeach
        @endif
    </select>
</div>
@if($break)
    <div class="w-100"></div> @endif