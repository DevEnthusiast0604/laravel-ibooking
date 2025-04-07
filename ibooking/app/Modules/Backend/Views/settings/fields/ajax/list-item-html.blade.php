@php
    $unique = time() . rand(0, 9999);
@endphp
<div class="card">
    <div class="card-header" id="heading{{$unique}}">
        <section class="mb-0 mt-0">
            <div role="menu" class="collapsed" data-toggle="collapse" data-target="#defaultAccordion{{$unique}}" aria-expanded="true" aria-controls="defaultAccordion{{$unique}}">
                <span class="item-title">
                    {{__('Title')}}
                </span>
                <div class="icons d-flex align-items-center">
                    {!! get_icon('icon_system_pencil', '', '15px', '15px') !!}
                    &nbsp;
                    <span class="delete-item">{!! get_icon('icon_system_delete', '#cc0000',  '15px', '15px') !!}</span>
                </div>
            </div>
        </section>
    </div>

    <div id="defaultAccordion{{$unique}}" class="collapse" aria-labelledby="heading{{$unique}}" data-parent="#toggleAccordion{{$id}}">
        <div class="card-body">
            @if(!empty($fields))
                <div class="row">
                @foreach($fields as $item)
                    @php
                        $item = array_merge(get_option_default_fields(), $item);
                        if($item['type'] == 'checkbox'){
                            $item['id'] = $id . '[' . $item['id'] . ']['. $unique .']';
                        }else{
                            $item['id'] = $id . '[' . $item['id'] . ']['. $unique .'][]';
                        }
                    @endphp

                        @include('Backend::settings.fields.render', [
                            'field' => $item
                        ])
                @endforeach
                </div>
            @endif
        </div>
    </div>
</div>