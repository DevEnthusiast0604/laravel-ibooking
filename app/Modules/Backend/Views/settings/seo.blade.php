@php
    $default_field = get_option_default_fields();
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
            $val_temp = $val['id'] . '_' . $page_id;
            $val['id'] = $val_temp;
        @endphp
        @include('Backend::settings.fields.render', [
            'field' => $val
        ])
    @endforeach
    </div>
@else
    <div class="alert alert-warning mb-0">{{__('No config fields')}}</div>
@endif