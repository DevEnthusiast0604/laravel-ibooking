<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/3/20
 * Time: 14:33
 */
?>
@php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value) && !is_array($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);

$langs = get_languages_field();
@endphp

<div class="gmz-field form-group {{$layout}} gmz-field-{{$type}}" id="gmz-field-{{$id}}-wrapper" @if(!empty($condition))data-condition="{{$condition}}" @endif>
    <label for="gmz-field-{{$id}}">
        {{ __($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label><br/>

    <div class="checkbox-wrapper">
        @if (!empty($choices))
            @if (!is_array($choices))
                @php
                    $choices = get_terms('name', $choices, true);
                @endphp
            @endif
            @if(!empty($choices))
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox checkbox-success gmz-check-all">
                                    <input type="checkbox" id="gmz-checkbox-all">
                                    <label for="gmz-checkbox-all" class="mb-0">
                                        <span>{{__('Name')}}</span>
                                    </label>
                                </div>
                            </th>
                            <th>{{__('Base Price')}}</th>
                            <th>{{__('Custom Price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($choices as $item)
                            @php
                                $term_id = $item->id;
                                $custom_price = '';
                                $choose = 'no';
                                if(isset($value[$term_id])){
                                    $choose = $value[$term_id]['choose'];
                                    $custom_price = $value[$term_id]['price'];
                                }
                            @endphp
                            <tr>
                                <th scope="row" class="align-middle">
                                    <div class="checkbox  checkbox-success ">
                                        <input type="checkbox" @if($choose == 'yes') checked @endif name="{{$idName}}[id][{{$term_id}}]" value="{{$term_id}}" id="car_equipment-{{$term_id}}" class="gmz-check-all-item">
                                        <label for="car_equipment-{{$term_id}}">
                                            @foreach($langs as $lkey => $litem)
                                                <span class="{{get_lang_class($lkey, $litem)}}" @if(!empty($litem))
                                                data-lang="{{$litem}}" @endif>
                                                {{ get_translate($item->term_title, $litem) }}
                                                </span>
                                            @endforeach
                                        </label>
                                    </div>
                                </th>
                                <td class="align-middle">${{$item->term_price}}</td>
                                <td class="align-middle">
                                    <input type="text" value="{{$custom_price}}" name="{{$idName}}[price][{{$term_id}}]" class="form-control p-1 w-50">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <i><small>{{__('No Data')}}</small></i>
            @endif
        @endif
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif
