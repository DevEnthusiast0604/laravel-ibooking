@php
    render_flag_option();
    $default_field = get_option_default_fields();
@endphp

<div class="row">
@foreach($fields as $_key => $_val)
    @php
        $_val = array_merge( $default_field, $_val );
        if(isset($serviceData[$_val['id']])){
            $_val['value'] = $serviceData[$_val['id']];
        }
    @endphp
    @include('Backend::settings.fields.render', [
        'field' => $_val
    ])
@endforeach
</div>