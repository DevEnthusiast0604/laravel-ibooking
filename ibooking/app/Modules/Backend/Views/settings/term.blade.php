@php
    $default_field = get_option_default_fields();
    if(isset($is_user)){
        $data_temp = $data['user_object'];
    }elseif(isset($is_coupon)){
        $data_temp = $data['coupon_object'];
    }else{
        $data_temp = $data['term_object'];
    }
@endphp
@if(!empty($fields))
    <div class="row">
    @foreach($fields as $key => $val)
        @php
            $val = array_merge( $default_field, $val );
            if(!empty($data_temp)){
                if(isset($data_temp[$val['id']])){
                    $val['value'] = $data_temp[$val['id']];
                }
            }
            if(isset($data['term_id']) && !empty($data['term_id'])){
                if($val['id'] == 'parent'){
                    $val['choices'] .= ':ex:' . $data['term_id'];
                }
            }
        @endphp
        @include('Backend::settings.fields.render', [
            'field' => $val
        ])
    @endforeach
    </div>
@else
    <div class="alert alert-warning mb-0">{{__('No config fields')}}</div>
@endif