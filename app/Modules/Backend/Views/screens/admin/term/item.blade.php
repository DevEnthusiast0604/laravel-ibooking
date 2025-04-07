<tr data-id="{{$item->id}}">
    @foreach($columns as $key => $col)
        @if($key == 'term_title')
            <td class="d-flex align-items-center">
                @php
                echo $depth;
                    $term_title = get_translate($item->term_title);
                    $term_desc = get_translate($item->term_description);
                @endphp
                @if(!empty($item->term_image) && isset($columns['term_image']))
                    @php
                        $img = get_attachment_url($item->term_image, [50, 50]);
                    @endphp
                    @if(!empty($img))
                        <img src="{{$img}}" class="img-fluid mr-2 mw-5" alt="{{$term_title}}" />
                    @endif
                @endif
                <h6 class="mb-0">{{$term_title}}</h6>
            </td>
        @endif
        @if($key == 'term_description')
            <td>
                @if(!empty($term_desc))
                    {{$term_desc}}
                @else
                    ---
                @endif
            </td>
        @endif

        @if($key == 'term_price')
            <td>
                @if(!empty($item->term_price))
                    {{'$' . $item->term_price}}
                @else
                    ---
                @endif
            </td>
        @endif

        @if($key == 'term_icon')
            <td>
                @if(!empty($item->term_icon))
                    @if(strpos($item->term_icon, ' fa-'))
                        <i class="{{$item->term_icon}} term-icon"></i>
                    @else
                        {!! get_icon($item->term_icon) !!}
                    @endif
                @endif
            </td>
        @endif
    @endforeach
    <td class="text-center">
        <div class="dropdown custom-dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                @php
                    $params['termID'] = $item->id;
                    $params['termHashing'] = gmz_hashing($item->id);
                @endphp
                <a class="dropdown-item" href="{{dashboard_url('edit-term/' . $item->id . '/' . $params['taxName'])}}">{{__('Edit')}}</a>
                <a class="dropdown-item text-danger gmz-link-action" href="javascript:void(0);" data-confirm="true"  data-action="{{dashboard_url('delete-term')}}" data-params="{{base64_encode(json_encode($params))}}"  data-remove-el="tr">{{__('Delete')}}</a>
            </div>
        </div>
    </td>
</tr>