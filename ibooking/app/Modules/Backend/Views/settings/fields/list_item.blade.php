@php
    admin_enqueue_scripts('jquery-ui');
    admin_enqueue_styles('jquery-ui');
    admin_enqueue_scripts('nested-sort-js');
    admin_enqueue_styles('gmz-custom-accordions');

    if(empty($value))
       $value = $std;

    if(!is_array($value)){
       $value = [];
    }
@endphp
<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif data-binding="gmz-field-{{$id . '[' . $binding . ']'}}">
    <label for="gmz-field-{{$id}}">{{__($label)}}</label>

    <div id="toggleAccordion{{$id}}" class="gmz-list-item sortable">
        @if(!empty($value))
            @foreach($value as $k => $v)
                @php
                    $keys = array_keys($v);
                    $unique = time() . rand(0, 9999);
                @endphp
                <div class="card">
                    <div class="card-header" id="heading{{$k}}">
                        <section class="mb-0 mt-0">
                            <div role="menu" class="collapsed" data-toggle="collapse" data-target="#defaultAccordion{{$k}}" aria-expanded="true" aria-controls="defaultAccordion{{$k}}">
                                <span class="item-title">
                                    @if(!empty($v[$keys[0]]))
                                        {{get_translate($v[$keys[0]])}}
                                    @else
                                        {{__('Title')}}
                                    @endif
                                </span>
                                <div class="icons d-flex align-items-center">
                                    {!! get_icon('icon_system_pencil', '', '15px', '15px') !!}
                                    &nbsp;
                                    <span class="delete-item">{!! get_icon('icon_system_delete', '#cc0000',  '15px', '15px') !!}</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div id="defaultAccordion{{$k}}" class="collapse" aria-labelledby="heading{{$k}}" data-parent="#toggleAccordion{{$id}}">
                        <div class="card-body">
                            @if(!empty($fields))
                                <div class="row">
                                @foreach($fields as $item)
                                    @php
                                        $item = array_merge(get_option_default_fields(), $item);
                                        if(isset($v[$item['id']])){
                                            $item['value'] = $v[$item['id']];
                                        }
                                        if($item['type'] == 'checkbox'){
                                            $item['id'] = $id . '[' . $item['id'] . ']['. $k .']';
                                        }else{
                                            $item['id'] = $id . '[' . $item['id'] . ']['. $k .'][]';
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
            @endforeach
        @endif
    </div>
    <button type="button" class="btn btn-success btn-sm add-list-item mt-2"
            data-action="{{dashboard_url('get-list-item-html')}}" data-fields="{{base64_encode(json_encode($fields))}}" data-id="{{$id}}">
        {{__('Add New')}}
    </button>
    @if($description)
        <small class="d-block mt-1">{!! balance_tags(__($description)) !!}</small>
    @endif
</div>
@if($break)
    <div class="w-100"></div> @endif